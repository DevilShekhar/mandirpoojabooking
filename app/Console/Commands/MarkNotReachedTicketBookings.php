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
        $now = Carbon::now('Asia/Kolkata');

        $this->info("Current India time: " . $now->toDateTimeString());

        $totalProcessed = 0;
        $totalMarked = 0;
        $totalFailed = 0;

        TicketBooking::with(['user', 'temple'])
            ->where('payment_status', 'paid')
            ->whereRaw(
                'TIMESTAMP(booking_date, booking_time) <= ?',
                [$now->toDateTimeString()]
            )
            ->where(function ($query) {
                $query->where('booking_status', 'confirmed')
                    ->orWhere(function ($q) {
                        $q->where('booking_status', 'not_reached')
                            ->whereNull('not_reached_notified_at');
                    });
            })
            ->chunkById(100, function ($ticketBookings) use (
                &$totalProcessed,
                &$totalMarked,
                &$totalFailed
            ) {
                foreach ($ticketBookings as $ticketBooking) {
                    try {

                        // Mark booking as not reached
                        if ($ticketBooking->booking_status === 'confirmed') {

                            $ticketBooking->booking_status = 'not_reached';
                            $ticketBooking->save();

                            $totalMarked++;

                            Log::info('Ticket booking marked as not reached', [
                                'ticket_booking_id' => $ticketBooking->id,
                                'booking_reference' => $ticketBooking->booking_reference,
                            ]);

                            $this->info(
                                "Marked not reached: {$ticketBooking->booking_reference}"
                            );
                        }

                        // Prevent duplicate emails
                        if ($ticketBooking->not_reached_notified_at) {
                            continue;
                        }

                        // Check customer email
                        if (!$ticketBooking->user ||
                            empty($ticketBooking->user->email)) {

                            Log::warning('Ticket booking email unavailable', [
                                'ticket_booking_id' => $ticketBooking->id,
                            ]);

                            continue;
                        }

                        // Send notification email
                        Mail::to($ticketBooking->user->email)
                            ->send(new NotReachedTicketBookingMail($ticketBooking));

                        // Update notification timestamp
                        $ticketBooking->not_reached_notified_at =
                            Carbon::now('Asia/Kolkata');

                        $ticketBooking->save();

                        $totalProcessed++;

                        $this->info(
                            "Email sent: {$ticketBooking->booking_reference}"
                        );

                    } catch (\Throwable $e) {

                        $totalFailed++;

                        Log::error('Ticket booking not reached failed', [
                            'ticket_booking_id' => $ticketBooking->id,
                            'booking_reference' => $ticketBooking->booking_reference,
                            'error' => $e->getMessage(),
                        ]);

                        $this->error(
                            "Failed booking ID: {$ticketBooking->id} - {$e->getMessage()}"
                        );
                    }
                }
            });

        $this->newLine();

        $this->info("Total bookings marked: {$totalMarked}");
        $this->info("Total emails sent: {$totalProcessed}");
        $this->info("Total failed: {$totalFailed}");

        $this->info('Ticket booking checking completed.');

        return Command::SUCCESS;
    }
}