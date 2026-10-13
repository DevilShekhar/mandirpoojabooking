<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class PanditController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $panditRole = $this->getPanditRole();

        $query = User::where('role_id', $panditRole->id);

        if (!$user->hasAnyRole(['Super Admin', 'Admin'])) {
            $query->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                    ->orWhere('updated_by', $user->id);
            });
        }

        $pandits = $query
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.pandits.index', compact('pandits'));
    }

    public function create()
    {
        $this->getPanditRole();

        return view('admin.pandits.create');
    }

    public function store(Request $request)
    {
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
                'required',
                'string',
                'max:20',
                'unique:users,phone',
            ],
            'gender' => [
                'nullable',
                'in:male,female,other',
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
                'required',
                'boolean',
            ],
        ]);

        $panditRole = $this->getPanditRole();
        $profilePath = null;
        $userId = auth()->id();

        DB::beginTransaction();

        try {
            if ($request->hasFile('profile')) {
                $profilePath = $request->file('profile')
                    ->store('users/profiles', 'public');
            }

            $pandit = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'gender' => $validated['gender'] ?? null,
                'birth_date' => $validated['birth_date'] ?? null,
                'profile' => $profilePath,
                'password' => Hash::make($validated['password']),
                'status' => $request->boolean('status'),
                'role_id' => $panditRole->id,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);

            $pandit->assignRole($panditRole);

            DB::commit();

            return redirect()
                ->route('pandits.index')
                ->with('success', 'Pandit created successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();

            if ($profilePath) {
                Storage::disk('public')->delete($profilePath);
            }

            report($e);

            return back()
                ->withInput($request->except([
                    'password',
                    'password_confirmation',
                ]))
                ->withErrors([
                    'error' => 'Unable to create Pandit. Please try again.',
                ]);
        }
    }

    public function show(User $pandit)
    {
        $this->ensurePandit($pandit);
        $this->authorizePanditAccess($pandit);

        return view('admin.pandits.show', compact('pandit'));
    }

    public function edit(User $pandit)
    {
        $this->ensurePandit($pandit);
        $this->authorizePanditAccess($pandit);

        return view('admin.pandits.edit', compact('pandit'));
    }

    public function update(Request $request, User $pandit)
    {
        $this->ensurePandit($pandit);
        $this->authorizePanditAccess($pandit);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $pandit->id,
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                'unique:users,phone,' . $pandit->id,
            ],
            'gender' => ['nullable', 'in:male,female,other'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'profile' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'remove_profile' => ['nullable', 'boolean'],
            'status' => ['required', 'boolean'],
        ]);

        $panditRole = $this->getPanditRole();
        $userId = auth()->id();
        $oldProfilePath = $pandit->profile;
        $newProfilePath = null;
        $removeProfile = $request->boolean('remove_profile');

        DB::beginTransaction();

        try {
            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'gender' => $validated['gender'] ?? null,
                'birth_date' => $validated['birth_date'] ?? null,
                'status' => $request->boolean('status'),
                'role_id' => $panditRole->id,
                'updated_by' => $userId,
            ];

            if ($removeProfile) {
                $data['profile'] = null;
            } elseif ($request->hasFile('profile')) {
                $newProfilePath = $request->file('profile')
                    ->store('users/profiles', 'public');

                $data['profile'] = $newProfilePath;
            }

            $pandit->update($data);
            $pandit->syncRoles([$panditRole]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($newProfilePath) {
                Storage::disk('public')->delete($newProfilePath);
            }

            report($e);

            return back()
                ->withInput($request->except(['_token', '_method']))
                ->withErrors([
                    'error' => 'Unable to update Pandit. Please try again.',
                ]);
        }

        if (
            ($newProfilePath || $removeProfile)
            && $oldProfilePath
            && $oldProfilePath !== $newProfilePath
        ) {
            Storage::disk('public')->delete($oldProfilePath);
        }

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
        $role = Role::where('name', 'Pandit')
            ->where('guard_name', 'web')
            ->first();

        if (!$role) {
            abort(500, 'The Pandit role does not exist. Please create it first.');
        }

        return $role;
    }

    private function ensurePandit(User $user): void
    {
        $panditRole = $this->getPanditRole();

        if ((int) $user->role_id !== (int) $panditRole->id) {
            abort(404);
        }
    }

    private function authorizePanditAccess(User $pandit): void
    {
        $user = auth()->user();

        if (!$user->hasAnyRole(['Super Admin', 'Admin'])) {
            abort_unless(
                (int) $pandit->created_by === (int) $user->id
                || (int) $pandit->updated_by === (int) $user->id,
                403
            );
        }
    }
}