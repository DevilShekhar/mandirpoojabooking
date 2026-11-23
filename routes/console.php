
<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Automatically mark yesterday's missed ticket bookings
Schedule::command('ticket-bookings:mark-not-reached')
    ->dailyAt('01:00')
    ->timezone('Asia/Kolkata')
    ->withoutOverlapping();
// Automatically send Mandir Puja OTP reminder emails
Schedule::command('mandir-puja:not-reached')
    ->dailyAt('01:00')
    ->timezone('Asia/Kolkata')
    ->withoutOverlapping();
// Automatically send Sanatan Puja not-completed emails
Schedule::command('sanatan-orders:not-completed')
    ->dailyAt('15:08')
    ->timezone('Asia/Kolkata')
    ->withoutOverlapping();
