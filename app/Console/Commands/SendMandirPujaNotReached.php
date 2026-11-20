<?php
namespace App\Console\Commands;

use App\Mail\MandirPujaNotReached;
use App\Models\PujaBooking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendMandirPujaNotReached extends Command
{
    protected $signature = 'mandir-puja:not-reached';
    protected $description = 'Send not reached emails and update Mandir Puja booking status';
    public function handle()
    {
        $this->info('Starting Mandir Puja Not Reached process...');
        $bookings = PujaBooking::with(['puja', 'temple'])
            ->where('booking_status', 'confirmed')
            ->whereNull('otp_reminder_sent_at')
            ->where('created_at', '<=', now()->subDay())
            ->where(function ($query) {
                $query->whereNull('samgri_otp')
                    ->orWhere('samgri_otp', '')
                    ->orWhereNull('puja_completed_otp')
                    ->orWhere('puja_completed_otp', '');
            })
            ->get();
        if ($bookings->isEmpty()) {
            $this->info('No eligible Mandir Puja bookings found.');
            return self::SUCCESS;
        }
        $sentCount = 0;
        $skippedCount = 0;
        $failedCount = 0;
        foreach ($bookings as $booking) {
            if (empty($booking->customer_email)) {
                $this->warn(
                    "Email missing for booking: {$booking->booking_number}"
                );
                Log::warning('Mandir Puja Not Reached email skipped: customer email missing.', [
                    'booking_id' => $booking->id,
                    'booking_number' => $booking->booking_number,
                ]);
                $skippedCount++;
                continue;
            }
            try {
                Mail::to($booking->customer_email)->send(new MandirPujaNotReached($booking));
                // Update status only after successful email sending.
                $booking->booking_status = 'not_reached';
                $booking->otp_reminder_sent_at = now();
                $booking->save();
                $sentCount++;
                $this->info("Not Reached email sent: {$booking->booking_number}");
                Log::info('Mandir Puja Not Reached email sent successfully.', [
                    'booking_id' => $booking->id,
                    'booking_number' => $booking->booking_number,
                    'customer_email' => $booking->customer_email,
                ]);
            } catch (Throwable $e) {
                $failedCount++;
                $this->error("Failed for booking {$booking->booking_number}: {$e->getMessage()}");
                Log::error('Mandir Puja Not Reached email failed.', [
                    'booking_id' => $booking->id,
                    'booking_number' => $booking->booking_number,
                    'customer_email' => $booking->customer_email,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        $this->newLine();
        $this->info('Mandir Puja Not Reached process completed.');
        $this->info("Emails sent: {$sentCount}");
        $this->info("Skipped: {$skippedCount}");
        $this->info("Failed: {$failedCount}");
        return self::SUCCESS;
    }
}