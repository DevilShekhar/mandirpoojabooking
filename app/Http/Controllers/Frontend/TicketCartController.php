<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Temple;
use App\Models\TicketCart;
use App\Models\TicketBooking;
use App\Models\TicketPrice;
use App\Models\TempleDailyTicketLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use DomainException;

class TicketCartController extends Controller
{
    public function store(Request $request, Temple $temple)
    {
        if ($request->boolean('availability_only')) {
            return $this->checkAvailability($request, $temple);
        }

        $validated = $request->validate([
            'booking_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'members' => ['required', 'array', 'min:1', 'max:10'],
            'members.*.name' => ['required', 'string', 'max:150'],
            'members.*.aadhaar_number' => ['required', 'regex:/^[0-9]{12}$/'],
            'members.*.aadhaar_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'members.*.ticket_type' => ['required', 'in:normal,vip'],
            'members.*.type' => ['required', 'in:Adult,Child,Senior Citizen'],
            'members.*.age' => ['required', 'integer', 'min:0', 'max:120'],
        ]);

        $uploadedPhotos = [];

        try {
            $members = [];

            foreach ($validated['members'] as $index => $member) {
                $ticketPrice = $this->findTicketPrice(
                    $temple->id,
                    $member['ticket_type'],
                    $member['type'],
                    (int) $member['age']
                );

                if (!$ticketPrice) {
                    throw ValidationException::withMessages([
                        "members.{$index}.age" => ['No active ticket price is configured for this member.'],
                    ]);
                }

                $photo = $request->file("members.{$index}.aadhaar_photo");
                if (!$photo) {
                    throw ValidationException::withMessages([
                        "members.{$index}.aadhaar_photo" => ['Please upload the Aadhaar photo for this member.'],
                    ]);
                }

                $photoPath = $photo->store('ticket-cart/aadhaar', 'public');
                $uploadedPhotos[] = $photoPath;

                $members[] = [
                    'temple_id' => $temple->id,
                    'booking_date' => $validated['booking_date'],
                    'member_name' => $member['name'],
                    'aadhaar_number' => $member['aadhaar_number'],
                    'aadhaar_photo' => $photoPath,
                    'ticket_type' => strtolower(trim($member['ticket_type'])),
                    'type' => $member['type'],
                    'age' => (int) $member['age'],
                    'ticket_price_id' => $ticketPrice->id,
                    'price' => $ticketPrice->price,
                ];
            }

            // Guests may prepare a cart, but must log in before database cart storage.
            if (!auth()->check()) {
                $this->assertRequestedCapacity($temple->id, $validated['booking_date'], $members);

                $pending = $request->session()->get('pending_ticket_cart', []);
                $request->session()->put('pending_ticket_cart', array_merge($pending, $members));
                $request->session()->put('url.intended', route('frontend.ticket-cart.claim'));

                return response()->json([
                    'success' => true,
                    'redirect_url' => route('login'),
                    'message' => 'Please log in to continue to your ticket cart.',
                ]);
            }

            DB::transaction(function () use ($temple, $validated, $members) {
                $this->assertRequestedCapacity(
                    (int) $temple->id,
                    $validated['booking_date'],
                    $members,
                    true
                );

                foreach ($members as $member) {
                    TicketCart::create(array_merge($member, [
                        'user_id' => auth()->id(),
                        'status' => 'active',
                    ]));
                }
            }, 3);

            return response()->json([
                'success' => true,
                'message' => 'All members have been added to your cart.',
                'redirect_url' => route('frontend.ticket-cart.index'),
            ]);
        } catch (ValidationException $exception) {
            foreach ($uploadedPhotos as $photo) {
                Storage::disk('public')->delete($photo);
            }
            throw $exception;
        } catch (DomainException $exception) {
            foreach ($uploadedPhotos as $photo) {
                Storage::disk('public')->delete($photo);
            }
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 409);
        } catch (\Throwable $exception) {
            foreach ($uploadedPhotos as $photo) {
                Storage::disk('public')->delete($photo);
            }
            report($exception);
            return response()->json([
                'success' => false,
                'message' => 'Unable to save your ticket cart. Please try again.',
            ], 500);
        }
    }

    /** Availability counts only paid + confirmed bookings. Cart rows do not consume capacity. */
    private function checkAvailability(Request $request, Temple $temple)
    {
        $validated = $request->validate([
            'booking_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
        ]);

        $ticketTypes = [];

        foreach (['normal', 'vip'] as $ticketType) {
            $limit = TempleDailyTicketLimit::query()
                ->where('temple_id', $temple->id)
                ->whereRaw('LOWER(TRIM(ticket_type)) = ?', [$ticketType])
                ->value('daily_limit');

            $confirmed = $this->confirmedCount(
                (int) $temple->id,
                $validated['booking_date'],
                $ticketType
            );

            $remaining = max(0, (int) ($limit ?? 0) - $confirmed);

            $ticketTypes[$ticketType] = [
                'limit' => (int) ($limit ?? 0),
                'confirmed_booked' => $confirmed,
                'cart_booked' => 0,
                'booked' => $confirmed,
                'remaining' => $remaining,
                'available' => $limit !== null && $remaining > 0,
            ];
        }

        return response()->json([
            'booking_date' => $validated['booking_date'],
            'ticket_types' => $ticketTypes,
            'any_available' => collect($ticketTypes)->contains(
                fn ($ticket) => $ticket['available'] === true
            ),
        ]);
    }

    /** Validate capacity again before storing cart members; cart rows are not counted as bookings. */
    private function assertRequestedCapacity(
        int $templeId,
        string $bookingDate,
        array $members,
        bool $lockLimits = false
    ): void {
        $requestedCounts = collect($members)->countBy(function ($member) {
            return strtolower(trim($member['ticket_type']));
        });

        foreach (['normal', 'vip'] as $ticketType) {
            $requested = (int) ($requestedCounts[$ticketType] ?? 0);
            if ($requested === 0) {
                continue;
            }

            $limitQuery = TempleDailyTicketLimit::query()
                ->where('temple_id', $templeId)
                ->whereRaw('LOWER(TRIM(ticket_type)) = ?', [$ticketType]);

            if ($lockLimits) {
                $limitQuery->lockForUpdate();
            }

            $limit = $limitQuery->value('daily_limit');
            $confirmed = $this->confirmedCount($templeId, $bookingDate, $ticketType);

            if ($limit === null || ($confirmed + $requested) > (int) $limit) {
                $remaining = max(0, (int) ($limit ?? 0) - $confirmed);
                throw new DomainException(
                    strtoupper($ticketType) . ' tickets are no longer available for ' .
                    $bookingDate . '. Remaining tickets: ' . $remaining . '.'
                );
            }
        }
    }

    private function confirmedCount(int $templeId, string $bookingDate, string $ticketType): int
    {
        return TicketBooking::query()
            ->where('temple_id', $templeId)
            ->whereDate('booking_date', $bookingDate)
            ->whereRaw('LOWER(TRIM(ticket_type)) = ?', [$ticketType])
            ->where('payment_status', 'paid')
            ->where('booking_status', 'confirmed')
            ->count();
    }

    public function claimPendingCart(Request $request)
    {
        $pending = $request->session()->get('pending_ticket_cart', []);
        if (empty($pending)) {
            return redirect()->route('frontend.ticket-cart.index');
        }

        try {
            DB::transaction(function () use ($pending) {
                $groups = collect($pending)->groupBy(function ($member) {
                    return $member['temple_id'] . '|' . $member['booking_date'];
                });

                foreach ($groups as $members) {
                    $first = $members->first();
                    $this->assertRequestedCapacity(
                        (int) $first['temple_id'],
                        $first['booking_date'],
                        $members->all(),
                        true
                    );
                }

                foreach ($pending as $member) {
                    TicketCart::create(array_merge($member, [
                        'user_id' => auth()->id(),
                        'status' => 'active',
                    ]));
                }
            }, 3);
        } catch (\Throwable $exception) {
            report($exception);
            return redirect()->route('frontend.ticket-cart.index')
                ->with('error', $exception instanceof DomainException
                    ? $exception->getMessage()
                    : 'Unable to add your pending tickets. Please try again.');
        }

        $request->session()->forget('pending_ticket_cart');
        return redirect()->route('frontend.ticket-cart.index')
            ->with('success', 'Your ticket members have been added to the cart.');
    }

    public function index()
    {
        $cartItems = TicketCart::with('temple', 'ticketPrice')
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->latest()
            ->get();

        $totalAmount = $cartItems->sum(fn ($item) => (float) $item->price);
        return view('frontend.ticket-cart.index', compact('cartItems', 'totalAmount'));
    }

    public function remove(TicketCart $ticketCart)
    {
        abort_unless((int) $ticketCart->user_id === (int) auth()->id(), 403);

        if ($ticketCart->status !== 'active') {
            return back()->with('error', 'This cart item cannot be removed.');
        }

        Storage::disk('public')->delete($ticketCart->aadhaar_photo);
        $ticketCart->delete();

        return redirect()->route('frontend.ticket-cart.index')
            ->with('success', 'Member removed from your cart.');
    }

    private function findTicketPrice(
        int $templeId,
        string $ticketType,
        string $visitorType,
        int $age
    ): ?TicketPrice {
        return TicketPrice::query()
            ->where('temple_id', $templeId)
            ->whereRaw('LOWER(TRIM(ticket_type)) = ?', [strtolower(trim($ticketType))])
            ->whereRaw('LOWER(TRIM(type)) = ?', [strtolower(trim($visitorType))])
            ->where('min_age', '<=', $age)
            ->where(function ($query) use ($age) {
                $query->whereNull('max_age')->orWhere('max_age', '>=', $age);
            })
            ->where(function ($query) {
                $query->where('status', 1)->orWhere('status', 'active');
            })
            ->first();
    }
}
