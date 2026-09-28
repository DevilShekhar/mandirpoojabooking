<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageAddon;
use App\Models\PujaPackage;
use Illuminate\Http\Request;

class PackageAddonController extends Controller
{
    public function index()
    {
        $packageAddons = PackageAddon::with('pujaPackage')
            ->latest()
            ->get();

        return view('admin.package-addons.index', compact('packageAddons'));
    }

    public function create()
    {
        $pujaPackages = PujaPackage::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.package-addons.create', compact('pujaPackages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'puja_package_id' => 'required|exists:puja_packages,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'max_quantity' => 'required|integer|min:1',
        ]);

        $image = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('package_addons', 'public');
        }

        PackageAddon::create([
            'puja_package_id' => $request->puja_package_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $image,
            'max_quantity' => $request->max_quantity,
            'status' => 1,
        ]);

        return redirect()
            ->route('package-addons.index')
            ->with('success', 'Package Addon created successfully.');
    }

    public function show(PackageAddon $packageAddon)
    {
        $packageAddon->load('pujaPackage');

        return view('admin.package-addons.show', compact('packageAddon'));
    }

    public function edit(PackageAddon $packageAddon)
    {
        $pujaPackages = PujaPackage::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.package-addons.edit', compact('packageAddon', 'pujaPackages'));
    }

    public function update(Request $request, PackageAddon $packageAddon)
    {
        $request->validate([
            'puja_package_id' => 'required|exists:puja_packages,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'max_quantity' => 'required|integer|min:1',
            'status' => 'required|boolean',
        ]);

        $data = [
            'puja_package_id' => $request->puja_package_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'max_quantity' => $request->max_quantity,
            'status' => $request->status,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('package_addons', 'public');
        }

        $packageAddon->update($data);

        return redirect()
            ->route('package-addons.index')
            ->with('success', 'Package Addon updated successfully.');
    }

    public function destroy(PackageAddon $packageAddon)
    {
        $packageAddon->update([
            'status' => 0,
        ]);

        return redirect()
            ->route('package-addons.index')
            ->with('success', 'Package Addon deactivated successfully.');
    }
}
