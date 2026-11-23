<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\SanatanOrderNotCompleted;
use Carbon\Carbon;
use Throwable;

class SendSanatanOrdersNotCompleted extends Command
{
    protected $signature = 'sanatan-orders:not-completed';

    protected $description = 'Send emails for confirmed Sanatan puja bookings that were not completed';

    public function handle()
    {
        $today = Carbon::today('Asia/Kolkata')->toDateString();

        $orders = DB::table('sanatan_event_orders as orders')

            // Customer details
            ->join('users as customers', 'customers.id', '=', 'orders.user_id')

            // Event details
            ->join('sanatans as sanatan', 'sanatan.id', '=', 'orders.sanatan_id')

            // Event creator
            ->leftJoin('users as creators', 'creators.id', '=', 'sanatan.created_by')

            // Booking conditions
            ->where('orders.booking_status', 'confirmed')
            ->where('orders.payment_status', 'paid')
            ->whereNotNull('orders.event_date')
            ->whereDate('orders.event_date', '<', $today)
            ->whereNull('orders.sanatan_otp')
            ->whereNull('orders.completed_at')
            ->whereNull('orders.not_completed_email_sent_at')

            ->select(
                'orders.*',

                // Customer details
                'customers.name as customer_name',
                'customers.email as customer_email',

                // Event details
                'sanatan.name as event_name',
                'sanatan.created_by as event_created_by',

                // Event creator details
                'creators.name as creator_name'
            )

            ->orderBy('orders.event_date', 'asc')
            ->get();

        $sent = 0;
        $failed = 0;
        $skipped = 0;

        if ($orders->isEmpty()) {
            $this->info('No pending Sanatan orders found.');

            return Command::SUCCESS;
        }

        foreach ($orders as $order) {
            try {

                // Check customer email
                if (empty($order->customer_email)) {
                    $skipped++;

                    Log::warning('Sanatan email skipped: Customer email missing', [
                        'order_id' => $order->id,
                    ]);

                    continue;
                }

                // Send email
                Mail::to($order->customer_email)
                    ->send(new SanatanOrderNotCompleted($order));

                // Update booking status and email sent timestamp
                DB::table('sanatan_event_orders')
                    ->where('id', $order->id)
                    ->whereNull('not_completed_email_sent_at')
                    ->update([
                        'booking_status' => 'not_completed',
                        'not_completed_email_sent_at' => Carbon::now('Asia/Kolkata'),
                        'updated_at' => now(),
                    ]);

                $sent++;

                Log::info('Sanatan not completed email sent and booking status updated', [
                    'order_id' => $order->id,
                    'customer_email' => $order->customer_email,
                    'event_name' => $order->event_name,
                    'booking_status' => 'not_completed',
                ]);

            } catch (Throwable $e) {

                $failed++;

                Log::error('Sanatan not completed email failed', [
                    'order_id' => $order->id,
                    'customer_email' => $order->customer_email ?? null,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->newLine();

        $this->info("Total orders found: {$orders->count()}");
        $this->info("Emails sent: {$sent}");
        $this->warn("Skipped: {$skipped}");
        $this->error("Failed: {$failed}");

        return Command::SUCCESS;
    }
}