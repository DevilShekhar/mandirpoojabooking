<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TempleWiseRevenueReportExport implements FromArray, WithStyles, WithColumnWidths
{
    protected array $reportData;

    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    public function array(): array
    {
        $summary = $this->reportData['summary'];

        return [
            ['Temple-wise Revenue Report'],
            ['Temple Name', $this->reportData['selectedTemple']->name],
            ['From Date', $this->reportData['fromDate'] ?? 'All Dates'],
            ['To Date', $this->reportData['toDate'] ?? 'All Dates'],
            [],
            ['Booking Module', 'Booking Count', 'Revenue'],
            [
                'Ticket Booking',
                $summary['ticket_bookings'],
                $summary['ticket_revenue'],
            ],
            [
                'Puja Booking',
                $summary['puja_bookings'],
                $summary['puja_revenue'],
            ],
            [
                'Grand Total',
                $summary['total_bookings'],
                $summary['total_revenue'],
            ],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1:C1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A6:C6')->getFont()->setBold(true);
        $sheet->getStyle('A9:C9')->getFont()->setBold(true);
        $sheet->getStyle('C7:C9')->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('A6:C9')->getAlignment()->setVertical('center');

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 20,
            'C' => 22,
        ];
    }
}