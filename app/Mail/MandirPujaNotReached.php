<?php

namespace App\Mail;

use App\Models\PujaBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MandirPujaNotReached extends Mailable
{
    use Queueable, SerializesModels;
    public PujaBooking $booking;
    public function __construct(PujaBooking $booking)
    {
        $this->booking = $booking;
    }
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mandir Puja - Booking Marked as Not Reached',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.mandir-puja-not-reached',
            with: [
                'booking' => $this->booking,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}