<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Exports\RepeatCustomerReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RepeatCustomerReportController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getReportData($request);
        return view('admin.reports.repeat-customer', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getReportData($request);
        $pdf = Pdf::loadView('admin.reports.repeat-customer-pdf',$data)->setPaper('a4', 'landscape');
        return $pdf->download('repeat-customer-report.pdf');
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getReportData($request);
        return Excel::download(new RepeatCustomerReportExport($data),'repeat-customer-report.xlsx');
    }

    public function exportCsv(Request $request)
    {
        $data = $this->getReportData($request);
        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [
                'Customer Name',
                'Booking Type',
                'Temple Name',
                'City',
                'State',
                'Booking Count',
                'Last Booking Date',
            ]);
            foreach ($data['rows'] as $row) {
                fputcsv($handle, [
                    $row['customer_name'],
                    $row['booking_type'],
                    $row['temple_name'],
                    $row['city_name'],
                    $row['state_name'],
                    $row['booking_count'],
                    $row['last_booking'],
                ]);
            }
            fputcsv($handle, []);
            fputcsv($handle, [
                'Total Repeat Customer Entries',
                '',
                '',
                '',
                '',
                $data['summary']['total_bookings'],
                '',
            ]);
            fclose($handle);
        }, 'repeat-customer-report.csv', [
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
        $rows = array_merge(
            $this->getPujaRepeatCustomers($filters),
            $this->getTicketRepeatCustomers($filters),
            $this->getEventRepeatCustomers($filters)
        );
        usort($rows, function ($a, $b) {
            $nameCompare = strcasecmp(
                $a['customer_name'],
                $b['customer_name']
            );
            if ($nameCompare !== 0) {
                return $nameCompare;
            }
            $moduleCompare = strcmp(
                $a['booking_type'],
                $b['booking_type']
            );
            if ($moduleCompare !== 0) {
                return $moduleCompare;
            }
            return strcasecmp($a['temple_name'], $b['temple_name']);
        });
        $summary = [
            'repeat_customer_entries' => count($rows),
            'unique_customers' => count(array_unique(array_column($rows, 'user_id'))),
            'total_bookings' => array_sum(array_column($rows, 'booking_count')),
        ];
        return [
            'filters' => $filters,
            'rows' => $rows,
            'summary' => $summary,
        ];
    }
    private function getPujaRepeatCustomers(array $filters): array
    {
        $query = DB::table('puja_bookings')
            ->join('users', 'users.id', '=', 'puja_bookings.user_id')
            ->leftJoin('temples', 'temples.id', '=', 'puja_bookings.temple_id')
            ->leftJoin('cities', 'cities.id', '=', 'temples.city_id')
            ->leftJoin('states', 'states.id', '=', 'temples.state_id')
            ->select(
                'users.id as user_id',
                'users.name as customer_name',
                'temples.id as temple_id',
                'temples.name as temple_name',
                'cities.name as city_name',
                'states.name as state_name'
            )
            ->selectRaw('COUNT(puja_bookings.id) as booking_count')
            ->selectRaw('MAX(puja_bookings.created_at) as last_booking')
            ->groupBy(
                'users.id',
                'users.name',
                'temples.id',
                'temples.name',
                'cities.name',
                'states.name'
            )
            ->havingRaw('COUNT(puja_bookings.id) >= 2');
        $this->applyDateFilters($query, 'puja_bookings.created_at', $filters);
        return $query->get()->map(function ($item) {
            return [
                'user_id' => $item->user_id,
                'customer_name' => $item->customer_name,
                'booking_type' => 'Puja Booking',
                'temple_name' => $item->temple_name ?? 'Temple Not Available',
                'city_name' => $item->city_name ?? '-',
                'state_name' => $item->state_name ?? '-',
                'booking_count' => (int) $item->booking_count,
                'last_booking' => $item->last_booking,
            ];
        })->all();
    }

    private function getTicketRepeatCustomers(array $filters): array
    {
        $bookingCount = "COUNT(DISTINCT COALESCE(ticket_bookings.razorpay_order_id, CONCAT('ticket-', ticket_bookings.id)))";
        $query = DB::table('ticket_bookings')
            ->join('users', 'users.id', '=', 'ticket_bookings.user_id')
            ->leftJoin('temples', 'temples.id', '=', 'ticket_bookings.temple_id')
            ->leftJoin('cities', 'cities.id', '=', 'temples.city_id')
            ->leftJoin('states', 'states.id', '=', 'temples.state_id')
            ->select(
                'users.id as user_id',
                'users.name as customer_name',
                'temples.id as temple_id',
                'temples.name as temple_name',
                'cities.name as city_name',
                'states.name as state_name'
            )
            ->selectRaw("$bookingCount as booking_count")
            ->selectRaw('MAX(ticket_bookings.created_at) as last_booking')
            ->groupBy(
                'users.id',
                'users.name',
                'temples.id',
                'temples.name',
                'cities.name',
                'states.name'
            )
            ->havingRaw("$bookingCount >= 2");
        $this->applyDateFilters($query, 'ticket_bookings.created_at', $filters);
        return $query->get()->map(function ($item) {
            return [
                'user_id' => $item->user_id,
                'customer_name' => $item->customer_name,
                'booking_type' => 'Ticket Booking',
                'temple_name' => $item->temple_name ?? 'Temple Not Available',
                'city_name' => $item->city_name ?? '-',
                'state_name' => $item->state_name ?? '-',
                'booking_count' => (int) $item->booking_count,
                'last_booking' => $item->last_booking,
            ];
        })->all();
    }

    private function getEventRepeatCustomers(array $filters): array
    {
        $query = DB::table('sanatan_event_orders')
            ->join('users', 'users.id', '=', 'sanatan_event_orders.user_id')
            ->select(
                'users.id as user_id',
                'users.name as customer_name'
            )
            ->selectRaw('COUNT(sanatan_event_orders.id) as booking_count')
            ->selectRaw('MAX(sanatan_event_orders.created_at) as last_booking')
            ->groupBy('users.id', 'users.name')
            ->havingRaw('COUNT(sanatan_event_orders.id) >= 2');
        $this->applyDateFilters(
            $query,
            'sanatan_event_orders.created_at',
            $filters
        );
        return $query->get()->map(function ($item) {
            return [
                'user_id' => $item->user_id,
                'customer_name' => $item->customer_name,
                'booking_type' => 'Event Booking',
                'temple_name' => 'Independent Event',
                'city_name' => '-',
                'state_name' => '-',
                'booking_count' => (int) $item->booking_count,
                'last_booking' => $item->last_booking,
            ];
        })->all();
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
}