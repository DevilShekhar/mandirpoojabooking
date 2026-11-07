<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Exports\DailyWeeklyMonthlyYearlyBookingReportExport;
use App\Models\Temple;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DailyWeeklyMonthlyYearlyBookingReportController extends Controller
{
    private array $moduleNames = [
        'puja' => 'Puja Booking',
        'ticket' => 'Ticket Booking',
        'event' => 'Event Booking',
        'samgri' => 'Samgri Verification',
    ];
    private array $statuses = [
        'pending',
        'confirmed',
        'completed',
        'cancelled',
    ];

    public function index(Request $request)
    {
        $data = $this->getReportData($request, false);
        return view('admin.reports.daily-weekly-monthly-yearly-booking', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getReportData($request, true);
        $pdf = Pdf::loadView(
            'admin.reports.daily-weekly-monthly-yearly-booking-pdf',
            $data
        )->setPaper('a4', 'landscape');
        return $pdf->download('booking-period-report.pdf');
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getReportData($request, true);
        return Excel::download(
            new DailyWeeklyMonthlyYearlyBookingReportExport($data),
            'booking-period-report.xlsx'
        );
    }

    public function exportCsv(Request $request)
    {
        $data = $this->getReportData($request, true);
        $fileName = 'booking-period-report.csv';
        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM for Excel compatibility.
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [
                'Period',
                'Booking Module',
                'Pending',
                'Confirmed',
                'Completed',
                'Cancelled',
                'Total',
            ]);
            foreach ($data['rows'] as $row) {
                fputcsv($handle, [
                    $row['period'],
                    $row['module'],
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
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function getReportData(Request $request, bool $requireFilter): array
    {
        $validated = $request->validate([
            'period' => 'nullable|in:daily,weekly,monthly,yearly,datewise',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);
        if ($requireFilter && empty($validated['from_date']) && empty($validated['to_date'])) {
            abort(422, 'Please select a From Date or To Date before exporting.');
        }
        $period = $validated['period'] ?? 'daily';
        $filters = [
            'period' => $period,
            'from_date' => $validated['from_date'] ?? null,
            'to_date' => $validated['to_date'] ?? null,
        ];
        $records = [];
        if ($requireFilter || $request->hasAny(['period', 'from_date', 'to_date'])) {
            $records = $this->getAllBookingRecords($filters);
        }
        $grouped = [];
        foreach ($records as $record) {
            $date = Carbon::parse($record['created_at']);
            $periodKey = $this->getPeriodKey($date, $period);
            $periodLabel = $this->getPeriodLabel($date, $period);
            if (!isset($grouped[$periodKey])) {
                $grouped[$periodKey] = [
                    'period' => $periodLabel,
                    'modules' => [],
                ];
                foreach ($this->moduleNames as $module => $name) {
                    $grouped[$periodKey]['modules'][$module] = $this->emptyCounts();
                }
            }
            $module = $record['module'];
            $status = strtolower($record['status'] ?? '');
            $grouped[$periodKey]['modules'][$module]['total']++;
            if (in_array($status, $this->statuses, true)) {
                $grouped[$periodKey]['modules'][$module][$status]++;
            }
        }
        ksort($grouped);
        $rows = [];
        $summary = $this->emptyCounts();
        $periods = [];
        foreach ($grouped as $group) {
            $periods[] = $group['period'];
            foreach ($this->moduleNames as $module => $moduleName) {
                $counts = $group['modules'][$module];
                $rows[] = array_merge(
                    [
                        'period' => $group['period'],
                        'module' => $moduleName,
                        'module_key' => $module,
                    ],
                    $counts
                );
                foreach ($this->statuses as $status) {
                    $summary[$status] += $counts[$status];
                }
                $summary['total'] += $counts['total'];
            }
        }
        $moduleSummary = [];
        foreach ($this->moduleNames as $module => $moduleName) {
            $moduleSummary[$module] = $this->emptyCounts();
            foreach ($rows as $row) {
                if ($row['module_key'] !== $module) {
                    continue;
                }
                foreach ($this->statuses as $status) {
                    $moduleSummary[$module][$status] += $row[$status];
                }
                $moduleSummary[$module]['total'] += $row['total'];
            }
        }
        return [
            'filters' => $filters,
            'moduleNames' => $this->moduleNames,
            'statuses' => $this->statuses,
            'rows' => $rows,
            'periods' => $periods,
            'summary' => $summary,
            'moduleSummary' => $moduleSummary,
        ];
    }

    private function getAllBookingRecords(array $filters): array
    {
        $records = [];
        $queries = [
            'puja' => DB::table('puja_bookings')
                ->select('id', 'booking_status as status', 'created_at'),
            'ticket' => DB::table('ticket_bookings')
                ->select('id', 'razorpay_order_id', 'booking_status as status', 'created_at'),
            'event' => DB::table('sanatan_event_orders')
                ->select('id', 'booking_status as status', 'created_at'),
            'samgri' => DB::table('samgri_booking_verifications')
                ->join(
                    'puja_bookings',
                    'puja_bookings.id',
                    '=',
                    'samgri_booking_verifications.puja_booking_id'
                )
                ->select(
                    'samgri_booking_verifications.id',
                    'samgri_booking_verifications.is_verified',
                    'puja_bookings.booking_status',
                    'puja_bookings.created_at'
                ),
        ];
        foreach ($queries as $module => $query) {
            if ($filters['from_date']) {
                $query->whereDate(
                    $module === 'samgri' ? 'puja_bookings.created_at' : 'created_at',
                    '>=',
                    $filters['from_date']
                );
            }
            if ($filters['to_date']) {
                $query->whereDate(
                    $module === 'samgri' ? 'puja_bookings.created_at' : 'created_at',
                    '<=',
                    $filters['to_date']
                );
            }
            $items = $query->get();
            $seenTicketOrders = [];
            foreach ($items as $item) {
                if ($module === 'ticket') {
                    $orderKey = $item->razorpay_order_id ?: 'ticket-' . $item->id;
                    if (isset($seenTicketOrders[$orderKey])) {
                        continue;
                    }
                    $seenTicketOrders[$orderKey] = true;
                }
                if ($module === 'samgri') {
                    if (strtolower($item->booking_status ?? '') === 'cancelled') {
                        $status = 'cancelled';
                    } elseif ((bool) $item->is_verified) {
                        $status = 'completed';
                    } else {
                        $status = 'pending';
                    }
                    $createdAt = $item->created_at;
                } else {
                    $status = $item->status ?? '';
                    $createdAt = $item->created_at;
                }
                if (!$createdAt) {
                    continue;
                }
                $records[] = [
                    'module' => $module,
                    'status' => $status,
                    'created_at' => $createdAt,
                ];
            }
        }
        return $records;
    }

    private function getPeriodKey(Carbon $date, string $period): string
    {
        return match ($period) {
            'weekly' => $date->copy()->startOfWeek(Carbon::MONDAY)->format('Y-m-d'),
            'monthly' => $date->format('Y-m'),
            'yearly' => $date->format('Y'),
            default => $date->format('Y-m-d'),
        };
    }

    private function getPeriodLabel(Carbon $date, string $period): string
    {
        return match ($period) {
            'weekly' => $date->copy()->startOfWeek(Carbon::MONDAY)->format('d M Y')
                . ' - '
                . $date->copy()->endOfWeek(Carbon::SUNDAY)->format('d M Y'),
            'monthly' => $date->format('F Y'),
            'yearly' => $date->format('Y'),
            'datewise' => $date->format('d M Y'),
            default => $date->format('d M Y'),
        };
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