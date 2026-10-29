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

class StoreManagerController extends Controller
{
    private function isAdmin(): bool
    {
        $user = auth()->user();
        return $user !== null && $user->hasAnyRole(['Admin', 'Super Admin']);
    }

    private function authorizeManagerAccess(array $permissions): void
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
            'You are not authorized to manage Store Managers.'
        );
        $hasPermission = collect($permissions)->contains(
            fn ($permission) => $user->can($permission)
        );
        abort_unless($hasPermission,403,'You do not have permission to perform this action.');
    }

    private function templeId(): int
    {
        $user = auth()->user();
        abort_unless($user && $user->hasRole('Temple Manager'),403);
        $templeId = DB::table('temple_user')
            ->where('user_id', $user->id)
            ->value('temple_id');
        abort_unless($templeId,403,'No temple is assigned to this Temple Manager.');

        return (int) $templeId;
    }

    private function storeManagerRole(): Role
    {
        return Role::query()
            ->where('name', 'Store Manager')
            ->where('guard_name', 'web')
            ->firstOrFail();
    }

    private function templeHasStoreManager(int $templeId, ?int $ignoreUserId = null ): bool {
        $query = User::query()
            ->where('temple_id', $templeId)
            ->whereHas('roles', function ($roleQuery) {
                $roleQuery
                    ->where('name', 'Store Manager')
                    ->where('guard_name', 'web');
            });
        if ($ignoreUserId !== null) {
            $query->where('id', '!=', $ignoreUserId);
        }
        return $query->exists();
    }

    private function availableTemplesQuery()
    {
        $role = $this->storeManagerRole();
        $assignedTempleIds = User::query()->whereNotNull('temple_id')
            ->whereHas('roles', function ($roleQuery) use ($role) {
                $roleQuery
                    ->where('roles.id', $role->id)
                    ->where('roles.guard_name', 'web');
            })->select('temple_id');
        return Temple::query()->whereNotIn('id', $assignedTempleIds)->orderBy('name');
    }

    private function authorizeStoreManager(User $storeManager): void
    {
        abort_unless($storeManager->hasRole('Store Manager'), 404);
        if ($this->isAdmin()) {
            return;
        }
        $user = auth()->user();
        $templeId = $this->templeId();
        abort_unless((int) $storeManager->created_by === (int) $user->id  && (int) $storeManager->temple_id === $templeId,404,'You cannot manage this Store Manager.');
    }

    public function index()
    {
        $this->authorizeManagerAccess([
            'store-managers.view',
            'store-managers.index',
        ]);
        $query = User::with(['temple', 'roles'])
            ->whereHas('roles', function ($roleQuery) {
                $roleQuery
                    ->where('name', 'Store Manager')
                    ->where('guard_name', 'web');
            });
        if (!$this->isAdmin()) {
            $templeId = $this->templeId();
            $query->where('temple_id', $templeId);
        }
        $storeManagers = $query->latest()->paginate(10)->withQueryString();
        if ($this->isAdmin()) {
            $canAddStoreManager = $this->availableTemplesQuery()->exists();
        } else {
            $canAddStoreManager = !$this->templeHasStoreManager(
                $this->templeId()
            );
        }
        return view('admin.store-managers.index',compact('storeManagers', 'canAddStoreManager'));
    }

    public function create()
    {
        $this->authorizeManagerAccess([
            'store-managers.create',
        ]);
        if ($this->isAdmin()) {
            $temples = $this->availableTemplesQuery()->get();
            if ($temples->isEmpty()) {
                return redirect()
                    ->route('admin.store-managers.index')
                    ->with(
                        'error',
                        'Every temple already has a Store Manager. No additional Store Manager can be created.'
                    );
            }
        } else {
            $templeId = $this->templeId();
            if ($this->templeHasStoreManager($templeId)) {
                return redirect()
                    ->route('admin.store-managers.index')
                    ->with(
                        'error',
                        'Your temple already has a Store Manager. Only one Store Manager is allowed per temple.'
                    );
            }
            $temples = collect();
        }
        return view('admin.store-managers.create',compact('temples'));
    }
    public function store(Request $request)
    {
        $this->authorizeManagerAccess([
            'store-managers.create',
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
        $templeId = $isAdmin ? (int) $validated['temple_id'] : $this->templeId();
        if ($this->templeHasStoreManager($templeId)) {
            return redirect()
                ->route('admin.store-managers.index')
                ->withInput(
                    $request->except([
                        'password',
                        'password_confirmation',
                    ])
                )
                ->with(
                    'error',
                    'This temple already has a Store Manager. Only one Store Manager is allowed per temple.'
                );
        }
        $role = $this->storeManagerRole();
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

                $storeManager = User::create([
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

                $storeManager->assignRole($role);
            });
        } catch (\Throwable $exception) {
            if ($profilePath) {
                Storage::disk('public')->delete($profilePath);
            }
            throw $exception;
        }
        return redirect()->route('admin.store-managers.index')->with('success','Store Manager created successfully.');
    }

    public function show(User $storeManager)
    {
        $this->authorizeManagerAccess([
            'store-managers.show',
        ]);
        $this->authorizeStoreManager($storeManager);
        $storeManager->load(['temple', 'roles']);
        return view('admin.store-managers.show',compact('storeManager'));
    }

    public function edit(User $storeManager)
    {
        $this->authorizeManagerAccess([
            'store-managers.edit',
        ]);
        $this->authorizeStoreManager($storeManager);
        $storeManager->load(['temple', 'roles']);
        if ($this->isAdmin()) {
            $temples = $this->availableTemplesQuery()
                ->orWhere('id', $storeManager->temple_id)
                ->orderBy('name')
                ->get();
        } else {
            $temples = collect();
        }
        return view('admin.store-managers.edit',compact('storeManager', 'temples'));
    }

    public function update(Request $request, User $storeManager)
    {
        $this->authorizeManagerAccess([
            'store-managers.edit',
        ]);
        $this->authorizeStoreManager($storeManager);
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
                Rule::unique('users', 'email')->ignore($storeManager->id),
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
        $newTempleId = $isAdmin ? (int) $validated['temple_id'] : (int) $storeManager->temple_id;
        if ($this->templeHasStoreManager($newTempleId,(int) $storeManager->id)) {
            return redirect()
                ->back()
                ->withInput(
                    $request->except([
                        'password',
                        'password_confirmation',
                    ])
                )
                ->with(
                    'error',
                    'The selected temple already has another Store Manager. Please select a different temple.'
                );
        }
        $oldProfile = $storeManager->profile;
        $newProfile = null;
        if ($request->hasFile('profile')) {
            $newProfile = $request->file('profile')->store('profiles', 'public');
        }
        try {
            DB::transaction(function () use (
                $request,
                $storeManager,
                $validated,
                $newTempleId,
                $newProfile
            ) {
                $storeManager->name = $validated['name'];
                $storeManager->email = $validated['email'];
                $storeManager->phone = $validated['phone'] ?? null;
                $storeManager->gender = $validated['gender'] ?? null;
                $storeManager->birth_date = $validated['birth_date'] ?? null;
                $storeManager->temple_id = $newTempleId;
                $storeManager->status = $request->boolean('status');
                $storeManager->updated_by = $request->user()->id;
                if ($newProfile) {
                    $storeManager->profile = $newProfile;
                }
                $storeManager->save();
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
            ->route('admin.store-managers.index')
            ->with(
                'success',
                'Store Manager updated successfully.'
            );
    }
    public function destroy(User $storeManager)
    {
        $this->authorizeManagerAccess([
            'store-managers.delete',
        ]);
        $this->authorizeStoreManager($storeManager);
        $storeManager->update([
            'status' => 0,
        ]);
        return redirect()->route('admin.store-managers.index')->with('success', 'Store Manager deactivated successfully.');
    }
}