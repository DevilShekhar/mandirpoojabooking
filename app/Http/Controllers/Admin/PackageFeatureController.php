<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageFeature;
use App\Models\PujaPackage;
use Illuminate\Http\Request;

class PackageFeatureController extends Controller
{
    public function index()
    {
        $packageFeatures = PackageFeature::with('pujaPackage')
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view('admin.package-features.index', compact('packageFeatures'));
    }

    public function create()
    {
        $pujaPackages = PujaPackage::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.package-features.create', compact('pujaPackages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'puja_package_id' => 'required|exists:puja_packages,id',
            'feature_name' => 'required|string|max:255',
            'feature_value' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        PackageFeature::create([
            'puja_package_id' => $request->puja_package_id,
            'feature_name' => $request->feature_name,
            'feature_value' => $request->feature_value,
            'sort_order' => $request->sort_order ?? 0,
            'status' => 1,
        ]);

        return redirect()
            ->route('package-features.index')
            ->with('success', 'Package feature created successfully.');
    }
    public function edit(PackageFeature $packageFeature)
    {
        $pujaPackages = PujaPackage::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.package-features.edit', compact(
            'packageFeature',
            'pujaPackages'
        ));
    }

    public function update(Request $request, PackageFeature $packageFeature)
    {
        $request->validate([
            'puja_package_id' => 'required|exists:puja_packages,id',
            'feature_name' => 'required|string|max:255',
            'feature_value' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
        ]);

        $packageFeature->update([
            'puja_package_id' => $request->puja_package_id,
            'feature_name' => $request->feature_name,
            'feature_value' => $request->feature_value,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('package-features.index')
            ->with('success', 'Package feature updated successfully.');
    }

    public function destroy(PackageFeature $packageFeature)
    {
        $packageFeature->update([
            'status' => 0,
        ]);

        return redirect()
            ->route('package-features.index')
            ->with('success', 'Package feature deactivated successfully.');
    }
}
