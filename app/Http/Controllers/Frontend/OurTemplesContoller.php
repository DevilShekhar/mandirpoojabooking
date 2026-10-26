<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Temple;
use App\Models\TicketPrice;

class OurTemplesContoller extends Controller
{
    public function index()
    {
        $temples = Temple::with(['state', 'district', 'city'])
            ->where('status', 1)
            ->latest()
            ->paginate(12);

        return view('frontend.our-temples.index', compact('temples'));
    }

   public function show(Temple $temple)
{
    abort_if($temple->status != 1, 404);

    $temple->load([
        'state',
        'district',
        'city',
        'pujas' => function ($query) {
            $query->where('status', 1)
                ->with('creator:id,name')
                ->latest();
        }
    ]);

    $ticketPrices = TicketPrice::where('temple_id', $temple->id)
        ->where('status', 1)
        ->get([
            'id',
            'temple_id',
            'ticket_type',
            'type',
            'min_age',
            'max_age',
            'price',
            'status'
        ]);

    return view('frontend.our-temples.show', compact(
        'temple',
        'ticketPrices'
    ));
}
}