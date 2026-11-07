<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingSummaryReportExport implements FromArray,WithStyles,WithColumnWidths
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
            ['BOOKING SUMMARY REPORT'],
            ['Temple', $data['selectedTemple']->name],
            ['From Date', $data['filters']['from_date'] ?: 'All'],
            ['To Date', $data['filters']['to_date'] ?: 'All'],
            [
                'Note',
                'Event Booking counts are overall and not filtered by Temple.',
            ],
            [],
            [
                'Booking Module',
                'Pending',
                'Confirmed',
                'Completed',
                'Cancelled',
                'Total',
            ],
        ];
        foreach ($data['reports'] as $module => $report) {
            $rows[] = [
                $data['moduleNames'][$module],
                $report['pending'],
                $report['confirmed'],
                $report['completed'],
                $report['cancelled'],
                $report['total'],
            ];
        }
        $rows[] = [
            'Grand Total',
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

            7 => [
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
            'A' => 30,
            'B' => 18,
            'C' => 18,
            'D' => 18,
            'E' => 18,
            'F' => 18,
        ];
    }
}