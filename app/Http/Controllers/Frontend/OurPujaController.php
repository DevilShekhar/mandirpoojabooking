<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Deity;
use App\Models\PanditPuja;
use App\Models\Puja;
use App\Models\PujaReview;
use App\Models\Temple;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OurPujaController extends Controller
{
    public function index()
    {
        $pujas = $this->pujaQuery()->paginate(9);

        $cities = Temple::with('city')
            ->whereHas('city')
            ->get()
            ->pluck('city')
            ->filter()
            ->unique('id')
            ->sortBy('name')
            ->values();

        $deities = Deity::where('status', 1)
            ->orderBy('name')
            ->get();

        /*
         * Get active pujas with valid slugs.
         * Keep only one dropdown option per unique puja name.
         * The first record's ID is retained for filtering.
         */
        $allPujas = Puja::where('status', 1)
            ->whereNotNull('slug')
            ->whereNotNull('name')
            ->orderBy('name')
            ->orderBy('id')
            ->get(['id', 'name'])
            ->unique(function ($item) {
                return mb_strtolower(trim($item->name));
            })
            ->values();

        $panditIds = PanditPuja::select('pandit_id')
            ->distinct()
            ->pluck('pandit_id');

        $pandits = User::whereIn('id', $panditIds)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('frontend.pujas.index', compact(
            'pujas',
            'cities',
            'deities',
            'allPujas',
            'pandits'
        ));
    }

    public function filter(Request $request)
    {
        $query = $this->pujaQuery();

        // Filter by city
        if ($request->filled('city')) {
            $query->whereHas('temple.city', function ($q) use ($request) {
                $q->where('id', $request->input('city'));
            });
        }

        // Filter by deity
        if ($request->filled('deity')) {
            $query->where('deity_id', $request->input('deity'));
        }

        /*
         * Filter by puja name.
         * Selecting one dropdown entry returns all active puja
         * records with the same name, including case/space variants.
         */
        if ($request->filled('puja')) {
            $selectedPuja = Puja::where('status', 1)
                ->whereNotNull('slug')
                ->where('id', $request->input('puja'))
                ->first(['id', 'name']);

            if ($selectedPuja) {
                $normalizedName = mb_strtolower(
                    trim($selectedPuja->name)
                );

                $query->whereRaw(
                    'LOWER(TRIM(name)) = ?',
                    [$normalizedName]
                );
            } else {
                // Invalid puja selection returns no results.
                $query->whereRaw('1 = 0');
            }
        }

        // Filter by pandit
        if ($request->filled('pandit')) {
            $pujaIds = PanditPuja::where(
                'pandit_id',
                $request->input('pandit')
            )->pluck('puja_id');

            $query->whereIn('id', $pujaIds);
        }

        $pujas = $query->paginate(
            9,
            ['*'],
            'page',
            max(1, (int) $request->input('page', 1))
        );

        return view('frontend.pujas.index', [
            'pujas' => $pujas,
            'cities' => collect(),
            'deities' => collect(),
            'allPujas' => collect(),
            'pandits' => collect(),
        ])->fragment('pujaResults');
    }

    private function pujaQuery()
    {
        return Puja::with([
            'temple.state',
            'temple.district',
            'temple.city',
            'deity',
            'pujaCategory',
            'faqs',
            'packages',
        ])
            ->where('status', 1)
            ->whereNotNull('slug')
            ->latest();
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
            'packages',
        ])
            ->where('status', 1)
            ->where('slug', $slug)
            ->firstOrFail();

        $reviews = PujaReview::with('user')
            ->where('puja_id', $puja->id)
            ->where(function ($query) {
                $query->where('status', 1);

                if (auth()->check()) {
                    $query->orWhere(function ($q) {
                        $q->where('created_by', auth()->id())
                            ->where('status', 0);
                    });
                }
            })
            ->latest()
            ->get();

        return view(
            'frontend.pujas.show',
            compact('puja', 'reviews')
        );
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

        return redirect()->back()->with(
            'success',
            'Your review has been submitted successfully and is waiting for approval.'
        );
    }
}