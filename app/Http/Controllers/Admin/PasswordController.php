<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function index()
    {
        return view('admin.password.index');
    }
    public function verifyOldPassword(Request $request)
    {
        $request->validate(['old_password' => 'required',], ['old_password.required' => 'Please enter your old password.',]);
        $user = Auth::user();
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withInput()->with('error', 'Old password is incorrect.');
        }
        session(['password_verified' => true]);
        return redirect()->route('password.new');
    }
    public function newPassword()
    {
        if (!session('password_verified')) {
            return redirect()->route('password.index')->with('error', 'Please verify your old password first.');
        }
        return view('admin.password.new');
    }
    public function update(Request $request)
    {
        if (!session('password_verified')) {
            return redirect()->route('password.index')->with('error', 'Please verify your old password first.');
        }
        $request->validate([
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'new_password.required' => 'Please enter a new password.',
            'new_password.min' => 'Password must be at least 8 characters.',
            'new_password.confirmed' => 'New password and confirm password do not match.',
        ]);
        $user = Auth::user();
        if (Hash::check($request->new_password, $user->password)) {
            return back()->with('error', 'New password must be different from your old password.');
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        session()->forget('password_verified');
        return redirect()->route('admin.profile.index')->with('success', 'Password changed successfully.');
    }
}