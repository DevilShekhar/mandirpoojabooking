<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Exports\TemplePerformanceReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TemplePerformanceReportController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getReportData($request, false);
        return view('admin.reports.temple-performance', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getReportData($request, true);
        $pdf = Pdf::loadView('admin.reports.temple-performance-pdf',$data)->setPaper('a4', 'landscape');
        return $pdf->download('temple-performance-report.pdf');
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getReportData($request, true);
        return Excel::download(new TemplePerformanceReportExport($data),'temple-performance-report.xlsx');
    }

    public function exportCsv(Request $request)
    {
        $data = $this->getReportData($request, true);
        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [
                'Temple Name',
                'City',
                'State',
                'Booking Module',
                'Total Bookings',
                'Paid Bookings',
                'Completed Bookings',
                'Cancelled Bookings',
                'Revenue',
            ]);
            foreach ($data['reports'] as $report) {
                fputcsv($handle, [
                    $data['selectedTemple']->name,
                    $data['selectedTemple']->city_name ?? '-',
                    $data['selectedTemple']->state_name ?? '-',
                    $report['module'],
                    $report['total_bookings'],
                    $report['paid_bookings'],
                    $report['completed_bookings'],
                    $report['cancelled_bookings'],
                    number_format($report['revenue'], 2, '.', ''),
                ]);
            }
            fputcsv($handle, [
                $data['selectedTemple']->name,
                $data['selectedTemple']->city_name ?? '-',
                $data['selectedTemple']->state_name ?? '-',
                'Grand Total',
                $data['summary']['total_bookings'],
                $data['summary']['paid_bookings'],
                $data['summary']['completed_bookings'],
                $data['summary']['cancelled_bookings'],
                number_format($data['summary']['total_revenue'], 2, '.', ''),
            ]);
            fputcsv($handle, []);
            fputcsv($handle, [
                'CUSTOMER-WISE BOOKING DETAILS',
            ]);
            fputcsv($handle, [
                'Customer Name',
                'Booking Module',
                'Booking Status',
                'Payment Status',
                'Amount',
                'Booking Date',
            ]);
            foreach ($data['customerBookings'] as $booking) {
                fputcsv($handle, [
                    $booking['customer_name'],
                    $booking['booking_type'],
                    $booking['booking_status'],
                    $booking['payment_status'],
                    number_format($booking['amount'], 2, '.', ''),
                    $booking['created_at'],
                ]);
            }
            fclose($handle);
        }, 'temple-performance-report.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
    private function getReportData(Request $request, bool $requireTemple): array
    {
        $validated = $request->validate([
            'temple_id' => ($requireTemple ? 'required' : 'nullable'). '|integer|exists:temples,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'status' => 'nullable|in:all,paid,confirmed,completed,cancelled,cancel_requested',
        ]);
        $filters = [
            'temple_id' => $validated['temple_id'] ?? null,
            'from_date' => $validated['from_date'] ?? null,
            'to_date' => $validated['to_date'] ?? null,
            'status' => $validated['status'] ?? 'all',
        ];
        $temples = DB::table('temples')->orderBy('name')->get(['id', 'name']);
        $selectedTemple = null;
        $reports = [];
        $customerBookings = [];
        $summary = $this->emptySummary();
        if ($filters['temple_id']) {
            $selectedTemple = DB::table('temples')
                ->leftJoin('cities', 'cities.id', '=', 'temples.city_id')
                ->leftJoin('states', 'states.id', '=', 'temples.state_id')
                ->where('temples.id', $filters['temple_id'])
                ->select(
                    'temples.id',
                    'temples.name',
                    'cities.name as city_name',
                    'states.name as state_name'
                )
                ->first();
            $reports = [
                $this->getPujaPerformance($filters),
                $this->getTicketPerformance($filters),
            ];
            foreach ($reports as $report) {
                $summary['total_bookings'] += $report['total_bookings'];
                $summary['paid_bookings'] += $report['paid_bookings'];
                $summary['completed_bookings'] += $report['completed_bookings'];
                $summary['cancelled_bookings'] += $report['cancelled_bookings'];
                $summary['total_revenue'] += $report['revenue'];
            }
            $customerBookings = $this->getCustomerBookings($filters);
        }
        return [
            'temples' => $temples,
            'selectedTemple' => $selectedTemple,
            'filters' => $filters,
            'reports' => $reports,
            'summary' => $summary,
            'customerBookings' => $customerBookings,
        ];
    }

    private function getPujaPerformance(array $filters): array
    {
        $query = DB::table('puja_bookings')->where('temple_id', $filters['temple_id']);
        $this->applyDateFilters($query, 'created_at', $filters);
        $result = $query->selectRaw('COUNT(*) as total_bookings')
            ->selectRaw("
                SUM(
                    CASE WHEN LOWER(payment_status) = 'paid'
                    THEN 1 ELSE 0 END
                ) as paid_bookings
            ")
            ->selectRaw("
                SUM(
                    CASE WHEN LOWER(booking_status) = 'completed'
                    THEN 1 ELSE 0 END
                ) as completed_bookings
            ")
            ->selectRaw("
                SUM(
                    CASE WHEN LOWER(booking_status) = 'cancelled'
                    THEN 1 ELSE 0 END
                ) as cancelled_bookings
            ")
            ->selectRaw("
                SUM(
                    CASE WHEN LOWER(payment_status) = 'paid'
                    THEN total_amount ELSE 0 END
                ) as revenue
            ")
            ->first();
        return [
            'module' => 'Puja Booking',
            'total_bookings' => (int) ($result->total_bookings ?? 0),
            'paid_bookings' => (int) ($result->paid_bookings ?? 0),
            'completed_bookings' => (int) ($result->completed_bookings ?? 0),
            'cancelled_bookings' => (int) ($result->cancelled_bookings ?? 0),
            'revenue' => (float) ($result->revenue ?? 0),
        ];
    }

    private function getTicketPerformance(array $filters): array
    {
        $query = DB::table('ticket_bookings')->where('temple_id', $filters['temple_id']);
        $this->applyDateFilters($query, 'created_at', $filters);
        $orderExpression = "COALESCE(razorpay_order_id, CONCAT('ticket-', id))";
        $result = $query
            ->selectRaw("
                COUNT(DISTINCT $orderExpression) as total_bookings
            ")
            ->selectRaw("
                COUNT(
                    DISTINCT CASE
                        WHEN LOWER(payment_status) = 'paid'
                        AND LOWER(booking_status) = 'confirmed'
                        THEN $orderExpression
                    END
                ) as paid_bookings
            ")
            ->selectRaw("
                COUNT(
                    DISTINCT CASE
                        WHEN LOWER(booking_status) = 'completed'
                        THEN $orderExpression
                    END
                ) as completed_bookings
            ")
            ->selectRaw("
                COUNT(
                    DISTINCT CASE
                        WHEN LOWER(booking_status) = 'cancelled'
                        THEN $orderExpression
                    END
                ) as cancelled_bookings
            ")
            ->selectRaw("
                SUM(
                    CASE
                        WHEN LOWER(payment_status) = 'paid'
                        AND LOWER(booking_status) = 'confirmed'
                        THEN price ELSE 0
                    END
                ) as revenue
            ")
            ->first();

        return [
            'module' => 'Ticket Booking',
            'total_bookings' => (int) ($result->total_bookings ?? 0),
            'paid_bookings' => (int) ($result->paid_bookings ?? 0),
            'completed_bookings' => (int) ($result->completed_bookings ?? 0),
            'cancelled_bookings' => (int) ($result->cancelled_bookings ?? 0),
            'revenue' => (float) ($result->revenue ?? 0),
        ];
    }

    private function getCustomerBookings(array $filters): array
    {
        $rows = [];
        // Puja bookings
        $pujaQuery = DB::table('puja_bookings')
            ->leftJoin('users', 'users.id', '=', 'puja_bookings.user_id')
            ->where('puja_bookings.temple_id', $filters['temple_id'])
            ->select(
                'users.name as customer_name',
                'puja_bookings.booking_status',
                'puja_bookings.payment_status',
                'puja_bookings.total_amount as amount',
                'puja_bookings.created_at'
            );
        $this->applyDateFilters($pujaQuery,'puja_bookings.created_at',$filters);
        $this->applyStatusFilter($pujaQuery,'puja_bookings',$filters['status']);
        foreach ($pujaQuery->orderByDesc('puja_bookings.created_at')->get() as $booking) {
            $rows[] = [
                'customer_name' => $booking->customer_name ?? 'Guest Customer',
                'booking_type' => 'Puja Booking',
                'booking_status' => $booking->booking_status ?? '-',
                'payment_status' => $booking->payment_status ?? '-',
                'amount' => (float) ($booking->amount ?? 0),
                'created_at' => $booking->created_at,
            ];
        }
        // Ticket bookings
        $ticketQuery = DB::table('ticket_bookings')
            ->leftJoin('users', 'users.id', '=', 'ticket_bookings.user_id')
            ->where('ticket_bookings.temple_id', $filters['temple_id'])
            ->select(
                'users.name as customer_name',
                'ticket_bookings.booking_status',
                'ticket_bookings.payment_status',
                'ticket_bookings.price as amount',
                'ticket_bookings.created_at'
            );
        $this->applyDateFilters($ticketQuery,'ticket_bookings.created_at',$filters);
        $this->applyStatusFilter($ticketQuery,'ticket_bookings',$filters['status']);
        foreach ($ticketQuery->orderByDesc('ticket_bookings.created_at')->get() as $booking) {
            $rows[] = [
                'customer_name' => $booking->customer_name ?? 'Guest Customer',
                'booking_type' => 'Ticket Booking',
                'booking_status' => $booking->booking_status ?? '-',
                'payment_status' => $booking->payment_status ?? '-',
                'amount' => (float) ($booking->amount ?? 0),
                'created_at' => $booking->created_at,
            ];
        }
        usort($rows, function ($a, $b) {
            return strcmp(
                (string) $b['created_at'],
                (string) $a['created_at']
            );
        });
        return $rows;
    }

    private function applyStatusFilter($query, string $table, string $status): void
    {
        if ($status === 'paid') {
            $query->whereRaw(
                "LOWER($table.payment_status) = ?",
                ['paid']
            );
        } elseif (in_array($status, [
            'confirmed',
            'completed',
            'cancelled',
            'cancel_requested',
        ], true)) {
            $query->whereRaw(
                "LOWER($table.booking_status) = ?",
                [$status]
            );
        }
    }

    private function applyDateFilters($query, string $column, array $filters): void
    {
        if (!empty($filters['from_date'])) {
            $query->whereDate($column, '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $query->whereDate($column, '<=', $filters['to_date']);
        }
    }

    private function emptySummary(): array
    {
        return [
            'total_bookings' => 0,
            'paid_bookings' => 0,
            'completed_bookings' => 0,
            'cancelled_bookings' => 0,
            'total_revenue' => 0,
        ];
    }
}