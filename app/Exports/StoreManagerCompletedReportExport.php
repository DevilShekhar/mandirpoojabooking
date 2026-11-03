<?php
namespace App\Exports;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StoreManagerCompletedReportExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithColumnWidths
{
    protected $bookings;
    protected $summary;

    public function __construct($bookings, $summary)
    {
        $this->bookings = $bookings;
        $this->summary = $summary;
    }

    public function collection(): Collection
    {
        $rows = collect();
        foreach ($this->bookings as $booking) {
            foreach ($booking->report_items ?? [] as $item) {
                $rows->push([
                    $booking->booking_number ?? '',
                    $booking->customer_name ?? '',
                    $booking->report_temple_name
                        ?? optional($booking->temple)->name
                        ?? '',
                    $booking->report_store_manager ?? 'Not Assigned',
                    $item['name'] ?? '',
                    $item['unit'] ?? '',
                    $item['quantity'] ?? 1,
                    $item['price'] ?? 0,
                    $item['total'] ?? 0,
                    $this->formatDate($booking->completed_at),
                    $this->formatDate($booking->samgri_otp_verified_at),
                ]);
            }
        }
        $rows->push([]);
        $rows->push([
            'Total Bookings',
            $this->summary['total_bookings'] ?? 0,
        ]);
        $rows->push([
            'Total Item Quantity',
            '',
            '',
            '',
            '',
            '',
            $this->summary['total_quantity'] ?? 0,
        ]);
        $rows->push([
            'Total Item Amount',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            $this->summary['total_amount'] ?? 0,
        ]);
        return $rows;
    }
    private function formatDate($date): string
    {
        if (empty($date)) {
            return '';
        }
        return Carbon::parse($date)->format('d-m-Y H:i');
    }

    public function headings(): array
    {
        return [
            'Booking Number',
            'Customer Name',
            'Temple',
            'Store Manager',
            'Item Name',
            'Unit',
            'Quantity',
            'Item Price',
            'Item Total',
            'Booking Completed Date',
            'Samagri Verified Date',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '263B5A'],
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 24,
            'B' => 24,
            'C' => 24,
            'D' => 25,
            'E' => 24,
            'F' => 14,
            'G' => 12,
            'H' => 15,
            'I' => 15,
            'J' => 23,
            'K' => 23,
        ];
    }
}