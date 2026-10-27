<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = $permissions = [

            // Roles
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.index',
            'roles.delete',

            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.index',
            'users.delete',

            // Countries
            'countries.view',
            'countries.create',
            'countries.edit',
            'countries.index',
            'countries.delete',

            // States
            'states.view',
            'states.create',
            'states.edit',
            'states.index',
            'states.delete',

            // Districts
            'districts.view',
            'districts.create',
            'districts.edit',
            'districts.index',
            'districts.delete',

            // Cities
            'cities.view',
            'cities.create',
            'cities.edit',
            'cities.index',
            'cities.delete',

            // Temples
            'temples.view',
            'temples.create',
            'temples.edit',
            'temples.index',
            'temples.delete',

            // Deities
            'deities.view',
            'deities.create',
            'deities.edit',
            'deities.index',
            'deities.delete',

            // Temple Deities
            'temple-deities.view',
            'temple-deities.create',
            'temple-deities.edit',
            'temple-deities.index',
            'temple-deities.delete',

            // Puja Categories
            'puja-categories.view',
            'puja-categories.create',
            'puja-categories.edit',
            'puja-categories.index',
            'puja-categories.delete',

            // Pujas - ith parynat add kelay
            'pujas.view',
            'pujas.create',
            'pujas.edit',
            'pujas.index',
            'pujas.delete',

            // Puja Packages
            'puja-packages.view',
            'puja-packages.create',
            'puja-packages.edit',
            'puja-packages.index',
            'puja-packages.delete',

            // Package Features
            'package-features.view',
            'package-features.create',
            'package-features.edit',
            'package-features.index',
            'package-features.delete',

            // Package Addons
            'package-addons.view',
            'package-addons.create',
            'package-addons.edit',
            'package-addons.index',
            'package-addons.delete',

            // Puja Schedules
            'puja-schedules.view',
            'puja-schedules.create',
            'puja-schedules.edit',
            'puja-schedules.index',
            'puja-schedules.delete',

            // Festivals
            'festivals.view',
            'festivals.create',
            'festivals.edit',
            'festivals.index',
            'festivals.delete',

            // Festival Pujas
            'festival-pujas.view',
            'festival-pujas.create',
            'festival-pujas.edit',
            'festival-pujas.index',
            'festival-pujas.delete',

            // Pandits
            'pandits.view',
            'pandits.create',
            'pandits.edit',
            'pandits.index',
            'pandits.delete',

            // Pandit Pujas
            'pandit-pujas.view',
            'pandit-pujas.create',
            'pandit-pujas.edit',
            'pandit-pujas.index',
            'pandit-pujas.delete',

            // Puja Assignments
            'puja-assignments.view',
            'puja-assignments.create',
            'puja-assignments.edit',
            'puja-assignments.index',
            'puja-assignments.delete',

            // Puja Bookings
            'puja-bookings.view',
            'puja-bookings.create',
            'puja-bookings.edit',
            'puja-bookings.index',
            'puja-bookings.delete',

            // Booking Devotees
            'booking-devotees.view',
            'booking-devotees.create',
            'booking-devotees.edit',
            'booking-devotees.index',
            'booking-devotees.delete',

            // Booking Sankalps
            'booking-sankalps.view',
            'booking-sankalps.create',
            'booking-sankalps.edit',
            'booking-sankalps.index',
            'booking-sankalps.delete',

            // Home Banners
            'home-banners.view',
            'home-banners.create',
            'home-banners.edit',
            'home-banners.index',
            'home-banners.delete',
            'puja-bookings-my.view',
            'puja-bookings-order.view',
            'customer-cancellled-bookings-order.view',
            'my-cancelled-order.view',
            'all-templae-register.list',
            'view-event-bookings',
            'view-event-calendar',
            'puja-review-rating',
            'santan-event-view',

            // Ticket Prices
            'ticket-prices.view',
            'ticket-prices.create',
            'ticket-prices.edit',
            'ticket-prices.index',
            'ticket-prices.delete',

            'temple-daily-ticket-limits.view',
            'temple-daily-ticket-limits.create',
            'temple-daily-ticket-limits.edit',
            'temple-daily-ticket-limits.index',
            'temple-daily-ticket-limits.delete',
            // Ticket Booking
            'my-tickit-booking.view',
            'all-tickit-booking.view',
            'my-tickit-booking.index',
            'all-tickit-booking.index',
            'my-tickit-booking.details',
            'all-tickit-booking.details',

        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}