<?php

use App\Http\Controllers\Admin\BookingDevoteeController;
use App\Http\Controllers\Admin\BookingSankalpController;
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
use App\Http\Controllers\Admin\PasswordController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\HomeBannerController;
use App\Http\Controllers\Admin\PujaReviewController;
use App\Models\PujaAssignment;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\OurTemplesContoller;
use App\Http\Controllers\Frontend\OurPujaController;
use App\Http\Controllers\Admin\SanatanController;
use App\Http\Controllers\Frontend\SanatanEventsController;
use App\Http\Controllers\Frontend\PaymentSanatanController;
use App\Http\Controllers\Auth\CustomerRegistrationController;
use App\Http\Controllers\Frontend\TempleRegistrationController;
use App\Http\Controllers\Admin\TempleRegistrationListController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\RazorpayPaymentController;
use App\Http\Controllers\Admin\CustomerBookingCancellationController;
use App\Http\Controllers\Admin\SanatanEventBookingController;
use App\Http\Controllers\Admin\SanatanEventCalendarController;

Route::get('/', [HomeController::class, 'index'])->name('frontend.home');
Route::get('our-temples', [OurTemplesContoller::class, 'index'])->name('frontend.our-temples');
Route::get('our-temples/{temple:slug}', [OurTemplesContoller::class, 'show'])->name('frontend.our-temple.show');
Route::get('/puja', [OurPujaController::class, 'index'])->name('frontend.puja');
Route::post('/puja/filter', [OurPujaController::class, 'filter'])->name('frontend.puja.filter');
Route::get('/puja/{slug}', [OurPujaController::class, 'show'])->name('frontend.puja.show');
Route::post('/our-puja/{pujaId}/review', [OurPujaController::class, 'storeReview'])->name('our-puja.review.store');
Route::get('/sanatanevents/type/{typeSlug}', [SanatanEventsController::class,'type'])->name('frontend.sanatanevents.type');
Route::get('/sanatanevents', [SanatanEventsController::class,'index'])->name('frontend.sanatanevents');
Route::get('/sanatanevents/{slug}/buy', [SanatanEventsController::class,'buy'])->name('frontend.sanatanevents.buy');
Route::get('/sanatanevents/{slug}/checkout', [SanatanEventsController::class,'checkout'])->middleware('auth')->name('frontend.sanatanevents.checkout');
Route::post('/sanatanevents/{slug}/payment/order', [PaymentSanatanController::class,'createOrder'])->middleware('auth')->name('frontend.sanatanevents.payment.order');
Route::post('/sanatanevents/payment/verify', [PaymentSanatanController::class,'verifyPayment'])->middleware('auth')->name('frontend.sanatanevents.payment.verify');
Route::get('/sanatanevents/booking/success', [PaymentSanatanController::class,'bookingSuccess'])->middleware('auth')->name('frontend.sanatanevents.booking.success');
Route::get('/sanatanevents/booking/failed', [PaymentSanatanController::class,'bookingFailed'])->name('frontend.sanatanevents.booking.failed');
Route::get('/sanatan-event-bookings', [SanatanEventBookingController::class, 'index'])->name('customer.sanatan-event-bookings.index');
Route::get('/sanatan-event-bookings/{id}', [SanatanEventBookingController::class, 'show'])->whereNumber('id')->name('customer.sanatan-event-bookings.show');
Route::post('/sanatan-event-bookings/{id}/cancel', [SanatanEventBookingController::class, 'cancel'])->whereNumber('id')->name('customer.sanatan-event-bookings.cancel');
Route::get('sanatan-events-calendar', [SanatanEventCalendarController::class, 'index'])->name('admin.sanatan-events-calendar.index');
Route::get('/sanatanevents/{slug}', [SanatanEventsController::class,'show'])->name('frontend.sanatanevents.show');
Route::get('/customer-registration', [CustomerRegistrationController::class, 'create'])->name('customer.register');
Route::post('/customer-registration', [CustomerRegistrationController::class, 'store'])->name('customer.register.store');
Route::get('/auth/google/redirect', [ CustomerRegistrationController::class,'redirectToGoogle',])->name('auth.google.redirect');
Route::get('/auth/google/callback', [CustomerRegistrationController::class,'handleGoogleCallback',])->name('auth.google.callback');
Route::get('/temple-register', [TempleRegistrationController::class, 'create'])->name('temple.register');
Route::post('/temple-register', [TempleRegistrationController::class, 'store'])->name('temple.register.store');
Route::get('/temple-register/get-states/{country}', [TempleRegistrationController::class,'getStates',])->name('temple.register.states');
Route::get('/temple-register/get-districts/{state}', [TempleRegistrationController::class,'getDistricts',])->name('temple.register.districts');
Route::get('/temple-register/get-cities/{district}', [TempleRegistrationController::class,'getCities',])->name('temple.register.cities');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Home Banners
    Route::resource('home-banners', HomeBannerController::class);
    Route::get('/profile', [ProfileController::class, 'index']) ->name('admin.profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit']) ->name('admin.profile.edit'); 
    Route::put('/profile', [ProfileController::class, 'update']) ->name('admin.profile.update');
    Route::get('/change-password', [PasswordController::class, 'index'])->name('password.index');
    Route::post('/change-password/verify', [PasswordController::class, 'verifyOldPassword'])->name('password.verify');
    Route::get('/change-password/new', [PasswordController::class, 'newPassword'])->name('password.new');
    Route::post('/change-password/update', [PasswordController::class, 'update'])->name('password.update');

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
    Route::resource('booking-puja-order', PujaBookingController::class);
    Route::resource('booking-devotees', BookingDevoteeController::class);
    Route::resource('booking-sankalps', BookingSankalpController::class);
    Route::get('/puja-reviews',[PujaReviewController::class, 'index'])->name('admin.puja-reviews.index');
    Route::post('/puja-reviews/{id}/approve',[PujaReviewController::class, 'approve'])->name('admin.puja-reviews.approve');
    Route::get('booking-puja-order-successful',[PujaBookingController::class, 'successful'])->name('booking-puja-order.successful');
    Route::get('booking-puja-order-failed',[PujaBookingController::class, 'failed'])->name('booking-puja-order.failed');
    Route::get('booking-puja-order-cancelled',[PujaBookingController::class, 'cancelled'])->name('booking-puja-order.cancelled');
    Route::post('/puja-reviews/{id}/reject',[PujaReviewController::class, 'reject'])->name('admin.puja-reviews.reject');
    Route::resource('sanatan-events', SanatanController::class)->names('admin.sanatan-events')->parameters(['sanatan-events' => 'sanatan']);
    Route::get('/all-templae-register', [TempleRegistrationListController::class,'index'])->name('all-templae-register');
    Route::post('/all-templae-register/{registrationId}/approve',[TempleRegistrationListController::class, 'approve'])->name('all-templae-register.approve');
    Route::post('/all-templae-register/{registrationId}/reject',[TempleRegistrationListController::class, 'reject'])->name('all-templae-register.reject');
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('frontend.checkout');
    Route::get('/customer-cancel-booking', [CustomerBookingCancellationController::class, 'customerIndex'])->name('customer-cancel-booking.index');
    Route::post('/customer-cancel-booking/{booking}', [CustomerBookingCancellationController::class, 'store'])->name('customer-cancel-booking.store');
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/customer-cancel-booking', [CustomerBookingCancellationController::class, 'staffIndex'])->name('customer-cancel-booking.index');
        Route::get('/customer-cancel-booking/{cancellation}', [CustomerBookingCancellationController::class, 'show'])->name('customer-cancel-booking.show');
        Route::post('/customer-cancel-booking/{cancellation}/approve', [CustomerBookingCancellationController::class, 'approve'])->name('customer-cancel-booking.approve');
        Route::post('/customer-cancel-booking/{cancellation}/reject', [CustomerBookingCancellationController::class, 'reject'])->name('customer-cancel-booking.reject');
    });
});
Route::middleware('auth')->prefix('payment/razorpay')->name('razorpay.')->group(function () {
    Route::post('/create-order', [RazorpayPaymentController::class, 'createOrder'])->name('create-order');
    Route::post('/verify', [RazorpayPaymentController::class, 'verifyPayment'])->name('verify');
    Route::get('/success/{payment}', [RazorpayPaymentController::class, 'success'])->name('success');
    Route::post('/failed', [RazorpayPaymentController::class, 'failed'])->name('failed');
    Route::get('/failed/{payment}', [RazorpayPaymentController::class, 'failedPage'])->name('failed-page');
    
    
});
require __DIR__.'/auth.php';
