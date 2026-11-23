<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SanatanBookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $sanatan;
    public $customer;

    public function __construct($booking, $sanatan, $customer)
    {
        $this->booking = $booking;
        $this->sanatan = $sanatan;
        $this->customer = $customer;
    }

    public function build()
    {
        return $this->subject('Sanatan Event Booking Confirmation')->view('emails.sanatan-booking-confirmation');
    }
}