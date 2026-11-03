<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PujaBooking;
use App\Models\Temple;
use App\Models\User;
use App\Exports\StoreManagerCompletedReportExport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class StoreManagerCompletedReportController extends Controller
{
    private function applyTempleAccess(Builder $query): Builder
    {
        $user = Auth::user();
        if ($user->hasRole(['Admin', 'Super Admin'])) {
            return $query;
        }
        if ($user->hasRole('Store Manager')) {
            if (!$user->temple_id) {
                abort(403, 'No temple is assigned to your account.');
            }
            return $query->where('temple_id', $user->temple_id);
        }
        abort(403, 'You are not authorized to access this report.');
    }

    private function getAccessibleTemples()
    {
        $user = Auth::user();
        if ($user->hasRole(['Admin', 'Super Admin'])) {
            return Temple::orderBy('name')->get(['id', 'name']);
        }
        if ($user->hasRole('Store Manager')) {
            if (!$user->temple_id) {
                abort(403, 'No temple is assigned to your account.');
            }
            return Temple::where('id', $user->temple_id)->get(['id', 'name']);
        }
        abort(403, 'You are not authorized to access this report.');
    }

    private function validateTempleAccess($templeId): Temple
    {
        $user = Auth::user();
        $query = Temple::query()->whereKey($templeId);
        if ($user->hasRole(['Admin', 'Super Admin'])) {
            return $query->firstOrFail();
        }
        if ($user->hasRole('Store Manager')) {
            if (!$user->temple_id || (int) $user->temple_id !== (int) $templeId) {
                abort(403, 'You are not authorized to access this temple.');
            }
            return $query->where('id', $user->temple_id)->firstOrFail();
        }
        abort(403, 'You are not authorized to access this report.');
    }
    public function index(Request $request)
    {
        $request->validate([
            'temple_id' => ['nullable', 'integer', 'exists:temples,id'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);
        $user = Auth::user();
        $temples = $this->getAccessibleTemples();
        $selectedTemple = null;
        $storeManager = null;
        $bookings = collect();
        $summary = [
            'total_bookings' => 0,
            'total_quantity' => 0,
            'total_amount' => 0,
        ];
        $reportGenerated = false;
        if ($request->filled('temple_id')) {
            $selectedTemple = $this->validateTempleAccess(
                $request->temple_id
            );
            $storeManager = $this->findStoreManager(
                $selectedTemple->id
            );
            if ($request->boolean('generate_report')) {
                $bookings = $this->getReportQuery(
                    $selectedTemple->id,
                    $request->from_date,
                    $request->to_date
                )->latest('samgri_otp_verified_at')->get();
                foreach ($bookings as $booking) {
                    $booking->report_items = $this->getProductItems(
                        $booking->selected_products
                    );
                    $booking->report_temple_name = $selectedTemple->name;
                    $booking->report_store_manager = $storeManager?->name;
                }
                $summary = $this->getSummary($bookings);
                $reportGenerated = true;
            }
        }
        $isStoreManager = $user->hasRole('Store Manager');
        return view(
            'admin.reports.store-manager-completed',
            compact(
                'temples',
                'selectedTemple',
                'storeManager',
                'bookings',
                'summary',
                'reportGenerated',
                'isStoreManager'
            )
        );
    }
    public function getStoreManager($templeId)
    {
        $temple = $this->validateTempleAccess($templeId);
        $manager = $this->findStoreManager($temple->id);
        if (!$manager) {
            return response()->json([
                'success' => false,
                'message' => 'No Store Manager is assigned to this temple.',
                'manager' => null,
            ]);
        }
        return response()->json([
            'success' => true,
            'manager' => [
                'id' => $manager->id,
                'name' => $manager->name,
                'email' => $manager->email,
                'phone' => $manager->phone,
            ],
        ]);
    }
    private function findStoreManager($templeId)
    {
        return User::query()
            ->join('model_has_roles as mhr', function ($join) {
                $join->on('mhr.model_id', '=', 'users.id')
                    ->where('mhr.model_type', User::class);
            })
            ->join('roles as r', function ($join) {
                $join->on('r.id', '=', 'mhr.role_id')
                    ->where('r.name', '=', 'Store Manager')
                    ->where('r.guard_name', '=', 'web');
            })
            ->where('users.temple_id', $templeId)
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.phone',
                'users.temple_id'
            )
            ->first();
    }
    private function getReportQuery($templeId,$fromDate = null,$toDate = null): Builder {
        $query = PujaBooking::query()
            ->whereNotNull('selected_products')
            ->whereJsonLength('selected_products', '>', 0)
            ->where('samgri_otp_verified', 1);
        $query = $this->applyTempleAccess($query);
        $query->where('temple_id', $templeId);
        if ($fromDate) {
            $query->whereDate(
                'samgri_otp_verified_at',
                '>=',
                $fromDate
            );
        }
        if ($toDate) {
            $query->whereDate(
                'samgri_otp_verified_at',
                '<=',
                $toDate
            );
        }
        return $query;
    }
    private function getProductItems($products): array
    {
        if (is_string($products)) {
            $products = json_decode($products, true);
        }
        if (!is_array($products)) {
            return [];
        }
        $items = [];
        foreach ($products as $product) {
            if (!is_array($product)) {
                continue;
            }
            $quantity = (float) (
                $product['quantity']
                ?? $product['qty']
                ?? $product['count']
                ?? 1
            );
            $price = (float) (
                $product['price']
                ?? $product['unit_price']
                ?? 0
            );
            $total = (float) (
                $product['total']
                ?? $product['total_price']
                ?? ($price * $quantity)
            );
            $items[] = [
                'name' => $product['name']
                    ?? $product['product_name']
                    ?? 'N/A',
                'unit' => $product['unit'] ?? '-',
                'quantity' => $quantity,
                'price' => $price,
                'total' => $total,
            ];
        }
        return $items;
    }
    private function getSummary($bookings): array
    {
        $totalQuantity = 0;
        $totalAmount = 0;
        foreach ($bookings as $booking) {
            foreach ($booking->report_items ?? [] as $item) {
                $totalQuantity += $item['quantity'];
                $totalAmount += $item['total'];
            }
        }
        return [
            'total_bookings' => $bookings->count(),
            'total_quantity' => $totalQuantity,
            'total_amount' => $totalAmount,
        ];
    }
    private function getExportBookings(Request $request): array
    {
        $request->validate([
            'temple_id' => ['required', 'integer', 'exists:temples,id'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);
        $temple = $this->validateTempleAccess($request->temple_id);
        $storeManager = $this->findStoreManager($temple->id);
        $bookings = $this->getReportQuery(
            $temple->id,
            $request->from_date,
            $request->to_date
        )->latest('samgri_otp_verified_at')->get();
        foreach ($bookings as $booking) {
            $booking->report_items = $this->getProductItems(
                $booking->selected_products
            );
            $booking->report_temple_name = $temple->name;
            $booking->report_store_manager = $storeManager?->name;
        }
        return [
            $bookings,
            $temple,
            $storeManager,
            $this->getSummary($bookings),
        ];
    }
    public function exportPdf(Request $request)
    {
        [
            $bookings,
            $temple,
            $storeManager,
            $summary
        ] = $this->getExportBookings($request);
        $pdf = Pdf::loadView(
            'admin.reports.store-manager-completed-pdf',
            compact(
                'bookings',
                'temple',
                'storeManager',
                'summary'
            )
        )->setPaper('a4', 'landscape');
        return $pdf->download(
            'store-manager-completed-report.pdf'
        );
    }
    public function exportCsv(Request $request)
    {
        [
            $bookings,
            $temple,
            $storeManager,
            $summary
        ] = $this->getExportBookings($request);
        $fileName = 'store-manager-completed-report.csv';
        $callback = function () use (
            $bookings,
            $temple,
            $storeManager,
            $summary
        ) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Booking Number',
                'Customer Name',
                'Temple',
                'Store Manager',
                'Item Name',
                'Unit',
                'Quantity',
                'Item Price',
                'Item Total',
                'Completed Date',
                'Verified Date',
            ]);
            foreach ($bookings as $booking) {
                foreach ($booking->report_items as $item) {
                    fputcsv($file, [
                        $booking->booking_number,
                        $booking->customer_name,
                        $temple->name,
                        $storeManager?->name ?? 'Not Assigned',
                        $item['name'],
                        $item['unit'],
                        $item['quantity'],
                        $item['price'],
                        $item['total'],
                        $booking->completed_at,
                        $booking->samgri_otp_verified_at,
                    ]);
                }
            }
            fputcsv($file, []);
            fputcsv($file, [
                'Total Bookings',
                $summary['total_bookings'],
            ]);
            fputcsv($file, [
                'Total Item Quantity',
                $summary['total_quantity'],
            ]);
            fputcsv($file, [
                'Total Item Amount',
                $summary['total_amount'],
            ]);
            fclose($file);
        };
        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' =>
                'attachment; filename="' . $fileName . '"',
        ]);
    }
    public function exportExcel(Request $request)
    {
        [
            $bookings,
            $temple,
            $storeManager,
            $summary
        ] = $this->getExportBookings($request);
        return Excel::download(
            new StoreManagerCompletedReportExport(
                $bookings,
                $summary
            ),
            'store-manager-completed-report.xlsx'
        );
    }
}