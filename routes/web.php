<?php

use App\Http\Controllers\Admin\BookingDevoteeController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\DeityController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\FestivalController;
use App\Http\Controllers\Admin\FestivalPujaController;
use App\Http\Controllers\Admin\PackageAddonController;
use App\Http\Controllers\Admin\PackageFeatureController;
use App\Http\Controllers\Admin\PanditController;
use App\Http\Controllers\Admin\PanditPujaController;
use App\Http\Controllers\Admin\PujaAssignmentController;
use App\Http\Controllers\Admin\PujaBookingController;
use App\Http\Controllers\Admin\PujaCategoryController;
use App\Http\Controllers\Admin\PujaController;
use App\Http\Controllers\Admin\PujaPackageController;
use App\Http\Controllers\Admin\PujaScheduleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\TempleController;
use App\Http\Controllers\Admin\TempleDeityController;
use App\Http\Controllers\Admin\UserController;
use App\Models\PujaAssignment;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //roles
    Route::resource('roles', RoleController::class);
    Route::get('roles/{role}/permissions-data', [RoleController::class, 'getPermissionsData'])->name('roles.permissions.data');
    Route::get('roles/{role}/permissions', [RoleController::class, 'managePermissions'])->name('roles.permissions');
    Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');

    //users
    Route::resource('users', UserController::class)->names('admin.users');
    //Country
    Route::resource('country',CountryController::class)->names('countries');
    //State
    Route::resource('states',StateController::class)->names('states');
    //Districts
    Route::resource('districts', DistrictController::class)->names('districts');
    Route::get('districts/get-states/{country}',[DistrictController::class, 'getStates'])->name('districts.get-states');
    //Cities
    Route::resource('cities', CityController::class);
    Route::get('cities/get-states/{country}',[CityController::class, 'getStates'])->name('cities.get-states');
    Route::get('cities/get-districts/{state}',[CityController::class, 'getDistricts'])->name('cities.get-districts');
    //Temples
    Route::resource('temples', TempleController::class);
    Route::get('temples/get-districts/{state}',[TempleController::class, 'getDistricts'])->name('temples.get-districts');
    Route::get('temples/get-cities/{district}',[TempleController::class, 'getCities'])->name('temples.get-cities');
    //Deities
    Route::resource('deities', DeityController::class);
    //TempleDeities
    Route::resource('temple-deities', TempleDeityController::class);
    //PujaCategories
    Route::resource('puja-categories', PujaCategoryController::class);
    //Puja
    Route::resource('pujas',PujaController::class);
    Route::resource('puja-packages', PujaPackageController::class);
    Route::resource('package-features', PackageFeatureController::class);
    Route::resource('package-addons', PackageAddonController::class);
    Route::resource('puja-schedules', PujaScheduleController::class);
    Route::resource('festivals', FestivalController::class);
    Route::resource('festival-pujas', FestivalPujaController::class);
    Route::resource('pandits', PanditController::class);
    Route::resource('pandit-pujas', PanditPujaController::class);
    Route::resource('puja-assignments', PujaAssignmentController::class);
    Route::resource('puja-bookings', PujaBookingController::class);
    Route::resource('booking-devotees', BookingDevoteeController::class);
});

require __DIR__.'/auth.php';
