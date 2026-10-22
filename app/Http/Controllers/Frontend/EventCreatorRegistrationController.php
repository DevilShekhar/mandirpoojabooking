<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class EventCreatorRegistrationController extends Controller
{
    public function create()
    {
        return view('frontend.event-creator-register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'gender' => ['nullable', 'in:male,female,other'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $role = Role::query()
            ->where('name', 'Event Creator')
            ->where('guard_name', 'web')
            ->first();

        if (!$role) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors([
                    'role' => 'Event Creator role was not found.',
                ]);
        }

        DB::transaction(function () use ($validated) {
            $user = new User();
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'];
            $user->gender = $validated['gender'] ?? null;
            $user->birth_date = $validated['birth_date'] ?? null;
            $user->password = Hash::make($validated['password']);
            $user->role_id = 10;
            $user->status = 1;
            $user->created_by = null;
            $user->updated_by = null;
            $user->save();

            $user->assignRole('Event Creator');
        });

        return redirect()
            ->route('event-creator.register')
            ->with('success', 'Registration successful. Event Creator role assigned successfully.');
    }
}