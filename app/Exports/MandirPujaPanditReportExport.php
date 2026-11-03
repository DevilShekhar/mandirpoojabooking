<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class MandirPujaPanditReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithColumnFormatting,
    WithColumnWidths,
    WithEvents
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

    /**
     * Excel headings.
     */
    public function headings(): array
    {
        return [
            'Booking Number',
            'Customer Name',
            'Customer Email',
            'Customer Mobile',
            'Temple',
            'Deity',
            'Puja',
            'Pandit',
            'Package',
            'Schedule Date',
            'Schedule Time',
            'Completed Date',
            'Person Count',
            'Total Amount',
            'Completed By',
            'Booking Status',
        ];
    }

    /**
     * Map booking data.
     */
    public function map($booking): array
    {
        return [
            (string) ($booking->booking_number ?? ''),
            $booking->customer_name ?? '',
            $booking->customer_email ?? '',
            (string) ($booking->customer_mobile ?? ''),
            $booking->temple->name ?? '',
            $booking->deity->name ?? '',
            $booking->puja->name ?? '',
            $booking->pandit->name ?? '',

            $booking->package_name
                ?? ($booking->pujaPackage->name ?? ''),

            $booking->schedule_date
                ? $booking->schedule_date->format('d-m-Y')
                : '',

            $booking->schedule_time ?? '',

            $booking->completed_at
                ? $booking->completed_at->format('d-m-Y H:i')
                : '',

            (int) ($booking->person_count ?? 0),

            // Fetch amount from base_amount database column.
            (float) ($booking->base_amount ?? 0),

            $booking->completedBy->name ?? '',
            $booking->booking_status ?? '',
        ];
    }

    /**
     * Excel column formats.
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'N' => '#,##0.00',
        ];
    }

    /**
     * Set readable column widths.
     */
    public function columnWidths(): array
    {
        return [
            'A' => 28,
            'B' => 22,
            'C' => 30,
            'D' => 18,
            'E' => 25,
            'F' => 20,
            'G' => 28,
            'H' => 22,
            'I' => 22,
            'J' => 18,
            'K' => 16,
            'L' => 22,
            'M' => 14,
            'N' => 18,
            'O' => 22,
            'P' => 18,
        ];
    }

    /**
     * Add summary rows after booking records.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $lastDataRow = $this->bookings->count() + 1;

                $summaryStartRow = $lastDataRow + 2;

                $totalBookings = $this->bookings->count();

                // Calculate total using base_amount.
                $totalAmount = $this->bookings->sum(function ($booking) {
                    return (float) ($booking->base_amount ?? 0);
                });

                // Total bookings.
                $sheet->setCellValue(
                    'A' . $summaryStartRow,
                    'Total Bookings'
                );

                $sheet->setCellValue(
                    'B' . $summaryStartRow,
                    $totalBookings
                );

                // Total amount.
                $sheet->setCellValue(
                    'A' . ($summaryStartRow + 1),
                    'Total Amount'
                );

                $sheet->setCellValue(
                    'B' . ($summaryStartRow + 1),
                    (float) $totalAmount
                );

                // Bold summary rows.
                $sheet->getStyle(
                    'A' . $summaryStartRow . ':B' . ($summaryStartRow + 1)
                )->getFont()->setBold(true);

                // Amount number format.
                $sheet->getStyle(
                    'B' . ($summaryStartRow + 1)
                )->getNumberFormat()->setFormatCode('#,##0.00');

                // Bold table heading.
                $sheet->getStyle('A1:P1')
                    ->getFont()
                    ->setBold(true);

                // Header background.
                $sheet->getStyle('A1:P1')
                    ->getFill()
                    ->setFillType(
                        \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
                    )
                    ->getStartColor()
                    ->setARGB('FFE8EEF7');

                // Freeze heading row.
                $sheet->freezePane('A2');

                // Enable Excel filters.
                $sheet->setAutoFilter(
                    'A1:P' . max(1, $lastDataRow)
                );
            },
        ];
    }
}