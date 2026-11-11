<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\RefundFinancialReconciliationReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RefundFinancialReconciliationReportController extends Controller
{
    public function index(Request $request)
    {
        $refunds = $this->getRefundRows($request);

        $summary = [
            'total_requests' => $refunds->count(),
            'processed_count' => $refunds->where('refund_status', 'processed')->count(),
            'pending_count' => $refunds->where('refund_status', 'pending')->count(),
            'failed_count' => $refunds->where('refund_status', 'failed')->count(),
            'processed_amount' => $refunds->where('refund_status', 'processed')->sum('refund_amount'),
            'pending_amount' => $refunds->where('refund_status', 'pending')->sum('refund_amount'),
            'failed_amount' => $refunds->where('refund_status', 'failed')->sum('refund_amount'),
        ];

        $summary['net_refund_amount'] = $summary['processed_amount'];

        $temples = DB::table('temples')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view(
            'admin.reports.refund-financial-reconciliation',
            compact('refunds', 'summary', 'temples')
        );
    }

    private function getRefundRows(Request $request): Collection
    {
        $rows = collect();

        $module = $request->input('module');
        $templeId = $request->input('temple_id');
        $refundStatus = $request->input('refund_status');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        /*
        |--------------------------------------------------------------------------
        | PUJA REFUNDS
        |--------------------------------------------------------------------------
        */

        if (!$module || $module === 'puja') {
            $latestPaidPayment = DB::table('puja_booking_payments')
                ->select(
                    'puja_booking_id',
                    DB::raw('MAX(id) as payment_id')
                )
                ->where('status', 'paid')
                ->groupBy('puja_booking_id');

            $query = DB::table('customer_booking_cancellations as cancellations')
                ->join(
                    'puja_bookings as bookings',
                    'bookings.id',
                    '=',
                    'cancellations.puja_booking_id'
                )
                ->leftJoin(
                    'temples',
                    'temples.id',
                    '=',
                    'bookings.temple_id'
                )
                ->leftJoinSub(
                    $latestPaidPayment,
                    'latest_payment',
                    function ($join) {
                        $join->on(
                            'latest_payment.puja_booking_id',
                            '=',
                            'bookings.id'
                        );
                    }
                )
                ->leftJoin(
                    'puja_booking_payments as payments',
                    'payments.id',
                    '=',
                    'latest_payment.payment_id'
                )
                ->leftJoin(
                    'users',
                    'users.id',
                    '=',
                    'cancellations.customer_id'
                )
                ->select(
                    'cancellations.id',
                    'cancellations.puja_booking_id',
                    'cancellations.refund_amount',
                    'cancellations.refund_status',
                    'cancellations.razorpay_refund_id',
                    'cancellations.refund_error',
                    'cancellations.refunded_at',
                    'cancellations.created_at',
                    'bookings.booking_number',
                    'bookings.total_amount as booking_amount',
                    'bookings.temple_id',
                    'temples.name as temple_name',
                    'users.name as customer_name',
                    'payments.razorpay_payment_id',
                    'payments.razorpay_order_id'
                );

            if ($templeId) {
                $query->where('bookings.temple_id', $templeId);
            }

            if ($refundStatus) {
                $query->where('cancellations.refund_status', $refundStatus);
            }

            if ($fromDate) {
                $query->whereDate(
                    DB::raw('COALESCE(cancellations.refunded_at, cancellations.created_at)'),
                    '>=',
                    $fromDate
                );
            }

            if ($toDate) {
                $query->whereDate(
                    DB::raw('COALESCE(cancellations.refunded_at, cancellations.created_at)'),
                    '<=',
                    $toDate
                );
            }

            $pujaRows = $query->get()->map(function ($item) {
                return [
                    'module' => 'Puja',
                    'refund_id' => $item->razorpay_refund_id ?: '-',
                    'payment_id' => $item->razorpay_payment_id ?: '-',
                    'order_id' => $item->razorpay_order_id ?: '-',
                    'booking_id' => $item->booking_number ?: $item->puja_booking_id,
                    'customer_name' => $item->customer_name ?: '-',
                    'temple_name' => $item->temple_name ?: '-',
                    'booking_amount' => (float) $item->booking_amount,
                    'refund_amount' => (float) $item->refund_amount,
                    'refund_status' => strtolower($item->refund_status ?: 'pending'),
                    'refund_date' => $item->refunded_at ?: $item->created_at,
                    'failure_details' => $item->refund_error ?: '-',
                ];
            });

            $rows = $rows->concat($pujaRows);
        }

        /*
        |--------------------------------------------------------------------------
        | TICKET REFUNDS
        |--------------------------------------------------------------------------
        | One row per cancellation/refund request (Razorpay order).
        |--------------------------------------------------------------------------
        */

        if (!$module || $module === 'ticket') {
            $ticketBookingDetails = DB::table('ticket_bookings')
                ->select(
                    'razorpay_order_id',
                    DB::raw('GROUP_CONCAT(DISTINCT booking_reference SEPARATOR ", ") as booking_references'),
                    DB::raw('GROUP_CONCAT(DISTINCT razorpay_payment_id SEPARATOR ", ") as payment_ids'),
                    DB::raw('GROUP_CONCAT(DISTINCT ticket_type SEPARATOR ", ") as ticket_types')
                )
                ->whereNotNull('razorpay_order_id')
                ->groupBy('razorpay_order_id');

            $query = DB::table('ticket_booking_cancellations as cancellations')
                ->leftJoin(
                    'users',
                    'users.id',
                    '=',
                    'cancellations.user_id'
                )
                ->leftJoin(
                    'temples',
                    'temples.id',
                    '=',
                    'cancellations.temple_id'
                )
                ->leftJoinSub(
                    $ticketBookingDetails,
                    'ticket_details',
                    function ($join) {
                        $join->on(
                            'ticket_details.razorpay_order_id',
                            '=',
                            'cancellations.razorpay_order_id'
                        );
                    }
                )
                ->select(
                    'cancellations.id',
                    'cancellations.razorpay_order_id',
                    'cancellations.razorpay_payment_id',
                    'cancellations.razorpay_refund_id',
                    'cancellations.total_amount',
                    'cancellations.refund_amount',
                    'cancellations.refund_status',
                    'cancellations.refund_error',
                    'cancellations.refunded_at',
                    'cancellations.requested_at',
                    'cancellations.processed_at',
                    'cancellations.created_at',
                    'users.name as customer_name',
                    'temples.name as temple_name',
                    'ticket_details.booking_references',
                    'ticket_details.payment_ids',
                    'ticket_details.ticket_types'
                );

            if ($templeId) {
                $query->where('cancellations.temple_id', $templeId);
            }

            if ($refundStatus) {
                $query->where('cancellations.refund_status', $refundStatus);
            }

            if ($fromDate) {
                $query->whereDate(
                    DB::raw('COALESCE(cancellations.refunded_at, cancellations.processed_at, cancellations.requested_at, cancellations.created_at)'),
                    '>=',
                    $fromDate
                );
            }

            if ($toDate) {
                $query->whereDate(
                    DB::raw('COALESCE(cancellations.refunded_at, cancellations.processed_at, cancellations.requested_at, cancellations.created_at)'),
                    '<=',
                    $toDate
                );
            }

            $ticketRows = $query->get()->map(function ($item) {
                return [
                    'module' => 'Ticket',
                    'refund_id' => $item->razorpay_refund_id ?: '-',
                    'payment_id' => $item->razorpay_payment_id ?: ($item->payment_ids ?: '-'),
                    'order_id' => $item->razorpay_order_id ?: '-',
                    'booking_id' => $item->booking_references ?: $item->id,
                    'customer_name' => $item->customer_name ?: '-',
                    'temple_name' => $item->temple_name ?: '-',
                    'booking_amount' => (float) $item->total_amount,
                    'refund_amount' => (float) $item->refund_amount,
                    'refund_status' => strtolower($item->refund_status ?: 'pending'),
                    'refund_date' => $item->refunded_at
                        ?: $item->processed_at
                        ?: $item->requested_at
                        ?: $item->created_at,
                    'failure_details' => $item->refund_error ?: '-',
                ];
            });

            $rows = $rows->concat($ticketRows);
        }

        /*
        |--------------------------------------------------------------------------
        | EVENT REFUNDS
        |--------------------------------------------------------------------------
        */

        if ((!$module || $module === 'event') && !$templeId) {
            $query = DB::table('sanatan_event_orders as events')
                ->leftJoin(
                    'users',
                    'users.id',
                    '=',
                    'events.user_id'
                )
                ->leftJoin(
                    'sanatans',
                    'sanatans.id',
                    '=',
                    'events.sanatan_id'
                )
                ->select(
                    'events.id',
                    'events.razorpay_order_id',
                    'events.razorpay_payment_id',
                    'events.razorpay_refund_id',
                    'events.amount',
                    'events.refund_amount',
                    'events.refund_status',
                    'events.refunded_at',
                    'events.created_at',
                    'users.name as customer_name'
                )
                ->whereNotNull('events.refund_status');

            if ($refundStatus) {
                $query->where('events.refund_status', $refundStatus);
            }

            if ($fromDate) {
                $query->whereDate(
                    DB::raw('COALESCE(events.refunded_at, events.created_at)'),
                    '>=',
                    $fromDate
                );
            }

            if ($toDate) {
                $query->whereDate(
                    DB::raw('COALESCE(events.refunded_at, events.created_at)'),
                    '<=',
                    $toDate
                );
            }

            $eventRows = $query->get()->map(function ($item) {
                return [
                    'module' => 'Event',
                    'refund_id' => $item->razorpay_refund_id ?: '-',
                    'payment_id' => $item->razorpay_payment_id ?: '-',
                    'order_id' => $item->razorpay_order_id ?: '-',
                    'booking_id' => $item->id,
                    'customer_name' => $item->customer_name ?: '-',
                    'temple_name' => 'Not Applicable',
                    'booking_amount' => (float) $item->amount,
                    'refund_amount' => (float) $item->refund_amount,
                    'refund_status' => strtolower($item->refund_status ?: 'pending'),
                    'refund_date' => $item->refunded_at ?: $item->created_at,
                    'failure_details' => '-',
                ];
            });

            $rows = $rows->concat($eventRows);
        }

        return $rows->sortByDesc('refund_date')->values();
    }

    public function exportPdf(Request $request)
    {
        $refunds = $this->getRefundRows($request);

        $pdf = Pdf::loadView(
            'admin.reports.refund-financial-reconciliation-pdf',
            [
                'refunds' => $refunds,
                'filters' => $request->all(),
            ]
        )->setPaper('a4', 'landscape');

        return $pdf->download('refund-financial-reconciliation-report.pdf');
    }

    public function exportExcel(Request $request)
    {
        $refunds = $this->getRefundRows($request);

        return Excel::download(
            new RefundFinancialReconciliationReportExport($refunds),
            'refund-financial-reconciliation-report.xlsx'
        );
    }

    public function exportCsv(Request $request)
    {
        $refunds = $this->getRefundRows($request);

        return response()->streamDownload(function () use ($refunds) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
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
            ]);

            foreach ($refunds as $row) {
                fputcsv($file, [
                    $row['module'],
                    $row['refund_id'],
                    $row['payment_id'],
                    $row['order_id'],
                    $row['booking_id'],
                    $row['customer_name'],
                    $row['temple_name'],
                    $row['booking_amount'],
                    $row['refund_amount'],
                    $row['refund_status'],
                    $row['refund_date'],
                    $row['failure_details'],
                ]);
            }

            fclose($file);
        }, 'refund-financial-reconciliation-report.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}