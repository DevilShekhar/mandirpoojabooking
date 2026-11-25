@extends('frontend.layouts.app')
@section('title', 'Help & Support - All Sanatan')
@section('meta_description', 'Get assistance with bookings, payments, cancellations, refunds, online pooja, darshan and other services at All Sanatan.')
@section('content')
<style>
    .support-page {
        background: #fffaf5;
        padding-bottom: 60px;
    }
    .support-hero {
        background: linear-gradient(135deg, #7d2e0c, #ed741f);
        padding: 65px 20px;
        color: #fff;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .support-hero h1 {
        font-size: 38px;
        font-weight: 700;
        margin-bottom: 12px;
    }
    .support-hero p {
        max-width: 650px;
        margin: auto;
        font-size: 16px;
        line-height: 1.8;
        color: #fff3e8;
    }
    .support-icon {
        width: 75px;
        height: 75px;
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 32px;
    }
    .support-container {
        max-width: 1100px;
        margin: -25px auto 0;
        position: relative;
        z-index: 2;
    }
    .support-contact-card {
        background: #fff;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 5px 25px rgba(0,0,0,.07);
        height: 100%;
        border: 1px solid #f4e8dc;
    }
    .contact-icon {
        width: 48px;
        height: 48px;
        background: #fff0e5;
        color: #ed741f;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        margin-bottom: 15px;
    }
    .support-contact-card h5 {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
    }
    .support-contact-card p,
    .support-contact-card a {
        font-size: 14px;
        color: #666;
        margin-bottom: 0;
        word-break: break-word;
        text-decoration: none;
    }

    .support-content {
        max-width: 1000px;
        margin: 45px auto 0;
    }

    .support-section {
        background: #fff;
        border: 1px solid #f1e8df;
        border-radius: 12px;
        padding: 27px 30px;
        margin-bottom: 20px;
        box-shadow: 0 3px 12px rgba(0,0,0,.025);
    }

    .support-section h4 {
        color: #7d2e0c;
        font-size: 19px;
        font-weight: 700;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .support-section h4 i {
        color: #ed741f;
        font-size: 21px;
    }

    .support-section p {
        color: #626262;
        font-size: 14px;
        line-height: 1.9;
        margin-bottom: 12px;
    }

    .support-section ul {
        padding-left: 0;
        list-style: none;
        margin-bottom: 10px;
    }

    .support-section ul li {
        color: #626262;
        font-size: 14px;
        line-height: 1.8;
        margin-bottom: 9px;
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }

    .support-section ul li i {
        color: #ed741f;
        margin-top: 5px;
        font-size: 12px;
    }

    .support-note {
        background: #fff5eb;
        border-left: 4px solid #ed741f;
        padding: 14px 17px;
        border-radius: 5px;
        margin-top: 15px;
    }

    .support-note p {
        margin: 0;
    }

    .support-bottom {
        background: linear-gradient(135deg, #fff0e3, #fff8f1);
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        margin-top: 30px;
    }

    .support-bottom h4 {
        font-weight: 700;
        color: #7d2e0c;
        margin-bottom: 12px;
    }

    .support-btn {
        display: inline-block;
        background: #ed741f;
        color: #fff !important;
        padding: 11px 24px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        margin: 8px 5px 0;
        transition: .3s;
    }

    .support-btn:hover {
        background: #7d2e0c;
    }

    @media(max-width:767px) {
        .support-hero {
            padding: 45px 15px;
        }

        .support-hero h1 {
            font-size: 28px;
        }

        .support-section {
            padding: 22px 18px;
        }

        .support-content {
            margin-top: 30px;
        }
    }
</style>
<div class="support-page">
    <section class="support-hero">
        <div class="support-icon">
            <i class="fa fa-headphones" aria-hidden="true"></i>
        </div>
        <h1>Help & Support</h1>
        <p>
            We are here to help you with your bookings, payments,
            religious services and other queries. Connect with
            All Sanatan whenever you need assistance.
        </p>
    </section>
    <div class="container support-container">
        <div class="row g-3">
            <div class="col-lg-4 col-md-6">
                <div class="support-contact-card">
                    <div class="contact-icon">
                        <i class="fa fa-envelope"></i>
                    </div>
                    <h5>Email Support</h5>
                    <a href="mailto:allsanatan@gmail.com">
                        allsanatan@gmail.com
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="support-contact-card">
                    <div class="contact-icon">
                        <i class="fa fa-phone" aria-hidden="true"></i>
                    </div>
                    <h5>Phone / WhatsApp</h5>
                    <a href="tel:9970711238">9970711238</a>
                    <br>
                    <a href="https://wa.me/919970711238" target="_blank">
                        Chat on WhatsApp
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="support-contact-card">
                    <div class="contact-icon">
                        <i class="fa fa-globe"></i>
                    </div>
                    <h5>Official Website</h5>
                    <a href="https://allsanatan.com" target="_blank">
                        www.allsanatan.com
                    </a>
                </div>
            </div>
        </div>
        <div class="support-content">
            <div class="support-section">
                <h4>
                    <i class="fa fa-hands-helping"></i>
                  How We Can Help
                </h4>
                <p>
                    The All Sanatan Help & Support section is designed
                    to assist users with questions about our services,
                    bookings, payments and other website-related matters.
                </p>
            </div>
            <div class="support-section">
                <h4>
                    <i class="fa fa-calendar-check"></i>
                    Booking Assistance
                </h4>
                <p>
                    If you need assistance with a booking, please contact
                    us with your booking ID and registered contact details.
                </p>
                <p>Our support team may assist with:</p>
                <ul>
                    <li><i class="fa fa-dot-circle-o"></i> Understanding available services.</li>
                    <li><i class="fa fa-dot-circle-o"></i> Checking booking details.</li>
                    <li><i class="fa fa-dot-circle-o"></i> Clarifying booking confirmation status.</li>
                    <li><i class="fa fa-dot-circle-o"></i> Providing available service information.</li>
                    <li><i class="fa fa-dot-circle-o"></i> Directing you to the relevant service provider, where required.</li>
                </ul>
                <div class="support-note">
                    <p>
                        <strong>Important:</strong> A booking is not
                        considered confirmed unless confirmation has
                        been issued through the applicable booking process.
                    </p>
                </div>
            </div>
            <div class="support-section">
                <h4>
                    <i class="fa fa-credit-card"></i>
                    Payment Assistance
                </h4>
                <p>
                    If your payment has been deducted but you have not
                    received a booking confirmation, contact us with:
                </p>
                <ul>
                    <li><i class="fa fa-dot-circle-o"></i> Booking ID, if available.</li>
                    <li><i class="fa fa-dot-circle-o"></i> Transaction reference or payment ID.</li>
                    <li><i class="fa fa-dot-circle-o"></i> Date and approximate time of payment.</li>
                    <li><i class="fa fa-dot-circle-o"></i> Registered mobile number or email address.</li>
                    <li><i class="fa fa-dot-circle-o"></i> Relevant payment confirmation screenshot, with sensitive information hidden.</li>
                </ul>
                <div class="support-note">
                    <p>
                        <strong>Security Alert:</strong> Do not share
                        your UPI PIN, OTP, card PIN or password with anyone.
                    </p>
                </div>
            </div>
            <div class="support-section">
                <h4>
                    <i class="fa fa-undo-alt"></i>
                    Cancellation and Refund Assistance
                </h4>
                <p>
                    Cancellation and refund requests are governed by
                    the applicable Cancellation & Refund Policy and
                    the specific service terms.
                </p>
                <p>
                    Please provide your booking ID and reason for
                    the request when contacting support.
                </p>
                <p>
                    A refund is not guaranteed merely because a request
                    has been submitted. Eligibility will be assessed
                    under the applicable policy and circumstances.
                </p>
                <div class="support-note">
                    <p>
                        Please review our
                        <a href="{{ url('/cancellation-refund-policy') }}"
                           style="color:#ed741f;font-weight:600;">
                            Cancellation & Refund Policy
                        </a>
                        before submitting your request.
                    </p>
                </div>
            </div>
            <div class="support-section">
                <h4>
                    <i class="fa fa-place-of-worship"></i>
                    Darshan, Pooja and Pandit Assistance
                </h4>
                <p>
                    For questions about temple darshan, pooja ceremonies
                    or pandit services, please provide the name of the
                    temple or service and your booking details.
                </p>
                <p>
                    Temple entry, ritual arrangements, schedules and
                    availability may depend on the relevant temple
                    or service provider.
                </p>
            </div>
            <div class="support-section">
                <h4>
                    <i class="fa fa-video"></i>
                    Online Pooja and Live Streaming Assistance
                </h4>
                <p>
                    For online pooja or live streaming issues,
                    please include:
                </p>
                <ul>
                    <li><i class="fa fa-dot-circle-o"></i> Your booking ID.</li>
                    <li><i class="fa fa-dot-circle-o"></i> Scheduled date and time.</li>
                    <li><i class="fa fa-dot-circle-o"></i> Service or temple name.</li>
                    <li><i class="fa fa-dot-circle-o"></i> Description of the technical issue.</li>
                </ul>
                <p>
                    Streaming availability may depend on internet
                    connectivity, equipment and the relevant
                    service provider.
                </p>
            </div>
            <div class="support-section">
                <h4>
                    <i class="fa fa-exclamation-circle"></i>
                    Report a Problem
                </h4>
                <p>You may contact us if you experience:</p>
                <ul>
                    <li><i class="fa fa-dot-circle-o"></i> Incorrect booking information.</li>
                    <li><i class="fa fa-dot-circle-o"></i> Payment or transaction issues.</li>
                    <li><i class="fa fa-dot-circle-o"></i> Booking confirmation delays.</li>
                    <li><i class="fa fa-dot-circle-o"></i> Service delivery concerns.</li>
                    <li><i class="fa fa-dot-circle-o"></i> Technical errors on the website.</li>
                    <li><i class="fa fa-dot-circle-o"></i> Suspected fraudulent activity.</li>
                    <li><i class="fa fa-dot-circle-o"></i> Any other service-related issue.</li>
                </ul>
            </div>
            <div class="support-section">
                <h4>
                    <i class="fa fa-phone-volume"></i>
                    Contact Support
                </h4>
                <p>
                    For assistance or service-related queries,
                    please reach out using our official contact details.
                </p>
                <ul>
                    <li>
                        <i class="fa fa-dot-circle-o"></i>
                        <strong>Email:</strong>&nbsp;
                        <a href="mailto:allsanatan@gmail.com">
                            allsanatan@gmail.com
                        </a>
                    </li>
                    <li>
                        <i class="fa fa-dot-circle-o"></i>
                        <strong>Phone / WhatsApp:</strong>&nbsp;
                        <a href="tel:9970711238">9970711238</a>
                    </li>
                    <li>
                        <i class="fa fa-dot-circle-o"></i>
                        <strong>Website:</strong>&nbsp;
                        <a href="https://allsanatan.com" target="_blank">
                            https://allsanatan.com
                        </a>
                    </li>
                </ul>
                <div class="support-note">
                    <p>
                        <strong>Support Hours:</strong>
                        [Insert Official Support Days and Timings]
                    </p>
                </div>
            </div>
            <div class="support-section">
                <h4>
                    <i class="fa fa-shield-alt"></i>
                    Important Information
                </h4>
                <ul>
                    <li>
                        <i class="fa fa-dot-circle-o"></i>
                        Please use only the contact details published
                        on the official All Sanatan website.
                    </li>
                    <li>
                        <i class="fa fa-dot-circle-o"></i>
                        Never share OTPs, passwords, UPI PINs or
                        card security credentials with anyone claiming
                        to provide customer support.
                    </li>
                    <li>
                        <i class="fa fa-dot-circle-o"></i>
                        All requests remain subject to the applicable
                        website terms and service policies.
                    </li>
                </ul>
            </div>
            <div class="support-bottom">
                <h4>Need Further Assistance?</h4>
                <p>
                    Our team is available to help you with your
                    All Sanatan service-related queries.
                </p>
                <a href="mailto:allsanatan@gmail.com" class="support-btn">
                    <i class="fa fa-envelope"></i>
                    Email Us
                </a>
                <a href="https://wa.me/919970711238" target="_blank"  class="support-btn">
                    <i class="fa fa-whatsapp"></i>
                    WhatsApp Us
                </a>
            </div>
        </div>
    </div>
</div>
@endsection