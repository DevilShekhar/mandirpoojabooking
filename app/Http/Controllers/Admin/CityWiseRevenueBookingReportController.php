<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\CityWiseRevenueBookingReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class CityWiseRevenueBookingReportController extends Controller
{
    public function index(Request $request)
    {
        $states = DB::table('states')
            ->orderBy('name')
            ->get(['id', 'name']);

        $cities = collect();

        if ($request->filled('state_id')) {
            $cities = DB::table('cities')
                ->where('state_id', $request->state_id)
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        $report = null;

        if ($request->filled('state_id') &&
            $request->filled('city_id')) {

            $filters = $this->validateFilters($request);
            $report = $this->getReportData($filters);
        }

        return view(
            'admin.reports.city-wise-revenue-booking',
            compact('states', 'cities', 'report')
        );
    }

    public function getCities(Request $request)
    {
        $request->validate([
            'state_id' => ['required', 'integer', 'exists:states,id'],
        ]);

        $cities = DB::table('cities')
            ->where('state_id', $request->state_id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($cities);
    }

    private function validateFilters(Request $request)
    {
        return $request->validate([
            'state_id' => [
                'required',
                'integer',
                'exists:states,id',
            ],
            'city_id' => [
                'required',
                'integer',
                Rule::exists('cities', 'id')->where(
                    'state_id',
                    $request->input('state_id')
                ),
            ],
            'from_date' => [
                'nullable',
                'date',
            ],
            'to_date' => [
                'nullable',
                'date',
                'after_or_equal:from_date',
            ],
        ]);
    }

    private function applyDateFilter(
        $query,
        array $filters,
        string $column
    ) {
        if (!empty($filters['from_date'])) {
            $query->whereDate(
                $column,
                '>=',
                $filters['from_date']
            );
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate(
                $column,
                '<=',
                $filters['to_date']
            );
        }

        return $query;
    }

    private function getReportData(array $filters)
    {
        $state = DB::table('states')
            ->where('id', $filters['state_id'])
            ->first(['id', 'name']);

        $city = DB::table('cities')
            ->where('id', $filters['city_id'])
            ->where('state_id', $filters['state_id'])
            ->first(['id', 'name']);

        // PUJA BOOKINGS
        $pujaQuery = DB::table('puja_bookings as pb')
            ->join(
                'temples as t',
                't.id',
                '=',
                'pb.temple_id'
            )
            ->where('t.state_id', $filters['state_id'])
            ->where('t.city_id', $filters['city_id']);

        $this->applyDateFilter(
            $pujaQuery,
            $filters,
            'pb.created_at'
        );

        $pujaRows = $pujaQuery
            ->select(
                't.id as temple_id',
                't.name as temple_name'
            )
            ->selectRaw('COUNT(pb.id) as total_bookings')
            ->selectRaw("
                SUM(
                    CASE
                        WHEN pb.payment_status = 'paid'
                        THEN 1
                        ELSE 0
                    END
                ) as paid_bookings
            ")
            ->selectRaw("
                SUM(
                    CASE
                        WHEN pb.payment_status = 'paid'
                        THEN COALESCE(pb.total_amount, 0)
                        ELSE 0
                    END
                ) as revenue
            ")
            ->groupBy('t.id', 't.name')
            ->get()
            ->map(function ($row) {
                return [
                    'temple_id' => $row->temple_id,
                    'temple_name' => $row->temple_name,
                    'module' => 'Puja',
                    'total_bookings' => (int) $row->total_bookings,
                    'paid_bookings' => (int) $row->paid_bookings,
                    'revenue' => (float) $row->revenue,
                ];
            });

        // TICKET BOOKINGS
        // Multiple ticket rows belonging to one Razorpay order
        // are counted as one booking.
        $ticketQuery = DB::table('ticket_bookings as tb')
            ->join(
                'temples as t',
                't.id',
                '=',
                'tb.temple_id'
            )
            ->where('t.state_id', $filters['state_id'])
            ->where('t.city_id', $filters['city_id']);

        $this->applyDateFilter(
            $ticketQuery,
            $filters,
            'tb.created_at'
        );

        $ticketRows = $ticketQuery
            ->select(
                't.id as temple_id',
                't.name as temple_name'
            )
            ->selectRaw("
                COUNT(
                    DISTINCT COALESCE(
                        NULLIF(tb.razorpay_order_id, ''),
                        CONCAT('ticket-', tb.id)
                    )
                ) as total_bookings
            ")
            ->selectRaw("
                COUNT(
                    DISTINCT CASE
                        WHEN tb.payment_status = 'paid'
                        THEN COALESCE(
                            NULLIF(tb.razorpay_order_id, ''),
                            CONCAT('ticket-', tb.id)
                        )
                        ELSE NULL
                    END
                ) as paid_bookings
            ")
            ->selectRaw("
                SUM(
                    CASE
                        WHEN tb.payment_status = 'paid'
                        THEN COALESCE(tb.price, 0)
                        ELSE 0
                    END
                ) as revenue
            ")
            ->groupBy('t.id', 't.name')
            ->get()
            ->map(function ($row) {
                return [
                    'temple_id' => $row->temple_id,
                    'temple_name' => $row->temple_name,
                    'module' => 'Ticket',
                    'total_bookings' => (int) $row->total_bookings,
                    'paid_bookings' => (int) $row->paid_bookings,
                    'revenue' => (float) $row->revenue,
                ];
            });

        $rows = $pujaRows
            ->concat($ticketRows)
            ->sort(function ($a, $b) {
                $templeCompare = strcasecmp(
                    $a['temple_name'],
                    $b['temple_name']
                );

                if ($templeCompare !== 0) {
                    return $templeCompare;
                }

                return strcmp($a['module'], $b['module']);
            })
            ->values();

        $summary = [
            'total_bookings' => $rows->sum('total_bookings'),
            'paid_bookings' => $rows->sum('paid_bookings'),

            'puja_bookings' => $pujaRows->sum('total_bookings'),
            'ticket_bookings' => $ticketRows->sum('total_bookings'),

            'puja_revenue' => $pujaRows->sum('revenue'),
            'ticket_revenue' => $ticketRows->sum('revenue'),

            'total_revenue' => $rows->sum('revenue'),

            'total_temples' => $rows
                ->pluck('temple_id')
                ->unique()
                ->count(),
        ];

        return [
            'state' => $state,
            'city' => $city,
            'filters' => $filters,
            'rows' => $rows,
            'summary' => $summary,
        ];
    }

    public function exportPdf(Request $request)
    {
        $filters = $this->validateFilters($request);
        $report = $this->getReportData($filters);

        $pdf = Pdf::loadView(
            'admin.reports.city-wise-revenue-booking-pdf',
            $report
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'city-wise-revenue-booking-report.pdf'
        );
    }

    public function exportExcel(Request $request)
    {
        $filters = $this->validateFilters($request);
        $report = $this->getReportData($filters);

        return Excel::download(
            new CityWiseRevenueBookingReportExport($report),
            'city-wise-revenue-booking-report.xlsx'
        );
    }

    public function exportCsv(Request $request)
    {
        $filters = $this->validateFilters($request);
        $report = $this->getReportData($filters);

        return response()->streamDownload(function () use ($report) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, ['City-wise Revenue & Booking Report']);
            fputcsv($file, ['State', $report['state']->name]);
            fputcsv($file, ['City', $report['city']->name]);

            fputcsv($file, [
                'From Date',
                $report['filters']['from_date'] ?? 'All',
            ]);

            fputcsv($file, [
                'To Date',
                $report['filters']['to_date'] ?? 'All',
            ]);

            fputcsv($file, []);

            fputcsv($file, [
                '#',
                'Temple Name',
                'Module',
                'Total Bookings',
                'Paid Bookings',
                'Revenue (INR)',
            ]);

            foreach ($report['rows'] as $index => $row) {
                fputcsv($file, [
                    $index + 1,
                    $row['temple_name'],
                    $row['module'],
                    $row['total_bookings'],
                    $row['paid_bookings'],
                    number_format($row['revenue'], 2, '.', ''),
                ]);
            }

            fputcsv($file, [
                '',
                'GRAND TOTAL',
                '',
                $report['summary']['total_bookings'],
                $report['summary']['paid_bookings'],
                number_format(
                    $report['summary']['total_revenue'],
                    2,
                    '.',
                    ''
                ),
            ]);

            fputcsv($file, []);
            fputcsv($file, [
                'Puja Revenue',
                number_format(
                    $report['summary']['puja_revenue'],
                    2,
                    '.',
                    ''
                ),
            ]);

            fputcsv($file, [
                'Ticket Revenue',
                number_format(
                    $report['summary']['ticket_revenue'],
                    2,
                    '.',
                    ''
                ),
            ]);

            fclose($file);
        }, 'city-wise-revenue-booking-report.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}