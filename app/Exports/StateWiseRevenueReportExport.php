<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StateWiseRevenueReportExport implements
    FromArray,
    ShouldAutoSize,
    WithStyles,
    WithTitle
{
    protected Collection $rows;
    protected object $state;
    protected array $summary;
    protected array $filters;

    public function __construct(
        Collection $rows,
        object $state,
        array $summary,
        array $filters = []
    ) {
        $this->rows = $rows;
        $this->state = $state;
        $this->summary = $summary;
        $this->filters = $filters;
    }

    public function array(): array
    {
        $data = [];

        $data[] = ['State-wise Revenue Report'];
        $data[] = ['State', $this->state->name];
        $data[] = ['From Date', $this->filters['from_date'] ?: 'All Dates'];
        $data[] = ['To Date', $this->filters['to_date'] ?: 'All Dates'];
        $data[] = [];

        $data[] = [
            'Temple',
            'Module',
            'Total Bookings',
            'Paid Bookings',
            'Revenue',
        ];

        foreach ($this->rows as $row) {
            $data[] = [
                $row['temple_name'],
                $row['module'],
                $row['total_bookings'],
                $row['paid_bookings'],
                $row['revenue'],
            ];
        }

        $data[] = [];
        $data[] = ['Summary'];
        $data[] = ['Total Bookings', $this->summary['total_bookings']];
        $data[] = ['Paid Bookings', $this->summary['paid_bookings']];
        $data[] = ['Puja Bookings', $this->summary['puja_bookings']];
        $data[] = ['Ticket Bookings', $this->summary['ticket_bookings']];
        $data[] = ['Puja Revenue', $this->summary['puja_revenue']];
        $data[] = ['Ticket Revenue', $this->summary['ticket_revenue']];
        $data[] = ['Total Revenue', $this->summary['total_revenue']];

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
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
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '343A40'],
                ],
            ],
            8 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'State Wise Revenue';
    }
}