<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DailyWeeklyMonthlyYearlyRevenueExport implements FromArray, WithStyles
{
    protected array $summary;
    protected array $periods;
    protected string $period;
    public function __construct(array $summary,array $periods,string $period) {
        $this->summary = $summary;
        $this->periods = $periods;
        $this->period = $period;
    }

    public function array(): array
    {
        $rows = [
            ['DAILY / WEEKLY / MONTHLY / YEARLY REVENUE REPORT'],
            ['Report Type', ucfirst($this->period)],
            [],
            ['SUMMARY', 'VALUE'],
            ['Total Bookings', $this->summary['total_bookings']],
            ['Ticket Revenue', $this->summary['ticket_revenue']],
            ['Event Revenue', $this->summary['event_revenue']],
            ['Puja Revenue', $this->summary['puja_revenue']],
            ['Total Revenue', $this->summary['total_revenue']],
            [],
            [
                'Period',
                'Ticket Bookings',
                'Ticket Revenue',
                'Event Bookings',
                'Event Revenue',
                'Puja Bookings',
                'Puja Revenue',
                'Total Bookings',
                'Total Revenue',
            ],
        ];
        foreach ($this->periods as $item) {
            $rows[] = [
                $item['period'],
                $item['ticket_bookings'],
                $item['ticket_revenue'],
                $item['event_bookings'],
                $item['event_revenue'],
                $item['puja_bookings'],
                $item['puja_revenue'],
                $item['total_bookings'],
                $item['total_revenue'],
            ];
        }
        $rows[] = [
            'GRAND TOTAL',
            $this->summary['ticket_bookings'],
            $this->summary['ticket_revenue'],
            $this->summary['event_bookings'],
            $this->summary['event_revenue'],
            $this->summary['puja_bookings'],
            $this->summary['puja_revenue'],
            $this->summary['total_bookings'],
            $this->summary['total_revenue'],
        ];
        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1')
            ->getFont()
            ->setBold(true)
            ->setSize(16);
        $sheet->getStyle('A4:B4')
            ->getFont()
            ->setBold(true);
        $sheet->getStyle('A11:I11')
            ->getFont()
            ->setBold(true);
        $sheet->getStyle("A{$lastRow}:I{$lastRow}")
            ->getFont()
            ->setBold(true);
        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        // Summary amount formatting
        $sheet->getStyle('B6:B9')
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');
        // Period-wise revenue formatting
        if ($lastRow >= 12) {
            foreach (['C', 'E', 'G', 'I'] as $column) {
                $sheet->getStyle("{$column}12:{$column}{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00');
            }
        }
        return [];
    }
}