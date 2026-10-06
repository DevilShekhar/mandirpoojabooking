<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Puja;

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

        return view('frontend.pujas.show', compact('puja'));
    }
}