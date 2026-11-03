<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Exports\EventBookingCompletedReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class EventBookingCompletedReportController extends Controller
{
    /**
     * Display completed event booking report.
     */
    public function index(Request $request)
    {
        $request->validate([
            'event_creator_id' => 'nullable|integer|exists:users,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        // Get Event Creator users from Spatie role tables.
        $eventCreators = $this->getEventCreators();

        $bookings = collect();
        $totalBookings = 0;
        $totalAmount = 0;

        if ($request->filled('event_creator_id')) {

            // Verify selected user has Event Creator role.
            $this->validateEventCreator($request);

            $query = $this->getReportQuery($request);

            $totalBookings = (clone $query)->count('seo.id');

            $totalAmount = (clone $query)->sum('seo.amount');

            $bookings = $query
                ->orderByDesc('seo.completed_at')
                ->paginate(20)
                ->withQueryString();
        }

        return view(
            'admin.reports.event-booking-completed',
            compact(
                'eventCreators',
                'bookings',
                'totalBookings',
                'totalAmount'
            )
        );
    }

    /**
     * Get Event Creator users.
     */
    private function getEventCreators()
    {
        return DB::table('users as u')
            ->join('model_has_roles as mhr', function ($join) {
                $join->on('mhr.model_id', '=', 'u.id')
                    ->where('mhr.model_type', User::class);
            })
            ->join('roles as r', 'r.id', '=', 'mhr.role_id')
            ->where('r.name', 'Event Creator')
            ->where('r.guard_name', 'web')
            ->select([
                'u.id',
                'u.name',
            ])
            ->distinct()
            ->orderBy('u.name')
            ->get();
    }

    /**
     * Validate selected Event Creator.
     */
    private function validateEventCreator(Request $request)
    {
        $exists = DB::table('users as u')
            ->join('model_has_roles as mhr', function ($join) {
                $join->on('mhr.model_id', '=', 'u.id')
                    ->where('mhr.model_type', User::class);
            })
            ->join('roles as r', 'r.id', '=', 'mhr.role_id')
            ->where('u.id', $request->event_creator_id)
            ->where('r.name', 'Event Creator')
            ->where('r.guard_name', 'web')
            ->exists();

        if (!$exists) {
            abort(422, 'Selected user is not an Event Creator.');
        }
    }

    /**
     * Common query for completed event bookings.
     */
    private function getReportQuery(Request $request)
    {
        $query = DB::table('sanatan_event_orders as seo')

            ->join('sanatans as s', 's.id', '=', 'seo.sanatan_id')

            ->leftJoin('users as customer', 'customer.id', '=', 'seo.user_id')

            ->leftJoin('users as event_creator', 'event_creator.id', '=', 's.created_by')

            ->leftJoin('users as completed_user', 'completed_user.id', '=', 'seo.completed_by')

            ->where('seo.booking_status', 'completed')

            ->whereNotNull('seo.completed_at')

            ->where('s.created_by', $request->event_creator_id)

            ->select([
                'seo.id',
                'seo.sanatan_id',
                'seo.user_id',
                'seo.event_date',
                'seo.event_time',
                'seo.amount',
                'seo.payment_status',
                'seo.booking_status',
                'seo.completed_at',

                'customer.name as customer_name',
                'event_creator.name as event_creator_name',
                'completed_user.name as completed_by_name',

                's.name as event_name',
            ]);

        // Completed date: From Date.
        if ($request->filled('from_date')) {
            $query->whereDate(
                'seo.completed_at',
                '>=',
                $request->from_date
            );
        }

        // Completed date: To Date.
        if ($request->filled('to_date')) {
            $query->whereDate(
                'seo.completed_at',
                '<=',
                $request->to_date
            );
        }

        return $query;
    }

    /**
     * Validate export filters and Event Creator.
     */
    private function validateExportRequest(Request $request)
    {
        $request->validate([
            'event_creator_id' => 'required|integer|exists:users,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $this->validateEventCreator($request);
    }

    /**
     * Export completed bookings to PDF.
     */
    public function exportPdf(Request $request)
    {
        $this->validateExportRequest($request);

        $bookings = $this->getReportQuery($request)
            ->orderByDesc('seo.completed_at')
            ->get();

        $totalBookings = $bookings->count();

        $totalAmount = $bookings->sum(function ($booking) {
            return (float) ($booking->amount ?? 0);
        });

        $eventCreator = User::find($request->event_creator_id);

        $pdf = Pdf::loadView(
            'admin.reports.event-booking-completed-pdf',
            compact(
                'bookings',
                'totalBookings',
                'totalAmount',
                'eventCreator'
            )
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'event-booking-completed-report.pdf'
        );
    }

    /**
     * Export completed bookings to CSV.
     */
    public function exportCsv(Request $request)
    {
        $this->validateExportRequest($request);

        $bookings = $this->getReportQuery($request)
            ->orderByDesc('seo.completed_at')
            ->get();

        $totalAmount = $bookings->sum(function ($booking) {
            return (float) ($booking->amount ?? 0);
        });

        return response()->streamDownload(
            function () use ($bookings, $totalAmount) {

                $file = fopen('php://output', 'w');

                // UTF-8 BOM for Excel compatibility.
                fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

                fputcsv($file, [
                    'Booking ID',
                    'Event Name',
                    'Customer Name',
                    'Event Creator',
                    'Event Date',
                    'Event Time',
                    'Amount',
                    'Payment Status',
                    'Booking Status',
                    'Completed Date',
                    'Completed By',
                ]);

                foreach ($bookings as $booking) {

                    fputcsv($file, [
                        $booking->id,
                        $booking->event_name ?? '',
                        $booking->customer_name ?? '',
                        $booking->event_creator_name ?? '',
                        $booking->event_date ?? '',
                        $booking->event_time ?? '',
                        number_format(
                            (float) ($booking->amount ?? 0),
                            2,
                            '.',
                            ''
                        ),
                        $booking->payment_status ?? '',
                        $booking->booking_status ?? '',
                        $booking->completed_at ?? '',
                        $booking->completed_by_name ?? '',
                    ]);
                }

                fputcsv($file, []);

                fputcsv($file, [
                    'Total Completed Bookings',
                    $bookings->count(),
                ]);

                fputcsv($file, [
                    'Total Amount',
                    number_format($totalAmount, 2, '.', ''),
                ]);

                fclose($file);

            },
            'event-booking-completed-report.csv',
            [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]
        );
    }

    /**
     * Export completed bookings to Excel.
     */
    public function exportExcel(Request $request)
    {
        $this->validateExportRequest($request);

        $bookings = $this->getReportQuery($request)
            ->orderByDesc('seo.completed_at')
            ->get();

        return Excel::download(
            new EventBookingCompletedReportExport($bookings),
            'event-booking-completed-report.xlsx'
        );
    }
}
