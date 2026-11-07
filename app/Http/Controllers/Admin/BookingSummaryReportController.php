<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Temple;
use App\Exports\BookingSummaryReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingSummaryReportController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getReportData($request, false);

        return view('admin.reports.booking-summary', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getReportData($request, true);

        $pdf = Pdf::loadView(
            'admin.reports.booking-summary-pdf',
            $data
        )->setPaper('a4', 'landscape');

        return $pdf->download('booking-summary-report.pdf');
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getReportData($request, true);

        return Excel::download(
            new BookingSummaryReportExport($data),
            'booking-summary-report.xlsx'
        );
    }

    public function exportCsv(Request $request)
    {
        $data = $this->getReportData($request, true);

        return response()->streamDownload(function () use ($data) {
            $file = fopen('php://output', 'w');

            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, ['BOOKING SUMMARY REPORT']);

            fputcsv($file, [
                'Temple',
                $data['selectedTemple']->name,
            ]);

            fputcsv($file, [
                'From Date',
                $data['filters']['from_date'] ?: 'All',
            ]);

            fputcsv($file, [
                'To Date',
                $data['filters']['to_date'] ?: 'All',
            ]);

            fputcsv($file, [
                'Event Booking',
                'Overall counts - not filtered by Temple',
            ]);

            fputcsv($file, []);

            fputcsv($file, [
                'Booking Module',
                'Pending',
                'Confirmed',
                'Completed',
                'Cancelled',
                'Total',
            ]);

            foreach ($data['reports'] as $module => $report) {
                fputcsv($file, [
                    $data['moduleNames'][$module],
                    $report['pending'],
                    $report['confirmed'],
                    $report['completed'],
                    $report['cancelled'],
                    $report['total'],
                ]);
            }

            fputcsv($file, [
                'Grand Total',
                $data['summary']['pending'],
                $data['summary']['confirmed'],
                $data['summary']['completed'],
                $data['summary']['cancelled'],
                $data['summary']['total'],
            ]);

            fclose($file);
        }, 'booking-summary-report.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function getReportData(
        Request $request,
        bool $requireTemple = false
    ): array {
        $request->validate([
            'temple_id' => [
                $requireTemple ? 'required' : 'nullable',
                'integer',
                'exists:temples,id',
            ],
            'from_date' => ['nullable', 'date'],
            'to_date' => [
                'nullable',
                'date',
                'after_or_equal:from_date',
            ],
        ]);

        $filters = [
            'temple_id' => $request->temple_id,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
        ];

        $temples = Temple::orderBy('name')->get();

        $selectedTemple = $request->filled('temple_id')
            ? Temple::findOrFail($request->temple_id)
            : null;

        $moduleNames = [
            'puja' => 'Puja Booking',
            'ticket' => 'Ticket Booking',
            'event' => 'Event Booking',
            'samgri' => 'Samgri Verification',
        ];

        $reports = [];

        $summary = [
            'pending' => 0,
            'confirmed' => 0,
            'completed' => 0,
            'cancelled' => 0,
            'total' => 0,
        ];

        if ($selectedTemple) {
            $reports = $this->getReports($filters);

            foreach ($reports as $report) {
                foreach ($summary as $status => $value) {
                    $summary[$status] += $report[$status];
                }
            }
        }

        return compact(
            'temples',
            'selectedTemple',
            'reports',
            'summary',
            'filters',
            'moduleNames'
        );
    }

    private function getReports(array $filters): array
    {
        return [
            'puja' => $this->getStatusCounts(
                $this->applyTempleAndDateFilters(
                    DB::table('puja_bookings'),
                    'puja_bookings',
                    $filters
                ),
                'booking_status'
            ),

            'ticket' => $this->getStatusCounts(
                $this->applyTempleAndDateFilters(
                    DB::table('ticket_bookings'),
                    'ticket_bookings',
                    $filters
                ),
                'booking_status'
            ),

            // Event booking is not associated with temple_id.
            'event' => $this->getStatusCounts(
                $this->applyDateFilters(
                    DB::table('sanatan_event_orders'),
                    'sanatan_event_orders',
                    $filters
                ),
                'booking_status'
            ),

            'samgri' => $this->getSamgriCounts($filters),
        ];
    }

    private function applyTempleAndDateFilters(
        $query,
        string $table,
        array $filters
    ) {
        $query->where(
            $table . '.temple_id',
            $filters['temple_id']
        );

        return $this->applyDateFilters(
            $query,
            $table,
            $filters
        );
    }

    private function applyDateFilters(
        $query,
        string $table,
        array $filters
    ) {
        if (!empty($filters['from_date'])) {
            $query->whereDate(
                $table . '.created_at',
                '>=',
                $filters['from_date']
            );
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate(
                $table . '.created_at',
                '<=',
                $filters['to_date']
            );
        }

        return $query;
    }

    private function getStatusCounts(
        $query,
        string $statusColumn
    ): array {
        $countStatus = function ($status) use (
            $query,
            $statusColumn
        ) {
            return (clone $query)
                ->whereRaw(
                    'LOWER(' . $statusColumn . ') = ?',
                    [$status]
                )
                ->count();
        };

        return [
            'pending' => $countStatus('pending'),
            'confirmed' => $countStatus('confirmed'),
            'completed' => $countStatus('completed'),
            'cancelled' => $countStatus('cancelled'),
            'total' => (clone $query)->count(),
        ];
    }

    private function getSamgriCounts(array $filters): array
    {
        $query = DB::table('samgri_booking_verifications')
            ->join(
                'puja_bookings',
                'puja_bookings.id',
                '=',
                'samgri_booking_verifications.puja_booking_id'
            )
            ->where(
                'puja_bookings.temple_id',
                $filters['temple_id']
            );

        if (!empty($filters['from_date'])) {
            $query->whereDate(
                'puja_bookings.created_at',
                '>=',
                $filters['from_date']
            );
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate(
                'puja_bookings.created_at',
                '<=',
                $filters['to_date']
            );
        }

        $count = function ($callback) use ($query) {
            $filteredQuery = clone $query;

            $callback($filteredQuery);

            return $filteredQuery
                ->distinct()
                ->count('samgri_booking_verifications.id');
        };

        // Cancelled booking has first priority.
        $cancelled = $count(function ($q) {
            $q->whereRaw(
                'LOWER(puja_bookings.booking_status) = ?',
                ['cancelled']
            );
        });

        // OTP verified means completed, unless cancelled.
        $completed = $count(function ($q) {
            $q->whereRaw(
                'LOWER(puja_bookings.booking_status) != ?',
                ['cancelled']
            )->where(
                'samgri_booking_verifications.is_verified',
                1
            );
        });

        // OTP not verified means pending, unless cancelled.
        $pending = $count(function ($q) {
            $q->whereRaw(
                'LOWER(puja_bookings.booking_status) != ?',
                ['cancelled']
            )->where(function ($verificationQuery) {
                $verificationQuery
                    ->where(
                        'samgri_booking_verifications.is_verified',
                        0
                    )
                    ->orWhereNull(
                        'samgri_booking_verifications.is_verified'
                    );
            });
        });

        return [
            'pending' => $pending,
            'confirmed' => 0,
            'completed' => $completed,
            'cancelled' => $cancelled,
            'total' => $pending + $completed + $cancelled,
        ];
    }
}