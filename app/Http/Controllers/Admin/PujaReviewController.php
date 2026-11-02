<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PujaReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PujaReviewController extends Controller
{
    /**
     * Get reviews based on logged-in user's role.
     */
    private function reviewQuery()
    {
        $user = Auth::user();

        $query = PujaReview::with([
            'puja.temple',
            'user',
        ]);

        // Admin and Super Admin can see all temple reviews.
        if (
            $user->hasRole('Admin') ||
            $user->hasRole('Super Admin')
        ) {
            return $query;
        }

        // Temple Manager can see reviews
        // belonging only to approved assigned temples.
        if ($user->hasRole('Temple Manager')) {

            $templeIds = DB::table('temple_user')
                ->where('user_id', $user->id)
                ->where('approval_status', 'approved')
                ->pluck('temple_id');

            return $query->whereHas('puja', function ($q) use ($templeIds) {
                $q->whereIn('temple_id', $templeIds);
            });
        }

        // Other roles cannot access these reviews.
        abort(403, 'Unauthorized access.');
    }

    /**
     * Display puja reviews.
     */
    public function index()
    {
        $query = $this->reviewQuery();

        // Dashboard statistics using the same role restrictions.
        $totalReviews = (clone $query)->count();

        $averageRating = (clone $query)->avg('rating') ?? 0;

        $approvedReviews = (clone $query)
            ->where('status', 1)
            ->count();

        $pendingReviews = (clone $query)
            ->where('status', 0)
            ->count();

        $rejectedReviews = (clone $query)
            ->where('status', 2)
            ->count();

        // Paginated review listing.
        $reviews = $query
            ->latest()
            ->paginate(20);

        return view('admin.puja-reviews.index', compact(
            'reviews',
            'totalReviews',
            'averageRating',
            'approvedReviews',
            'pendingReviews',
            'rejectedReviews'
        ));
    }

    /**
     * Approve review.
     */
    public function approve($id)
    {
        $review = $this->reviewQuery()->findOrFail($id);

        $review->update([
            'status' => 1,
            'approved_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->back()->with(
            'success',
            'Review approved successfully.'
        );
    }

    /**
     * Reject review.
     */
    public function reject($id)
    {
        $review = $this->reviewQuery()->findOrFail($id);

        $review->update([
            'status' => 2,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->back()->with(
            'success',
            'Review rejected successfully.'
        );
    }
}
