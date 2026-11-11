<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class PujariAssignmentPerformanceExport implements FromArray, ShouldAutoSize, WithStyles, WithTitle
{
    protected $data;
    protected $sectionRows = [];
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function title(): string
    {
        return 'Pujari Performance';
    }
    public function array(): array
    {
        $data = $this->data;
        $rows = [];
        $rows[] = ['PUJARI ASSIGNMENT & PERFORMANCE REPORT'];
        $rows[] = [
            'Temple',
            $data['selectedTemple']->name ?? 'N/A',
            'Pujari',
            $data['selectedPandit']->name ?? 'All Pujaris'
        ];
        $rows[] = [
            'Status',
            $data['selectedStatus'] ?? 'All',
            'From Date',
            $data['filters']['from_date'] ?? 'All Dates',
            'To Date',
            $data['filters']['to_date'] ?? 'All Dates'
        ];
        $rows[] = [];
        $this->sectionRows['summary_title'] = count($rows) + 1;
        $rows[] = ['PERFORMANCE SUMMARY'];
        $this->sectionRows['summary_header'] = count($rows) + 1;
        $rows[] = [
            'Total Assignments',
            'Confirmed',
            'Pending',
            'Cancelled',
            'Paid Bookings',
            'Refund Bookings',
            'Refund Amount',
            'Revenue',
            'Completion Percentage'
        ];
        $rows[] = [
            $data['summary']['total_assignments'],
            $data['summary']['confirmed'] ?? 0,
            $data['summary']['pending'],
            $data['summary']['cancelled'],
            $data['summary']['paid_bookings'],
            $data['summary']['refund_bookings'],
            $data['summary']['refund_amount'],
            $data['summary']['revenue'],
            $data['summary']['completion_percentage'] . '%'
        ];
        $rows[] = [];
        $this->sectionRows['performance_title'] = count($rows) + 1;
        $rows[] = ['PUJARI-WISE PERFORMANCE'];
        $this->sectionRows['performance_header'] = count($rows) + 1;
        $rows[] = [
            'Pujari Name',
            'Email',
            'Total Assignments',
            'Confirmed',
            'Pending',
            'Cancelled',
            'Paid Bookings',
            'Revenue',
            'Completion Percentage'
        ];
        foreach ($data['performanceRows'] as $row) {
            $rows[] = [
                $row->pandit_name,
                $row->pandit_email,
                $row->total_assignments,
                $row->confirmed ?? 0,
                $row->pending,
                $row->cancelled,
                $row->paid_bookings,
                $row->revenue,
                $row->completion_percentage . '%'
            ];
        }
        $rows[] = [];
        $this->sectionRows['booking_title'] = count($rows) + 1;
        $rows[] = ['PUJA ASSIGNMENT DETAILS'];
        $this->sectionRows['booking_header'] = count($rows) + 1;
        $rows[] = [
            'Booking Number',
            'Temple',
            'Puja',
            'Pujari',
            'Customer Name',
            'Customer Mobile',
            'Schedule Date',
            'Schedule Time',
            'Amount',
            'Payment Status',
            'Booking Status',
            'Refund Status',
            'Refund Amount'
        ];
        foreach ($data['bookingRows'] as $booking) {
            $rows[] = [
                $booking->booking_number,
                $booking->temple_name,
                $booking->puja_name,
                $booking->pandit_name,
                $booking->customer_name,
                $booking->customer_mobile,
                $booking->schedule_date,
                $booking->schedule_time,
                $booking->total_amount,
                $booking->payment_status,
                $booking->booking_status,
                $booking->refund_status,
                $booking->refund_amount
            ];
        }
        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();
        $styles = [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                    'color' => ['rgb' => '263445'],
                ],
            ],
        ];
        foreach ([
            'summary_title',
            'performance_title',
            'booking_title'
        ] as $key) {
            $row = $this->sectionRows[$key];
            $styles[$row] = [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => '263445'],
                ],
            ];
        }
        foreach ([
            'summary_header',
            'performance_header',
            'booking_header'
        ] as $key) {
            $row = $this->sectionRows[$key];
            $styles[$row] = [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '596FE8'],
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                ],
            ];
        }
        $sheet->getStyle('A1:' . $lastColumn . $lastRow)->getAlignment()->setVertical('center');
        $sheet->getStyle('A1:' . $lastColumn . $lastRow)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle('thin')
            ->getColor()
            ->setRGB('E5EAF0');
        $sheet->freezePane('A' . ($this->sectionRows['booking_header'] + 1));
        return $styles;
    }
}