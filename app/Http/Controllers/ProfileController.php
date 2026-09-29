<?php
namespace App\Http\Controllers;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
class ProfileController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user()->load('role');
        return view('admin.profile.index', compact('user'));
    }
    public function edit(Request $request): View
    {
        $user = $request->user()->load('role');
        return view('admin.profile.edit', compact('user'));
    }
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->gender = $request->gender;
        $user->birth_date = $request->birth_date;
        if ($request->hasFile('profile')) {
            if ($user->profile && Storage::disk('public')->exists($user->profile)) {
                Storage::disk('public')->delete($user->profile);
            }
            $user->profile = $request->file('profile')->store('profiles', 'public');
        }
        $user->save();
        return redirect()->route('admin.profile.index')->with('status', 'profile-updated');
    }
}