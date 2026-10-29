<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PujaBooking;
use App\Models\User;
use App\Models\PujaBookingPayment;
use App\Models\PujaAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PujaBookingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = PujaBooking::with([
            'user',
            'temple',
            'deity',
            'puja',
            'pujaPackage',
            'pandit',
            'createdBy',
            'updatedBy',
        ]);

        if ($user->hasRole('Temple Manager')) {
            $query->whereIn(
                'temple_id',
                $this->getManagedTempleIds($user)
            );
        } elseif ($user->hasRole('Pandit')) {
            $query->where('pandit_id', $user->id);
        } elseif (!$user->hasAnyRole(['Admin', 'Super Admin'])) {
            $query->where('user_id', $user->id);
        }

        $bookings = $query->latest()->paginate(10);

        return view('admin.puja-bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $user = auth()->user();

        $pujaBooking = PujaBooking::query()
            ->where(function ($query) use ($id) {
                $query->where('id', $id)
                    ->orWhere('booking_number', $id);
            })
            ->firstOrFail();

        if (!$this->canViewBooking($user, $pujaBooking)) {
            abort(403, 'You are not authorized to view this booking.');
        }

        $pujaBooking->load([
            'user',
            'temple',
            'deity',
            'puja',
            'pujaPackage',
            'pandit',
            'createdBy',
            'updatedBy',
        ]);

        $selectedProducts = $this->normalizeSelectedProducts(
            $pujaBooking->selected_products
        );
        $paymentTable = (new PujaBookingPayment())->getTable();
        $bookingPayments = collect();

        if (Schema::hasTable($paymentTable)) {
            $paymentForeignKey = $this->getBookingForeignKey($paymentTable);

            if ($paymentForeignKey) {
                $paymentQuery = PujaBookingPayment::where(
                    $paymentForeignKey,
                    $pujaBooking->id
                );
                $bookingPayments = (clone $paymentQuery)
                    ->latest()
                    ->get();
                if ($selectedProducts->isEmpty()) {
                    $paymentProducts = (clone $paymentQuery)
                        ->whereNotNull('selected_products')
                        ->latest()
                        ->value('selected_products');
                    $selectedProducts = $this->normalizeSelectedProducts(
                        $paymentProducts
                    );
                }
            }
        }

        $assignments = collect();
        $assignmentTable = (new PujaAssignment())->getTable();

        if (Schema::hasTable($assignmentTable)) {
            $assignmentForeignKey = $this->getBookingForeignKey(
                $assignmentTable
            );

            if ($assignmentForeignKey) {
                $assignments = PujaAssignment::where(
                    $assignmentForeignKey,
                    $pujaBooking->id
                )->latest()->get();
            }
        }

        return view('admin.booking-puja-order.show', compact(
            'pujaBooking',
            'bookingPayments',
            'assignments',
            'selectedProducts'
        ));
    }

    public function successful()
    {
        $user = auth()->user();

        $query = PujaBooking::with([
            'user',
            'temple',
            'deity',
            'puja',
            'pujaPackage',
            'pandit',
            'createdBy',
            'updatedBy',
            'payments',
        ])->where('payment_status', 'paid');

        if ($user->hasRole('Temple Manager')) {
            $query->whereIn(
                'temple_id',
                $this->getManagedTempleIds($user)
            );
        } elseif ($user->hasRole('Pandit')) {
            $query->where('pandit_id', $user->id);
        } elseif (!$user->hasAnyRole(['Admin', 'Super Admin'])) {
            $query->where('user_id', $user->id);
        }

        $bookings = $query->latest()->paginate(10);

        return view(
            'admin.booking-puja-order.successful',
            compact('bookings')
        );
    }

    public function failed()
    {
        $user = auth()->user();

        $query = PujaBookingPayment::with([
            'user',
            'puja',
            'package',
            'booking',
        ])->where('status', 'failed');

        if (!$user->hasAnyRole(['Admin', 'Super Admin'])) {
            $query->where('user_id', $user->id);
        }

        $payments = $query->latest()->paginate(10);

        return view(
            'admin.booking-puja-order.failed',
            compact('payments')
        );
    }

    public function cancelled()
    {
        $user = auth()->user();

        $query = PujaBooking::with([
            'user',
            'temple',
            'deity',
            'puja',
            'pujaPackage',
            'pandit',
            'createdBy',
            'updatedBy',
        ])->where('booking_status', 'cancelled');

        if ($user->hasRole('Temple Manager')) {
            $query->whereIn(
                'temple_id',
                $this->getManagedTempleIds($user)
            );
        } elseif ($user->hasRole('Pandit')) {
            $query->where('pandit_id', $user->id);
        } elseif (!$user->hasAnyRole(['Admin', 'Super Admin'])) {
            $query->where('user_id', $user->id);
        }

        $bookings = $query->latest()->paginate(10);

        return view(
            'admin.booking-puja-order.cancelled',
            compact('bookings')
        );
    }

    public function destroy(PujaBooking $pujaBooking)
    {
        $user = auth()->user();

        if (!$this->canViewBooking($user, $pujaBooking)) {
            abort(403, 'You are not authorized to cancel this booking.');
        }

        if ($pujaBooking->booking_status === 'cancelled') {
            return redirect()
                ->route('booking-puja-order.index')
                ->with('info', 'This booking has already been cancelled.');
        }

        $pujaBooking->update([
            'booking_status' => 'cancelled',
            'updated_by' => $user->id,
        ]);

        return redirect()
            ->route('booking-puja-order.index')
            ->with('success', 'Puja booking cancelled successfully.');
    }

    private function normalizeSelectedProducts($products)
    {
        if (is_string($products)) {
            $decoded = json_decode($products, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return collect();
            }

            $products = $decoded;
        }

        if (is_object($products)) {
            $products = (array) $products;
        }

        if (!is_array($products) || empty($products)) {
            return collect();
        }

        // Support {"products": [...]} and {"items": [...]}.
        if (isset($products['products']) && is_array($products['products'])) {
            $products = $products['products'];
        } elseif (isset($products['items']) && is_array($products['items'])) {
            $products = $products['items'];
        }

        // Support a single product object stored instead of a list.
        if (
            isset($products['name']) ||
            isset($products['product_name']) ||
            isset($products['product_id'])
        ) {
            $products = [$products];
        }

        return collect($products)
            ->map(function ($product) {
                if (is_object($product)) {
                    return (array) $product;
                }

                return $product;
            })
            ->filter(function ($product) {
                return is_array($product) || is_object($product);
            })
            ->values();
    }
    private function canViewBooking(
        User $user,
        PujaBooking $pujaBooking
    ): bool {
        if ($user->hasAnyRole(['Admin', 'Super Admin'])) {
            return true;
        }

        if ((int) $pujaBooking->user_id === (int) $user->id) {
            return true;
        }

        if (
            (int) $pujaBooking->created_by === (int) $user->id ||
            (int) $pujaBooking->updated_by === (int) $user->id
        ) {
            return true;
        }

        if ($user->hasRole('Pandit')) {
            return (int) $pujaBooking->pandit_id === (int) $user->id;
        }

        if ($user->hasRole('Temple Manager')) {
            return $this->getManagedTempleIds($user)
                ->contains((int) $pujaBooking->temple_id);
        }

        return false;
    }

    private function getManagedTempleIds(User $user)
    {
        $panditIds = User::where('created_by', $user->id)
            ->where('role_id', 5)
            ->pluck('id');

        if ($panditIds->isEmpty() || !Schema::hasTable('temple_user')) {
            return collect();
        }

        $query = DB::table('temple_user')
            ->whereIn('user_id', $panditIds);

        if (Schema::hasColumn('temple_user', 'approval_status')) {
            $query->where('approval_status', 'approved');
        }

        return $query->pluck('temple_id')
            ->unique()
            ->values();
    }

    private function getBookingForeignKey(string $table): ?string
    {
        if (Schema::hasColumn($table, 'puja_booking_id')) {
            return 'puja_booking_id';
        }

        if (Schema::hasColumn($table, 'booking_id')) {
            return 'booking_id';
        }

        return null;
    }
}