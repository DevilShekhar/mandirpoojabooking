<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RefundFinancialReconciliationReportExport implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithStyles,
    WithTitle
{
    protected Collection $refunds;

    public function __construct(Collection $refunds)
    {
        $this->refunds = $refunds;
    }

    public function collection()
    {
        return $this->refunds->map(function ($row) {
            return [
                $row['module'],
                $row['refund_id'],
                $row['payment_id'],
                $row['order_id'],
                $row['booking_id'],
                $row['customer_name'],
                $row['temple_name'],
                $row['booking_amount'],
                $row['refund_amount'],
                ucfirst(str_replace('_', ' ', $row['refund_status'])),
                $row['refund_date'],
                $row['failure_details'],
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Module',
            'Refund ID',
            'Payment ID',
            'Order ID',
            'Booking ID',
            'Customer',
            'Temple',
            'Booking Amount',
            'Refund Amount',
            'Refund Status',
            'Refund Date',
            'Failure Details',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '343A40'],
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Refund Reconciliation';
    }
}