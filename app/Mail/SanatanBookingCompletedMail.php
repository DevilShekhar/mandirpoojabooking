<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SanatanBookingCompletedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $order;
    public function __construct($order)
    {
        $this->order = $order;
    }
    public function build()
    {
        return $this->subject('Thank You for Choosing Our Sanatan Puja Service!')->view('emails.sanatan-booking-completed');
    }
}