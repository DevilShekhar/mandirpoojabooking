<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OverallBusinessPerformanceExport;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OverallBusinessPerformanceReportController extends Controller
{
    private function validateFilters(Request $request): void
    {
        $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);
    }
    private function applyDateFilter($query, ?string $fromDate, ?string $toDate)
    {
        if ($fromDate) {
            $query->where(
                'created_at',
                '>=',
                Carbon::parse($fromDate)->startOfDay()
            );
        }
        if ($toDate) {
            $query->where(
                'created_at',
                '<=',
                Carbon::parse($toDate)->endOfDay()
            );
        }
        return $query;
    }
    private function getReportData(?string $fromDate, ?string $toDate): array
    {
        $ticketTotalQuery = DB::table('ticket_bookings');
        $this->applyDateFilter($ticketTotalQuery, $fromDate, $toDate);
        $ticketTotalCount = (clone $ticketTotalQuery)
            ->whereNotNull('razorpay_order_id')
            ->distinct()
            ->count('razorpay_order_id');
        $ticketPaidQuery = DB::table('ticket_bookings')
            ->where('payment_status', 'paid')
            ->where('booking_status', 'confirmed');
        $this->applyDateFilter($ticketPaidQuery, $fromDate, $toDate);
        $ticketPaidCount = (clone $ticketPaidQuery)
            ->whereNotNull('razorpay_order_id')
            ->distinct()
            ->count('razorpay_order_id');
        $ticketPaidAmount = (clone $ticketPaidQuery)->sum('price');
        $ticketCancelledQuery = DB::table('ticket_bookings')
            ->where('booking_status', 'cancelled');
        $this->applyDateFilter($ticketCancelledQuery, $fromDate, $toDate);
        $ticketCancelledCount = (clone $ticketCancelledQuery)
            ->whereNotNull('razorpay_order_id')
            ->distinct()
            ->count('razorpay_order_id');
        $ticketCancelledAmount = (clone $ticketCancelledQuery)->sum('price');
        $eventTotalQuery = DB::table('sanatan_event_orders');
        $this->applyDateFilter($eventTotalQuery, $fromDate, $toDate);
        $eventTotalCount = (clone $eventTotalQuery)->count();
        $eventPaidQuery = DB::table('sanatan_event_orders')->where('payment_status', 'paid');
        $this->applyDateFilter($eventPaidQuery, $fromDate, $toDate);
        $eventPaidCount = (clone $eventPaidQuery)->count();
        $eventPaidAmount = (clone $eventPaidQuery)->sum('amount');
        $eventCancelledQuery = DB::table('sanatan_event_orders')
            ->where('booking_status', 'cancelled');
        $this->applyDateFilter($eventCancelledQuery, $fromDate, $toDate);
        $eventCancelledCount = (clone $eventCancelledQuery)->count();
        $eventCancelledAmount = (clone $eventCancelledQuery)->sum('amount');
        $eventRefundQuery = DB::table('sanatan_event_orders')
            ->where('refund_status', 'processed');
        $this->applyDateFilter($eventRefundQuery, $fromDate, $toDate);
        $eventRefundCount = (clone $eventRefundQuery)->count();
        $eventRefundAmount = (clone $eventRefundQuery)->sum('refund_amount');
        $pujaTotalQuery = DB::table('puja_bookings');
        $this->applyDateFilter($pujaTotalQuery, $fromDate, $toDate);
        $pujaTotalCount = (clone $pujaTotalQuery)->count();

        $pujaPaidQuery = DB::table('puja_bookings')
            ->where('payment_status', 'paid');
        $this->applyDateFilter($pujaPaidQuery, $fromDate, $toDate);
        $pujaPaidCount = (clone $pujaPaidQuery)->count();
        $pujaPaidAmount = (clone $pujaPaidQuery)->sum('total_amount');
        // Cancelled puja bookings
        $pujaCancelledQuery = DB::table('puja_bookings')
            ->where('booking_status', 'cancelled');
        $this->applyDateFilter($pujaCancelledQuery, $fromDate, $toDate);
        $pujaCancelledCount = (clone $pujaCancelledQuery)->count();
        $pujaCancelledAmount = (clone $pujaCancelledQuery)->sum('total_amount');
        // Processed puja refunds
        $pujaRefundQuery = DB::table('customer_booking_cancellations')
            ->where('refund_status', 'processed');
        $this->applyDateFilter($pujaRefundQuery, $fromDate, $toDate);
        $pujaRefundCount = (clone $pujaRefundQuery)->count();
        $pujaRefundAmount = (clone $pujaRefundQuery)->sum('refund_amount');
        $modules = [
            [
                'module' => 'Ticket Booking',
                'total_bookings' => $ticketTotalCount,
                'paid_count' => $ticketPaidCount,
                'paid_amount' => (float) $ticketPaidAmount,
                'cancelled_count' => $ticketCancelledCount,
                'cancelled_amount' => (float) $ticketCancelledAmount,
                'refund_count' => 0,
                'refund_amount' => 0,
            ],
            [
                'module' => 'Event Booking',
                'total_bookings' => $eventTotalCount,
                'paid_count' => $eventPaidCount,
                'paid_amount' => (float) $eventPaidAmount,
                'cancelled_count' => $eventCancelledCount,
                'cancelled_amount' => (float) $eventCancelledAmount,
                'refund_count' => $eventRefundCount,
                'refund_amount' => (float) $eventRefundAmount,
            ],
            [
                'module' => 'Puja Booking',
                'total_bookings' => $pujaTotalCount,
                'paid_count' => $pujaPaidCount,
                'paid_amount' => (float) $pujaPaidAmount,
                'cancelled_count' => $pujaCancelledCount,
                'cancelled_amount' => (float) $pujaCancelledAmount,
                'refund_count' => $pujaRefundCount,
                'refund_amount' => (float) $pujaRefundAmount,
            ],
        ];
        $summary = [
            'total_bookings' => array_sum(
                array_column($modules, 'total_bookings')
            ),
            'paid_count' => array_sum(
                array_column($modules, 'paid_count')
            ),
            'paid_amount' => array_sum(
                array_column($modules, 'paid_amount')
            ),
            'cancelled_count' => array_sum(
                array_column($modules, 'cancelled_count')
            ),
            'cancelled_amount' => array_sum(
                array_column($modules, 'cancelled_amount')
            ),
            'refund_count' => array_sum(
                array_column($modules, 'refund_count')
            ),
            'refund_amount' => array_sum(
                array_column($modules, 'refund_amount')
            ),
        ];
        $summary['net_collection'] =
            $summary['paid_amount'] - $summary['refund_amount'];
        return [
            'summary' => $summary,
            'modules' => $modules,
        ];
    }
    public function index(Request $request)
    {
        $this->validateFilters($request);
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $report = $this->getReportData($fromDate, $toDate);
        return view('admin.reports.overall-business-performance', [
            'summary' => $report['summary'],
            'modules' => $report['modules'],
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }
    public function exportPdf(Request $request)
    {
        $this->validateFilters($request);
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $report = $this->getReportData($fromDate, $toDate);
        $pdf = Pdf::loadView(
            'admin.reports.overall-business-performance-pdf',
            [
                'summary' => $report['summary'],
                'modules' => $report['modules'],
                'fromDate' => $fromDate,
                'toDate' => $toDate,
            ]
        )->setPaper('a4', 'landscape');
        return $pdf->download(
            'overall-business-performance-' . now()->format('Y-m-d') . '.pdf'
        );
    }
    public function exportExcel(Request $request)
    {
        $this->validateFilters($request);
        $report = $this->getReportData(
            $request->from_date,
            $request->to_date
        );
        return Excel::download(
            new OverallBusinessPerformanceExport(
                $report['summary'],
                $report['modules']
            ),
            'overall-business-performance-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
    public function exportCsv(Request $request): StreamedResponse
    {
        $this->validateFilters($request);
        $report = $this->getReportData(
            $request->from_date,
            $request->to_date
        );
        $summary = $report['summary'];
        $modules = $report['modules'];
        $fileName = 'overall-business-performance-' . now()->format('Y-m-d') . '.csv';
        return response()->streamDownload(function () use ($summary, $modules) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['OVERALL BUSINESS PERFORMANCE REPORT']);
            fputcsv($handle, []);
            fputcsv($handle, ['BUSINESS SUMMARY', 'VALUE']);
            fputcsv($handle, [
                'Total Bookings',
                $summary['total_bookings']
            ]);
            fputcsv($handle, [
                'Paid Bookings',
                $summary['paid_count']
            ]);
            fputcsv($handle, [
                'Paid Amount',
                number_format($summary['paid_amount'], 2, '.', '')
            ]);
            fputcsv($handle, [
                'Cancelled Bookings',
                $summary['cancelled_count']
            ]);
            fputcsv($handle, [
                'Cancelled Amount',
                number_format($summary['cancelled_amount'], 2, '.', '')
            ]);
            fputcsv($handle, [
                'Processed Refunds',
                $summary['refund_count']
            ]);
            fputcsv($handle, [
                'Refund Amount',
                number_format($summary['refund_amount'], 2, '.', '')
            ]);
            fputcsv($handle, [
                'Net Collection',
                number_format($summary['net_collection'], 2, '.', '')
            ]);
            fputcsv($handle, []);
            fputcsv($handle, [
                'Booking Module',
                'Total Bookings',
                'Paid Bookings',
                'Paid Amount',
                'Cancelled Bookings',
                'Cancelled Amount',
                'Processed Refunds',
                'Refund Amount',
                'Net Collection',
            ]);
            foreach ($modules as $module) {
                fputcsv($handle, [
                    $module['module'],
                    $module['total_bookings'],
                    $module['paid_count'],
                    number_format($module['paid_amount'], 2, '.', ''),
                    $module['cancelled_count'],
                    number_format($module['cancelled_amount'], 2, '.', ''),
                    $module['refund_count'],
                    number_format($module['refund_amount'], 2, '.', ''),
                    number_format(
                        $module['paid_amount'] - $module['refund_amount'],
                        2,
                        '.',
                        ''
                    ),
                ]);
            }
            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}