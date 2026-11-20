<?php

namespace App\Mail;

use App\Models\TicketBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotReachedTicketBookingMail extends Mailable
{
    use Queueable, SerializesModels;
    public $ticketBooking;
    public function __construct(TicketBooking $ticketBooking)
    {
        $this->ticketBooking = $ticketBooking;
    }

    public function build()
    {
        return $this->subject('Your Temple Visit – We Welcome You Again!')->view('emails.not-reached-ticket-booking');
    }
}