<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;

class NewsFrontendController extends Controller
{
    public function index()
    {
        $news = News::with('temple')
            ->where('status', 1)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->latest()
            ->paginate(3);

        return view('frontend.news.index', compact('news'));
    }

    public function show($slug)
    {
        $news = News::with('temple')
            ->where('status', 1)
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedNews = News::with('temple')
            ->where('status', 1)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->where('id', '!=', $news->id)
            ->latest()
            ->take(4)
            ->get();

        return view('frontend.news.show', compact('news', 'relatedNews'));
    }
}