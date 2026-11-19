<?php

namespace App\Mail;

use App\Models\PujaBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PujaBookingCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(PujaBooking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->subject('Your Puja Booking Has Been Completed')
            ->view('emails.puja-booking-completed');
    }
}
