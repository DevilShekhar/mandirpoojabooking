<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class StoreManagerController extends Controller
{
    private function templeId(): int
    {
        $user = auth()->user();
        abort_unless($user && $user->hasRole('Temple Manager'), 403);
        $templeId = DB::table('temple_user')->where('user_id', $user->id)->value('temple_id');
        abort_unless($templeId, 403, 'No temple is assigned to this Temple Manager.');
        return (int) $templeId;
    }
    private function storeManagerRole(): Role
    {
        return Role::where('name', 'Store Manager')->where('guard_name', 'web')->firstOrFail();
    }

    private function authorizeStoreManager(User $storeManager): void
    {
        abort_unless($storeManager->hasRole('Store Manager') && (int) $storeManager->created_by === (int) auth()->id() && (int) $storeManager->temple_id === $this->templeId(), 404);
    }

    public function index()
    {
        $templeId = $this->templeId();

        $storeManagers = User::where('created_by', auth()->id())->where('temple_id', $templeId)->whereHas('roles', function ($query) {
            $query->where('name', 'Store Manager')->where('guard_name', 'web');
        })->latest()->paginate(10);

        return view('admin.store-managers.index', compact('storeManagers'));
    }

    public function create()
    {
        $templeId = $this->templeId();

        $storeManagerExists = User::where('created_by', auth()->id())->where('temple_id', $templeId)->whereHas('roles', function ($query) {
            $query->where('name', 'Store Manager')->where('guard_name', 'web');
        })->exists();

        if ($storeManagerExists) {
            return redirect()->route('admin.store-managers.index')->with('error', 'You can create only one Store Manager for your temple.');
        }

        return view('admin.store-managers.create');
    }

    public function store(Request $request)
    {
        $templeId = $this->templeId();
        $role = $this->storeManagerRole();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'profile' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'status' => ['sometimes', 'boolean'],
        ]);

        $storeManager = DB::transaction(function () use ($request, $validated, $role, $templeId) {
            $exists = User::where('created_by', $request->user()->id)->where('temple_id', $templeId)->whereHas('roles', function ($query) {
                $query->where('name', 'Store Manager')->where('guard_name', 'web');
            })->exists();

            if ($exists) {
                return null;
            }

            $profilePath = $request->hasFile('profile') ? $request->file('profile')->store('profiles', 'public') : null;

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'birth_date' => $validated['birth_date'] ?? null,
                'profile' => $profilePath,
                'password' => $validated['password'],
                'status' => $request->boolean('status', true),
                'role_id' => $role->id,
                'temple_id' => $templeId,
                'created_by' => $request->user()->id,
                'updated_by' => $request->user()->id,
            ]);

            $user->assignRole($role);

            return $user;
        });

        if (!$storeManager) {
            return redirect()->route('admin.store-managers.index')->with('error', 'You can create only one Store Manager for your temple.');
        }

        return redirect()->route('admin.store-managers.index')->with('success', 'Store Manager created successfully.');
    }

    public function show(User $storeManager)
    {
        $this->authorizeStoreManager($storeManager);

        return view('admin.store-managers.show', compact('storeManager'));
    }

    public function edit(User $storeManager)
    {
        $this->authorizeStoreManager($storeManager);

        return view('admin.store-managers.edit', compact('storeManager'));
    }

    public function update(Request $request, User $storeManager)
    {
        $this->authorizeStoreManager($storeManager);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($storeManager->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'profile' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'boolean'],
        ]);

        if ($request->hasFile('profile')) {
            $newProfilePath = $request->file('profile')->store('profiles', 'public');

            if ($storeManager->profile) {
                Storage::disk('public')->delete($storeManager->profile);
            }

            $validated['profile'] = $newProfilePath;
        }

        $validated['updated_by'] = $request->user()->id;

        $storeManager->update($validated);

        return redirect()->route('admin.store-managers.index')->with('success', 'Store Manager updated successfully.');
    }

    public function destroy(User $storeManager)
    {
        $this->authorizeStoreManager($storeManager);

        $storeManager->update(['status' => false, 'updated_by' => auth()->id()]);

        return redirect()->route('admin.store-managers.index')->with('success', 'Store Manager deactivated successfully.');
    }
}