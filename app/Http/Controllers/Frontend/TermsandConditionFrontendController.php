<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

class TermsandConditionFrontendController extends Controller
{
    public function index()
    {   
        return view('frontend.termsandcondition.index');
    }
}