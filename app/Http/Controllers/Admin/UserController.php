<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $isPandit = auth()->user()->hasRole('Pandit');

        $roles = $isPandit
            ? collect()
            : Role::query()
                ->where('name', '!=', 'Super Admin')
                ->orderBy('name')
                ->get();

        return view('admin.users.create', compact('roles', 'isPandit'));
    }

    public function store(Request $request)
    {
        $isPandit = auth()->user()->hasRole('Pandit');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'profile' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'password' => 'required|string|min:8|confirmed',

            'role_id' => $isPandit
                ? 'nullable'
                : 'required|exists:roles,id',
        ]);

        if ($isPandit) {
            $role = Role::where('name', 'Customer')->firstOrFail();
        } else {
            $role = Role::findOrFail($request->role_id);
        }

        $profile = null;

        if ($request->hasFile('profile')) {
            $profile = $request->file('profile')->store('users', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'role_id' => $role->id,
            'profile' => $profile,
            'status' => 1,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole($role);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $isPandit = auth()->user()->hasRole('Pandit');

        $roles = $isPandit
            ? collect()
            : Role::query()
                ->where('name', '!=', 'Super Admin')
                ->orderBy('name')
                ->get();

        return view('admin.users.edit', compact('user', 'roles', 'isPandit'));
    }

    public function update(Request $request, User $user)
    {
        $isPandit = auth()->user()->hasRole('Pandit');
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|string|max:20',
            'role_id' => $isPandit ? 'nullable' : 'required|exists:roles,id',
            'birth_date' => 'nullable|date',
            'profile' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|boolean',
        ]);
        if ($isPandit) {
            $customerRoleId = Role::where('name', 'Customer')->value('id');
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'role_id' => $isPandit ? $customerRoleId : $request->role_id,
            'birth_date' => $request->birth_date,
            'status' => $request->status,
        ];

        if ($request->hasFile('profile')) {
            $data['profile'] = $request->file('profile')->store('users', 'public');
        }

        $user->update($data);
        if ($isPandit) {
            $role = Role::findOrFail($customerRoleId);
            $user->syncRoles([$role]);
        } else {
            $role = Role::findOrFail($request->role_id);
            $user->syncRoles([$role]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->update(['status' => 0]);

        return redirect()->route('admin.users.index')->with('success', 'User deactivated successfully.');
    }
}
