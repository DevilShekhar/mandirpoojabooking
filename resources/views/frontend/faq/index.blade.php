
@extends('frontend.layouts.app')

@section('title', 'Frequently Asked Questions - All Sanatan')

@section('content')
<style>
    .faq-important-section {
    padding: 10px 0;
}

.faq-important-card {
    display: flex;
    gap: 20px;
    padding: 30px;
    background: #fffaf5;
    border: 1px solid #f2dfcd;
    border-left: 5px solid #ed741f;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.04);
}

.faq-important-icon {
    flex: 0 0 55px;
    width: 55px;
    height: 55px;
    background: #ed741f;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 25px;
}

.faq-important-content {
    flex: 1;
}

.faq-important-content h4 {
    color: #333;
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 15px;
}

.faq-important-content p {
    color: #666;
    font-size: 14px;
    line-height: 1.8;
    margin-bottom: 12px;
}

.faq-support-box {
    background: #fff;
    border: 1px solid #f1e2d4;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
}

.faq-support-box h5 {
    color: #ed741f;
    font-size: 17px;
    font-weight: 600;
    margin-bottom: 10px;
}

.faq-support-details {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 15px;
}

.faq-support-details a {
    color: #ed741f;
    font-weight: 600;
    text-decoration: none;
}

.faq-support-details a i {
    margin-right: 7px;
}

@media (max-width: 576px) {
    .faq-important-card {
        padding: 20px 15px;
        gap: 12px;
    }

    .faq-important-icon {
        flex: 0 0 40px;
        width: 40px;
        height: 40px;
        font-size: 19px;
    }

    .faq-important-content h4 {
        font-size: 19px;
    }

    .faq-support-details {
        flex-direction: column;
        gap: 12px;
    }
}
</style>
@php
    $faqSections = [
        [
            'title' => 'General Questions',
            'faqs' => [
                [
                    'question' => 'What is All Sanatan?',
                    'answer' => 'All Sanatan is an online platform that helps devotees explore and arrange religious services such as temple darshan, pooja ceremonies, pandit bookings and selected online religious services.'
                ],
                [
                    'question' => 'What services are available on All Sanatan?',
                    'answer' => 'Depending on availability, services may include temple darshan bookings, pooja bookings, pandit services, online pooja, live pooja streaming, festival darshan and VIP darshan where officially available.'
                ],
                [
                    'question' => 'Does All Sanatan operate or manage temples?',
                    'answer' => 'Not necessarily. Depending on the service, All Sanatan may act as a booking facilitator or direct service provider. A booking does not mean that All Sanatan owns, manages or officially represents the temple.'
                ],
            ]
        ],
        [
            'title' => 'Booking Questions',
            'faqs' => [
                [
                    'question' => 'How can I make a booking?',
                    'answer' => 'Select the required service on the website, review the available details, provide the requested information and complete the applicable booking and payment process.'
                ],
                [
                    'question' => 'How will I know whether my booking is confirmed?',
                    'answer' => 'Booking confirmation will be communicated through the applicable confirmation process, such as the website or the registered contact details provided during booking.'
                ],
                [
                    'question' => 'What should I do if I do not receive a confirmation?',
                    'answer' => 'If your payment was deducted but you did not receive confirmation, contact us at <a href="mailto:allsanatan@gmail.com">allsanatan@gmail.com</a> or <a href="tel:9970711238">9970711238</a> with your transaction details and booking information, if available.'
                ],
                [
                    'question' => 'Can I book for family members?',
                    'answer' => 'You may book for other participants where the particular service allows it. Ensure that all participant details are accurate and that you follow the service-specific instructions.'
                ],
            ]
        ],
        [
            'title' => 'Darshan and Temple Questions',
            'faqs' => [
                [
                    'question' => 'Does a darshan booking guarantee entry into a temple?',
                    'answer' => 'A booking is subject to the applicable temple rules, entry conditions and confirmed booking entitlement. Users must follow the instructions communicated for their booking.'
                ],
                [
                    'question' => 'Can temple timings or darshan schedules change?',
                    'answer' => 'Yes. Temple schedules may change due to religious events, administrative decisions, special occasions or other circumstances.'
                ],
                [
                    'question' => 'Is VIP darshan available at every temple?',
                    'answer' => 'No. VIP darshan is offered only where it is officially available and permitted by the relevant temple or authority. All Sanatan does not guarantee VIP access where it is not officially authorised.'
                ],
            ]
        ],
        [
            'title' => 'Pooja and Pandit Questions',
            'faqs' => [
                [
                    'question' => 'Can I book a pandit through All Sanatan?',
                    'answer' => 'You may be able to book a pandit for selected religious ceremonies, depending on the services and availability displayed on the website.'
                ],
                [
                    'question' => 'Can I request a specific pooja?',
                    'answer' => 'You may select from the pooja services listed on the website. Additional requests will depend on the relevant service provider\'s availability and acceptance.'
                ],
                [
                    'question' => 'Can pooja arrangements or timings change?',
                    'answer' => 'Yes. Arrangements may be affected by the availability of the pandit, temple schedules, religious requirements or other unavoidable circumstances.'
                ],
            ]
        ],
        [
            'title' => 'Online Pooja and Live Streaming',
            'faqs' => [
                [
                    'question' => 'Can I participate in pooja online?',
                    'answer' => 'Online pooja services may be available for selected ceremonies. Please check the relevant service description for participation details.'
                ],
                [
                    'question' => 'Will I receive a live streaming link?',
                    'answer' => 'Where live streaming is included in a confirmed booking, the applicable access details will be communicated through the designated process.'
                ],
                [
                    'question' => 'What if the live stream does not work?',
                    'answer' => 'Please contact customer support with your booking ID, scheduled time and a description of the technical issue.'
                ],
            ]
        ],
        [
            'title' => 'Payments and Refunds',
            'faqs' => [
                [
                    'question' => 'What payment methods can I use?',
                    'answer' => 'Available payment methods will be displayed during checkout and may depend on the payment gateway and transaction options enabled on the website.'
                ],
                [
                    'question' => 'What should I do if money is deducted but my booking is not confirmed?',
                    'answer' => 'Contact customer support with your payment reference and booking details. The transaction and booking status will need to be checked.'
                ],
                [
                    'question' => 'Can I cancel my booking?',
                    'answer' => 'Cancellation eligibility depends on the type of service and its applicable cancellation terms. Please review the Cancellation & Refund Policy before submitting a request.'
                ],
                [
                    'question' => 'Will I receive a refund if I cancel?',
                    'answer' => 'Refund eligibility, applicable deductions and processing timelines depend on the relevant service policy and circumstances. A refund is not automatic for every cancellation.'
                ],
            ]
        ],
        [
            'title' => 'Customer Support',
            'faqs' => [
                [
                    'question' => 'How can I contact All Sanatan?',
                    'answer' => 'You can contact us through email at <a href="mailto:allsanatan@gmail.com">allsanatan@gmail.com</a> or by phone/WhatsApp at <a href="tel:9970711238">9970711238</a>.'
                ],
                [
                    'question' => 'What information should I provide when contacting support?',
                    'answer' => 'Please provide your name, registered contact details, booking ID or transaction reference, and a clear description of your query.'
                ],
                [
                    'question' => 'How can I submit a complaint?',
                    'answer' => 'You may contact customer support or follow the procedure described in our Grievance Redressal Policy.'
                ],
            ]
        ],
    ];
@endphp

<!-- Begin Breadcrumb Area -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="breadcrumb-content">
            <ul>
                <li>
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li class="active">FAQ</li>
            </ul>
        </div>
    </div>
</div>
<!-- Breadcrumb Area End Here -->

<!-- Begin Frequently Area -->
<div class="frequently-area pt-60 pb-50">
    <div class="container">

        <div class="row">
            <div class="col-md-12">

                <div class="frequently-content">
                    <div class="frequently-desc">

                        <h3>Frequently Asked Questions (FAQs)</h3>

                        <p>
                            Welcome to the All Sanatan FAQ section. Here you can find
                            answers to common questions about our temple darshan,
                            pooja ceremonies, pandit bookings, online pooja services,
                            bookings, payments and customer support.
                        </p>

                        <p>
                            We aim to make your spiritual journey and booking
                            experience convenient and transparent. Please explore
                            the questions below to find the information you need.
                        </p>

                    </div>
                </div>

                

                <!-- Begin Frequently Accordion -->
                <div class="frequently-accordion">
                    <div id="accordion">

                        @php
                            $faqCounter = 0;
                        @endphp

                        @foreach($faqSections as $sectionIndex => $section)

                            <!-- FAQ Section Heading -->
                            <div class="faq-section-heading mt-4 mb-3">
                                <h4>
                                    {{ $sectionIndex + 1 }}.
                                    {{ $section['title'] }}
                                </h4>
                            </div>

                            @foreach($section['faqs'] as $faq)

                                @php
                                    $faqCounter++;
                                    $headingId = 'headingFaq' . $faqCounter;
                                    $collapseId = 'collapseFaq' . $faqCounter;
                                    $isFirst = $faqCounter === 1;
                                @endphp

                                <div class="card {{ $isFirst ? 'actives' : '' }}">

                                    <div class="card-header"
                                         id="{{ $headingId }}">

                                        <h5 class="mb-0">

                                            <a class="{{ $isFirst ? '' : 'collapsed' }}"
                                               data-toggle="collapse"
                                               data-target="#{{ $collapseId }}"
                                               aria-expanded="{{ $isFirst ? 'true' : 'false' }}"
                                               aria-controls="{{ $collapseId }}">

                                                {{ $faq['question'] }}

                                            </a>

                                        </h5>

                                    </div>

                                    <div id="{{ $collapseId }}"
                                         class="collapse {{ $isFirst ? 'show' : '' }}"
                                         aria-labelledby="{{ $headingId }}"
                                         data-parent="#accordion">

                                        <div class="card-body">

                                            {!! $faq['answer'] !!}

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        @endforeach

                    </div>
                </div>
                <!-- Frequently Accordion End Here -->
                <div class="faq-important-section mt-5 mb-4">
                    <div class="faq-important-card">
                        <div class="faq-important-icon">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                        </div>
                        <div class="faq-important-content">
                            <h4>Important Information</h4>
                            <p>
                                The availability of services, booking procedures, charges and
                                conditions may vary by temple, location and service provider.
                            </p>
                            <p>
                                Please read the applicable Terms &amp; Conditions and
                                service-specific policies before making a booking or payment.
                            </p>
                            <div class="faq-support-box">
                                <h5>
                                    <i class="fa fa-headphones" aria-hidden="true"></i>
                                    Need Further Assistance?
                                </h5>
                                <p>
                                    For any questions or further assistance, feel free to
                                    contact our support team.
                                </p>
                                <div class="faq-support-details">
                                    <a href="mailto:allsanatan@gmail.com">
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                        allsanatan@gmail.com
                                    </a>
                                    <a href="tel:9970711238">
                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                        9970711238
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- Frequently Area End Here -->

@endsection
