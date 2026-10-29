<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PujaPackage;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $validated = $request->validate([
            'package_id' => ['required', 'integer', 'min:1'],
            'product_ids' => ['sometimes', 'array'],
            'product_ids.*' => ['integer', 'distinct', 'min:1'],
        ]);

        $package = PujaPackage::query()
            ->where('status', 1)
            ->with([
                'puja.temple.city',
                'puja.temple.district',
                'puja.temple.state',
                'puja.deity',
                'puja.pujaCategory',
            ])
            ->findOrFail((int) $validated['package_id']);

        $puja = $package->puja;

        abort_unless($puja && (int) $puja->status === 1, 404);

        $snapshot = $package->products;

        if (is_string($snapshot)) {
            $snapshot = json_decode($snapshot, true);
        }

        $snapshot = is_array($snapshot) ? $snapshot : [];

        $packageProducts = collect($snapshot)->map(function ($item) {
            $item = is_array($item) ? $item : [];
            $price = max(0, (float) ($item['price'] ?? 0));
            $quantity = max(1, (int) ($item['quantity'] ?? 1));
            $productId = (int) ($item['product_id'] ?? 0);

            return [
                'product_id' => $productId > 0 ? $productId : null,
                'name' => (string) ($item['name'] ?? 'Puja Samagri'),
                'unit' => (string) ($item['unit'] ?? 'Item'),
                'price' => $price,
                'quantity' => $quantity,
                'line_total' => round($price * $quantity, 2),
            ];
        })->values();

        $selectedIds = collect($validated['product_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $availableIds = $packageProducts
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->values();

        abort_if(
            $selectedIds->diff($availableIds)->isNotEmpty(),
            422,
            'A selected product does not belong to this package.'
        );

        $selectedProducts = $packageProducts
            ->filter(fn ($product) => $product['product_id'] !== null
                && $selectedIds->contains($product['product_id']))
            ->values();

        $originalPrice = max(0, (float) $package->price);
        $discountPrice = $package->discount_price;

        $hasDiscount = $discountPrice !== null
            && $discountPrice !== ''
            && (float) $discountPrice >= 0
            && (float) $discountPrice < $originalPrice;

        $allProductTotal = (float) $packageProducts->sum('line_total');
        $basePackagePrice = max(0, $originalPrice - $allProductTotal);

        $discountFactor = $hasDiscount && $originalPrice > 0
            ? (float) $discountPrice / $originalPrice
            : 1;

        $finalPackagePrice = round($basePackagePrice * $discountFactor, 2);
        $selectedProductsTotal = round(
            (float) $selectedProducts->sum('line_total'),
            2
        );
        $total = round($finalPackagePrice + $selectedProductsTotal, 2);

        $personCount = (int) ($package->person_count ?? 1);

        return view('frontend.checkout.checkout', compact(
            'puja',
            'package',
            'packageProducts',
            'selectedProducts',
            'selectedIds',
            'basePackagePrice',
            'finalPackagePrice',
            'selectedProductsTotal',
            'total',
            'hasDiscount',
            'originalPrice',
            'personCount'
        ));
    }
}
