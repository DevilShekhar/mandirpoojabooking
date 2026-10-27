<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Temple;
use App\Models\TicketCart;
use App\Models\TicketPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TicketCartController extends Controller
{
    public function store(Request $request, Temple $temple)
    {
        $validated = $request->validate([
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'members' => ['required', 'array', 'min:1', 'max:10'],
            'members.*.name' => ['required', 'string', 'max:150'],
            'members.*.aadhaar_number' => ['required', 'regex:/^[0-9]{12}$/'],
            'members.*.aadhaar_photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
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
                        "members.{$index}.age" => [
                            'No active ticket price is configured for this member.',
                        ],
                    ]);
                }

                $photoPath = $request->file(
                    "members.{$index}.aadhaar_photo"
                )->store('ticket-cart/aadhaar', 'public');

                $uploadedPhotos[] = $photoPath;

                $members[] = [
                    'temple_id' => $temple->id,
                    'booking_date' => $validated['booking_date'],
                    'member_name' => $member['name'],
                    'aadhaar_number' => $member['aadhaar_number'],
                    'aadhaar_photo' => $photoPath,
                    'ticket_type' => $member['ticket_type'],
                    'type' => $member['type'],
                    'age' => (int) $member['age'],
                    'ticket_price_id' => $ticketPrice->id,
                    'price' => $ticketPrice->price,
                ];
            }

            if (!auth()->check()) {
                $pending = $request->session()->get('pending_ticket_cart', []);

                $request->session()->put(
                    'pending_ticket_cart',
                    array_merge($pending, $members)
                );

                $request->session()->put(
                    'url.intended',
                    route('frontend.ticket-cart.claim')
                );

                return response()->json([
                    'success' => true,
                    'redirect_url' => route('login'),
                    'message' => 'Please log in to continue to your ticket cart.',
                ]);
            }

            DB::transaction(function () use ($members, $request) {
                foreach ($members as $member) {
                    TicketCart::create(array_merge($member, [
                        'user_id' => auth()->id(),
                        'status' => 'active',
                    ]));
                }
            });

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

    public function claimPendingCart(Request $request)
    {
        $pending = $request->session()->get('pending_ticket_cart', []);

        if (empty($pending)) {
            return redirect()->route('frontend.ticket-cart.index');
        }

        DB::transaction(function () use ($pending) {
            foreach ($pending as $member) {
                TicketCart::create(array_merge($member, [
                    'user_id' => auth()->id(),
                    'status' => 'active',
                ]));
            }
        });

        $request->session()->forget('pending_ticket_cart');

        return redirect()
            ->route('frontend.ticket-cart.index')
            ->with('success', 'Your ticket members have been added to the cart.');
    }

    public function index()
    {
        $cartItems = TicketCart::with('temple', 'ticketPrice')
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->latest()
            ->get();

        $totalAmount = $cartItems->sum(
            fn ($item) => (float) $item->price
        );

        return view('frontend.ticket-cart.index', compact(
            'cartItems',
            'totalAmount'
        ));
    }

    public function remove(TicketCart $ticketCart)
    {
        abort_unless(
            (int) $ticketCart->user_id === (int) auth()->id(),
            403
        );

        if ($ticketCart->status !== 'active') {
            return back()->with('error', 'This cart item cannot be removed.');
        }

        Storage::disk('public')->delete($ticketCart->aadhaar_photo);
        $ticketCart->delete();

        return redirect()
            ->route('frontend.ticket-cart.index')
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
            ->whereRaw('LOWER(TRIM(ticket_type)) = ?', [
                strtolower($ticketType),
            ])
            ->whereRaw('LOWER(TRIM(type)) = ?', [
                strtolower($visitorType),
            ])
            ->where('min_age', '<=', $age)
            ->where(function ($query) use ($age) {
                $query->whereNull('max_age')
                    ->orWhere('max_age', '>=', $age);
            })
            ->where(function ($query) {
                $query->where('status', 1)
                    ->orWhere('status', 'active');
            })
            ->first();
    }
}