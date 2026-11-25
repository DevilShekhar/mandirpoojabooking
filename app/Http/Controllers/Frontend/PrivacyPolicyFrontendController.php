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
    public function onlinepujabookingpolicy()
    {   
        return view('frontend.privacy.onlinepujabookingpolicy');
    }
    public function livepujastreamingpolicy()
    {   
        return view('frontend.privacy.livepujastreamingpolicy');
    }
    public function grievanceredressalpolicy()
    {   
        return view('frontend.privacy.grievanceredressalpolicy');
    }
    public function festivalvipdarshanpolicy()
    {   
        return view('frontend.privacy.festivalvipdarshanpolicy');
    }
    public function userconductpolicy()
    {   
        return view('frontend.privacy.userconductpolicy');
    }
    public function reviewsratingspolicy()
    {   
        return view('frontend.privacy.reviewsratingspolicy');
    }
    public function fraudpreventionsecuritypolicy()
    {   
        return view('frontend.privacy.fraudpreventionsecuritypolicy');
    }
    public function forcemajeurepolicy()
    {   
        return view('frontend.privacy.forcemajeurepolicy');
    }
    public function cookietrackingpolicy()
    {   
        return view('frontend.privacy.cookietrackingpolicy');
    }
    public function intellectualpropertypolicy()
    {   
        return view('frontend.privacy.intellectualpropertypolicy');
    }
}