<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\OnlinePuja;
use App\Models\OnlinePujaCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class FrontendOnlinePujaController extends Controller
{
    /**
     * Online Puja listing and AJAX category filtering.
     */
    public function index(Request $request)
    {
        $categories = OnlinePujaCategory::where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        // Initial page load
        if (!$request->ajax()) {
            $onlinePujas = $this->upcomingPujaQuery()->paginate(9);

            return view('frontend.online-puja.index', compact(
                'categories',
                'onlinePujas'
            ));
        }

        // AJAX validation
        $validated = $request->validate([
            'category' => 'nullable|integer|min:1',
            'page' => 'nullable|integer|min:1',
        ]);

        $categoryId = $validated['category'] ?? null;
        $page = (int) ($validated['page'] ?? 1);

        $categoryName = 'All Online Pujas';

        // Validate active category
        if ($categoryId) {
            $category = $categories->firstWhere('id', $categoryId);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected category is not available.',
                ], 422);
            }

            $categoryName = $category->name;
        }

        // Build query
        $query = $this->upcomingPujaQuery();

        if ($categoryId) {
            $query->where('online_puja_category_id', $categoryId);
        }

        $onlinePujas = $query->paginate(9, ['*'], 'page', $page);

        // Format records
        $pujas = $onlinePujas->getCollection()->map(function ($puja) {

            $totalSeats = (int) ($puja->total_seat ?? 0);
            $bookedSeats = (int) ($puja->booked_seat ?? 0);

            $image = $puja->thumbnail_image ?: $puja->banner_image;

            return [
                'id' => $puja->id,
                'title' => $puja->title,
                'slug' => $puja->slug,

                'short_description' => Str::limit(
                    strip_tags($puja->short_description ?? ''),
                    120
                ),

                'category_name' => $puja->category->name ?? '',

                'creator_name' => $puja->creator->name ?? 'Admin',

                'puja_datetime' => $puja->puja_datetime
                    ? Carbon::parse($puja->puja_datetime)->format('d M Y, h:i A')
                    : 'Not scheduled',

                'booking_start_at' => $puja->booking_start_at
                    ? Carbon::parse($puja->booking_start_at)->format('d M Y, h:i A')
                    : 'Not specified',

                'total_seat' => $totalSeats,
                'booked_seat' => $bookedSeats,

                'available_seats' => max(
                    0,
                    $totalSeats - $bookedSeats
                ),

                'image' => $image
                    ? asset('storage/' . $image)
                    : null,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => $pujas,
            'total' => $onlinePujas->total(),
            'current_page' => $onlinePujas->currentPage(),
            'last_page' => $onlinePujas->lastPage(),
            'per_page' => $onlinePujas->perPage(),
            'category_name' => $categoryName,
        ]);
    }

    /**
     * Common upcoming puja query.
     */
    private function upcomingPujaQuery()
    {
        return OnlinePuja::with([
                'category:id,name',
                'creator:id,name',
            ])
            ->where('status', 1)
            ->whereNotNull('puja_datetime')
            ->where('puja_datetime', '>=', now())
            ->orderBy('puja_datetime', 'asc')
            ->select([
                'id',
                'online_puja_category_id',
                'created_by',
                'title',
                'slug',
                'short_description',
                'thumbnail_image',
                'banner_image',
                'puja_datetime',
                'booking_start_at',
                'total_seat',
                'booked_seat',
            ]);
    }

    /**
     * Online Puja details.
     */
    public function show($slug)
    {
        $onlinePuja = OnlinePuja::with([
            'category',
            'creator',
            'packages',
            'faqs',
        ])
            ->where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $relatedPujas = OnlinePuja::with([
                'category',
                'creator',
            ])
            ->where('status', 1)
            ->whereNotNull('puja_datetime')
            ->where('puja_datetime', '>=', now())
            ->where('id', '!=', $onlinePuja->id)
            ->where(
                'online_puja_category_id',
                $onlinePuja->online_puja_category_id
            )
            ->orderBy('puja_datetime', 'asc')
            ->take(4)
            ->get();

        return view('frontend.online-puja.show', compact(
            'onlinePuja',
            'relatedPujas'
        ));
    }
}