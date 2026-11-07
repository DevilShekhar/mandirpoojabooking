<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Temple;
use App\Exports\TempleWiseRevenueReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class TempleWiseRevenueReportController extends Controller
{
    private function validateFilters(Request $request, bool $requireTemple = false): array
    {
        return $request->validate([
            'temple_id' => ($requireTemple ? 'required' : 'nullable') . '|integer|exists:temples,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);
    }

    private function applyDateFilter($query, Request $request, string $column = 'created_at')
    {
        if ($request->filled('from_date')) {
            $query->where($column, '>=', Carbon::parse($request->from_date)->startOfDay());
        }

        if ($request->filled('to_date')) {
            $query->where($column, '<=', Carbon::parse($request->to_date)->endOfDay());
        }

        return $query;
    }

    private function getTicketRevenue(Request $request): array
    {
        $query = DB::table('ticket_bookings')
            ->where('temple_id', $request->temple_id)
            ->where('payment_status', 'paid')
            ->where('booking_status', 'confirmed');

        $this->applyDateFilter($query, $request);

        $result = $query->selectRaw('
            COUNT(DISTINCT razorpay_order_id) as booking_count,
            COALESCE(SUM(price), 0) as revenue
        ')->first();

        return [
            'booking_count' => (int) ($result->booking_count ?? 0),
            'revenue' => (float) ($result->revenue ?? 0),
        ];
    }

    private function getPujaRevenue(Request $request): array
    {
        $query = DB::table('puja_bookings')
            ->where('temple_id', $request->temple_id)
            ->where('payment_status', 'paid');

        $this->applyDateFilter($query, $request);

        $result = $query->selectRaw('
            COUNT(id) as booking_count,
            COALESCE(SUM(total_amount), 0) as revenue
        ')->first();

        return [
            'booking_count' => (int) ($result->booking_count ?? 0),
            'revenue' => (float) ($result->revenue ?? 0),
        ];
    }

    private function getReportData(Request $request): array
    {
        $selectedTemple = $request->filled('temple_id')
            ? Temple::find($request->temple_id)
            : null;

        $ticket = [
            'booking_count' => 0,
            'revenue' => 0,
        ];

        $puja = [
            'booking_count' => 0,
            'revenue' => 0,
        ];

        $rows = [];

        if ($selectedTemple) {
            $ticket = $this->getTicketRevenue($request);
            $puja = $this->getPujaRevenue($request);

            $rows = [
                [
                    'module' => 'Ticket Booking',
                    'booking_count' => $ticket['booking_count'],
                    'revenue' => $ticket['revenue'],
                ],
                [
                    'module' => 'Puja Booking',
                    'booking_count' => $puja['booking_count'],
                    'revenue' => $puja['revenue'],
                ],
            ];
        }

        $summary = [
            'ticket_bookings' => $ticket['booking_count'],
            'ticket_revenue' => $ticket['revenue'],
            'puja_bookings' => $puja['booking_count'],
            'puja_revenue' => $puja['revenue'],
            'total_bookings' => $ticket['booking_count'] + $puja['booking_count'],
            'total_revenue' => $ticket['revenue'] + $puja['revenue'],
        ];

        return [
            'temples' => Temple::orderBy('name')->get(),
            'selectedTemple' => $selectedTemple,
            'rows' => $rows,
            'summary' => $summary,
            'fromDate' => $request->from_date,
            'toDate' => $request->to_date,
        ];
    }

    public function index(Request $request)
    {
        $this->validateFilters($request);

        $data = $this->getReportData($request);

        return view('admin.reports.temple-wise-revenue', $data);
    }

    public function exportPdf(Request $request)
    {
        $this->validateFilters($request, true);

        $data = $this->getReportData($request);

        $pdf = Pdf::loadView('admin.reports.temple-wise-revenue-pdf', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->download('temple-wise-revenue-report.pdf');
    }

    public function exportExcel(Request $request)
    {
        $this->validateFilters($request, true);

        $data = $this->getReportData($request);

        return Excel::download(
            new TempleWiseRevenueReportExport($data),
            'temple-wise-revenue-report.xlsx'
        );
    }

    public function exportCsv(Request $request)
    {
        $this->validateFilters($request, true);

        $data = $this->getReportData($request);

        return Response::streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Temple-wise Revenue Report']);
            fputcsv($handle, ['Temple Name', $data['selectedTemple']->name]);
            fputcsv($handle, ['From Date', $data['fromDate'] ?? 'All Dates']);
            fputcsv($handle, ['To Date', $data['toDate'] ?? 'All Dates']);
            fputcsv($handle, []);

            fputcsv($handle, ['Booking Module', 'Booking Count', 'Revenue']);

            foreach ($data['rows'] as $row) {
                fputcsv($handle, [
                    $row['module'],
                    $row['booking_count'],
                    number_format($row['revenue'], 2, '.', ''),
                ]);
            }

            $summary = $data['summary'];

            fputcsv($handle, [
                'Ticket Booking Total',
                $summary['ticket_bookings'],
                number_format($summary['ticket_revenue'], 2, '.', ''),
            ]);

            fputcsv($handle, [
                'Puja Booking Total',
                $summary['puja_bookings'],
                number_format($summary['puja_revenue'], 2, '.', ''),
            ]);

            fputcsv($handle, [
                'Grand Total',
                $summary['total_bookings'],
                number_format($summary['total_revenue'], 2, '.', ''),
            ]);

            fclose($handle);
        }, 'temple-wise-revenue-report.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}