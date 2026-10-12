<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class CustomerRegistrationController extends Controller
{
    public function create()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.customer-registration');
    }

    public function store(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'gender' => ['nullable', Rule::in(['Male', 'Female', 'Other'])],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

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

        try {
            $user = DB::transaction(function () use ($validated, $customerRole) {
                $user = new User();
                $user->name = $validated['name'];
                $user->email = $validated['email'];
                $user->phone = $validated['phone'];
                $user->gender = $validated['gender'] ?? null;
                $user->birth_date = $validated['birth_date'] ?? null;
                $user->password = Hash::make($validated['password']);
                $user->status = 1;
                $user->role_id = $customerRole->id;
                $user->save();
                $user->assignRole($customerRole);

                return $user;
            });
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors([
                    'registration' => 'Unable to create your account. Please try again.',
                ]);
        }

        return redirect()
            ->route('login')
            ->with('success', 'Your customer account has been created successfully. Please log in.');
    }

    public function redirectToGoogle()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return Socialite::driver('google')
            ->scopes(['openid', 'email', 'profile'])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        try {
            $googleUser = Socialite::driver('google')->user();

            $emailVerified = filter_var(
                data_get($googleUser->user, 'verified_email', false),
                FILTER_VALIDATE_BOOLEAN
            );

            if (!$googleUser->getEmail() || !$emailVerified) {
                return redirect()
                    ->route('customer.register')
                    ->withErrors([
                        'google' => 'Google did not provide a verified email address. Please try another account.',
                    ]);
            }

            $customerRole = Role::where('name', 'Customer')
                ->where('guard_name', 'web')
                ->first();

            if (!$customerRole) {
                return redirect()
                    ->route('customer.register')
                    ->withErrors([
                        'google' => 'Customer role is not configured. Please contact the administrator.',
                    ]);
            }

            $user = DB::transaction(function () use ($googleUser, $customerRole) {
                $user = User::where('google_id', $googleUser->getId())
                    ->orWhere('email', $googleUser->getEmail())
                    ->lockForUpdate()
                    ->first();

                if ($user) {
                    $isCustomer = (int) $user->role_id === (int) $customerRole->id
                        || $user->hasRole('Customer');

                    if (!$isCustomer) {
                        throw new \RuntimeException(
                            'This Google account is associated with a non-customer account.'
                        );
                    }

                    if ($user->google_id && $user->google_id !== $googleUser->getId()) {
                        throw new \RuntimeException(
                            'This email is already linked to another Google account.'
                        );
                    }

                    if (!$user->google_id) {
                        $user->google_id = $googleUser->getId();
                        $user->save();
                    }

                    if (isset($user->status) && (int) $user->status !== 1) {
                        throw new \RuntimeException(
                            'Your account is inactive. Please contact the administrator.'
                        );
                    }

                    return $user;
                }

                $user = new User();
                $user->name = $googleUser->getName()
                    ?: $googleUser->getNickname()
                    ?: 'Customer';
                $user->email = $googleUser->getEmail();
                $user->google_id = $googleUser->getId();
                $user->phone = null;
                $user->password = Hash::make(Str::random(64));
                $user->status = 1;
                $user->role_id = $customerRole->id;
                $user->save();
                $user->assignRole($customerRole);

                return $user;
            });

            Auth::login($user);
            request()->session()->regenerate();

            return redirect()
                ->route('dashboard')
                ->with('success', 'You have successfully logged in with Google.');
        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->route('customer.register')
                ->withErrors([
                    'google' => $e instanceof \RuntimeException
                        ? $e->getMessage()
                        : 'Google authentication failed. Please try again.',
                ]);
        }
    }
}