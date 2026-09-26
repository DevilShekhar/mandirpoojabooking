<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pandit;
use App\Models\Temple;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PanditController extends Controller
{
    public function index()
    {
        $pandits = Pandit::with('temple')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.pandits.index', compact('pandits'));
    }

    public function create()
    {
        $temples = Temple::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.pandits.create', compact('temples'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'temple_id' => 'nullable|exists:temples,id',
            'experience' => 'required|integer|min:0',
            'languages' => 'nullable|string',
            'specialization' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'verification_status' => 'required|in:pending,verified,rejected',
            'status' => 'required|boolean',
        ]);

        $profileImage = null;

        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image')
                ->store('pandit_profiles', 'public');
        }

        Pandit::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'profile_image' => $profileImage,
            'experience' => $request->experience,
            'languages' => $request->languages,
            'specialization' => $request->specialization,
            'temple_id' => $request->temple_id,
            'verification_status' => $request->verification_status,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('pandits.index')
            ->with('success', 'Pandit created successfully.');
    }

    public function show(Pandit $pandit)
    {
        $pandit->load([
            'temple',
            'panditPujas.puja'
        ]);

        return view('admin.pandits.show', compact('pandit'));
    }

    public function edit(Pandit $pandit)
    {
        $temples = Temple::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.pandits.edit', compact('pandit', 'temples'));
    }

    public function update(Request $request, Pandit $pandit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'temple_id' => 'nullable|exists:temples,id',
            'experience' => 'required|integer|min:0',
            'languages' => 'nullable|string',
            'specialization' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'verification_status' => 'required|in:pending,verified,rejected',
            'status' => 'required|boolean',
        ]);

        $profileImage = $pandit->profile_image;

        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image')
                ->store('pandit_profiles', 'public');
        }

        $pandit->update([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'profile_image' => $profileImage,
            'experience' => $request->experience,
            'languages' => $request->languages,
            'specialization' => $request->specialization,
            'temple_id' => $request->temple_id,
            'verification_status' => $request->verification_status,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('pandits.index')
            ->with('success', 'Pandit updated successfully.');
    }

    public function destroy(Pandit $pandit)
    {
        $pandit->update([
            'status' => 0
        ]);

        return redirect()
            ->route('pandits.index')
            ->with('success', 'Pandit deactivated successfully.');
    }
}
