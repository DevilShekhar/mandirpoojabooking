<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PujaBooking;
use App\Models\Temple;
use App\Models\User;
use App\Exports\MandirPujaPanditReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class MandirPujaPanditReportController extends Controller
{
    /**
     * Display Mandir / Puja / Pandit report.
     */
    public function index(Request $request)
    {
        $request->validate([
            'temple_id' => 'nullable|integer|exists:temples,id',
            'pandit_id' => 'nullable|integer|exists:users,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $temples = Temple::orderBy('name')->get();

        $totalBookings = 0;
        $totalPanditFee = 0;

        // Empty paginator before selecting a temple.
        $bookings = new LengthAwarePaginator(
            [],
            0,
            20,
            1,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        if ($request->filled('temple_id')) {

            $this->validateSelectedPandit($request);

            $query = $this->getReportQuery($request);

            // Total count of all filtered completed bookings.
            $totalBookings = (clone $query)->count();

            // IMPORTANT: Fetch amount from base_amount column.
            $totalPanditFee = (clone $query)->sum('base_amount');

            // Paginated booking listing.
            $bookings = $query
                ->orderByDesc('completed_at')
                ->paginate(20)
                ->withQueryString();
        }

        return view(
            'admin.reports.mandir-puja-pandit',
            compact(
                'temples',
                'bookings',
                'totalBookings',
                'totalPanditFee'
            )
        );
    }

    /**
     * Get approved Pandits for selected temple.
     */
    public function getPandits(Request $request)
    {
        $request->validate([
            'temple_id' => 'required|integer|exists:temples,id',
        ]);

        $pandits = DB::table('temple_user as tu')
            ->join('users as u', 'u.id', '=', 'tu.user_id')
            ->join('model_has_roles as mhr', function ($join) {
                $join->on('mhr.model_id', '=', 'u.id')
                    ->where('mhr.model_type', User::class);
            })
            ->join('roles as r', 'r.id', '=', 'mhr.role_id')
            ->where('tu.temple_id', $request->temple_id)
            ->where('tu.approval_status', 'approved')
            ->where('r.name', 'Pandit')
            ->where('r.guard_name', 'web')
            ->select('u.id', 'u.name')
            ->distinct()
            ->orderBy('u.name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pandits,
        ]);
    }

    /**
     * Validate selected Pandit belongs to selected temple
     * and has approved Pandit role.
     */
    private function validateSelectedPandit(Request $request)
    {
        if (!$request->filled('pandit_id')) {
            return;
        }

        $exists = DB::table('temple_user as tu')
            ->join('model_has_roles as mhr', function ($join) {
                $join->on('mhr.model_id', '=', 'tu.user_id')
                    ->where('mhr.model_type', User::class);
            })
            ->join('roles as r', 'r.id', '=', 'mhr.role_id')
            ->where('tu.temple_id', $request->temple_id)
            ->where('tu.user_id', $request->pandit_id)
            ->where('tu.approval_status', 'approved')
            ->where('r.name', 'Pandit')
            ->where('r.guard_name', 'web')
            ->exists();

        if (!$exists) {
            abort(422, 'Selected pandit is not approved for this temple.');
        }
    }

    /**
     * Common report query.
     *
     * Used by:
     * - Report listing
     * - PDF export
     * - CSV export
     * - Excel export
     */
    private function getReportQuery(Request $request)
    {
        $query = PujaBooking::with([
            'user',
            'temple',
            'deity',
            'puja',
            'pandit',
            'pujaPackage',
            'completedBy',
        ])
            ->where('temple_id', $request->temple_id)
            ->where('booking_status', 'completed')
            ->whereNotNull('completed_at');

        // Pandit filter.
        if ($request->filled('pandit_id')) {
            $query->where('pandit_id', $request->pandit_id);
        }

        // Completed from date.
        if ($request->filled('from_date')) {
            $query->whereDate(
                'completed_at',
                '>=',
                $request->from_date
            );
        }

        // Completed to date.
        if ($request->filled('to_date')) {
            $query->whereDate(
                'completed_at',
                '<=',
                $request->to_date
            );
        }

        return $query;
    }

    /**
     * Validate export filters.
     */
    private function validateExportRequest(Request $request)
    {
        $request->validate([
            'temple_id' => 'required|integer|exists:temples,id',
            'pandit_id' => 'nullable|integer|exists:users,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $this->validateSelectedPandit($request);
    }

    /**
     * Export PDF.
     */
    public function exportPdf(Request $request)
    {
        $this->validateExportRequest($request);

        $bookings = $this->getReportQuery($request)
            ->orderByDesc('completed_at')
            ->get();

        $totalBookings = $bookings->count();

        // Total from base_amount.
        $totalPanditFee = $bookings->sum(function ($booking) {
            return (float) ($booking->base_amount ?? 0);
        });

        $pdf = Pdf::loadView(
            'admin.reports.mandir-puja-pandit-pdf',
            compact(
                'bookings',
                'totalBookings',
                'totalPanditFee'
            )
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'mandir-puja-pandit-report.pdf'
        );
    }

    /**
     * Export CSV.
     */
    public function exportCsv(Request $request)
    {
        $this->validateExportRequest($request);

        $bookings = $this->getReportQuery($request)
            ->orderByDesc('completed_at')
            ->get();

        $totalBookings = $bookings->count();

        // Total from base_amount.
        $totalPanditFee = $bookings->sum(function ($booking) {
            return (float) ($booking->base_amount ?? 0);
        });

        return response()->streamDownload(function () use (
            $bookings,
            $totalBookings,
            $totalPanditFee
        ) {

            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility.
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'Booking Number',
                'Customer Name',
                'Customer Email',
                'Customer Mobile',
                'Temple',
                'Deity',
                'Puja',
                'Pandit',
                'Package',
                'Schedule Date',
                'Schedule Time',
                'Completed Date',
                'Person Count',
                'Pandit Fee',
                'Completed By',
                'Booking Status',
            ]);

            foreach ($bookings as $booking) {

                fputcsv($file, [
                    $booking->booking_number ?? '',
                    $booking->customer_name ?? '',
                    $booking->customer_email ?? '',

                    // Preserve mobile number as text in Excel.
                    $booking->customer_mobile !== null
                        ? "\t" . (string) $booking->customer_mobile
                        : '',

                    $booking->temple->name ?? '',
                    $booking->deity->name ?? '',
                    $booking->puja->name ?? '',
                    $booking->pandit->name ?? '',

                    $booking->package_name
                        ?? ($booking->pujaPackage->name ?? ''),

                    $booking->schedule_date
                        ? $booking->schedule_date->format('d-m-Y')
                        : '',

                    $booking->schedule_time ?? '',

                    $booking->completed_at
                        ? $booking->completed_at->format('d-m-Y H:i')
                        : '',

                    $booking->person_count ?? 0,

                    // IMPORTANT: base_amount.
                    (float) ($booking->base_amount ?? 0),

                    $booking->completedBy->name ?? '',
                    $booking->booking_status ?? '',
                ]);
            }

            // Summary rows.
            fputcsv($file, []);

            fputcsv($file, [
                'Total Bookings',
                $totalBookings,
            ]);

            fputcsv($file, [
                'Total Pandit Fee',
                number_format($totalPanditFee, 2, '.', ''),
            ]);

            fclose($file);

        }, 'mandir-puja-pandit-report.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Export Excel.
     */
    public function exportExcel(Request $request)
    {
        $this->validateExportRequest($request);

        $bookings = $this->getReportQuery($request)
            ->orderByDesc('completed_at')
            ->get();

        return Excel::download(
            new MandirPujaPanditReportExport($bookings),
            'mandir-puja-pandit-report.xlsx'
        );
    }
}