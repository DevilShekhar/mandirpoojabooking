<?php
namespace App\Exports;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TemplePerformanceReportExport implements FromCollection, ShouldAutoSize, WithStyles, WithTitle
{
    protected array $data;
    protected array $headerRows = [];
    protected array $sectionRows = [];
    protected int $summaryTotalRow = 0;
    protected int $customerTotalRow = 0;
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    public function collection(): Collection
    {
        $rows = [];
        $temple = $this->data['selectedTemple'];
        $summary = $this->data['summary'];
        $filters = $this->data['filters'];
        $reports = $this->data['reports'];
        $customerBookings = $this->data['customerBookings'];
        $statusLabels = [
            'all' => 'All Bookings',
            'paid' => 'Paid',
            'confirmed' => 'Confirmed',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'cancel_requested' => 'Cancel Requested',
        ];
        $selectedStatus = $statusLabels[$filters['status'] ?? 'all'] ?? 'All Bookings';
        $rows[] = ['TEMPLE PERFORMANCE REPORT'];
        $this->headerRows[] = count($rows);
        $rows[] = ['Temple Name', $temple->name];
        $rows[] = ['City', $temple->city_name ?? '-'];
        $rows[] = ['State', $temple->state_name ?? '-'];
        $rows[] = ['From Date', $filters['from_date'] ?: 'All Dates'];
        $rows[] = ['To Date', $filters['to_date'] ?: 'All Dates'];
        $rows[] = ['Selected Booking Status', $selectedStatus];
        $rows[] = ['Generated On', now()->format('d-m-Y h:i A')];
        $rows[] = [];
        $rows[] = ['OVERALL PERFORMANCE SUMMARY'];
        $this->sectionRows[] = count($rows);
        $rows[] = [
            'Total Bookings',
            'Paid Bookings',
            'Completed Bookings',
            'Cancelled Bookings',
            'Total Revenue',
        ];
        $this->headerRows[] = count($rows);
        $rows[] = [
            $summary['total_bookings'],
            $summary['paid_bookings'],
            $summary['completed_bookings'],
            $summary['cancelled_bookings'],
            $summary['total_revenue'],
        ];
        $this->summaryTotalRow = count($rows);
        $rows[] = [];
        $rows[] = ['TEMPLE PERFORMANCE DETAILS'];
        $this->sectionRows[] = count($rows);
        $rows[] = [
            'Sr. No.',
            'Booking Module',
            'Total Bookings',
            'Paid Bookings',
            'Completed Bookings',
            'Cancelled Bookings',
            'Revenue',
        ];
        $this->headerRows[] = count($rows);
        foreach ($reports as $index => $report) {
            $rows[] = [
                $index + 1,
                $report['module'],
                $report['total_bookings'],
                $report['paid_bookings'],
                $report['completed_bookings'],
                $report['cancelled_bookings'],
                $report['revenue'],
            ];
        }
        $rows[] = [
            '',
            'Grand Total',
            $summary['total_bookings'],
            $summary['paid_bookings'],
            $summary['completed_bookings'],
            $summary['cancelled_bookings'],
            $summary['total_revenue'],
        ];
        $this->summaryTotalRow = count($rows);
        $rows[] = [];
        $rows[] = ['CUSTOMER-WISE BOOKING DETAILS'];
        $this->sectionRows[] = count($rows);
        $rows[] = [
            'Sr. No.',
            'Customer Name',
            'Booking Type',
            'Booking Status',
            'Payment Status',
            'Amount',
            'Booking Date',
        ];
        $this->headerRows[] = count($rows);
        foreach ($customerBookings as $index => $booking) {
            $rows[] = [
                $index + 1,
                $booking['customer_name'],
                $booking['booking_type'],
                ucfirst(str_replace(
                    '_',
                    ' ',
                    $booking['booking_status'] ?? 'Pending'
                )),
                ucfirst($booking['payment_status'] ?? '-'),
                $booking['amount'],
                $booking['created_at']
                    ? date('d-m-Y h:i A', strtotime($booking['created_at']))
                    : '-',
            ];
        }
        if (count($customerBookings) > 0) {
            $rows[] = [
                '',
                '',
                '',
                '',
                'Customer Booking Total',
                collect($customerBookings)->sum('amount'),
                '',
            ];
            $this->customerTotalRow = count($rows);
        }
        return collect($rows);
    }

    public function styles(Worksheet $sheet): array
    {
        $styles = [];
        foreach ($this->headerRows as $row) {
            $styles[$row] = [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '343A40'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ];
        }
        foreach ($this->sectionRows as $row) {
            $styles[$row] = [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9E2F3'],
                ],
            ];
        }
        $styles[1] = [
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ];
        if ($this->summaryTotalRow > 0) {
            $styles[$this->summaryTotalRow] = [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2F0D9'],
                ],
            ];
        }
        if ($this->customerTotalRow > 0) {
            $styles[$this->customerTotalRow] = [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2F0D9'],
                ],
            ];
        }
        return $styles;
    }

    public function title(): string
    {
        return 'Temple Performance';
    }
}