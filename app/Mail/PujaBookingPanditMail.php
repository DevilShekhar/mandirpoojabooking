<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PujaBookingPanditMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $details)
    {
    }

    public function build(): self
    {
        return $this
            ->subject(
                'New Puja Booking Assigned - ' .
                $this->details['booking_number']
            )
            ->view('emails.puja-booking-pandit');
    }
}