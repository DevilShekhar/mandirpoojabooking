<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Puja;
use App\Models\PujaPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PujaPackageController extends Controller
{
    public function index()
    {
        $packages = PujaPackage::with('puja')
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view('admin.puja-packages.index', compact('packages'));
    }

    public function create()
    {
        $pujas = Puja::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.puja-packages.create', compact('pujas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'puja_id' => 'required|exists:pujas,id',
            'name' => 'required|string|max:255',
            'person_count' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:price',
            'package_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $image = null;

        if ($request->hasFile('package_image')) {
            $image = $request->file('package_image')
                ->store('puja_packages', 'public');
        }

        PujaPackage::create([
            'puja_id' => $request->puja_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'person_count' => $request->person_count,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'package_image' => $image,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'includes_sankalp' => $request->has('includes_sankalp'),
            'includes_prasad' => $request->has('includes_prasad'),
            'includes_video' => $request->has('includes_video'),
            'includes_certificate' => $request->has('includes_certificate'),
            'is_featured' => $request->has('is_featured'),
            'sort_order' => $request->sort_order ?? 0,
            'status' => 1,  
        ]);

        return redirect()
            ->route('puja-packages.index')
            ->with('success', 'Puja Package created successfully.');
    }

    public function edit(PujaPackage $pujaPackage)
    {
        $pujas = Puja::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.puja-packages.edit', compact('pujaPackage', 'pujas'));
    }

    public function update(Request $request, PujaPackage $pujaPackage)
    {
        $request->validate([
            'puja_id' => 'required|exists:pujas,id',
            'name' => 'required|string|max:255',
            'person_count' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:price',
            'package_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = [
            'puja_id' => $request->puja_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'person_count' => $request->person_count,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'includes_sankalp' => $request->has('includes_sankalp'),
            'includes_prasad' => $request->has('includes_prasad'),
            'includes_video' => $request->has('includes_video'),
            'includes_certificate' => $request->has('includes_certificate'),
            'is_featured' => $request->has('is_featured'),
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status ?? 1,
        ];

        if ($request->hasFile('package_image')) {
            $data['package_image'] = $request->file('package_image')
                ->store('puja_packages', 'public');
        }

        $pujaPackage->update($data);

        return redirect()
            ->route('puja-packages.index')
            ->with('success', 'Puja Package updated successfully.');
    }

    public function destroy(PujaPackage $pujaPackage)
    {
        $pujaPackage->update([
            'status' => 0,
        ]);

        return redirect()
            ->route('puja-packages.index')
            ->with('success', 'Puja Package deactivated successfully.');
    }
    public function show(PujaPackage $pujaPackage)
    {
        $pujaPackage->load('puja');
        return view('admin.puja-packages.show', compact('pujaPackage'));
    }
}
