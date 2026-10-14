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
            'package_id' => 'required|integer',
        ]);

        $package = PujaPackage::with([
            'puja.temple.city',
            'puja.temple.district',
            'puja.temple.state',
            'puja.deity',
            'puja.pujaCategory',
        ])->findOrFail($validated['package_id']);

        $puja = $package->puja;

        abort_unless(
            $puja && (int) $puja->status === 1,
            404
        );

        return view(
            'frontend.checkout.checkout',
            compact('puja', 'package')
        );
    }
}
