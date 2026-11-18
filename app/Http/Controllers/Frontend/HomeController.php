<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\HomeBanner;
use App\Models\Puja;
use App\Models\Temple;
use App\Models\News;
use App\Models\OnlinePuja;

class HomeController extends Controller
{
    public function index()
    {
        $homeBanners = HomeBanner::where('status', 1)->latest()->get();
        $temples = Temple::with(['state', 'district', 'city'])->where('status', 1)->latest()->get();
        $pujas = Puja::with(['temple.city','temple.state','deity','pujaCategory'])->where('status', 1)->latest()->take(6)->get();
        $news = News::with('temple')->where('status', 1)->latest()->take(6)->get();
        $onlinePujas = OnlinePuja::with(['category','packages','faqs'])->where('status', 1)->where('puja_datetime', '>=', now())->orderBy('puja_datetime', 'asc')->take(6)->get();
        return view('frontend.home.index', compact('homeBanners','temples','pujas','news','onlinePujas'));
    }
}