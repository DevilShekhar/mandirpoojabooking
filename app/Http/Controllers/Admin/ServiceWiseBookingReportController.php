<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\ServiceWiseBookingReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ServiceWiseBookingReportController extends Controller
{
    private array $statuses = [
        'pending',
        'confirmed',
        'completed',
        'cancelled',
    ];

    private array $moduleNames = [
        'puja' => 'Puja Booking',
        'ticket' => 'Ticket Booking',
        'event' => 'Event Booking',
        'samgri' => 'Samgri Verification',
    ];

    public function index(Request $request)
    {
        $data = $this->getReportData($request);

        return view('admin.reports.service-wise-booking', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getReportData($request);

        $pdf = Pdf::loadView(
            'admin.reports.service-wise-booking-pdf',
            $data
        )->setPaper('a4', 'landscape');

        return $pdf->download('service-wise-booking-report.pdf');
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getReportData($request);

        return Excel::download(
            new ServiceWiseBookingReportExport($data),
            'service-wise-booking-report.xlsx'
        );
    }

    public function exportCsv(Request $request)
    {
        $data = $this->getReportData($request);

        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Booking Module',
                'Service Name',
                'Pending',
                'Confirmed',
                'Completed',
                'Cancelled',
                'Total',
            ]);

            foreach ($data['rows'] as $row) {
                fputcsv($handle, [
                    $row['module'],
                    $row['service_name'],
                    $row['pending'],
                    $row['confirmed'],
                    $row['completed'],
                    $row['cancelled'],
                    $row['total'],
                ]);
            }

            fputcsv($handle, []);

            fputcsv($handle, [
                'Grand Total',
                '',
                $data['summary']['pending'],
                $data['summary']['confirmed'],
                $data['summary']['completed'],
                $data['summary']['cancelled'],
                $data['summary']['total'],
            ]);

            fclose($handle);
        }, 'service-wise-booking-report.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function getReportData(Request $request): array
    {
        $validated = $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $filters = [
            'from_date' => $validated['from_date'] ?? null,
            'to_date' => $validated['to_date'] ?? null,
        ];

        $rows = [];

        $this->addPujaBookings($rows, $filters);
        $this->addTicketBookings($rows, $filters);
        $this->addEventBookings($rows, $filters);
        $this->addSamgriVerifications($rows, $filters);

        usort($rows, function ($a, $b) {
            $moduleCompare = strcmp($a['module'], $b['module']);

            return $moduleCompare !== 0
                ? $moduleCompare
                : strcasecmp($a['service_name'], $b['service_name']);
        });

        $summary = $this->emptyCounts();

        foreach ($rows as $row) {
            foreach ($this->statuses as $status) {
                $summary[$status] += $row[$status];
            }

            $summary['total'] += $row['total'];
        }

        $moduleSummary = [];

        foreach ($this->moduleNames as $key => $name) {
            $moduleSummary[$key] = [
                'name' => $name,
                ...$this->emptyCounts(),
            ];
        }

        foreach ($rows as $row) {
            $key = $row['module_key'];

            foreach ($this->statuses as $status) {
                $moduleSummary[$key][$status] += $row[$status];
            }

            $moduleSummary[$key]['total'] += $row['total'];
        }

        return [
            'filters' => $filters,
            'rows' => $rows,
            'summary' => $summary,
            'moduleSummary' => $moduleSummary,
            'moduleNames' => $this->moduleNames,
        ];
    }

    private function addPujaBookings(array &$rows, array $filters): void
    {
        $query = DB::table('puja_bookings')
            ->leftJoin('pujas', 'pujas.id', '=', 'puja_bookings.puja_id')
            ->select(
                'puja_bookings.id',
                'puja_bookings.booking_status as status',
                'pujas.name as service_name'
            );

        $this->applyDateFilters($query, 'puja_bookings.created_at', $filters);

        $this->aggregateRecords(
            $rows,
            $query->get(),
            'puja',
            'Puja Booking'
        );
    }

    private function addTicketBookings(array &$rows, array $filters): void
    {
        $query = DB::table('ticket_bookings')
            ->select(
                'id',
                'razorpay_order_id',
                'ticket_type as service_name',
                'booking_status as status'
            );

        $this->applyDateFilters($query, 'created_at', $filters);

        $this->aggregateRecords(
            $rows,
            $query->get(),
            'ticket',
            'Ticket Booking',
            true
        );
    }

    private function addEventBookings(array &$rows, array $filters): void
    {
        $query = DB::table('sanatan_event_orders')
            ->join(
                'sanatans',
                'sanatans.id',
                '=',
                'sanatan_event_orders.sanatan_id'
            )
            ->select(
                'sanatan_event_orders.id',
                'sanatan_event_orders.booking_status as status',
                'sanatans.name as service_name'
            );

        $this->applyDateFilters($query, 'sanatan_event_orders.created_at', $filters);

        $this->aggregateRecords(
            $rows,
            $query->get(),
            'event',
            'Event Booking'
        );
    }

    private function addSamgriVerifications(array &$rows, array $filters): void
    {
        $query = DB::table('samgri_booking_verifications')
            ->join(
                'puja_bookings',
                'puja_bookings.id',
                '=',
                'samgri_booking_verifications.puja_booking_id'
            )
            ->leftJoin('pujas', 'pujas.id', '=', 'puja_bookings.puja_id')
            ->select(
                'samgri_booking_verifications.id',
                'samgri_booking_verifications.is_verified',
                'puja_bookings.booking_status',
                'pujas.name as service_name'
            );

        $this->applyDateFilters($query, 'puja_bookings.created_at', $filters);

        $records = $query->get()->map(function ($record) {
            if (strtolower($record->booking_status ?? '') === 'cancelled') {
                $record->status = 'cancelled';
            } elseif ((bool) $record->is_verified) {
                $record->status = 'completed';
            } else {
                $record->status = 'pending';
            }

            return $record;
        });

        $this->aggregateRecords(
            $rows,
            $records,
            'samgri',
            'Samgri Verification'
        );
    }

    private function aggregateRecords(
        array &$rows,
        $records,
        string $moduleKey,
        string $moduleName,
        bool $distinctTicketOrders = false
    ): void {
        $grouped = [];
        $seenOrders = [];

        foreach ($records as $record) {
            $serviceName = trim((string) ($record->service_name ?? ''));

            if ($serviceName === '') {
                $serviceName = 'Unknown Service';
            }

            if ($distinctTicketOrders) {
                $orderId = $record->razorpay_order_id
                    ? 'order-' . $record->razorpay_order_id
                    : 'ticket-' . $record->id;

                $uniqueKey = $moduleKey . '|' . $serviceName . '|' . $orderId;

                if (isset($seenOrders[$uniqueKey])) {
                    continue;
                }

                $seenOrders[$uniqueKey] = true;
            }

            if (!isset($grouped[$serviceName])) {
                $grouped[$serviceName] = $this->emptyCounts();
            }

            $grouped[$serviceName]['total']++;

            $status = strtolower(trim((string) ($record->status ?? '')));

            if (in_array($status, $this->statuses, true)) {
                $grouped[$serviceName][$status]++;
            }
        }

        foreach ($grouped as $serviceName => $counts) {
            $rows[] = [
                'module_key' => $moduleKey,
                'module' => $moduleName,
                'service_name' => $serviceName,
                ...$counts,
            ];
        }
    }

    private function applyDateFilters($query, string $column, array $filters): void
    {
        if ($filters['from_date']) {
            $query->whereDate($column, '>=', $filters['from_date']);
        }

        if ($filters['to_date']) {
            $query->whereDate($column, '<=', $filters['to_date']);
        }
    }

    private function emptyCounts(): array
    {
        return [
            'pending' => 0,
            'confirmed' => 0,
            'completed' => 0,
            'cancelled' => 0,
            'total' => 0,
        ];
    }
}