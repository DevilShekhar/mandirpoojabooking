<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Exports\PujariAssignmentPerformanceExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PujariAssignmentPerformanceReportController extends Controller
{

    public function index(Request $request)
    {
        $data = $this->getReportData($request);
        return view('admin.reports.pujari-assignment-performance',$data);
    }

    public function getTemplePanditsAjax($templeId)
    {
        $panditRoleId = DB::table('roles')->where('name', 'Pandit')->value('id');
        if (!$panditRoleId) {
            return response()->json([]);
        }
        $pandits = DB::table('temple_user')
            ->join(
                'users',
                'users.id',
                '=',
                'temple_user.user_id'
            )
            ->where('temple_user.temple_id', $templeId)
            ->where('users.role_id', $panditRoleId)
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.phone'
            )
            ->distinct()
            ->orderBy('users.name')
            ->get();
        return response()->json($pandits);
    }

    private function getReportData(Request $request)
    {
        $temples = DB::table('temples')->select('id', 'name')->orderBy('name')->get();
        $templeId = $request->input('temple_id');
        $panditId = $request->input('pandit_id');
        $status = strtolower($request->input('status', 'all'));
        $allowedStatuses = ['all','confirmed','cancelled','refund',];
        if (!in_array($status, $allowedStatuses, true)) {
            $status = 'all';
        }
        $statusLabels = [
            'all' => 'All',
            'confirmed' => 'Confirmed',
            'cancelled' => 'Cancelled',
            'refund' => 'Refund',
        ];
        $pandits = collect();
        $selectedTemple = null;
        $selectedPandit = null;
        $bookingRows = collect();
        $performanceRows = collect();
        $summary = $this->emptySummary();
        $hasFilter = !empty($templeId);

        if ($templeId) {
            $selectedTemple = DB::table('temples')->where('id', $templeId)->first();
            if (!$selectedTemple) {
                $templeId = null;
                $panditId = null;
                $hasFilter = false;
            }
        }

        if ($templeId) {
            $pandits = $this->getTemplePandits($templeId);
            if ($panditId) {
                $selectedPandit = $pandits->first(function ($pandit) use ($panditId) {
                    return (int) $pandit->id === (int) $panditId;
                });
                if (!$selectedPandit) {
                    $panditId = null;
                }
            }
            $query = $this->bookingQuery($templeId);
            if ($panditId) {
                $query->where(
                    'puja_bookings.pandit_id',
                    $panditId
                );
            }

            if ($request->filled('from_date')) {
                $query->whereDate(
                    'puja_bookings.schedule_date',
                    '>=',
                    $request->from_date
                );
            }
            if ($request->filled('to_date')) {
                $query->whereDate(
                    'puja_bookings.schedule_date',
                    '<=',
                    $request->to_date
                );
            }

            if ($status === 'confirmed') {
                $query->where(
                    'puja_bookings.booking_status',
                    'confirmed'
                );
            } elseif ($status === 'cancelled') {
                $query->where(
                    'puja_bookings.booking_status',
                    'cancelled'
                );
            } elseif ($status === 'refund') {
                $query->whereExists(function ($subQuery) {
                    $subQuery->select(DB::raw(1))
                        ->from('customer_booking_cancellations')
                        ->whereColumn(
                            'customer_booking_cancellations.puja_booking_id',
                            'puja_bookings.id'
                        )
                        ->where(
                            'customer_booking_cancellations.refund_status',
                            'processed'
                        );
                });
            }

            $bookingRows = $query->orderByDesc('puja_bookings.schedule_date')->orderByDesc('puja_bookings.id')->get();
            $performanceRows = $this->getPerformanceRows($bookingRows);
            $summary = $this->calculateSummary($bookingRows);
        }
        return [
            'temples' => $temples,
            'pandits' => $pandits,
            'selectedTemple' => $selectedTemple,
            'selectedPandit' => $selectedPandit,
            'filters' => [
                'temple_id' => $templeId,
                'pandit_id' => $panditId,
                'from_date' => $request->input('from_date'),
                'to_date' => $request->input('to_date'),
                'status' => $status,
            ],
            'selectedStatus' => $statusLabels[$status],
            'bookingRows' => $bookingRows,
            'performanceRows' => $performanceRows,
            'summary' => $summary,
            'hasFilter' => $hasFilter,
        ];
    }
    private function getTemplePandits($templeId)
    {
        $panditRoleId = DB::table('roles')->where('name', 'Pandit')->value('id');
        if (!$panditRoleId) {
            return collect();
        }
        return DB::table('temple_user')
            ->join(
                'users',
                'users.id',
                '=',
                'temple_user.user_id'
            )
            ->where('temple_user.temple_id', $templeId)
            ->where('users.role_id', $panditRoleId)
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.phone'
            )
            ->distinct()
            ->orderBy('users.name')
            ->get();
    }

    private function bookingQuery($templeId)
    {
        $refundStatusQuery = DB::table('customer_booking_cancellations')
            ->select('refund_status')
            ->whereColumn(
                'customer_booking_cancellations.puja_booking_id',
                'puja_bookings.id'
            )
            ->orderByDesc('id')
            ->limit(1);
        $refundAmountQuery = DB::table('customer_booking_cancellations')
            ->select('refund_amount')
            ->whereColumn(
                'customer_booking_cancellations.puja_booking_id',
                'puja_bookings.id'
            )
            ->orderByDesc('id')
            ->limit(1);
        return DB::table('puja_bookings')
            ->leftJoin(
                'users as pandits',
                'pandits.id',
                '=',
                'puja_bookings.pandit_id'
            )
            ->leftJoin(
                'temples',
                'temples.id',
                '=',
                'puja_bookings.temple_id'
            )
            ->leftJoin(
                'pujas',
                'pujas.id',
                '=',
                'puja_bookings.puja_id'
            )
            ->where(
                'puja_bookings.temple_id',
                $templeId
            )
            ->whereNotNull(
                'puja_bookings.pandit_id'
            )
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('temple_user')
                    ->whereColumn(
                        'temple_user.user_id',
                        'puja_bookings.pandit_id'
                    )
                    ->whereColumn(
                        'temple_user.temple_id',
                        'puja_bookings.temple_id'
                    );
            })
            ->select(
                'puja_bookings.id',
                'puja_bookings.booking_number',
                'puja_bookings.user_id',
                'puja_bookings.temple_id',
                'puja_bookings.puja_id',
                'puja_bookings.pandit_id',
                'puja_bookings.customer_name',
                'puja_bookings.customer_mobile',
                'puja_bookings.schedule_date',
                'puja_bookings.schedule_time',
                'puja_bookings.total_amount',
                'puja_bookings.payment_status',
                'puja_bookings.booking_status',
                'puja_bookings.created_at',
                'pandits.name as pandit_name',
                'pandits.email as pandit_email',
                'temples.name as temple_name',
                'pujas.name as puja_name'
            )
            ->addSelect([
                'refund_status' => $refundStatusQuery,
                'refund_amount' => $refundAmountQuery,
            ]);
    }

    private function getPerformanceRows($bookingRows)
    {
        return $bookingRows
            ->groupBy('pandit_id')
            ->map(function ($bookings) {
                $first = $bookings->first();
                $total = $bookings->count();
                $confirmed = $bookings->where('booking_status','confirmed')->count();
                $completed = $bookings->where('booking_status','completed')->count();
                $cancelled = $bookings->where('booking_status','cancelled')->count();
                $pending = $bookings->filter(function ($booking) {
                    return !in_array(
                        strtolower($booking->booking_status ?? ''),
                        [
                            'confirmed',
                            'completed',
                            'cancelled',
                        ],
                        true
                    );
                })->count();
                $paidBookings = $bookings->filter(function ($booking) {
                    return strtolower(
                        $booking->payment_status ?? ''
                    ) === 'paid';

                });
                $refundBookings = $bookings->filter(function ($booking) {
                    return strtolower(
                        $booking->refund_status ?? ''
                    ) === 'processed';

                });
                return (object) [
                    'pandit_id' => $first->pandit_id,
                    'pandit_name' => $first->pandit_name ?? 'N/A',
                    'pandit_email' => $first->pandit_email ?? 'N/A',
                    'total_assignments' => $total,
                    'confirmed' => $confirmed,
                    'completed' => $completed,
                    'pending' => $pending,
                    'cancelled' => $cancelled,
                    'paid_bookings' => $paidBookings->count(),
                    'refund_bookings' => $refundBookings->count(),
                    'refund_amount' => $refundBookings->sum('refund_amount'),
                    'revenue' => $paidBookings->sum('total_amount'),
                    'completion_percentage' => $total > 0
                        ? round(($completed / $total) * 100, 2)
                        : 0,
                ];
            })
            ->sortBy('pandit_name')
            ->values();
    }

    private function calculateSummary($bookingRows)
    {
        $total = $bookingRows->count();
        $confirmed = $bookingRows->where('booking_status','confirmed')->count();
        $completed = $bookingRows->where('booking_status','completed')->count();
        $cancelled = $bookingRows->where('booking_status','cancelled')->count();
        $pending = $bookingRows->filter(function ($booking) {
            return !in_array(
                strtolower($booking->booking_status ?? ''),
                [
                    'confirmed',
                    'completed',
                    'cancelled',
                ],
                true
            );
        })->count();
        $paidBookings = $bookingRows->filter(function ($booking) {
            return strtolower(
                $booking->payment_status ?? ''
            ) === 'paid';
        });
        $refundBookings = $bookingRows->filter(function ($booking) {
            return strtolower(
                $booking->refund_status ?? ''
            ) === 'processed';
        });
        return [
           'total_assignments' => $total,
            'confirmed' => $confirmed,
            'completed' => $completed,
            'pending' => $pending,
            'cancelled' => $cancelled,
            'paid_bookings' => $paidBookings->count(),
            'refund_bookings' => $refundBookings->count(),
            'refund_amount' => $refundBookings->sum('refund_amount'),
            'revenue' => $paidBookings->sum('total_amount'),
            'completion_percentage' => $total > 0
                ? round(($completed / $total) * 100, 2)
                : 0,
        ];
    }

    private function emptySummary()
    {
        return [
            'total_assignments' => 0,
            'confirmed' => 0,
            'completed' => 0,
            'pending' => 0,
            'cancelled' => 0,
            'paid_bookings' => 0,
            'refund_bookings' => 0,
            'refund_amount' => 0,
            'revenue' => 0,
            'completion_percentage' => 0,
        ];
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getReportData($request);
        if (!$data['selectedTemple']) {
            return redirect()
                ->route('reports.pujari-assignment-performance.index')
                ->with(
                    'error',
                    'Please select a temple before exporting.'
                );
        }
        $pdf = Pdf::loadView('admin.reports.pujari-assignment-performance-pdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('pujari-assignment-performance-report.pdf');
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getReportData($request);
        if (!$data['selectedTemple']) {
            return redirect()
                ->route('reports.pujari-assignment-performance.index')
                ->with(
                    'error',
                    'Please select a temple before exporting.'
                );
        }
        return Excel::download( new PujariAssignmentPerformanceExport($data),'pujari-assignment-performance-report.xlsx');
    }
    public function exportCsv(Request $request)
    {
        $data = $this->getReportData($request);
        if (!$data['selectedTemple']) {
            return redirect()
                ->route('reports.pujari-assignment-performance.index')
                ->with(
                    'error',
                    'Please select a temple before exporting.'
                );
        }
        $fileName = 'pujari-assignment-performance-report.csv';
        return response()->streamDownload(function () use ($data) {
            $file = fopen('php://output', 'w');
            // UTF-8 BOM for Excel compatibility
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, [
                'PUJARI ASSIGNMENT & PERFORMANCE REPORT'
            ]);
            fputcsv($file, [
                'Temple',
                $data['selectedTemple']->name ?? 'N/A',
            ]);
            fputcsv($file, [
                'Pujari',
                $data['selectedPandit']->name ?? 'All Pujaris',
            ]);
            fputcsv($file, [
                'Status',
                $data['selectedStatus'],
            ]);
            fputcsv($file, [
                'From Date',
                $data['filters']['from_date'] ?? 'All Dates',
            ]);
            fputcsv($file, [
                'To Date',
                $data['filters']['to_date'] ?? 'All Dates',
            ]);
            fputcsv($file, []);
            // Summary
            fputcsv($file, [
                'Total Assignments',
                'Confirmed',
                'Completed',
                'Pending',
                'Cancelled',
                'Paid Bookings',
                'Refund Bookings',
                'Refund Amount',
                'Revenue',
                'Completion Percentage',
            ]);
            fputcsv($file, [
                $data['summary']['total_assignments'],
                $data['summary']['confirmed'],
                $data['summary']['completed'],
                $data['summary']['pending'],
                $data['summary']['cancelled'],
                $data['summary']['paid_bookings'],
                $data['summary']['refund_bookings'],
                $data['summary']['refund_amount'],
                $data['summary']['revenue'],
                $data['summary']['completion_percentage'] . '%',
            ]);
            fputcsv($file, []);
            // Pujari Performance
            fputcsv($file, [
                'PUJARI-WISE PERFORMANCE'
            ]);
            fputcsv($file, [
                'Pujari Name',
                'Email',
                'Total Assignments',
                'Confirmed',
                'Completed',
                'Pending',
                'Cancelled',
                'Paid Bookings',
                'Refund Bookings',
                'Refund Amount',
                'Revenue',
                'Completion Percentage',
            ]);
            foreach ($data['performanceRows'] as $row) {
                fputcsv($file, [
                    $row->pandit_name,
                    $row->pandit_email,
                    $row->total_assignments,
                    $row->confirmed,
                    $row->completed,
                    $row->pending,
                    $row->cancelled,
                    $row->paid_bookings,
                    $row->refund_bookings,
                    $row->refund_amount,
                    $row->revenue,
                    $row->completion_percentage . '%',
                ]);
            }
            fputcsv($file, []);
            // Booking Details
            fputcsv($file, [
                'PUJA ASSIGNMENT DETAILS'
            ]);
            fputcsv($file, [
                'Booking Number',
                'Temple',
                'Puja',
                'Pujari',
                'Customer Name',
                'Customer Mobile',
                'Schedule Date',
                'Schedule Time',
                'Amount',
                'Payment Status',
                'Booking Status',
                'Refund Status',
                'Refund Amount',
            ]);
            foreach ($data['bookingRows'] as $row) {
                fputcsv($file, [
                    $row->booking_number,
                    $row->temple_name,
                    $row->puja_name,
                    $row->pandit_name,
                    $row->customer_name,
                    $row->customer_mobile,
                    $row->schedule_date,
                    $row->schedule_time,
                    $row->total_amount,
                    $row->payment_status,
                    $row->booking_status,
                    $row->refund_status,
                    $row->refund_amount,
                ]);
            }
            fclose($file);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}