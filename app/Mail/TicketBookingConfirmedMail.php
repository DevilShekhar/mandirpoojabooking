<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketBookingConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $bookings;
    public $ticketOtp;
    public $bookingReference;

    public function __construct($bookings,$ticketOtp,$bookingReference) {
        $this->bookings = $bookings;
        $this->ticketOtp = $ticketOtp;
        $this->bookingReference = $bookingReference;
    }

    public function build()
    {
        return $this->subject('Temple Ticket Booking Confirmed')->view('emails.ticket-booking-confirmed');
    }
}