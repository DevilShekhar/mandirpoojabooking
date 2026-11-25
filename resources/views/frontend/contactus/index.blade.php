@extends('frontend.layouts.app')
@section('title', 'Contact Us - All Sanatan')
@section('meta_keyword', 'Contact All Sanatan, Customer Support, Temple Darshan Booking, Pooja Services, Pandit Booking')
@section('meta_description', 'Contact All Sanatan for temple darshan bookings, pooja services, pandit bookings, online pooja and other service enquiries.')
@section('content')
<div class="breadcrumb-area">
    <div class="container">
        <div class="breadcrumb-content">
            <ul>
                <li><a href="{{ url('/') }}">Home</a></li>
                <li class="active">Contact Us</li>
            </ul>
        </div>
    </div>
</div>
<div class="about-us-wrapper pt-60 pb-40">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 col-md-12 mb-40">
                <div class="about-text-wrap">
                    <h2><span>Contact</span> Us</h2>
                    <h4>Get in Touch</h4>
                    <p>
                        We are here to assist you with your enquiries regarding
                        temple darshan bookings, pooja services, pandit bookings,
                        online pooja and other services available on All Sanatan.
                    </p>
                    <p>
                        Whether you need help before booking or assistance with
                        an existing booking, you can contact our support team
                        using the details below.
                    </p>
                    <h4 class="mt-4 mb-3"> Contact Information</h4>
                    <div class="mb-4">
                        <div class="d-flex align-items-start mb-3">
                            <i class="fa fa-envelope fa-lg mr-3 mt-1" style="color: #EA0505;"></i>
                            <div>
                                <strong>Customer Support Email:</strong>
                                <br>
                                <a href="mailto:allsanatan@gmail.com">
                                    allsanatan@gmail.com
                                </a>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-3">
                            <i class="fa fa-phone fa-lg mr-3 mt-1" style="color: #EA0505;"></i>
                            <div>
                                <strong>Phone / WhatsApp:</strong>
                                <br>
                                <a href="tel:9970711238">9970711238</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-3">
                            <i class="fa fa-globe fa-lg mr-3 mt-1"  style="color: #EA0505;"></i>
                            <div>
                                <strong>Website:</strong>
                                <br>
                                <a href="https://allsanatan.com" target="_blank"  rel="noopener noreferrer">
                                    https://allsanatan.com
                                </a>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-3">
                            <i class="fa fa-map-marker fa-lg mr-3 mt-1" style="color: #EA0505;"></i>
                            <div>
                                <strong>Business Address:</strong>
                                <br>
                                [Insert Complete Business Address]
                            </div>
                        </div>
                    </div>
                    <h5>Brand Name: All Sanatan</h5>
                    <p>
                        <strong>Operated By:</strong>
                        [Insert Full Legal Name of Proprietor / Registered Legal Entity]
                    </p>
                    <p>
                        <strong>Effective Date:</strong> [Insert Date]
                    </p>
                    <p>
                        <strong>Last Updated:</strong> [Insert Date]
                    </p>
                </div>
            </div>
            <div class="col-lg-6 offset-lg-1 col-md-12 mb-40">
                <div class="single-vision-box p-4 p-lg-5 bg-white shadow-sm rounded" style="border-top: 4px solid #EA0505;">
                    <h3 class="mb-3">Contact Us</h3>
                    <form action="#" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">Full Name</label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control"
                                   placeholder="Enter your full name"
                                   required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email">Email Address</label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control"
                                   placeholder="Enter your email address"
                                   required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="phone">Mobile Number</label>
                            <input type="tel"
                                   name="phone"
                                   id="phone"
                                   class="form-control"
                                   placeholder="Enter your mobile number"
                                   required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="booking_id">Booking ID</label>
                            <input type="text"
                                   name="booking_id"
                                   id="booking_id"
                                   class="form-control"
                                   placeholder="Enter booking ID (if applicable)">
                        </div>
                        <div class="form-group mb-3">
                            <label for="subject">Enquiry Type</label>
                            <select name="subject"
                                    id="subject"
                                    class="form-control"
                                    required>
                                <option value="">Select Enquiry Type</option>
                                <option value="New Booking Enquiry">New booking enquiries</option>
                                <option value="Existing Booking">Existing booking status and confirmation</option>
                                <option value="Payment Related">Payment-related questions</option>
                                <option value="Cancellation and Refund">Cancellation and refund requests</option>
                                <option value="Darshan">Darshan and temple entry information</option>
                                <option value="Pooja and Pandit">Pooja and pandit booking assistance</option>
                                <option value="Online Pooja">Online pooja and live streaming enquiries</option>
                                <option value="Complaints and Feedback">Complaints, feedback and service-related concerns</option>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="message">Your Message</label>
                            <textarea name="message"
                                      id="message"
                                      class="form-control"
                                      rows="5"
                                      placeholder="Enter your enquiry or message..."
                                      required></textarea>
                        </div>
                        <button type="submit"
                                class="btn btn-danger"
                                style="background-color: #EA0505; border-color: #EA0505; padding: 12px 30px;">
                            <i class="fa fa-paper-plane mr-2"></i>
                            Submit Enquiry
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="why-choose-us-wrapper pt-50 pb-40">
    <div class="container">
        <div class="row">
            <div class="col-12 mb-30">
                <div class="section-title">
                    <h2> What Can We Help You With?</h2>
                    <p>You may contact us regarding:</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-3">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                        New booking enquiries.
                    </li>
                    <li class="mb-3">
                        <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                        Existing booking status and confirmation.
                    </li>
                    <li class="mb-3">
                        <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                        Payment-related questions.
                    </li>
                    <li class="mb-3">
                        <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                        Cancellation and refund requests.
                    </li>
                </ul>
            </div>
            <div class="col-lg-6 col-md-6 mb-3">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                        Darshan and temple entry information.
                    </li>
                    <li class="mb-3">
                        <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                        Pooja and pandit booking assistance.
                    </li>
                    <li class="mb-3">
                        <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                        Online pooja and live streaming enquiries.
                    </li>
                    <li class="mb-3">
                        <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                        Complaints, feedback and service-related concerns.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="vision-mission-wrapper pt-50 pb-50">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6 mb-30">
                <div class="single-vision-box p-4 p-lg-5 bg-white shadow-sm rounded" style="border-left: 4px solid #EA0505;">
                    <h3 class="mb-3"> Information to Include in Your Enquiry</h3>
                    <p>To help us respond efficiently, please provide:</p>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                            Your full name.
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                            Registered mobile number or email address.
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                            Booking ID or transaction reference, if applicable.
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                            Name of the temple or service.
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                            A brief description of your query or issue.
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                            Relevant supporting details, if available.
                        </li>
                    </ul>
                    <p class="mt-3 mb-0">
                        Please do not share your password, OTP, card PIN,
                        UPI PIN or other confidential payment credentials.
                    </p>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-30">
                <div class="single-vision-box p-4 p-lg-5 bg-white shadow-sm rounded" style="border-left: 4px solid #EA0505;">
                    <h3 class="mb-3"> Support Availability</h3>
                    <p>
                        <strong>Customer Support Hours:</strong>
                        [Insert Official Support Days and Timings]
                    </p>
                    <p class="mb-0">
                        We will make reasonable efforts to respond to enquiries
                        within a reasonable period. Response times may vary
                        depending on the nature and complexity of the request.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="why-choose-us-wrapper pt-50 pb-40">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12 mb-30">
                <div class="single-choose-box p-4 border rounded h-100">
                    <h3 class="mb-3">
                     Booking Complaints and Grievances
                    </h3>
                    <p class="mb-0">
                        For formal complaints, please refer to our
                        <strong>Grievance Redressal Policy</strong>
                        available on the website.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="vision-mission-wrapper pt-50 pb-50">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="single-vision-box p-4 p-lg-5 bg-white shadow-sm rounded" style="border-left: 4px solid #EA0505;">
                    <h3 class="mb-3"> Important Note</h3>
                    <p class="mb-0">
                        Contacting All Sanatan does not automatically confirm,
                        modify or cancel a booking. Any booking change,
                        cancellation or refund will be subject to the applicable
                        service policy and confirmation from the relevant
                        service provider, wherever applicable.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection