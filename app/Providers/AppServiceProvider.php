<?php

namespace App\Providers;

use App\Models\Puja;
use App\Models\Temple;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        View::share('headerTemples', Temple::where('status', 1)
            ->select('id', 'name', 'slug')
            ->orderBy('name')
            ->get());

        View::share('headerPujas', Puja::where('status', 1)
            ->select('id', 'temple_id', 'name', 'slug')
            ->orderBy('name')
            ->get());
    }
}