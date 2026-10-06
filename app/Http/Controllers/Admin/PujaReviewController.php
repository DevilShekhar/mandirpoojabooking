<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PujaReview;
use Illuminate\Http\Request;

class PujaReviewController extends Controller
{
    /**
     * Display all puja reviews.
     */
    public function index()
    {
        $reviews = PujaReview::with(['puja','user',])
        ->latest()
        ->paginate(20);
        return view('admin.puja-reviews.index', compact('reviews'));
    }

    /**
     * Approve review.
     */
    public function approve($id)
    {
        $review = PujaReview::findOrFail($id);
        $review->update([
            'status' => 1,
            'approved_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);
        return redirect()->back()->with('success', 'Review approved successfully.');
    }

    /**
     * Reject review.
     */
    public function reject($id)
    {
        $review = PujaReview::findOrFail($id);
        $review->update(['status' => 2,'updated_by' => auth()->id(),]);
        return redirect()->back()->with('success', 'Review rejected successfully.');
    }
}