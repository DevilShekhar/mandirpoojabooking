<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class OverallBusinessPerformanceExport implements FromArray, WithStyles
{
    protected array $summary;
    protected array $modules;
    public function __construct(array $summary, array $modules)
    {
        $this->summary = $summary;
        $this->modules = $modules;
    }
    public function array(): array
    {
        $rows = [
            ['OVERALL BUSINESS PERFORMANCE REPORT'],
            [],
            ['SUMMARY', 'VALUE'],
            ['Total Bookings', $this->summary['total_bookings']],
            ['Paid Bookings', $this->summary['paid_count']],
            ['Paid Amount', $this->summary['paid_amount']],
            ['Cancelled Bookings', $this->summary['cancelled_count']],
            ['Cancelled Amount', $this->summary['cancelled_amount']],
            ['Processed Refunds', $this->summary['refund_count']],
            ['Refund Amount', $this->summary['refund_amount']],
            ['Net Collection', $this->summary['net_collection']],
            [],
            [
                'Booking Module',
                'Total Bookings',
                'Paid Bookings',
                'Paid Amount',
                'Cancelled Bookings',
                'Cancelled Amount',
                'Processed Refunds',
                'Refund Amount',
                'Net Collection',
            ],
        ];
        foreach ($this->modules as $module) {
            $rows[] = [
                $module['module'],
                $module['total_bookings'],
                $module['paid_count'],
                $module['paid_amount'],
                $module['cancelled_count'],
                $module['cancelled_amount'],
                $module['refund_count'],
                $module['refund_amount'],
                $module['paid_amount'] - $module['refund_amount'],
            ];
        }
        $rows[] = [
            'GRAND TOTAL',
            $this->summary['total_bookings'],
            $this->summary['paid_count'],
            $this->summary['paid_amount'],
            $this->summary['cancelled_count'],
            $this->summary['cancelled_amount'],
            $this->summary['refund_count'],
            $this->summary['refund_amount'],
            $this->summary['net_collection'],
        ];
        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()
            ->setBold(true)
            ->setSize(16);
        $sheet->getStyle('A3:B3')->getFont()->setBold(true);
        $sheet->getStyle('A13:I13')->getFont()->setBold(true);
        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A{$lastRow}:I{$lastRow}")
            ->getFont()->setBold(true);
        $sheet->getStyle("D14:D{$lastRow}")
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');
        $sheet->getStyle("F14:F{$lastRow}")
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');
        $sheet->getStyle("H14:I{$lastRow}")
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');
        return [];
    }
}