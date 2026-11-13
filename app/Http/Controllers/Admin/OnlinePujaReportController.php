<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OnlinePujaReportController extends Controller
{
    private const PAID_STATUSES = ['paid', 'success', 'successful', 'captured', 'completed'];
    private const REFUNDED_STATUSES = ['refunded', 'partially_refunded'];

    public function index(Request $request)
    {
        $filters = $this->filters($request);

        // Dropdown options are based on pandits who created online pujas
        // and customers who have placed online puja orders.
        $pandits = DB::table('online_pujas as p')
            ->join('users as u', 'u.id', '=', 'p.created_by')
            ->select('u.id', 'u.name')
            ->distinct()
            ->orderBy('u.name')
            ->get();

        $customers = DB::table('online_puja_orders')
            ->whereNotNull('customer_id')
            ->select('customer_id', 'customer_name')
            ->distinct()
            ->orderBy('customer_name')
            ->get();

        $summary = $this->summary($filters);
        $report = $this->groupedReport($filters);

        $bookings = $this->bookingQuery($filters)
            ->orderByDesc('o.created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.reports.online-puja-report.index', compact(
            'filters', 'summary', 'report', 'bookings', 'pandits', 'customers'
        ));
    }

    private function filters(Request $request): array
    {
        $validated = $request->validate([
            'type' => 'nullable|in:pandit,puja',
            'period' => 'nullable|in:daily,weekly,monthly,yearly,custom',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'payment_status' => 'nullable|string|max:50',
            'booking_status' => 'nullable|string|max:50',
            'pandit_id' => 'nullable|integer',
            'customer_id' => 'nullable|integer',
        ]);
        $period = $validated['period'] ?? 'monthly';
        switch ($period) {
            case 'daily':
                $from = now()->startOfDay();
                $to = now()->endOfDay();
                break;
            case 'weekly':
                $from = now()->startOfWeek();
                $to = now()->endOfWeek();
                break;
            case 'yearly':
                $from = now()->startOfYear();
                $to = now()->endOfYear();
                break;
            case 'custom':
                $from = !empty($validated['from_date'])
                    ? Carbon::parse($validated['from_date'])->startOfDay()
                    : now()->startOfMonth();
                $to = !empty($validated['to_date'])
                    ? Carbon::parse($validated['to_date'])->endOfDay()
                    : now()->endOfDay();
                if ($to->lt($from)) {
                    throw ValidationException::withMessages([
                        'to_date' => 'To Date must be on or after From Date.',
                    ]);
                }
                break;
            default:
                $period = 'monthly';
                $from = now()->startOfMonth();
                $to = now()->endOfMonth();
                break;
        }
        return [
            'type' => $validated['type'] ?? 'pandit',
            'period' => $period,
            'from' => $from,
            'to' => $to,
            'from_input' => $from->format('Y-m-d'),
            'to_input' => $to->format('Y-m-d'),
            'payment_status' => $validated['payment_status'] ?? '',
            'booking_status' => $validated['booking_status'] ?? '',
            'pandit_id' => $validated['pandit_id'] ?? '',
            'customer_id' => $validated['customer_id'] ?? '',
        ];
    }
    private function applyFilters($query, array $filters)
    {
        $query->whereBetween('o.created_at', [$filters['from'], $filters['to']]);
        if ($filters['payment_status'] !== '') {
            $query->where('o.payment_status', $filters['payment_status']);
        }
        if ($filters['booking_status'] !== '') {
            $query->where('o.booking_status', $filters['booking_status']);
        }
        if ($filters['pandit_id'] !== '') {
            $query->where('p.created_by', $filters['pandit_id']);
        }
        if ($filters['customer_id'] !== '') {
            $query->where('o.customer_id', $filters['customer_id']);
        }
        return $query;
    }

    private function baseJoinQuery()
    {
        return DB::table('online_puja_orders as o')
            ->leftJoin('online_pujas as p', 'p.id', '=', 'o.online_puja_id')
            ->leftJoin('users as u', 'u.id', '=', 'p.created_by');
    }
    private function bookingQuery(array $filters)
    {
        $query = $this->baseJoinQuery()->select([
            'o.id',
            'o.order_number',
            'o.customer_id',
            'o.customer_name',
            'o.customer_email',
            'o.customer_phone',
            'o.online_puja_id',
            'o.package_id',
            'o.package_name',
            'o.amount',
            'o.currency',
            'o.payment_status',
            'o.booking_status',
            'o.created_at',
            'p.title as puja_title',
            'p.created_by as pandit_id',
            'u.name as pandit_name',
        ]);

        return $this->applyFilters($query, $filters);
    }

    private function summary(array $filters): array
    {
        $rows = $this->bookingQuery($filters)->get();
        $paid = $rows->filter(fn ($row) =>
            in_array(strtolower((string) $row->payment_status), self::PAID_STATUSES, true)
        );
        $refunded = $rows->filter(fn ($row) =>
            in_array(strtolower((string) $row->payment_status), self::REFUNDED_STATUSES, true)
        );
        $pending = $rows->filter(fn ($row) =>
            strtolower((string) $row->payment_status) === 'pending'
        );
        $completed = $rows->filter(fn ($row) =>
            strtolower((string) $row->booking_status) === 'completed'
        );
        $cancelled = $rows->filter(fn ($row) =>
            in_array(strtolower((string) $row->booking_status), ['cancelled', 'canceled'], true)
        );
        $gross = (float) $paid->sum('amount') + (float) $refunded->sum('amount');
        $refund = (float) $refunded->sum('amount');
        return [
            'total_bookings' => $rows->count(),
            'successful_bookings' => $paid->count(),
            'pending_bookings' => $pending->count(),
            'completed_bookings' => $completed->count(),
            'cancelled_bookings' => $cancelled->count(),
            'refunded_bookings' => $refunded->count(),
            'gross_revenue' => $gross,
            'refund_amount' => $refund,
            'net_revenue' => $gross - $refund,
        ];
    }
    private function groupedReport(array $filters)
    {
        $query = $this->applyFilters($this->baseJoinQuery(), $filters);
        $paidSql = "LOWER(o.payment_status) IN ('paid','success','successful','captured','completed')";
        $refundSql = "LOWER(o.payment_status) IN ('refunded','partially_refunded')";
        $grossSql = "CASE WHEN {$paidSql} OR {$refundSql} THEN COALESCE(o.amount,0) ELSE 0 END";
        $refundAmountSql = "CASE WHEN {$refundSql} THEN COALESCE(o.amount,0) ELSE 0 END";
        if ($filters['type'] === 'pandit') {
            return $query
                ->selectRaw("
                    p.created_by AS group_id,
                    COALESCE(u.name, 'Unknown Pandit') AS group_name,
                    o.currency AS currency,
                    COUNT(DISTINCT p.id) AS total_pujas,
                    COUNT(o.id) AS total_bookings,
                    SUM(CASE WHEN {$paidSql} THEN 1 ELSE 0 END) AS successful_bookings,
                    SUM(CASE WHEN LOWER(o.payment_status) = 'pending' THEN 1 ELSE 0 END) AS pending_bookings,
                    SUM(CASE WHEN LOWER(o.booking_status) = 'completed' THEN 1 ELSE 0 END) AS completed_bookings,
                    SUM(CASE WHEN LOWER(o.booking_status) IN ('cancelled','canceled') THEN 1 ELSE 0 END) AS cancelled_bookings,
                    SUM({$grossSql}) AS gross_revenue,
                    SUM({$refundAmountSql}) AS refund_amount
                ")
                ->groupBy('p.created_by', 'u.name', 'o.currency')
                ->orderByDesc('gross_revenue')
                ->get()
                ->map(function ($row) {
                    $row->net_revenue = (float) $row->gross_revenue - (float) $row->refund_amount;
                    return $row;
                });
        }
        return $query
            ->selectRaw("
                p.id AS group_id,
                p.title AS group_name,
                COALESCE(u.name, 'Unknown Pandit') AS pandit_name,
                o.currency AS currency,
                COUNT(o.id) AS total_bookings,
                SUM(CASE WHEN {$paidSql} THEN 1 ELSE 0 END) AS successful_bookings,
                SUM(CASE WHEN LOWER(o.payment_status) = 'pending' THEN 1 ELSE 0 END) AS pending_bookings,
                SUM(CASE WHEN LOWER(o.booking_status) = 'completed' THEN 1 ELSE 0 END) AS completed_bookings,
                SUM(CASE WHEN LOWER(o.booking_status) IN ('cancelled','canceled') THEN 1 ELSE 0 END) AS cancelled_bookings,
                SUM({$grossSql}) AS gross_revenue,
                SUM({$refundAmountSql}) AS refund_amount
            ")
            ->groupBy('p.id', 'p.title', 'u.name', 'o.currency')
            ->orderByDesc('gross_revenue')
            ->get()
            ->map(function ($row) {
                $row->net_revenue = (float) $row->gross_revenue - (float) $row->refund_amount;
                return $row;
            });
    }

    private function exportData(Request $request): array
    {
        $filters = $this->filters($request);
        return [
            'filters' => $filters,
            'summary' => $this->summary($filters),
            'report' => $this->groupedReport($filters),
            'bookings' => $this->bookingQuery($filters)->orderByDesc('o.created_at')->get(),
            'reportType' => $filters['type'],
        ];
    }

    public function exportPdf(Request $request)
    {
        $data = $this->exportData($request);
        if (!app()->bound('dompdf.wrapper')) {
            abort(500, 'PDF export requires barryvdh/laravel-dompdf. Run: composer require barryvdh/laravel-dompdf');
        }
        $pdf = app('dompdf.wrapper');
        return $pdf->loadView('admin.reports.online-puja-report.pdf', $data)
            ->setPaper('a4', 'landscape')
            ->download('online-puja-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportCsv(Request $request)
    {
        $data = $this->exportData($request);
        $type = $data['reportType'];
        $filename = 'online-puja-report-' . now()->format('Y-m-d') . '.csv';
        return response()->streamDownload(function () use ($data, $type) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            $headers = $type === 'pandit'
                ? ['Pandit', 'Total Pujas', 'Bookings', 'Successful', 'Pending', 'Completed', 'Cancelled', 'Gross Revenue', 'Refund', 'Net Revenue', 'Currency']
                : ['Online Puja', 'Pandit', 'Bookings', 'Successful', 'Pending', 'Completed', 'Cancelled', 'Gross Revenue', 'Refund', 'Net Revenue', 'Currency'];
            fputcsv($out, $headers);
            foreach ($data['report'] as $row) {
                $values = $type === 'pandit'
                    ? [$row->group_name, $row->total_pujas, $row->total_bookings, $row->successful_bookings, $row->pending_bookings, $row->completed_bookings, $row->cancelled_bookings, $row->gross_revenue, $row->refund_amount, $row->net_revenue, $row->currency]
                    : [$row->group_name, $row->pandit_name, $row->total_bookings, $row->successful_bookings, $row->pending_bookings, $row->completed_bookings, $row->cancelled_bookings, $row->gross_revenue, $row->refund_amount, $row->net_revenue, $row->currency];

                fputcsv($out, $values);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
    public function exportExcel(Request $request)
    {
        $data = $this->exportData($request);
        $type = $data['reportType'];
        $headers = $type === 'pandit'
            ? ['Pandit', 'Total Pujas', 'Bookings', 'Successful', 'Pending', 'Completed', 'Cancelled', 'Gross Revenue', 'Refund', 'Net Revenue', 'Currency']
            : ['Online Puja', 'Pandit', 'Bookings', 'Successful', 'Pending', 'Completed', 'Cancelled', 'Gross Revenue', 'Refund', 'Net Revenue', 'Currency'];
        $html = '<html><head><meta charset="UTF-8"></head><body>';
        $html .= '<h2>Online Puja Report</h2>';
        $html .= '<p>Period: ' . e($data['filters']['from_input']) . ' to ' . e($data['filters']['to_input']) . '</p>';
        $html .= '<table border="1"><thead><tr>';
        foreach ($headers as $header) {
            $html .= '<th>' . e($header) . '</th>';
        }
        $html .= '</tr></thead><tbody>';
        foreach ($data['report'] as $row) {
            $values = $type === 'pandit'
                ? [$row->group_name, $row->total_pujas, $row->total_bookings, $row->successful_bookings, $row->pending_bookings, $row->completed_bookings, $row->cancelled_bookings, $row->gross_revenue, $row->refund_amount, $row->net_revenue, $row->currency]
                : [$row->group_name, $row->pandit_name, $row->total_bookings, $row->successful_bookings, $row->pending_bookings, $row->completed_bookings, $row->cancelled_bookings, $row->gross_revenue, $row->refund_amount, $row->net_revenue, $row->currency];

            $html .= '<tr>';
            foreach ($values as $value) {
                $html .= '<td>' . e((string) $value) . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table></body></html>';
        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="online-puja-report-' . now()->format('Y-m-d') . '.xls"',
        ]);
    }
}