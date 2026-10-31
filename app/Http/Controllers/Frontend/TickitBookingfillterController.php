<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Puja;
use App\Models\Temple;
use Illuminate\Http\Request;

class TickitBookingfillterController extends Controller
{
    public function tickitbooking()
    {
        return view('frontend.ticket-booking.index');
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $search = trim($validated['search'] ?? '');

        if (mb_strlen($search) < 2) {
            return response()->json([
                'status' => true,
                'results' => [],
                'message' => 'Please enter at least 2 characters.',
            ]);
        }

        $matchingTempleIds = Temple::where(
            'name',
            'LIKE',
            '%' . $search . '%'
        )->pluck('id');

        $pujas = Puja::with([
            'temple:id,name,slug,main_image',
        ])
            ->where('status', 1)
            ->whereNotNull('slug')
            ->whereNotNull('temple_id')
            ->where(function ($query) use ($search, $matchingTempleIds) {
                $query->where('name', 'LIKE', '%' . $search . '%');

                if ($matchingTempleIds->isNotEmpty()) {
                    $query->orWhereIn('temple_id', $matchingTempleIds);
                }
            })
            ->orderBy('name')
            ->get([
                'id',
                'temple_id',
                'name',
                'slug',
                'image',
                'duration',
            ]);

        $results = $pujas
            ->filter(function ($puja) {
                return $puja->temple !== null;
            })
            ->groupBy('temple_id')
            ->map(function ($templePujas) {
                $temple = $templePujas->first()->temple;

                return [
                    'temple_id' => $temple->id,
                    'temple_name' => $temple->name,
                    'temple_slug' => $temple->slug,
                    'temple_image' => $temple->main_image,

                    'pujas' => $templePujas->map(function ($puja) {
                        return [
                            'id' => $puja->id,
                            'name' => $puja->name,
                            'slug' => $puja->slug,
                            'image' => $puja->image,
                            'duration' => $puja->duration,
                            'url' => route(
                                'tickit-booking.view-puja',
                                $puja->id
                            ),
                        ];
                    })->values(),
                ];
            })
            ->values();

        return response()->json([
            'status' => true,
            'results' => $results,
            'message' => $results->isEmpty()
                ? 'No matching temples or pujas found.'
                : 'Search results found.',
        ]);
    }

    public function viewPuja($puja)
    {
        $puja = Puja::where('id', $puja)
            ->where('status', 1)
            ->whereNotNull('slug')
            ->firstOrFail();

        return redirect()
            ->route('frontend.puja.show', $puja->slug)
            ->with('open_package_tab', true);
    }
}