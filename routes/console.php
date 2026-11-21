
<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// TEST: Ticket Booking Not Reached
Schedule::command('ticket-bookings:mark-not-reached')
    ->everyMinute()
    ->timezone('Asia/Kolkata')
    ->withoutOverlapping();

// TEST: Mandir Puja Not Reached
Schedule::command('mandir-puja:not-reached')
    ->everyMinute()
    ->timezone('Asia/Kolkata')
    ->withoutOverlapping();
