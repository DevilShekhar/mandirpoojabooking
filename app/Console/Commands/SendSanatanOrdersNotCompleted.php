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
        $today = Carbon::now('Asia/Kolkata')->toDateString();

        $this->info("Checking Sanatan orders for date: {$today}");

        try {

            $orders = DB::table('sanatan_event_orders as orders')

                ->join('users as customers', 'customers.id', '=', 'orders.user_id')

                ->join('sanatans as sanatan', 'sanatan.id', '=', 'orders.sanatan_id')

                ->leftJoin('users as creators', 'creators.id', '=', 'sanatan.created_by')

                // Booking conditions
                ->where('orders.booking_status', 'confirmed')
                ->where('orders.payment_status', 'paid')

                // Event date must be before today
                ->whereNotNull('orders.event_date')
                ->whereDate('orders.event_date', '<', $today)

                // OTP must NOT be verified
                ->whereNull('orders.otp_verifiction_at')

                // Booking must NOT be completed
                ->whereNull('orders.completed_at')

                // Prevent duplicate emails
                ->whereNull('orders.not_completed_email_sent_at')

                ->select(
                    'orders.*',
                    'customers.name as customer_name',
                    'customers.email as customer_email',
                    'sanatan.name as event_name',
                    'sanatan.created_by as event_created_by',
                    'creators.name as creator_name'
                )

                ->orderBy('orders.event_date', 'asc')
                ->get();

            $this->info("Total eligible orders: " . $orders->count());

            if ($orders->isEmpty()) {
                $this->info('No pending Sanatan orders found.');

                return Command::SUCCESS;
            }

            $sent = 0;
            $failed = 0;
            $skipped = 0;

            foreach ($orders as $order) {

                try {

                    $this->line(
                        "Checking Order ID: {$order->id} | Event Date: {$order->event_date}"
                    );

                    if (empty($order->customer_email)) {

                        $skipped++;

                        Log::warning('Sanatan not completed email skipped: Email missing', [
                            'order_id' => $order->id,
                        ]);

                        continue;
                    }

                    // Send customer email
                    Mail::to($order->customer_email)
                        ->send(new SanatanOrderNotCompleted($order));

                    // Update booking after successful email
                    $updated = DB::table('sanatan_event_orders')
                        ->where('id', $order->id)
                        ->where('booking_status', 'confirmed')
                        ->whereNull('otp_verifiction_at')
                        ->whereNull('completed_at')
                        ->whereNull('not_completed_email_sent_at')
                        ->update([
                            'booking_status' => 'not_completed',
                            'not_completed_email_sent_at' => Carbon::now('Asia/Kolkata'),
                            'updated_at' => Carbon::now('Asia/Kolkata'),
                        ]);

                    if ($updated) {

                        $sent++;

                        Log::info('Sanatan not completed email sent successfully', [
                            'order_id' => $order->id,
                            'customer_email' => $order->customer_email,
                            'event_name' => $order->event_name,
                            'event_date' => $order->event_date,
                        ]);

                        $this->info("Email sent successfully for Order ID: {$order->id}");

                    } else {

                        Log::warning('Sanatan email sent but booking was not updated', [
                            'order_id' => $order->id,
                        ]);
                    }

                } catch (Throwable $e) {

                    $failed++;

                    Log::error('Sanatan not completed email failed', [
                        'order_id' => $order->id,
                        'customer_email' => $order->customer_email ?? null,
                        'error' => $e->getMessage(),
                    ]);

                    $this->error("Failed Order ID: {$order->id}");
                }
            }

            $this->newLine();

            $this->info("Total orders found: {$orders->count()}");
            $this->info("Emails sent: {$sent}");
            $this->warn("Skipped: {$skipped}");
            $this->error("Failed: {$failed}");

            return Command::SUCCESS;

        } catch (Throwable $e) {

            Log::error('Sanatan not completed command failed', [
                'error' => $e->getMessage(),
            ]);

            $this->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}