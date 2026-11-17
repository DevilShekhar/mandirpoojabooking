<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

class AboutusFrontendController extends Controller
{
    public function index()
    {   
        return view('frontend.aboutus.index');
    }
}