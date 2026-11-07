<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DailyWeeklyMonthlyYearlyRevenueExport;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DailyWeeklyMonthlyYearlyRevenueReportController extends Controller
{

    private function validateFilters(Request $request): void
    {
        $request->validate([
            'period' => ['required', 'in:daily,weekly,monthly,yearly'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);
    }

    private function getPeriodExpressions(string $period): array
    {
        switch ($period) {
            case 'daily':
                return [
                    'key' => 'DATE(created_at)',
                    'start' => 'DATE(created_at)',
                ];
            case 'weekly':
                return [
                    'key' => 'YEARWEEK(created_at, 3)',
                    'start' => 'DATE_SUB(
                        DATE(created_at),
                        INTERVAL WEEKDAY(created_at) DAY
                    )',
                ];
            case 'yearly':
                return [
                    'key' => 'YEAR(created_at)',
                    'start' => 'MAKEDATE(YEAR(created_at), 1)',
                ];
            case 'monthly':
            default:
                return [
                    'key' => "DATE_FORMAT(created_at, '%Y-%m')",
                    'start' => "STR_TO_DATE(
                        DATE_FORMAT(created_at, '%Y-%m-01'),
                        '%Y-%m-%d'
                    )",
                ];
        }
    }

    private function applyDateFilter($query,?string $fromDate,?string $toDate) {
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
    private function getModuleRevenue(
        string $table,
        string $amountColumn,
        string $period,
        ?string $fromDate,
        ?string $toDate,
        ?string $statusColumn = null,
        ?string $statusValue = null,
        bool $ticketCount = false
    ) {
        $expressions = $this->getPeriodExpressions($period);
        $query = DB::table($table);
        // Payment status filter
        if ($statusColumn && $statusValue) {
            $query->where($statusColumn, $statusValue);
        }
        // Ticket booking conditions
        if ($table === 'ticket_bookings') {
            $query->where('booking_status', 'confirmed')->whereNotNull('razorpay_order_id');
        }
        // Apply selected date range
        $this->applyDateFilter(
            $query,
            $fromDate,
            $toDate
        );
        // Ticket booking count is based on distinct Razorpay orders
        $countExpression = $ticketCount
            ? 'COUNT(DISTINCT razorpay_order_id)'
            : 'COUNT(*)';
        return $query
            ->selectRaw(
                $expressions['key'] . ' as period_key'
            )
            // MIN() prevents ONLY_FULL_GROUP_BY errors
            ->selectRaw(
                'MIN(' . $expressions['start'] . ') as period_start'
            )
            ->selectRaw(
                $countExpression . ' as booking_count'
            )
            ->selectRaw(
                "COALESCE(SUM({$amountColumn}), 0) as revenue"
            )
            ->groupByRaw(
                $expressions['key']
            )
            ->get();
    }

    private function getReportData(string $period,?string $fromDate,?string $toDate): array {
        $ticketRows = $this->getModuleRevenue(
            'ticket_bookings',
            'price',
            $period,
            $fromDate,
            $toDate,
            'payment_status',
            'paid',
            true
        );
        $eventRows = $this->getModuleRevenue('sanatan_event_orders','amount',$period,$fromDate,$toDate,'payment_status','paid');
        $pujaRows = $this->getModuleRevenue('puja_bookings','total_amount',$period,$fromDate,$toDate,'payment_status','paid');
        $periods = [];
        $addRows = function ($rows, $module) use (&$periods,$period) {
            foreach ($rows as $row) {
                $key = (string) $row->period_key;
                if (!isset($periods[$key])) {
                    $date = Carbon::parse($row->period_start);
                    switch ($period) {
                        case 'daily':
                            $label = $date->format('d M Y');
                            break;
                        case 'weekly':
                            $weekStart = $date->copy()->startOfWeek(Carbon::MONDAY);
                            $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);
                            $label = $weekStart->format('d M Y')
                                . ' - '
                                . $weekEnd->format('d M Y');
                            break;
                        case 'yearly':
                            $label = $date->format('Y');
                            break;
                        case 'monthly':
                        default:
                            $label = $date->format('F Y');
                            break;
                    }
                    $periods[$key] = [
                        'period_key' => $key,
                        'period_start' => $row->period_start,
                        'period' => $label,
                        'ticket_bookings' => 0,
                        'ticket_revenue' => 0,
                        'event_bookings' => 0,
                        'event_revenue' => 0,
                        'puja_bookings' => 0,
                        'puja_revenue' => 0,
                        'total_bookings' => 0,
                        'total_revenue' => 0,
                    ];
                }
                $periods[$key][$module . '_bookings'] += (int) $row->booking_count;
                $periods[$key][$module . '_revenue'] += (float) $row->revenue;
            }
        };
        $addRows($ticketRows, 'ticket');
        $addRows($eventRows, 'event');
        $addRows($pujaRows, 'puja');
        $periods = array_values($periods);
        foreach ($periods as &$item) {
            $item['total_bookings'] =
                $item['ticket_bookings'] +
                $item['event_bookings'] +
                $item['puja_bookings'];
            $item['total_revenue'] =
                $item['ticket_revenue'] +
                $item['event_revenue'] +
                $item['puja_revenue'];
        }
        unset($item);
        usort($periods, function ($a, $b) {
            return strcmp(
                (string) $a['period_start'],
                (string) $b['period_start']
            );
        });
        $summary = [
            'total_bookings' => 0,
            'ticket_bookings' => 0,
            'event_bookings' => 0,
            'puja_bookings' => 0,
            'ticket_revenue' => 0,
            'event_revenue' => 0,
            'puja_revenue' => 0,
            'total_revenue' => 0,
        ];
        foreach ($periods as $item) {
            $summary['total_bookings'] += $item['total_bookings'];
            $summary['ticket_bookings'] += $item['ticket_bookings'];
            $summary['event_bookings'] += $item['event_bookings'];
            $summary['puja_bookings'] += $item['puja_bookings'];
            $summary['ticket_revenue'] += $item['ticket_revenue'];
            $summary['event_revenue'] += $item['event_revenue'];
            $summary['puja_revenue'] += $item['puja_revenue'];
            $summary['total_revenue'] += $item['total_revenue'];
        }
        return ['summary' => $summary,'periods' => $periods,];
    }

    public function index(Request $request)
    {
        $request->merge([
            'period' => $request->input('period', 'monthly'),
        ]);
        $this->validateFilters($request);
        $period = $request->period;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $report = $this->getReportData($period,$fromDate,$toDate);
        return view('admin.reports.daily-weekly-monthly-yearly-revenue',[
                'summary' => $report['summary'],
                'periods' => $report['periods'],
                'period' => $period,
                'fromDate' => $fromDate,
                'toDate' => $toDate,
            ]
        );
    }

    public function exportPdf(Request $request)
    {
        $request->merge([
            'period' => $request->input('period', 'monthly'),
        ]);
        $this->validateFilters($request);
        $period = $request->period;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $report = $this->getReportData($period,$fromDate,$toDate);
        $pdf = Pdf::loadView(
            'admin.reports.daily-weekly-monthly-yearly-revenue-pdf',
            [
                'summary' => $report['summary'],
                'periods' => $report['periods'],
                'period' => $period,
                'fromDate' => $fromDate,
                'toDate' => $toDate,
            ]
        )->setPaper('a4', 'landscape');
        return $pdf->download('revenue-report-' .$period . '-' .now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $request->merge([
            'period' => $request->input('period', 'monthly'),
        ]);
        $this->validateFilters($request);
        $period = $request->period;
        $report = $this->getReportData($period, $request->from_date, $request->to_date);
        return Excel::download( new DailyWeeklyMonthlyYearlyRevenueExport($report['summary'],
                $report['periods'],
                $period
            ),
            'revenue-report-' . $period . '-' .now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $request->merge([
            'period' => $request->input('period', 'monthly'),
        ]);
        $this->validateFilters($request);
        $period = $request->period;
        $report = $this->getReportData($period,$request->from_date,$request->to_date);
        $summary = $report['summary'];
        $periods = $report['periods'];
        $fileName = 'revenue-report-' . $period . '-' .now()->format('Y-m-d') . '.csv';
        return response()->streamDownload(function () use ($summary,$periods,$period) {
                $handle = fopen('php://output', 'w');
                fprintf($handle,chr(0xEF) . chr(0xBB) . chr(0xBF));
                fputcsv($handle, [
                    'DAILY / WEEKLY / MONTHLY / YEARLY REVENUE REPORT'
                ]);
                fputcsv($handle, [
                    'Report Type',
                    ucfirst($period)
                ]);
                fputcsv($handle, []);
                fputcsv($handle, ['SUMMARY','VALUE']);
                fputcsv($handle, [
                    'Total Bookings',
                    $summary['total_bookings']
                ]);
                fputcsv($handle, [
                    'Ticket Revenue',
                    number_format(
                        $summary['ticket_revenue'],
                        2,
                        '.',
                        ''
                    )
                ]);
                fputcsv($handle, [
                    'Event Revenue',
                    number_format(
                        $summary['event_revenue'],
                        2,
                        '.',
                        ''
                    )
                ]);
                fputcsv($handle, [
                    'Puja Revenue',
                    number_format(
                        $summary['puja_revenue'],
                        2,
                        '.',
                        ''
                    )
                ]);
                fputcsv($handle, [
                    'Total Revenue',
                    number_format(
                        $summary['total_revenue'],
                        2,
                        '.',
                        ''
                    )
                ]);
                fputcsv($handle, []);
                fputcsv($handle, [
                    'Period',
                    'Ticket Bookings',
                    'Ticket Revenue',
                    'Event Bookings',
                    'Event Revenue',
                    'Puja Bookings',
                    'Puja Revenue',
                    'Total Bookings',
                    'Total Revenue',
                ]);
                foreach ($periods as $item) {
                    fputcsv($handle, [
                        $item['period'],
                        $item['ticket_bookings'],
                        number_format(
                            $item['ticket_revenue'],
                            2,
                            '.',
                            ''
                        ),
                        $item['event_bookings'],
                        number_format(
                            $item['event_revenue'],
                            2,
                            '.',
                            ''
                        ),
                        $item['puja_bookings'],
                        number_format(
                            $item['puja_revenue'],
                            2,
                            '.',
                            ''
                        ),
                        $item['total_bookings'],
                        number_format(
                            $item['total_revenue'],
                            2,
                            '.',
                            ''
                        ),
                    ]);
                }
                fputcsv($handle, [
                    'GRAND TOTAL',
                    $summary['ticket_bookings'],
                    number_format(
                        $summary['ticket_revenue'],
                        2,
                        '.',
                        ''
                    ),
                    $summary['event_bookings'],
                    number_format(
                        $summary['event_revenue'],
                        2,
                        '.',
                        ''
                    ),
                    $summary['puja_bookings'],
                    number_format(
                        $summary['puja_revenue'],
                        2,
                        '.',
                        ''
                    ),
                    $summary['total_bookings'],
                    number_format(
                        $summary['total_revenue'],
                        2,
                        '.',
                        ''
                    ),
                ]);
                fclose($handle);
            },
            $fileName,
            [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]
        );
    }
}