<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\StateWiseRevenueReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StateWiseRevenueReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'state_id' => ['nullable', 'integer', 'exists:states,id'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);

        $states = DB::table('states')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $state = null;
        $rows = collect();
        $summary = $this->emptySummary();

        if ($request->filled('state_id')) {
            $report = $this->getReportData($request);

            $state = $report['state'];
            $rows = $report['rows'];
            $summary = $report['summary'];
        }

        return view(
            'admin.reports.state-wise-revenue',
            compact('states', 'state', 'rows', 'summary')
        );
    }

    private function emptySummary(): array
    {
        return [
            'total_bookings' => 0,
            'paid_bookings' => 0,
            'puja_bookings' => 0,
            'ticket_bookings' => 0,
            'puja_revenue' => 0,
            'ticket_revenue' => 0,
            'total_revenue' => 0,
        ];
    }

    private function getReportData(Request $request): array
    {
        $request->validate([
            'state_id' => ['required', 'integer', 'exists:states,id'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);

        $state = DB::table('states')
            ->where('id', $request->state_id)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | PUJA BOOKINGS
        |--------------------------------------------------------------------------
        */

        $pujaQuery = DB::table('puja_bookings')
            ->join('temples', 'temples.id', '=', 'puja_bookings.temple_id')
            ->where('temples.state_id', $request->state_id);

        if ($request->filled('from_date')) {
            $pujaQuery->whereDate(
                'puja_bookings.created_at',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {
            $pujaQuery->whereDate(
                'puja_bookings.created_at',
                '<=',
                $request->to_date
            );
        }

        $pujaRows = $pujaQuery
            ->select(
                'temples.id as temple_id',
                'temples.name as temple_name',
                DB::raw("'Puja' as module"),
                DB::raw('COUNT(puja_bookings.id) as total_bookings'),
                DB::raw("SUM(CASE WHEN puja_bookings.payment_status = 'paid' THEN 1 ELSE 0 END) as paid_bookings"),
                DB::raw("SUM(CASE WHEN puja_bookings.payment_status = 'paid' THEN puja_bookings.total_amount ELSE 0 END) as revenue")
            )
            ->groupBy('temples.id', 'temples.name')
            ->get();

        $rows = collect();

        foreach ($pujaRows as $item) {
            $rows->push([
                'temple_id' => $item->temple_id,
                'temple_name' => $item->temple_name,
                'module' => 'Puja',
                'total_bookings' => (int) $item->total_bookings,
                'paid_bookings' => (int) $item->paid_bookings,
                'revenue' => (float) $item->revenue,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | TICKET BOOKINGS
        |--------------------------------------------------------------------------
        | Count distinct Razorpay orders as bookings.
        | Sum the price of paid ticket rows as revenue.
        |--------------------------------------------------------------------------
        */

        $ticketQuery = DB::table('ticket_bookings')
            ->join('temples', 'temples.id', '=', 'ticket_bookings.temple_id')
            ->where('temples.state_id', $request->state_id);

        if ($request->filled('from_date')) {
            $ticketQuery->whereDate(
                'ticket_bookings.created_at',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {
            $ticketQuery->whereDate(
                'ticket_bookings.created_at',
                '<=',
                $request->to_date
            );
        }

        $ticketRows = $ticketQuery
            ->select(
                'temples.id as temple_id',
                'temples.name as temple_name',
                DB::raw("'Ticket' as module"),
                DB::raw("COUNT(DISTINCT COALESCE(ticket_bookings.razorpay_order_id, CONCAT('ticket-', ticket_bookings.id))) as total_bookings"),
                DB::raw("COUNT(DISTINCT CASE WHEN ticket_bookings.payment_status = 'paid' THEN COALESCE(ticket_bookings.razorpay_order_id, CONCAT('ticket-', ticket_bookings.id)) END) as paid_bookings"),
                DB::raw("SUM(CASE WHEN ticket_bookings.payment_status = 'paid' THEN ticket_bookings.price ELSE 0 END) as revenue")
            )
            ->groupBy('temples.id', 'temples.name')
            ->get();

        foreach ($ticketRows as $item) {
            $rows->push([
                'temple_id' => $item->temple_id,
                'temple_name' => $item->temple_name,
                'module' => 'Ticket',
                'total_bookings' => (int) $item->total_bookings,
                'paid_bookings' => (int) $item->paid_bookings,
                'revenue' => (float) $item->revenue,
            ]);
        }

        $rows = $rows->sortBy(function ($row) {
            return $row['temple_name'] . '-' . $row['module'];
        })->values();

        $summary = [
            'total_bookings' => $rows->sum('total_bookings'),
            'paid_bookings' => $rows->sum('paid_bookings'),
            'puja_bookings' => $rows->where('module', 'Puja')->sum('total_bookings'),
            'ticket_bookings' => $rows->where('module', 'Ticket')->sum('total_bookings'),
            'puja_revenue' => $rows->where('module', 'Puja')->sum('revenue'),
            'ticket_revenue' => $rows->where('module', 'Ticket')->sum('revenue'),
            'total_revenue' => $rows->sum('revenue'),
        ];

        return compact('state', 'rows', 'summary');
    }

    public function exportPdf(Request $request)
    {
        $report = $this->getReportData($request);

        $report['filters'] = $request->only([
            'from_date',
            'to_date',
        ]);

        $pdf = Pdf::loadView(
            'admin.reports.state-wise-revenue-pdf',
            $report
        )->setPaper('a4', 'landscape');

        return $pdf->download('state-wise-revenue-report.pdf');
    }

    public function exportExcel(Request $request)
    {
        $report = $this->getReportData($request);

        return Excel::download(
            new StateWiseRevenueReportExport(
                $report['rows'],
                $report['state'],
                $report['summary'],
                $request->only(['from_date', 'to_date'])
            ),
            'state-wise-revenue-report.xlsx'
        );
    }

    public function exportCsv(Request $request)
    {
        $report = $this->getReportData($request);

        $rows = $report['rows'];
        $summary = $report['summary'];
        $state = $report['state'];

        $fromDate = $request->input('from_date') ?: 'All Dates';
        $toDate = $request->input('to_date') ?: 'All Dates';

        return response()->streamDownload(function () use (
            $rows,
            $summary,
            $state,
            $fromDate,
            $toDate
        ) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['State-wise Revenue Report']);
            fputcsv($file, ['State', $state->name]);
            fputcsv($file, ['From Date', $fromDate]);
            fputcsv($file, ['To Date', $toDate]);
            fputcsv($file, []);

            fputcsv($file, [
                'Temple',
                'Module',
                'Total Bookings',
                'Paid Bookings',
                'Revenue',
            ]);

            foreach ($rows as $row) {
                fputcsv($file, [
                    $row['temple_name'],
                    $row['module'],
                    $row['total_bookings'],
                    $row['paid_bookings'],
                    number_format($row['revenue'], 2, '.', ''),
                ]);
            }

            fputcsv($file, []);

            fputcsv($file, ['Summary']);
            fputcsv($file, ['Total Bookings', $summary['total_bookings']]);
            fputcsv($file, ['Paid Bookings', $summary['paid_bookings']]);
            fputcsv($file, ['Puja Bookings', $summary['puja_bookings']]);
            fputcsv($file, ['Ticket Bookings', $summary['ticket_bookings']]);
            fputcsv($file, ['Puja Revenue', number_format($summary['puja_revenue'], 2, '.', '')]);
            fputcsv($file, ['Ticket Revenue', number_format($summary['ticket_revenue'], 2, '.', '')]);
            fputcsv($file, ['Total Revenue', number_format($summary['total_revenue'], 2, '.', '')]);

            fclose($file);
        }, 'state-wise-revenue-report.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}