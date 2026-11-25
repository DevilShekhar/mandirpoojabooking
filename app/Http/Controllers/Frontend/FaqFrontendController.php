<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class FaqFrontendController extends Controller
{
    public function index()
    {
        return view('frontend.faq.index');
    }
}