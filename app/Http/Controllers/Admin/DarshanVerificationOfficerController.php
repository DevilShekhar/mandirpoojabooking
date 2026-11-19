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

class DarshanVerificationOfficerController extends Controller
{
    private function isAdmin(): bool
    {
        return auth()->user()?->hasAnyRole([
            'Admin',
            'Super Admin',
        ]) ?? false;
    }

    private function authorizeAccess(array $permissions): void
    {
        $user = auth()->user();

        abort_unless($user, 403);

        abort_unless(
            $user->hasAnyRole([
                'Admin',
                'Super Admin',
                'Temple Manager',
            ]),
            403,
            'You are not authorized to manage Darshan Verification Officers.'
        );

        $hasPermission = collect($permissions)->contains(
            fn ($permission) => $user->can($permission)
        );

        abort_unless(
            $hasPermission,
            403,
            'You do not have permission to perform this action.'
        );
    }

    private function templeId(): int
    {
        $user = auth()->user();

        abort_unless(
            $user && $user->hasRole('Temple Manager'),
            403
        );

        $templeId = DB::table('temple_user')
            ->where('user_id', $user->id)
            ->value('temple_id');

        abort_unless(
            $templeId,
            403,
            'No temple is assigned to this Temple Manager.'
        );

        return (int) $templeId;
    }

    private function officerRole(): Role
    {
        return Role::where('name', 'Darshan Verification Officer')
            ->where('guard_name', 'web')
            ->firstOrFail();
    }

    private function authorizeOfficer(User $officer): void
    {
        abort_unless(
            $officer->hasRole('Darshan Verification Officer'),
            404
        );

        if ($this->isAdmin()) {
            return;
        }

        $user = auth()->user();
        $templeId = $this->templeId();

        abort_unless(
            (int) $officer->created_by === (int) $user->id &&
            (int) $officer->temple_id === $templeId,
            404,
            'You cannot manage this Darshan Verification Officer.'
        );
    }

    public function index()
    {
        $this->authorizeAccess([
            'darshan-verification-officers.view',
            'darshan-verification-officers.index',
        ]);

        $query = User::with(['temple', 'roles'])
            ->whereHas('roles', function ($roleQuery) {
                $roleQuery
                    ->where('name', 'Darshan Verification Officer')
                    ->where('guard_name', 'web');
            });

        if (!$this->isAdmin()) {
            $query->where('temple_id', $this->templeId());
        }

        $officers = $query->latest()->get();

        return view(
            'admin.darshan-verification-officers.index',
            compact('officers')
        );
    }

    public function create()
    {
        $this->authorizeAccess([
            'darshan-verification-officers.create',
        ]);

        if ($this->isAdmin()) {
            $temples = Temple::orderBy('name')->get();
        } else {
            $temples = collect();
        }

        return view(
            'admin.darshan-verification-officers.create',
            compact('temples')
        );
    }

    public function store(Request $request)
    {
        $this->authorizeAccess([
            'darshan-verification-officers.create',
        ]);

        $isAdmin = $this->isAdmin();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],
            'gender' => [
                'nullable',
                Rule::in(['male', 'female', 'other']),
            ],
            'birth_date' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'profile' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'status' => [
                'sometimes',
                'boolean',
            ],
            'temple_id' => [
                $isAdmin ? 'required' : 'prohibited',
                'integer',
                'min:1',
                Rule::exists('temples', 'id'),
            ],
        ]);

        $user = $request->user();

        $templeId = $isAdmin
            ? (int) $validated['temple_id']
            : $this->templeId();

        $role = $this->officerRole();

        $profilePath = null;

        try {
            DB::transaction(function () use (
                $request,
                $validated,
                $user,
                $role,
                $templeId,
                &$profilePath
            ) {
                if ($request->hasFile('profile')) {
                    $profilePath = $request
                        ->file('profile')
                        ->store('profiles', 'public');
                }

                $officer = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? null,
                    'gender' => $validated['gender'] ?? null,
                    'birth_date' => $validated['birth_date'] ?? null,
                    'profile' => $profilePath,
                    'password' => Hash::make($validated['password']),
                    'status' => $request->boolean('status', true),
                    'role_id' => $role->id,
                    'temple_id' => $templeId,
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]);

                $officer->assignRole($role);
            });
        } catch (\Throwable $exception) {
            if ($profilePath) {
                Storage::disk('public')->delete($profilePath);
            }

            throw $exception;
        }

        return redirect()
            ->route('admin.darshan-verification-officers.index')
            ->with(
                'success',
                'Darshan Verification Officer created successfully.'
            );
    }

    public function show(User $darshanVerificationOfficer)
    {
        $this->authorizeAccess([
            'darshan-verification-officers.show',
        ]);

        $this->authorizeOfficer($darshanVerificationOfficer);

        $darshanVerificationOfficer->load([
            'temple',
            'roles',
        ]);

        return view(
            'admin.darshan-verification-officers.show',
            ['officer' => $darshanVerificationOfficer]
        );
    }

    public function edit(User $darshanVerificationOfficer)
    {
        $this->authorizeAccess([
            'darshan-verification-officers.edit',
        ]);

        $this->authorizeOfficer($darshanVerificationOfficer);

        $darshanVerificationOfficer->load([
            'temple',
            'roles',
        ]);

        $temples = $this->isAdmin()
            ? Temple::orderBy('name')->get()
            : collect();

        return view(
            'admin.darshan-verification-officers.edit',
            [
                'officer' => $darshanVerificationOfficer,
                'temples' => $temples,
            ]
        );
    }

    public function update(
        Request $request,
        User $darshanVerificationOfficer
    ) {
        $this->authorizeAccess([
            'darshan-verification-officers.edit',
        ]);

        $this->authorizeOfficer($darshanVerificationOfficer);

        $isAdmin = $this->isAdmin();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($darshanVerificationOfficer->id),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],
            'gender' => [
                'nullable',
                Rule::in(['male', 'female', 'other']),
            ],
            'birth_date' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'profile' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'status' => [
                'sometimes',
                'boolean',
            ],
            'temple_id' => [
                $isAdmin ? 'required' : 'prohibited',
                'integer',
                'min:1',
                Rule::exists('temples', 'id'),
            ],
        ]);

        $newTempleId = $isAdmin
            ? (int) $validated['temple_id']
            : (int) $darshanVerificationOfficer->temple_id;

        $oldProfile = $darshanVerificationOfficer->profile;
        $newProfile = null;

        if ($request->hasFile('profile')) {
            $newProfile = $request
                ->file('profile')
                ->store('profiles', 'public');
        }

        try {
            DB::transaction(function () use (
                $request,
                $darshanVerificationOfficer,
                $validated,
                $newTempleId,
                $newProfile
            ) {
                $darshanVerificationOfficer->name = $validated['name'];
                $darshanVerificationOfficer->email = $validated['email'];
                $darshanVerificationOfficer->phone = $validated['phone'] ?? null;
                $darshanVerificationOfficer->gender = $validated['gender'] ?? null;
                $darshanVerificationOfficer->birth_date = $validated['birth_date'] ?? null;
                $darshanVerificationOfficer->temple_id = $newTempleId;
                $darshanVerificationOfficer->status = $request->boolean('status');
                $darshanVerificationOfficer->updated_by = $request->user()->id;

                if ($newProfile) {
                    $darshanVerificationOfficer->profile = $newProfile;
                }

                $darshanVerificationOfficer->save();
            });
        } catch (\Throwable $exception) {
            if ($newProfile) {
                Storage::disk('public')->delete($newProfile);
            }

            throw $exception;
        }

        if ($newProfile && $oldProfile && $oldProfile !== $newProfile) {
            Storage::disk('public')->delete($oldProfile);
        }

        return redirect()
            ->route('admin.darshan-verification-officers.index')
            ->with(
                'success',
                'Darshan Verification Officer updated successfully.'
            );
    }

    public function destroy(User $darshanVerificationOfficer)
    {
        $this->authorizeAccess([
            'darshan-verification-officers.delete',
        ]);

        $this->authorizeOfficer($darshanVerificationOfficer);

        $darshanVerificationOfficer->update([
            'status' => 0,
        ]);

        return redirect()
            ->route('admin.darshan-verification-officers.index')
            ->with(
                'success',
                'Darshan Verification Officer deactivated successfully.'
            );
    }
}