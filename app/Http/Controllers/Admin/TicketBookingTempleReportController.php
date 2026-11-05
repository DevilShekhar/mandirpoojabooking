<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Temple;
use App\Models\TicketBooking;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Barryvdh\DomPDF\Facade\Pdf;
class TicketBookingTempleReportController extends Controller
{
    public function index(Request $request)
    {
        $temples = Temple::orderBy('name')->get();
        $bookings = collect();
        $totalBookings = 0;
        $totalAmount = 0;
        if ($request->get('generate') == 1) {
            $request->validate([
                'temple_id' => 'required|integer|exists:temples,id',
                'from_date' => 'required|date',
                'to_date' => 'required|date|after_or_equal:from_date',
                'payment_status' => 'nullable|string|max:30',
                'booking_status' => 'nullable|string|max:30',
            ]);
            $query = $this->getReportQuery($request);
            $totalBookings = (clone $query)->count();
            $totalAmount = (clone $query)->sum('price');
            $bookings = $query
                ->latest('booking_date')
                ->paginate(20)
                ->withQueryString();
        }
        return view('admin.reports.ticket-booking-temple',compact(
                'temples',
                'bookings',
                'totalBookings',
                'totalAmount'
            )
        );
    }

    private function getReportQuery(Request $request)
    {
        $query = TicketBooking::query()
            ->where('temple_id', $request->temple_id)
            ->whereDate('booking_date', '>=', $request->from_date)
            ->whereDate('booking_date', '<=', $request->to_date);
        if ($request->filled('payment_status')) {
            $query->where(
                'payment_status',
                $request->payment_status
            );
        }
        if ($request->filled('booking_status')) {
            $query->where(
                'booking_status',
                $request->booking_status
            );
        }
        return $query;
    }
    private function validateExportRequest(Request $request)
    {
        $request->validate([
            'temple_id' => 'required|integer|exists:temples,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'payment_status' => 'nullable|string|max:30',
            'booking_status' => 'nullable|string|max:30',
        ]);
    }
    public function exportPdf(Request $request)
    {
        $this->validateExportRequest($request);
        $bookings = $this->getReportQuery($request)
            ->latest('booking_date')
            ->get();
        $temple = Temple::findOrFail($request->temple_id);
        $totalBookings = $bookings->count();
        $totalAmount = $bookings->sum('price');
        $pdf = Pdf::loadView('admin.reports.ticketpdf',compact(
                'bookings',
                'temple',
                'totalBookings',
                'totalAmount',
                'request'
            )
        )->setPaper('a4', 'landscape');
        return $pdf->download('ticket-booking-temple-report.pdf');
    }

    public function exportCsv(Request $request)
    {
        $this->validateExportRequest($request);
        $bookings = $this->getReportQuery($request)->latest('booking_date')->get();
        $temple = Temple::findOrFail($request->temple_id);
        $totalBookings = $bookings->count();
        $totalAmount = $bookings->sum('price');
        return response()->streamDownload(function () use (
            $bookings,
            $temple,
            $totalBookings,
            $totalAmount
        ) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, [
                'Ticket Booking Temple Report'
            ]);
            fputcsv($file, [
                'Temple',
                $temple->name
            ]);
            fputcsv($file, [
                'Total Bookings',
                $totalBookings
            ]);
            fputcsv($file, [
                'Total Amount',
                number_format($totalAmount, 2, '.', '')
            ]);
            fputcsv($file, []);
            fputcsv($file, [
                'Booking Reference',
                'Booking Date',
                'Member Name',
                'Aadhaar Number',
                'Ticket Type',
                'Type',
                'Age',
                'Price',
                'Payment Status',
                'Booking Status',
            ]);
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->booking_reference,
                    $booking->booking_date,
                    $booking->member_name,
                    // Aadhaar as text
                    '="' . (string) $booking->aadhaar_number . '"',
                    $booking->ticket_type,
                    $booking->type,
                    $booking->age,
                    $booking->price,
                    $booking->payment_status,
                    $booking->booking_status,
                ]);
            }
            fclose($file);
        }, 'ticket-booking-temple-report.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
    public function exportExcel(Request $request)
    {
        $this->validateExportRequest($request);
        $bookings = $this->getReportQuery($request)->latest('booking_date')->get();
        $totalBookings = $bookings->count();
        $totalAmount = $bookings->sum('price');
        return Excel::download(
            new class($bookings,$totalBookings,$totalAmount) implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithEvents {
                protected $bookings;
                protected $totalBookings;
                protected $totalAmount;
                public function __construct($bookings,$totalBookings,$totalAmount) {
                    $this->bookings = $bookings;
                    $this->totalBookings = $totalBookings;
                    $this->totalAmount = $totalAmount;
                }

                public function collection()
                {
                    return $this->bookings;
                }
                public function headings(): array
                {
                    return [
                        'Booking Reference',
                        'Booking Date',
                        'Member Name',
                        'Aadhaar Number',
                        'Ticket Type',
                        'Type',
                        'Age',
                        'Price',
                        'Payment Status',
                        'Booking Status',
                    ];
                }
                public function map($booking): array
                {
                    return [
                        (string) $booking->booking_reference,
                        $booking->booking_date,
                        $booking->member_name,
                        // Aadhaar number as text
                        (string) $booking->aadhaar_number,
                        $booking->ticket_type,
                        $booking->type,
                        $booking->age,
                        (float) $booking->price,
                        $booking->payment_status,
                        $booking->booking_status,
                    ];
                }
                public function columnFormats(): array
                {
                    return [
                        'A' => NumberFormat::FORMAT_TEXT,
                        'D' => NumberFormat::FORMAT_TEXT,
                        'H' => '#,##0.00',
                    ];
                }
                public function registerEvents(): array
                {
                    return [
                        AfterSheet::class => function (AfterSheet $event) {
                            $sheet = $event->sheet->getDelegate();
                            $lastDataRow = $this->bookings->count() + 1;
                            $summaryRow = $lastDataRow + 2;
                            $sheet->setCellValue(
                                'A' . $summaryRow,
                                'Total Bookings'
                            );
                            $sheet->setCellValue(
                                'B' . $summaryRow,
                                $this->totalBookings
                            );
                            $sheet->setCellValue(
                                'A' . ($summaryRow + 1),
                                'Total Amount'
                            );
                            $sheet->setCellValue(
                                'B' . ($summaryRow + 1),
                                $this->totalAmount
                            );
                            $sheet->getStyle(
                                'A' . $summaryRow . ':B' . ($summaryRow + 1)
                            )->applyFromArray([
                                'font' => [
                                    'bold' => true,
                                    'size' => 11,
                                ],
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => [
                                        'rgb' => 'E8EEF7',
                                    ],
                                ],
                                'borders' => [
                                    'allBorders' => [
                                        'borderStyle' => Border::BORDER_THIN,
                                        'color' => [
                                            'rgb' => 'CCCCCC',
                                        ],
                                    ],
                                ],
                            ]);
                            $sheet->getStyle(
                                'B' . ($summaryRow + 1)
                            )->getNumberFormat()->setFormatCode('#,##0.00');
                            $sheet->getStyle('B' . $summaryRow)->getNumberFormat()->setFormatCode('0');
                            foreach (range('A', 'J') as $column) {
                                $sheet->getColumnDimension($column)
                                    ->setAutoSize(true);
                            }
                            $sheet->getStyle('A1:J1')->getFont()->setBold(true);
                            $sheet->getStyle('A1:J1')->getFill()->setFillType(Fill::FILL_SOLID);
                            $sheet->getStyle('A1:J1')->getFill()->getStartColor()->setRGB('34495E');
                            $sheet->getStyle('A1:J1')->getFont()->getColor()->setRGB('FFFFFF');
                        },
                    ];
                }
            },
            'ticket-booking-temple-report.xlsx'
        );
    }
}