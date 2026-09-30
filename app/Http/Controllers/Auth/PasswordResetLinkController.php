<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withErrors([
                    'email' => 'We could not find an account with that email address.',
                ])
                ->withInput();
        }

        $otp = random_int(100000, 999999);

        session([
            'forgot_password_email' => $request->email,
            'forgot_password_otp' => $otp,
            'forgot_password_otp_expires_at' => now()->addMinutes(10)->timestamp,
            'forgot_password_otp_verified' => false,
        ]);

        Mail::raw(
            "Your password reset OTP is: {$otp}\n\nThis OTP will expire in 10 minutes.",
            function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Password Reset OTP - Temple Puja Booking');
            }
        );

        return back()
            ->with('otp_sent', true)
            ->with('status', 'OTP has been sent to your email address.');
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $sessionOtp = session('forgot_password_otp');
        $expiresAt = session('forgot_password_otp_expires_at');

        if (!$sessionOtp || !$expiresAt) {
            return back()->withErrors([
                'otp' => 'OTP session has expired. Please request a new OTP.',
            ]);
        }

        if (now()->timestamp > $expiresAt) {
            session()->forget([
                'forgot_password_email',
                'forgot_password_otp',
                'forgot_password_otp_expires_at',
                'forgot_password_otp_verified',
            ]);

            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'OTP has expired. Please request a new OTP.',
                ]);
        }

        if ((string) $request->otp !== (string) $sessionOtp) {
            return back()->withErrors([
                'otp' => 'Invalid OTP. Please enter the correct OTP.',
            ]);
        }

        session([
            'forgot_password_otp_verified' => true,
        ]);

        return back()
            ->with('otp_verified', true)
            ->with('status', 'OTP verified successfully.');
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        if (!session('forgot_password_otp_verified')) {
            return back()->withErrors([
                'otp' => 'Please verify the OTP first.',
            ]);
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $email = session('forgot_password_email');

        if (!$email) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'Your password reset session has expired. Please request a new OTP.',
                ]);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'User account not found.',
                ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        session()->forget([
            'forgot_password_email',
            'forgot_password_otp',
            'forgot_password_otp_expires_at',
            'forgot_password_otp_verified',
        ]);

        return redirect()
            ->route('login')
            ->with(
                'status',
                'Your password has been reset successfully. Please login with your new password.'
            );
    }

    public function changeEmail(): RedirectResponse
    {
        session()->forget([
            'forgot_password_email',
            'forgot_password_otp',
            'forgot_password_otp_expires_at',
            'forgot_password_otp_verified',
        ]);

        return redirect()->route('password.request');
    }
}