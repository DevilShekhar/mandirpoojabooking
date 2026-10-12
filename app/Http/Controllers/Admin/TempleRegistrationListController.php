<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class TempleRegistrationListController extends Controller
{
    /**
     * Display all temple registrations.
     */
    public function index(Request $request)
    {
        $query = DB::table('temple_user as tu')
            ->join('users as u', 'u.id', '=', 'tu.user_id')
            ->join('temples as t', 't.id', '=', 'tu.temple_id')
            ->leftJoin('countries as co', 'co.id', '=', 't.country_id')
            ->leftJoin('states as s', 's.id', '=', 't.state_id')
            ->leftJoin('districts as d', 'd.id', '=', 't.district_id')
            ->leftJoin('cities as c', 'c.id', '=', 't.city_id')
            ->select(
                'tu.id as registration_id',
                'tu.approval_status',
                'tu.created_at as registration_date',
                'u.id as user_id',
                'u.name as manager_name',
                'u.email',
                'u.phone',
                'u.status as user_status',
                't.id as temple_id',
                't.name as temple_name',
                't.temple_code',
                't.address',
                't.status as temple_status',
                't.is_verified',
                'co.name as country_name',
                's.name as state_name',
                'd.name as district_name',
                'c.name as city_name'
            );

        // Search registrations.
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('u.name', 'like', "%{$search}%")
                    ->orWhere('u.email', 'like', "%{$search}%")
                    ->orWhere('u.phone', 'like', "%{$search}%")
                    ->orWhere('t.name', 'like', "%{$search}%")
                    ->orWhere('t.temple_code', 'like', "%{$search}%");
            });
        }

        // Filter by registration status.
        if ($request->filled('status') &&
            in_array($request->status, ['pending', 'approved', 'rejected'], true)) {
            $query->where('tu.approval_status', $request->status);
        }

        $registrations = $query
            ->orderByDesc('tu.created_at')
            ->paginate(15)
            ->withQueryString();

        // Registration counts.
        $totalCount = DB::table('temple_user')->count();

        $pendingCount = DB::table('temple_user')
            ->where('approval_status', 'pending')
            ->count();

        $approvedCount = DB::table('temple_user')
            ->where('approval_status', 'approved')
            ->count();

        $rejectedCount = DB::table('temple_user')
            ->where('approval_status', 'rejected')
            ->count();

        return view('admin.temple-registrations.index', compact(
            'registrations',
            'totalCount',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Approve a temple registration.
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
                    abort(404, 'Temple registration not found.');
                }

                if ($registration->approval_status === 'approved') {
                    return;
                }

                if ($registration->approval_status === 'rejected') {
                    abort(422, 'A rejected registration cannot be approved directly.');
                }

                DB::table('temple_user')
                    ->where('id', $registrationId)
                    ->update([
                        'approval_status' => 'approved',
                        'updated_at' => now(),
                    ]);

                // Activate the temple manager account.
                DB::table('users')
                    ->where('id', $registration->user_id)
                    ->update([
                        'status' => 1,
                        'updated_at' => now(),
                    ]);

                // Activate and verify the temple.
                DB::table('temples')
                    ->where('id', $registration->temple_id)
                    ->update([
                        'status' => 1,
                        'is_verified' => 1,
                        'updated_at' => now(),
                    ]);
            });

            return redirect()
                ->route('all-templae-register')
                ->with('success', 'Temple registration approved successfully.');

        } catch (Throwable $e) {
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                throw $e;
            }

            Log::error('Temple registration approval failed', [
                'registration_id' => $registrationId,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('all-templae-register')
                ->with('error', 'Unable to approve the registration. Please check the application logs.');
        }
    }

    /**
     * Reject a temple registration.
     */
    public function reject($registrationId)
    {
        try {
            DB::transaction(function () use ($registrationId) {
                $registration = DB::table('temple_user')
                    ->where('id', $registrationId)
                    ->lockForUpdate()
                    ->first();

                if (!$registration) {
                    abort(404, 'Temple registration not found.');
                }

                if ($registration->approval_status === 'approved') {
                    abort(422, 'An approved registration cannot be rejected directly.');
                }

                if ($registration->approval_status === 'rejected') {
                    return;
                }

                DB::table('temple_user')
                    ->where('id', $registrationId)
                    ->update([
                        'approval_status' => 'rejected',
                        'updated_at' => now(),
                    ]);
            });

            return redirect()
                ->route('all-templae-register')
                ->with('success', 'Temple registration rejected successfully.');

        } catch (Throwable $e) {
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                throw $e;
            }

            Log::error('Temple registration rejection failed', [
                'registration_id' => $registrationId,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('all-templae-register')
                ->with('error', 'Unable to reject the registration. Please check the application logs.');
        }
    }
}