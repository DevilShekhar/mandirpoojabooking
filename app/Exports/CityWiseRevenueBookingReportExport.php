<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CityWiseRevenueBookingReportExport implements
    FromArray,
    ShouldAutoSize,
    WithStyles,
    WithTitle
{
    protected $report;

    public function __construct(array $report)
    {
        $this->report = $report;
    }

    public function array(): array
    {
        $report = $this->report;

        $data = [
            ['City-wise Revenue & Booking Report'],
            ['State', $report['state']->name],
            ['City', $report['city']->name],
            [
                'From Date',
                $report['filters']['from_date'] ?? 'All',
            ],
            [
                'To Date',
                $report['filters']['to_date'] ?? 'All',
            ],
            [],
            [
                '#',
                'Temple Name',
                'Module',
                'Total Bookings',
                'Paid Bookings',
                'Revenue (INR)',
            ],
        ];

        foreach ($report['rows'] as $index => $row) {
            $data[] = [
                $index + 1,
                $row['temple_name'],
                $row['module'],
                $row['total_bookings'],
                $row['paid_bookings'],
                $row['revenue'],
            ];
        }

        $data[] = [
            '',
            'GRAND TOTAL',
            '',
            $report['summary']['total_bookings'],
            $report['summary']['paid_bookings'],
            $report['summary']['total_revenue'],
        ];

        $data[] = [];

        $data[] = [
            'Total Temples',
            $report['summary']['total_temples'],
        ];

        $data[] = [
            'Puja Bookings',
            $report['summary']['puja_bookings'],
        ];

        $data[] = [
            'Ticket Bookings',
            $report['summary']['ticket_bookings'],
        ];

        $data[] = [
            'Puja Revenue',
            $report['summary']['puja_revenue'],
        ];

        $data[] = [
            'Ticket Revenue',
            $report['summary']['ticket_revenue'],
        ];

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        $headerRow = 7;
        $totalRow = 8 + count($this->report['rows']);
        $lastRow = $totalRow + 6;

        $sheet->getStyle('A1:F1')->getFont()
            ->setBold(true)
            ->setSize(16);

        $sheet->getStyle("A{$headerRow}:F{$headerRow}")
            ->getFont()
            ->setBold(true)
            ->getColor()
            ->setRGB('FFFFFF');

        $sheet->getStyle("A{$headerRow}:F{$headerRow}")
            ->getFill()
            ->setFillType('solid')
            ->getStartColor()
            ->setRGB('17365D');

        $sheet->getStyle("A{$totalRow}:F{$totalRow}")
            ->getFont()
            ->setBold(true);

        $sheet->getStyle("A{$totalRow}:F{$totalRow}")
            ->getFill()
            ->setFillType('solid')
            ->getStartColor()
            ->setRGB('E8EFF7');

        $sheet->getStyle("F8:F{$totalRow}")
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');

        $sheet->getStyle("B2:B5")
            ->getFont()
            ->setBold(true);

        $sheet->getStyle("A1:F{$lastRow}")
            ->getAlignment()
            ->setVertical('center');

        $sheet->freezePane('A8');

        return [];
    }

    public function title(): string
    {
        return 'City Revenue Booking';
    }
}