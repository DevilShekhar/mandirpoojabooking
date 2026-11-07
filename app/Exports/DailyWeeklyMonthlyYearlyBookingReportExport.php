<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DailyWeeklyMonthlyYearlyBookingReportExport implements FromArray, WithStyles, WithColumnWidths
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
            ['DAILY / WEEKLY / MONTHLY / YEARLY BOOKING REPORT'],
            ['Report Type', ucfirst($data['filters']['period'])],
            ['From Date', $data['filters']['from_date'] ?: 'All'],
            ['To Date', $data['filters']['to_date'] ?: 'All'],
            [],
            [
                'Period',
                'Booking Module',
                'Pending',
                'Confirmed',
                'Completed',
                'Cancelled',
                'Total',
            ],
        ];
        foreach ($data['rows'] as $row) {
            $rows[] = [
                $row['period'],
                $row['module'],
                $row['pending'],
                $row['confirmed'],
                $row['completed'],
                $row['cancelled'],
                $row['total'],
            ];
        }
        $rows[] = [
            'Grand Total',
            '',
            $data['summary']['pending'],
            $data['summary']['confirmed'],
            $data['summary']['completed'],
            $data['summary']['cancelled'],
            $data['summary']['total'],
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
            6 => [
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
            'A' => 24,
            'B' => 25,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 15,
        ];
    }
}