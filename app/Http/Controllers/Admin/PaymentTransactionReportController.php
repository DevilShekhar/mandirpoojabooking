<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Exports\PaymentTransactionReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PaymentTransactionReportController extends Controller
{
    public function index(Request $request)
    {
        $transactions = $this->getReportRows($request);
        $summary = [
            'total_transactions' => $transactions->count(),
            'paid' => $transactions->where('payment_status', 'paid')->count(),
            'pending' => $transactions->where('payment_status', 'pending')->count(),
            'failed' => $transactions->where('payment_status', 'failed')->count(),
            'refunded' => $transactions->where('payment_status', 'refunded')->count(),
            'partially_refunded' => $transactions->where('payment_status', 'partially_refunded')->count(),
            'paid_amount' => $transactions->where('payment_status', 'paid')->sum('amount'),
        ];
        $temples = DB::table('temples')->select('id', 'name')->orderBy('name')->get();
        return view('admin.reports.payment-transactions', compact('transactions','summary','temples'));
    }

    private function getReportRows(Request $request): Collection
    {
        $rows = collect();
        $module = $request->input('module');
        $templeId = $request->input('temple_id');
        $paymentStatus = $request->input('payment_status');
        $bookingStatus = $request->input('booking_status');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $gateway = strtolower($request->input('gateway', ''));
        if ($gateway && $gateway !== 'razorpay') {
            return collect();
        }
        if (!$module || $module === 'puja') {
            $query = DB::table('puja_booking_payments')
                ->leftJoin(
                    'users',
                    'users.id',
                    '=',
                    'puja_booking_payments.user_id'
                )
                ->leftJoin(
                    'temples',
                    'temples.id',
                    '=',
                    'puja_booking_payments.temple_id'
                )
                ->leftJoin(
                    'puja_bookings',
                    'puja_bookings.id',
                    '=',
                    'puja_booking_payments.puja_booking_id'
                )
                ->leftJoin(
                    'pujas',
                    'pujas.id',
                    '=',
                    'puja_booking_payments.puja_id'
                )
                ->select(
                    'puja_booking_payments.id',
                    'puja_booking_payments.razorpay_order_id',
                    'puja_booking_payments.razorpay_payment_id',
                    'puja_booking_payments.status as payment_status',
                    'puja_booking_payments.currency',
                    'puja_booking_payments.created_at',
                    'puja_booking_payments.error_code',
                    'puja_booking_payments.error_description',
                    'puja_booking_payments.error_reason',
                    'puja_booking_payments.amount_paise',
                    'users.name as customer_name',
                    'temples.name as temple_name',
                    'puja_bookings.booking_number',
                    'puja_bookings.booking_status',
                    'pujas.name as service_name'
                );
            if ($templeId) {
                $query->where(
                    'puja_booking_payments.temple_id',
                    $templeId
                );
            }
            if ($paymentStatus) {
                $query->where(
                    'puja_booking_payments.status',
                    $paymentStatus
                );
            }
            if ($bookingStatus) {
                $query->where(
                    'puja_bookings.booking_status',
                    $bookingStatus
                );
            }
            if ($fromDate) {
                $query->whereDate(
                    'puja_booking_payments.created_at',
                    '>=',
                    $fromDate
                );
            }
            if ($toDate) {
                $query->whereDate(
                    'puja_booking_payments.created_at',
                    '<=',
                    $toDate
                );
            }
            $pujaRows = $query->get()->map(function ($item) {
                $failureDetails = collect([
                    $item->error_code,
                    $item->error_description,
                    $item->error_reason,
                ])->filter()->implode(' | ');
                return [
                    'module' => 'Puja',
                    'transaction_id' => $item->razorpay_payment_id ?: '-',
                    'order_id' => $item->razorpay_order_id ?: '-',
                    'booking_id' => $item->booking_number ?: $item->id,
                    'customer_name' => $item->customer_name ?: '-',
                    'temple_name' => $item->temple_name ?: '-',
                    'service_name' => $item->service_name ?: 'Puja Booking',
                    'amount' => (float) $item->amount_paise / 100,
                    'currency' => $item->currency ?: 'INR',
                    'payment_status' => strtolower($item->payment_status),
                    'booking_status' => $item->booking_status ?: '-',
                    'gateway' => 'Razorpay',
                    'transaction_date' => $item->created_at,
                    'failure_details' => $failureDetails ?: '-',
                ];
            });
            $rows = $rows->concat($pujaRows);
        }
        if (!$module || $module === 'ticket') {
            $query = DB::table('ticket_bookings')
                ->leftJoin(
                    'users',
                    'users.id',
                    '=',
                    'ticket_bookings.user_id'
                )
                ->leftJoin(
                    'temples',
                    'temples.id',
                    '=',
                    'ticket_bookings.temple_id'
                )
                ->select(
                    'ticket_bookings.user_id',
                    'ticket_bookings.temple_id',
                    'ticket_bookings.razorpay_order_id',
                    'ticket_bookings.razorpay_payment_id',
                    'ticket_bookings.payment_status',
                    DB::raw("'INR' as currency"),
                    DB::raw('MIN(ticket_bookings.created_at) as transaction_date'),
                    DB::raw('GROUP_CONCAT(DISTINCT ticket_bookings.booking_reference SEPARATOR ", ") as booking_id'),
                    DB::raw('GROUP_CONCAT(DISTINCT ticket_bookings.ticket_type SEPARATOR ", ") as service_name'),
                    DB::raw('GROUP_CONCAT(DISTINCT ticket_bookings.booking_status SEPARATOR ", ") as booking_status'),
                    DB::raw('SUM(ticket_bookings.price) as total_amount'),
                    DB::raw('MIN(ticket_bookings.id) as first_id'),
                    'users.name as customer_name',
                    'temples.name as temple_name'
                )
                ->where(function ($q) {
                    $q->whereNotNull('ticket_bookings.razorpay_payment_id')
                        ->orWhereNotNull('ticket_bookings.razorpay_order_id');
                })
                ->groupBy(
                    'ticket_bookings.user_id',
                    'ticket_bookings.temple_id',
                    'ticket_bookings.razorpay_order_id',
                    'ticket_bookings.razorpay_payment_id',
                    'ticket_bookings.payment_status',
                    'users.name',
                    'temples.name'
                );

            if ($templeId) {
                $query->where(
                    'ticket_bookings.temple_id',
                    $templeId
                );
            }
            if ($paymentStatus) {
                $query->where(
                    'ticket_bookings.payment_status',
                    $paymentStatus
                );
            }
            if ($bookingStatus) {
                $query->where(
                    'ticket_bookings.booking_status',
                    $bookingStatus
                );
            }
            if ($fromDate) {
                $query->whereDate(
                    'ticket_bookings.created_at',
                    '>=',
                    $fromDate
                );
            }
            if ($toDate) {
                $query->whereDate(
                    'ticket_bookings.created_at',
                    '<=',
                    $toDate
                );
            }
            $ticketRows = $query->get()->map(function ($item) {
                return [
                    'module' => 'Ticket',
                    'transaction_id' => $item->razorpay_payment_id ?: '-',
                    'order_id' => $item->razorpay_order_id ?: '-',
                    'booking_id' => $item->booking_id ?: $item->first_id,
                    'customer_name' => $item->customer_name ?: '-',
                    'temple_name' => $item->temple_name ?: '-',
                    'service_name' => $item->service_name ?: 'Temple Ticket',
                    'amount' => (float) $item->total_amount,
                    'currency' => $item->currency,
                    'payment_status' => strtolower($item->payment_status),
                    'booking_status' => $item->booking_status ?: '-',
                    'gateway' => 'Razorpay',
                    'transaction_date' => $item->transaction_date,
                    'failure_details' => '-',
                ];
            });
            $rows = $rows->concat($ticketRows);
        }

        if ((!$module || $module === 'event') && !$templeId) {
            $query = DB::table('sanatan_event_orders')
                ->leftJoin(
                    'users',
                    'users.id',
                    '=',
                    'sanatan_event_orders.user_id'
                )
                ->leftJoin(
                    'sanatans',
                    'sanatans.id',
                    '=',
                    'sanatan_event_orders.sanatan_id'
                )
                ->select(
                    'sanatan_event_orders.id',
                    'sanatan_event_orders.razorpay_order_id',
                    'sanatan_event_orders.razorpay_payment_id',
                    'sanatan_event_orders.payment_status',
                    'sanatan_event_orders.booking_status',
                    'sanatan_event_orders.amount',
                    'sanatan_event_orders.currency',
                    'sanatan_event_orders.created_at',
                    'users.name as customer_name',
                    'sanatans.name as service_name'
                );

            if ($paymentStatus) {
                $query->where(
                    'sanatan_event_orders.payment_status',
                    $paymentStatus
                );
            }
            if ($bookingStatus) {
                $query->where(
                    'sanatan_event_orders.booking_status',
                    $bookingStatus
                );
            }
            if ($fromDate) {
                $query->whereDate(
                    'sanatan_event_orders.created_at',
                    '>=',
                    $fromDate
                );
            }
            if ($toDate) {
                $query->whereDate(
                    'sanatan_event_orders.created_at',
                    '<=',
                    $toDate
                );
            }
            $eventRows = $query->get()->map(function ($item) {
                return [
                    'module' => 'Event',
                    'transaction_id' => $item->razorpay_payment_id ?: '-',
                    'order_id' => $item->razorpay_order_id ?: '-',
                    'booking_id' => $item->id,
                    'customer_name' => $item->customer_name ?: '-',
                    'temple_name' => 'Not Applicable',
                    'service_name' => $item->service_name ?: 'Event Booking',
                    'amount' => (float) $item->amount,
                    'currency' => $item->currency ?: 'INR',
                    'payment_status' => strtolower($item->payment_status),
                    'booking_status' => $item->booking_status ?: '-',
                    'gateway' => 'Razorpay',
                    'transaction_date' => $item->created_at,
                    'failure_details' => '-',
                ];
            });
            $rows = $rows->concat($eventRows);
        }
        return $rows->sortByDesc('transaction_date')->values();
    }

    public function exportPdf(Request $request)
    {
        $transactions = $this->getReportRows($request);
        $pdf = Pdf::loadView(
            'admin.reports.payment-transactions-pdf',
            [
                'transactions' => $transactions,
                'filters' => $request->all(),
            ]
        )->setPaper('a4', 'landscape');
        return $pdf->download('payment-transactions-report.pdf');
    }

    public function exportExcel(Request $request)
    {
        $transactions = $this->getReportRows($request);
        return Excel::download(
            new PaymentTransactionReportExport($transactions),
            'payment-transactions-report.xlsx'
        );
    }

    public function exportCsv(Request $request)
    {
        $transactions = $this->getReportRows($request);
        return response()->streamDownload(function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, [
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
                'Booking Status',
                'Gateway',
                'Transaction Date',
                'Failure Details',
            ]);
            foreach ($transactions as $row) {
                fputcsv($file, [
                    $row['module'],
                    $row['transaction_id'],
                    $row['order_id'],
                    $row['booking_id'],
                    $row['customer_name'],
                    $row['temple_name'],
                    $row['service_name'],
                    $row['amount'],
                    $row['currency'],
                    $row['payment_status'],
                    $row['booking_status'],
                    $row['gateway'],
                    $row['transaction_date'],
                    $row['failure_details'],
                ]);
            }
            fclose($file);
        }, 'payment-transactions-report.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}