<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Temple;

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
        $temple->load(['state', 'district', 'city']);

        abort_if($temple->status != 1, 404);

        return view('frontend.our-temples.show', compact('temple'));
    }
}