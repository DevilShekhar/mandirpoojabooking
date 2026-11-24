<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class OnlinePujaPaymentSuccessfulMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $customer;
    public $puja;
    public $package;
    public $paymentId;
    public $bookingStatus;
    public $pujaDate;
    public $pujaTime;
    public $muhuratEnd;

    public function __construct(
        $order,
        $customer,
        $puja,
        $package,
        $paymentId,
        $bookingStatus
    ) {
        $this->order = $order;
        $this->customer = $customer;
        $this->puja = $puja;
        $this->package = $package;
        $this->paymentId = $paymentId;
        $this->bookingStatus = $bookingStatus;

        // Format Puja Date and Time
        $this->pujaDate = 'N/A';
        $this->pujaTime = null;
        $this->muhuratEnd = null;

        if ($puja && !empty($puja->puja_datetime)) {
            try {
                $dateTime = Carbon::parse($puja->puja_datetime);

                $this->pujaDate = $dateTime->format('d M Y');

                if ($dateTime->format('H:i:s') !== '00:00:00') {
                    $this->pujaTime = $dateTime->format('h:i A');
                }
            } catch (\Throwable $e) {
                $this->pujaDate = $puja->puja_datetime;
            }
        }

        if ($puja && !empty($puja->muhurat_end_at)) {
            try {
                $this->muhuratEnd = Carbon::parse(
                    $puja->muhurat_end_at
                )->format('h:i A');
            } catch (\Throwable $e) {
                $this->muhuratEnd = $puja->muhurat_end_at;
            }
        }
    }

    public function build()
    {
        return $this->subject('Online Puja Booking Payment Successful')
            ->view('emails.online-puja.payment-success');
    }
}