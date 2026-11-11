<?php
namespace App\Exports;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaymentTransactionReportExport implements FromArray, ShouldAutoSize, WithStyles, WithTitle
{
    protected $transactions;
    public function __construct(Collection $transactions)
    {
        $this->transactions = $transactions;
    }

    public function array(): array
    {
        $data = [[
            'Module',
            'Transaction ID',
            'Order ID',
            'Booking ID',
            'Customer',
            'Temple',
            'Service',
            'Amount',
            'Currency',
            'Payment Status',
            'Gateway',
            'Transaction Date',
            'Failure Details',
        ]];
        foreach ($this->transactions as $row) {
            $data[] = [
                $row['module'],
                $row['transaction_id'],
                $row['order_id'],
                $row['booking_id'],
                $row['customer_name'],
                $row['temple_name'],
                $row['service_name'],
                $row['amount'],
                $row['currency'],
                ucfirst($row['payment_status']),
                $row['gateway'],
                $row['transaction_date'],
                $row['failure_details'],
            ];
        }
        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['vertical' => 'center'],
            ],
        ];
    }

    public function title(): string
    {
        return 'Payment Transactions';
    }
}