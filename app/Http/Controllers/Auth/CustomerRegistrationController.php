<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class CustomerRegistrationController extends Controller
{
    /**
     * Display customer registration form.
     */
    public function create()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.customer-registration');
    }

    /**
     * Store a new customer account.
     */
    public function store(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
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
                Rule::in(['Male', 'Female', 'Other']),
            ],

            'birth_date' => [
                'nullable',
                'date',
                'before:today',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],
        ]);

        // Find the existing Customer role.
        $customerRole = Role::where('name', 'Customer')
            ->where('guard_name', 'web')
            ->first();

        if (!$customerRole) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors([
                    'registration' => 'Customer role is not configured. Please contact the administrator.',
                ]);
        }

        // Create the customer.
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->gender = $validated['gender'] ?? null;
        $user->birth_date = $validated['birth_date'] ?? null;
        $user->password = Hash::make($validated['password']);

        // Assumes status = 1 means active in your application.
        $user->status = 1;

        // Keep the role ID in your existing users table.
        $user->role_id = $customerRole->id;

        $user->save();

        // Assign the role in Spatie's model_has_roles table.
        $user->assignRole($customerRole);

        return redirect()
            ->route('login')
            ->with('success', 'Your customer account has been created successfully. Please log in.');
    }
}
