<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::query()->get();
        $permissions = Permission::all();

        $role = null;
        $rolePermissions = [];

        return view('admin.roles.index', compact(
            'roles',
            'permissions',
            'role',
            'rolePermissions',
        ));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();

        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:roles,name,'.$id,
            'status' => 'required|in:0,1',
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $role->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully');
    }

    public function destroy($role)
    {
        $role = Role::findOrFail($role);

        $role->update(['status' => 0]);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role Inactivated successfully');
    }

    public function managePermissions(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.permissions', compact('role', 'permissions', 'rolePermissions'));
    }

    public function updatePermissions(Request $request, Role $role)
    {
        // dd($request->all());
        $role->syncPermissions($request->permissions ?? []);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Permissions updated successfully');
    }

    public function getPermissionsData($role)
    {
        $role = Role::with('permissions')->findOrFail($role);

        return response()->json([
            'role' => $role,
            'permissions' => Permission::all(),
            'rolePermissions' => $role->permissions->pluck('name')->toArray(),
        ]);
    }
}
