<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PujaBookingCustomerMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $details)
    {
    }

    public function build(): self
    {
        return $this
            ->subject(
                'Puja Booking Confirmation - ' .
                ($this->details['booking_number'] ?? 'Confirmed')
            )
            ->view('emails.puja-booking-customer');
    }
}