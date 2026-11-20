<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketBookingCompletedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $booking;
    public function __construct($booking)
    {
        $this->booking = $booking;
    }
    public function build()
    {
        return $this->subject('Thank You for Visiting Us!')->view('emails.ticket-booking-completed');
    }
}
