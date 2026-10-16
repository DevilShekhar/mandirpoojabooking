<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Temple;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class PanditController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $panditRole = $this->getPanditRole();

        $query = User::query()
            ->where(function ($q) use ($panditRole) {
                $q->where('role_id', $panditRole->id)
                    ->orWhereHas('roles', function ($roleQuery) {
                        $roleQuery->where('name', 'Pandit')
                            ->where('guard_name', 'web');
                    });
            })
            ->with(['temples' => function ($query) {
                $query->wherePivot('approval_status', 'approved');
            }]);

        if (!$user->hasAnyRole(['Admin', 'Super Admin'])) {
            $templeIds = $this->availableTemples()->pluck('temples.id');

            $query->where(function ($q) use ($user, $templeIds) {
                $q->where('created_by', $user->id)
                    ->orWhere('updated_by', $user->id);

                if ($templeIds->isNotEmpty()) {
                    $q->orWhereHas('temples', function ($templeQuery) use ($templeIds) {
                        $templeQuery->whereIn('temples.id', $templeIds)
                            ->where('temple_user.approval_status', 'approved');
                    });
                }
            });
        }
        $pandits = $query->latest()->paginate(10);
        return view('admin.pandits.index', compact('pandits'));
    }
    public function create()
    {
         $temples = $this->availableTemples()
            ->orderBy('name')
            ->get(['temples.id', 'temples.name']);

        if ($temples->isEmpty()) {
            return redirect()
                ->route('pandits.index')
                ->with('error', 'No temple is assigned to your account.');
        }

        return view('admin.pandits.create', compact('temples'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'temple_id' => ['nullable', 'integer', 'exists:temples,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'status' => ['required', Rule::in(['active', 'inactive', '1', '0', 1, 0])],
        ]);

        if ($user->hasAnyRole(['Admin', 'Super Admin'])) {
            if (empty($validated['temple_id'])) {
                return back()->withInput()->withErrors([
                    'temple_id' => 'Please select a temple.',
                ]);
            }

            $temple = Temple::find($validated['temple_id']);
        } else {
            $temple = $this->availableTemples()->first();
        }

        if (!$temple) {
            return back()->withInput()->withErrors([
                'temple_id' => 'No authorized temple is available for your account.',
            ]);
        }

        $panditRole = $this->getPanditRole();

        DB::transaction(function () use ($request, $validated, $panditRole, $temple) {
            $profileImage = $request->hasFile('profile_image')
                ? $request->file('profile_image')->store('pandits', 'public')
                : null;

            $status = in_array(
                (string) $validated['status'],
                ['active', '1'],
                true
            );

            $pandit = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'birth_date' => $validated['birth_date'] ?? null,
                'profile_image' => $profileImage,
                'password' => Hash::make($validated['password']),
                'status' => $status,
                'role_id' => $panditRole->id,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            $pandit->assignRole($panditRole);

            $pandit->temples()->sync([
                $temple->id => [
                    'approval_status' => 'approved',
                ],
            ]);
        });

        return redirect()
            ->route('pandits.index')
            ->with('success', 'Pandit created successfully and approved for the temple.');
    }

    public function show(User $pandit)
    {
        $this->ensurePandit($pandit);
        $this->authorizePanditAccess($pandit);

        $pandit->load(['temples' => function ($query) {
            $query->wherePivot('approval_status', 'approved');
        }]);

        return view('admin.pandits.show', compact('pandit'));
    }

    public function edit(User $pandit)
    {
        $this->ensurePandit($pandit);
        $this->authorizePanditAccess($pandit);

        $temples = $this->availableTemples()
            ->orderBy('name')
            ->get(['temples.id', 'temples.name']);

        $pandit->load(['temples' => function ($query) {
            $query->wherePivot('approval_status', 'approved');
        }]);

        return view('admin.pandits.edit', compact('pandit', 'temples'));
    }

    public function update(Request $request, User $pandit)
    {
        $this->ensurePandit($pandit);
        $this->authorizePanditAccess($pandit);

        $validated = $request->validate([
            'temple_id' => ['nullable', 'integer', 'exists:temples,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($pandit->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'status' => ['required', Rule::in(['active', 'inactive', '1', '0', 1, 0])],
        ]);

        $user = auth()->user();
        $temple = null;

        if ($user->hasAnyRole(['Admin', 'Super Admin'])) {
            if (!empty($validated['temple_id'])) {
                $temple = Temple::find($validated['temple_id']);
            }
        } else {
            $temple = $this->availableTemples()->first();

            if (!$temple) {
                return back()->withInput()->withErrors([
                    'temple_id' => 'No authorized temple is available for your account.',
                ]);
            }
        }

        if (!empty($validated['temple_id']) && !$user->hasAnyRole(['Admin', 'Super Admin'])) {
            $authorizedTemple = $this->availableTemples()
                ->where('temples.id', $validated['temple_id'])
                ->first();

            if (!$authorizedTemple) {
                return back()->withInput()->withErrors([
                    'temple_id' => 'You are not authorized to use this temple.',
                ]);
            }

            $temple = $authorizedTemple;
        }

        DB::transaction(function () use ($request, $validated, $pandit, $temple) {
            $status = in_array(
                (string) $validated['status'],
                ['active', '1'],
                true
            );

            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'birth_date' => $validated['birth_date'] ?? null,
                'status' => $status,
                'updated_by' => auth()->id(),
            ];

            if (!empty($validated['password'])) {
                $data['password'] = Hash::make($validated['password']);
            }

            if ($request->hasFile('profile_image')) {
                if ($pandit->profile_image) {
                    Storage::disk('public')->delete($pandit->profile_image);
                }

                $data['profile_image'] = $request->file('profile_image')
                    ->store('pandits', 'public');
            }

            $pandit->update($data);

            $pandit->syncRoles([$this->getPanditRole()]);

            if ($temple) {
                $pandit->temples()->sync([
                    $temple->id => [
                        'approval_status' => 'approved',
                    ],
                ]);
            }
        });

        return redirect()
            ->route('pandits.index')
            ->with('success', 'Pandit updated successfully.');
    }

    public function destroy(User $pandit)
    {
        $this->ensurePandit($pandit);
        $this->authorizePanditAccess($pandit);

        $pandit->update([
            'status' => false,
            'updated_by' => auth()->id(),
        ]);

        return redirect()
            ->route('pandits.index')
            ->with('success', 'Pandit deactivated successfully.');
    }

    private function getPanditRole(): Role
    {
        return Role::where('name', 'Pandit')
            ->where('guard_name', 'web')
            ->firstOrFail();
    }

    private function availableTemples()
    {
        $user = auth()->user();

        if ($user->hasAnyRole(['Admin', 'Super Admin'])) {
            return Temple::query();
        }

        return $user->temples()
            ->wherePivot('approval_status', 'approved');
    }

    private function ensurePandit(User $pandit): void
    {
        abort_unless(
            (int) $pandit->role_id === (int) $this->getPanditRole()->id
                || $pandit->hasRole('Pandit'),
            404
        );
    }

    private function authorizePanditAccess(User $pandit): void
    {
        $user = auth()->user();

        if ($user->hasAnyRole(['Admin', 'Super Admin'])) {
            return;
        }

        $createdOrUpdatedByUser =
            (int) $pandit->created_by === (int) $user->id
            || (int) $pandit->updated_by === (int) $user->id;

        $templeIds = $this->availableTemples()->pluck('temples.id');

        $hasApprovedTempleAccess = $templeIds->isNotEmpty()
            && $pandit->temples()
                ->wherePivot('approval_status', 'approved')
                ->whereIn('temples.id', $templeIds)
                ->exists();

        abort_unless(
            $createdOrUpdatedByUser || $hasApprovedTempleAccess,
            403
        );
    }
}