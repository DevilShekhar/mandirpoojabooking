<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Temple;
use App\Models\Puja;
use App\Models\OnlinePuja;
use App\Models\Sanatan;
use App\Models\News;

class SitemapController extends Controller
{
    public function index()
    {
        $temples     = Temple::all();
        $pujas       = Puja::all();
        $onlinePujas = OnlinePuja::all();
        $events      = Sanatan::all();
        $news        = News::all();

        return response()->view('sitemap.index', [
            'temples'     => $temples,
            'pujas'       => $pujas,
            'onlinePujas' => $onlinePujas,
            'events'      => $events,
            'news'        => $news,
        ])->header('Content-Type', 'text/xml');
    }
}