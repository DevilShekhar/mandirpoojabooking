<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class TempleRegistrationController extends Controller
{
    /**
     * Display all temple registrations.
     */
    public function index(Request $request)
    {
        $query = DB::table('temple_user')
            ->join('users', 'users.id', '=', 'temple_user.user_id')
            ->join('temples', 'temples.id', '=', 'temple_user.temple_id')
            ->leftJoin('countries', 'countries.id', '=', 'temples.country_id')
            ->leftJoin('states', 'states.id', '=', 'temples.state_id')
            ->leftJoin('districts', 'districts.id', '=', 'temples.district_id')
            ->leftJoin('cities', 'cities.id', '=', 'temples.city_id')
            ->select(
                'temple_user.id as registration_id',
                'temple_user.approval_status',
                'temple_user.created_at as registration_date',
                'users.id as user_id',
                'users.name as manager_name',
                'users.email',
                'users.phone',
                'users.status as user_status',
                'temples.id as temple_id',
                'temples.name as temple_name',
                'temples.address',
                'temples.status as temple_status',
                'countries.name as country_name',
                'states.name as state_name',
                'districts.name as district_name',
                'cities.name as city_name'
            );

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%")
                    ->orWhere('users.phone', 'like', "%{$search}%")
                    ->orWhere('temples.name', 'like', "%{$search}%");
            });
        }

        if (in_array($request->status, ['pending', 'approved', 'rejected'], true)) {
            $query->where(
                'temple_user.approval_status',
                $request->status
            );
        }

        $registrations = $query
            ->orderByDesc('temple_user.id')
            ->paginate(10)
            ->withQueryString();

        $counts = [
            'total' => DB::table('temple_user')->count(),
            'pending' => DB::table('temple_user')
                ->where('approval_status', 'pending')->count(),
            'approved' => DB::table('temple_user')
                ->where('approval_status', 'approved')->count(),
            'rejected' => DB::table('temple_user')
                ->where('approval_status', 'rejected')->count(),
        ];

        return view(
            'admin.temple-registrations.index',
            compact('registrations', 'counts')
        );
    }

    /**
     * Approve a registration.
     */
    public function approve($registrationId)
    {
        try {
            DB::transaction(function () use ($registrationId) {
                $registration = DB::table('temple_user')
                    ->where('id', $registrationId)
                    ->lockForUpdate()
                    ->first();

                if (!$registration) {
                    abort(404, 'Registration not found.');
                }

                if ($registration->approval_status === 'approved') {
                    return;
                }

                if ($registration->approval_status === 'rejected') {
                    throw new \RuntimeException(
                        'Rejected registrations cannot be approved directly.'
                    );
                }

                DB::table('users')
                    ->where('id', $registration->user_id)
                    ->update([
                        'status' => 1,
                        'updated_at' => now(),
                    ]);

                DB::table('temples')
                    ->where('id', $registration->temple_id)
                    ->update([
                        'status' => 1,
                        'is_verified' => 1,
                        'updated_at' => now(),
                    ]);

                DB::table('temple_user')
                    ->where('id', $registrationId)
                    ->update([
                        'approval_status' => 'approved',
                        'updated_at' => now(),
                    ]);
            });

            return redirect()
                ->route('admin.temple-registrations.index')
                ->with('success', 'Temple registration approved successfully.');

        } catch (Throwable $e) {
            Log::error('Temple approval failed', [
                'registration_id' => $registrationId,
                'error' => $e->getMessage(),
            ]);

            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                throw $e;
            }

            return back()->with(
                'error',
                'Unable to approve registration. Please check the Laravel log.'
            );
        }
    }

    /**
     * Reject a registration.
     */
    public function reject($registrationId)
    {
        $registration = DB::table('temple_user')
            ->where('id', $registrationId)
            ->first();

        if (!$registration) {
            abort(404, 'Registration not found.');
        }

        if ($registration->approval_status === 'approved') {
            return back()->with(
                'error',
                'This registration is already approved.'
            );
        }

        DB::table('temple_user')
            ->where('id', $registrationId)
            ->update([
                'approval_status' => 'rejected',
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.temple-registrations.index')
            ->with('success', 'Temple registration rejected successfully.');
    }
}