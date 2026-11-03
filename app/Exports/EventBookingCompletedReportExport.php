<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EventBookingCompletedReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $bookings;

    public function __construct(Collection $bookings)
    {
        $this->bookings = $bookings;
    }

    public function collection()
    {
        return $this->bookings;
    }

    public function headings(): array
    {
        return [
            'Booking ID',
            'Event Name',
            'Customer Name',
            'Event Creator',
            'Event Date',
            'Event Time',
            'Amount',
            'Payment Status',
            'Booking Status',
            'Completed Date',
            'Completed By',
        ];
    }

    public function map($booking): array
    {
        return [
            $booking->id,
            $booking->event_name ?? '',
            $booking->customer_name ?? '',
            $booking->event_creator_name ?? '',
            $booking->event_date ?? '',
            $booking->event_time ?? '',
            (float) ($booking->amount ?? 0),
            $booking->payment_status ?? '',
            $booking->booking_status ?? '',
            $booking->completed_at ?? '',
            $booking->completed_by_name ?? '',
        ];
    }
}