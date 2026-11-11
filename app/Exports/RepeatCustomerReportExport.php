<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RepeatCustomerReportExport implements FromArray, WithStyles, WithColumnWidths
{
    protected array $reportData;

    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    public function array(): array
    {
        $data = $this->reportData;

        $rows = [
            ['REPEAT CUSTOMER REPORT'],
            ['From Date', $data['filters']['from_date'] ?: 'All'],
            ['To Date', $data['filters']['to_date'] ?: 'All'],
            [],
            [
                'Customer Name',
                'Booking Type',
                'Temple Name',
                'City',
                'State',
                'Booking Count',
                'Last Booking Date',
            ],
        ];

        foreach ($data['rows'] as $row) {
            $rows[] = [
                $row['customer_name'],
                $row['booking_type'],
                $row['temple_name'],
                $row['city_name'],
                $row['state_name'],
                $row['booking_count'],
                $row['last_booking']
                    ? date('d-m-Y h:i A', strtotime($row['last_booking']))
                    : '-',
            ];
        }

        $rows[] = [
            'Grand Total',
            '',
            '',
            '',
            '',
            $data['summary']['total_bookings'],
            '',
        ];

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        $lastRow = count($this->array());

        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                ],
            ],
            5 => [
                'font' => [
                    'bold' => true,
                ],
            ],
            $lastRow => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 22,
            'C' => 30,
            'D' => 20,
            'E' => 22,
            'F' => 18,
            'G' => 25,
        ];
    }
}