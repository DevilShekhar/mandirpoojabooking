<?php

namespace App\Console\Commands;

use App\Models\TicketBooking;
use App\Mail\NotReachedTicketBookingMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MarkNotReachedTicketBookings extends Command
{
    protected $signature = 'ticket-bookings:mark-not-reached';
    protected $description = 'Mark missed ticket bookings as not reached and send notification emails';
    public function handle()
    {
        $yesterday = Carbon::yesterday('Asia/Kolkata')->toDateString();
        $this->info("Checking ticket bookings up to: {$yesterday}");
        $totalProcessed = 0;
        $totalMarked = 0;
        $totalFailed = 0;
        TicketBooking::with(['user', 'temple'])
            ->whereDate('booking_date', '<=', $yesterday)
            ->where('payment_status', 'paid')
            ->where(function ($query) {
                $query->where('booking_status', 'confirmed')
                    ->orWhere(function ($q) {
                        $q->where('booking_status', 'not_reached')
                            ->whereNull('not_reached_notified_at');
                    });
            })
            ->chunkById(100, function ($ticketBookings) use (&$totalProcessed,&$totalMarked,&$totalFailed) {
                foreach ($ticketBookings as $ticketBooking) {
                    try {
                        if ($ticketBooking->booking_status === 'confirmed') {
                            $ticketBooking->booking_status = 'not_reached';
                            $ticketBooking->save();
                            $totalMarked++;
                            $this->info(
                                "Ticket booking {$ticketBooking->booking_reference} marked as not reached."
                            );
                        }
                        // Prevent duplicate notification emails
                        if ($ticketBooking->not_reached_notified_at) {
                            continue;
                        }
                        // Check customer email
                        if (!$ticketBooking->user ||
                            empty($ticketBooking->user->email)) {
                            Log::warning('Not reached ticket booking email skipped', [
                                'ticket_booking_id' => $ticketBooking->id,
                                'booking_reference' => $ticketBooking->booking_reference,
                                'reason' => 'Customer email not found',
                            ]);
                            continue;
                        }
                        // Send notification email
                        Mail::to($ticketBooking->user->email)->send(new NotReachedTicketBookingMail($ticketBooking));
                        // Update notification timestamp after successful send
                        $ticketBooking->not_reached_notified_at = now('Asia/Kolkata');
                        $ticketBooking->save();
                        $totalProcessed++;
                        $this->info("Email sent successfully: {$ticketBooking->booking_reference}");
                    } catch (\Throwable $e) {
                        $totalFailed++;
                        Log::error('Not reached ticket booking processing failed', [
                            'ticket_booking_id' => $ticketBooking->id,
                            'booking_reference' => $ticketBooking->booking_reference,
                            'error' => $e->getMessage(),
                        ]);
                        $this->error(
                            "Processing failed for ticket booking ID: {$ticketBooking->id}"
                        );
                    }
                }
            });
        $this->newLine();
        $this->info("Total ticket bookings marked: {$totalMarked}");
        $this->info("Total emails sent: {$totalProcessed}");
        $this->info("Total failed: {$totalFailed}");
        $this->info('Not reached ticket booking checking completed.');
        return Command::SUCCESS;
    }
}