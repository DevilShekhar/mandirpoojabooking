<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Puja;
use App\Models\PujaReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OurPujaController extends Controller
{
    public function index()
    {
        $pujas = Puja::with([
            'temple.state',
            'temple.district',
            'temple.city',
            'deity',
            'pujaCategory',
            'faqs',
        ])
            ->where('status', 1)
            ->whereNotNull('slug')
            ->latest()
            ->paginate(12);

        return view('frontend.pujas.index', compact('pujas'));
    }

    public function show(string $slug)
    {
        $puja = Puja::with([
            'temple.state',
            'temple.district',
            'temple.city',
            'deity',
            'pujaCategory',
            'faqs',
        ])
            ->where('status', 1)
            ->where('slug', $slug)
            ->firstOrFail();
        $reviews = PujaReview::with('user')
        ->where('puja_id', $puja->id)
        ->where(function ($query) {
            // Approved reviews are visible to everyone
            $query->where('status', 1);
            // Current user can see their own pending review
            if (auth()->check()) {
                $query->orWhere(function ($q) {
                    $q->where('created_by', auth()->id())
                      ->where('status', 0);
                });
            }
        })
        ->latest()
        ->get();
        return view('frontend.pujas.show', compact('puja','reviews'));
    }
    public function storeReview(Request $request, $pujaId)
    {
        $puja = Puja::where('id', $pujaId)
            ->where('status', 1)
            ->firstOrFail();

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:2000',
        ]);

        PujaReview::create([
            'puja_id' => $puja->id,
            'review' => $validated['review'],
            'rating' => $validated['rating'],
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'status' => 0,
            'approved_by' => null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Your review has been submitted successfully and is waiting for approval.');
    }
}