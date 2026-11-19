<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PujaCompletedOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $bookingDetails;

    public function __construct(array $bookingDetails)
    {
        $this->bookingDetails = $bookingDetails;
    }

    public function build()
    {
        return $this->subject('Puja Samagri Delivery - Completion OTP')
            ->view('emails.puja-completed-otp');
    }
}