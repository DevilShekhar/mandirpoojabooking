<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

class PrivacyPolicyFrontendController extends Controller
{
    public function index()
    {   
        return view('frontend.privacy.index');
    }
}