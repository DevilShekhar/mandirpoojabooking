<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TempleProduct;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TempleProductController extends Controller
{
    private function templeId(): int
    {
        $user = auth()->user();

        abort_unless($user && $user->hasRole('Store Manager'), 403);
        abort_unless($user->temple_id, 403, 'No temple is assigned to your account.');

        return (int) $user->temple_id;
    }

    private function findProduct(int $id): TempleProduct
    {
        return TempleProduct::where('temple_id', $this->templeId())
            ->where('created_by', auth()->id())
            ->findOrFail($id);
    }

    public function index()
    {
        $products = TempleProduct::where('temple_id', $this->templeId())
            ->where('created_by', auth()->id())
            ->latest()
            ->paginate(10);

        return view('admin.temple-products.index', compact('products'));
    }

    public function create()
    {
        $this->templeId();

        return view('admin.temple-products.create');
    }

    public function store(Request $request)
    {
        $templeId = $this->templeId();

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique('temple_products', 'name')->where('temple_id', $templeId)],
            'sku'         => ['nullable', 'string', 'max:100', Rule::unique('temple_products', 'sku')->where('temple_id', $templeId)],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'status'      => ['sometimes', 'boolean'],
        ], [
            'name.unique' => 'This product name already exists for your temple.',
            'sku.unique'  => 'This SKU already exists for your temple.',
        ]);

        $validated['temple_id']  = $templeId;
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();
        $validated['status']     = $request->boolean('status', true);

        TempleProduct::create($validated);

        return redirect()->route('admin.temple-products.index')
            ->with('success', 'Temple product created successfully.');
    }

    public function show(int $id)
    {
        $product = $this->findProduct($id);

        return view('admin.temple-products.show', compact('product'));
    }

    public function edit(int $id)
    {
        $product = $this->findProduct($id);

        return view('admin.temple-products.edit', compact('product'));
    }

    public function update(Request $request, int $id)
    {
        $product = $this->findProduct($id);

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique('temple_products', 'name')->where('temple_id', $product->temple_id)->ignore($product->id)],
            'sku'         => ['nullable', 'string', 'max:100', Rule::unique('temple_products', 'sku')->where('temple_id', $product->temple_id)->ignore($product->id)],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'status'      => ['required', 'boolean'],
        ], [
            'name.unique' => 'This product name already exists for your temple.',
            'sku.unique'  => 'This SKU already exists for your temple.',
        ]);

        $validated['updated_by'] = auth()->id();

        $product->update($validated);

        return redirect()->route('admin.temple-products.index')
            ->with('success', 'Temple product updated successfully.');
    }

    public function destroy(int $id)
    {
        $product = $this->findProduct($id);
        $product->delete();

        return redirect()->route('admin.temple-products.index')
            ->with('success', 'Temple product deleted successfully.');
    }
}