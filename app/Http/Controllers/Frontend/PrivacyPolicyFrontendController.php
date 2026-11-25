<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

class PrivacyPolicyFrontendController extends Controller
{
    public function index()
    {   
        return view('frontend.privacy.index');
    }
    public function bookingpolicy()
    {   
        return view('frontend.privacy.bookingpolicy');
    }
    public function cancellationrefundpolicy()
    {   
        return view('frontend.privacy.canellationrefundpolicy');
    }
    public function paymenttransactionpolicy()
    {   
        return view('frontend.privacy.paymenttransactionpolicy');
    }
    public function templedarshanpolicy()
    {   
        return view('frontend.privacy.templedarshanpolicy');
    }
    public function pujabookingpolicy()
    {   
        return view('frontend.privacy.pujabookingpolicy');
    }
    public function panditbookingpolicy()
    {   
        return view('frontend.privacy.panditbookingpolicy');
    }
}