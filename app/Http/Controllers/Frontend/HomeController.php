<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\HomeBanner;

class HomeController extends Controller
{
    public function index()
    {
        $homeBanners = HomeBanner::where('status', 1)
            ->latest()
            ->get();

        return view('frontend.home.index', compact('homeBanners'));
    }
}