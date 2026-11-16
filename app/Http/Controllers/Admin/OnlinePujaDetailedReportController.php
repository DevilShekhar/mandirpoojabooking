<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnlinePujaOrder;
use App\Models\OnlinePuja;
use App\Models\OnlinePujaCategory;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OnlinePujaDetailedExport;
use Barryvdh\DomPDF\Facade\Pdf;

class OnlinePujaDetailedReportController extends Controller
{
    public function index(Request $request)
    {
        $query = OnlinePujaOrder::with(['customer', 'puja', 'package']);

        // Filter by Date Range (Only if both dates are provided)
        if (!empty($request->from_date) && !empty($request->to_date)) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00', 
                $request->to_date . ' 23:59:59'
            ]);
        }

        // Filter by Online Puja
        if ($request->filled('online_puja_id')) {
            $query->where('online_puja_id', $request->online_puja_id);
        }

        // Filter by Category
        if ($request->filled('online_puja_cat_id')) {
            $query->where('online_puja_cat_id', $request->online_puja_cat_id);
        }

        // Filter by Booking Status
        if ($request->filled('booking_status')) {
            $query->where('booking_status', $request->booking_status);
        }

        // Filter by Puja's status using relationship (since 'status' belongs to online_pujas table)
        if ($request->filled('status')) {
            $status = $request->status;
            $query->whereHas('puja', function($q) use ($status) {
                if ($status === '2' || strtolower($status) === 'completed') {
                    $q->where(function($sub) {
                        $sub->where('status', 2)
                          ->orWhere('status', 'completed');
                    });
                } else {
                    $q->where('status', $status);
                }
            });
        }

        // Calculate summary metrics before pagination
        $totalRevenue = (clone $query)->sum('amount');
        $totalBookings = (clone $query)->count();

        // Paginate results preserving filters in pagination links
        $reports = $query->latest()->paginate(15)->withQueryString();
        
        $onlinePujas = OnlinePuja::all();
        $categories = OnlinePujaCategory::all();

        return view('admin.reports.online-puja-detailed', compact(
            'reports', 
            'onlinePujas', 
            'categories', 
            'totalRevenue', 
            'totalBookings'
        ));
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getFilteredData($request);
        
        $pdf = Pdf::loadView('admin.reports.online-puja-detailed-pdf', [
            'reports' => $data['reports'],
            'totalRevenue' => $data['totalRevenue'],
            'totalBookings' => $data['totalBookings'],
            'filters' => $request->all()
        ]);

        return $pdf->download('online-puja-detailed-report.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new OnlinePujaDetailedExport($request->all()), 'online-puja-detailed-report.xlsx');
    }

    public function exportCsv(Request $request)
    {
        return Excel::download(new OnlinePujaDetailedExport($request->all()), 'online-puja-detailed-report.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    private function getFilteredData(Request $request)
    {
        $query = OnlinePujaOrder::with(['customer', 'puja', 'package']);

        if (!empty($request->from_date) && !empty($request->to_date)) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00', 
                $request->to_date . ' 23:59:59'
            ]);
        }
        if ($request->filled('online_puja_id')) {
            $query->where('online_puja_id', $request->online_puja_id);
        }
        if ($request->filled('online_puja_cat_id')) {
            $query->where('online_puja_cat_id', $request->online_puja_cat_id);
        }
        if ($request->filled('booking_status')) {
            $query->where('booking_status', $request->booking_status);
        }
        if ($request->filled('status')) {
            $status = $request->status;
            $query->whereHas('puja', function($q) use ($status) {
                if ($status === '2' || strtolower($status) === 'completed') {
                    $q->where(function($sub) {
                        $sub->where('status', 2)
                          ->orWhere('status', 'completed');
                    });
                } else {
                    $q->where('status', $status);
                }
            });
        }

        return [
            'reports' => $query->latest()->get(),
            'totalRevenue' => (clone $query)->sum('amount'),
            'totalBookings' => (clone $query)->count(),
        ];
    }
}