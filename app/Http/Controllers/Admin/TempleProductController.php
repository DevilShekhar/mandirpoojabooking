<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Temple;
use App\Models\TempleProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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

    private function allowedUnits(): array
    {
        return [
            'piece',
            'packet',
            'pack',
            'box',
            'ml',
            'litre',
            'gram',
            'kg',
            'mg',
            'metre',
            'cm',
            'roll',
            'kit',
            'dozen',
            'pair',
            'set',
            'bottle',
            'jar',
            'bag',
            'bundle',
            'mala',
            'garland',
            'offering',
            'plate',
            'bowl',
            'diya',
            'stick',
            'bunch',
            'basket',
            'thali',
            'pouch',
            'container',
            'can',
            'sachet',
            'tube',
            'string',
            'leaf',
            'flower',
            'coconut',
            'per_person',
            'per_day',
        ];
    }

    private function generateSku(Temple $temple): string
    {
        $prefix = Str::upper(Str::slug($temple->name)) ?: 'TEMPLE';
        $skuPrefix = $prefix . '-';

        $existingSkus = TempleProduct::where('temple_id', $temple->id)
            ->where('sku', 'like', $skuPrefix . '%')
            ->pluck('sku');

        $maxNumber = 0;

        foreach ($existingSkus as $existingSku) {
            if (preg_match('/^' . preg_quote($skuPrefix, '/') . '(\d+)$/i', $existingSku, $matches)) {
                $maxNumber = max($maxNumber, (int) $matches[1]);
            }
        }

        return $skuPrefix . str_pad((string) ($maxNumber + 1), 4, '0', STR_PAD_LEFT);
    }

    private function findProduct(int $id): TempleProduct
    {
        return TempleProduct::where('temple_id', $this->templeId())
            ->where('created_by', auth()->id())
            ->findOrFail($id);
    }

    public function index()
    {
        $templeId = $this->templeId();

        $products = TempleProduct::where('temple_id', $templeId)
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('temple_products', 'name')->where('temple_id', $templeId),
            ],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'qty' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', Rule::in($this->allowedUnits())],
            'status' => ['required', 'boolean'],
        ], [
            'name.unique' => 'This product name already exists for your temple.',
            'qty.required' => 'Please enter the product quantity.',
            'qty.integer' => 'Quantity must be a whole number.',
            'qty.min' => 'Quantity cannot be negative.',
            'unit.required' => 'Please select a product unit.',
            'unit.in' => 'Please select a valid product unit.',
        ]);

        DB::transaction(function () use ($templeId, $validated) {
            $temple = Temple::whereKey($templeId)->lockForUpdate()->firstOrFail();

            $validated['sku'] = $this->generateSku($temple);
            $validated['temple_id'] = $templeId;
            $validated['created_by'] = auth()->id();
            $validated['updated_by'] = auth()->id();

            TempleProduct::create($validated);
        });

        return redirect()
            ->route('admin.temple-products.index')
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('temple_products', 'name')
                    ->where('temple_id', $product->temple_id)
                    ->ignore($product->id),
            ],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'qty' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', Rule::in($this->allowedUnits())],
            'status' => ['required', 'boolean'],
        ], [
            'name.unique' => 'This product name already exists for your temple.',
            'qty.required' => 'Please enter the product quantity.',
            'qty.integer' => 'Quantity must be a whole number.',
            'qty.min' => 'Quantity cannot be negative.',
            'unit.required' => 'Please select a product unit.',
            'unit.in' => 'Please select a valid product unit.',
        ]);

        $validated['updated_by'] = auth()->id();

        $product->update($validated);

        return redirect()
            ->route('admin.temple-products.index')
            ->with('success', 'Temple product updated successfully.');
    }

    public function destroy(int $id)
    {
        $product = $this->findProduct($id);
        $product->delete();

        return redirect()
            ->route('admin.temple-products.index')
            ->with('success', 'Temple product deleted successfully.');
    }
}
