<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Temple;
use App\Models\TicketPrice;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TicketPriceController extends Controller
{
    private function assignedTemple(): Temple
    {
        $temple = auth()->user()
            ->temples()
            ->wherePivot('approval_status', 'approved')
            ->orderBy('temples.name')
            ->first();

        abort_unless($temple, 403, 'No approved temple is assigned to this user.');

        return $temple;
    }

    private function authorizeTemple(TicketPrice $ticketPrice, Temple $temple): void
    {
        abort_unless(
            (int) $ticketPrice->temple_id === (int) $temple->id,
            403,
            'You are not authorized to access this ticket price.'
        );
    }

    public function index()
    {
        $temple = $this->assignedTemple();

        $ticketPrices = TicketPrice::with([
            'temple',
            'creator',
            'updater',
        ])
            ->where('temple_id', $temple->id)
            ->where('created_by', auth()->id())
            ->latest()
            ->paginate(10);

        return view('admin.ticket-prices.index', compact('ticketPrices'));
    }

    public function create()
    {
        $temple = $this->assignedTemple();

        return view('admin.ticket-prices.create', compact('temple'));
    }

    public function store(Request $request)
    {
        $temple = $this->assignedTemple();

        $validated = $request->validate([
            'ticket_type' => [
                'required',
                Rule::in(['normal', 'vip']),
            ],
            'type' => [
                'required',
                'string',
                'max:100',
                Rule::unique('ticket_prices', 'type')
                    ->where('temple_id', $temple->id)
                    ->where('ticket_type', $request->input('ticket_type')),
            ],
            'min_age' => [
                'required',
                'integer',
                'min:0',
                'max:150',
            ],
            'max_age' => [
                'nullable',
                'integer',
                'min:0',
                'max:150',
                'gte:min_age',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        $validated['temple_id'] = $temple->id;
        $validated['max_age'] = $validated['max_age'] ?? null;
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();
        $validated['status'] = 1;

        TicketPrice::create($validated);

        return redirect()
            ->route('ticket-prices.index')
            ->with('success', 'Ticket price created successfully.');
    }

    public function show(TicketPrice $ticketPrice)
    {
        $temple = $this->assignedTemple();

        $this->authorizeTemple($ticketPrice, $temple);

        $ticketPrice->load([
            'temple',
            'creator',
            'updater',
        ]);

        return view('admin.ticket-prices.show', compact('ticketPrice'));
    }

    public function edit(TicketPrice $ticketPrice)
    {
        $temple = $this->assignedTemple();

        $this->authorizeTemple($ticketPrice, $temple);

        return view(
            'admin.ticket-prices.edit',
            compact('ticketPrice', 'temple')
        );
    }

    public function update(Request $request, TicketPrice $ticketPrice)
    {
        $temple = $this->assignedTemple();

        $this->authorizeTemple($ticketPrice, $temple);

        $validated = $request->validate([
            'ticket_type' => [
                'required',
                Rule::in(['normal', 'vip']),
            ],
            'type' => [
                'required',
                'string',
                'max:100',
                Rule::unique('ticket_prices', 'type')
                    ->where('temple_id', $temple->id)
                    ->where('ticket_type', $request->input('ticket_type'))
                    ->ignore($ticketPrice->id),
            ],
            'min_age' => [
                'required',
                'integer',
                'min:0',
                'max:150',
            ],
            'max_age' => [
                'nullable',
                'integer',
                'min:0',
                'max:150',
                'gte:min_age',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'status' => [
                'sometimes',
                'boolean',
            ],
        ]);

        $validated['max_age'] = $validated['max_age'] ?? null;
        $validated['updated_by'] = auth()->id();
        $validated['status'] = $validated['status'] ?? $ticketPrice->status;

        $ticketPrice->update($validated);

        return redirect()
            ->route('ticket-prices.index')
            ->with('success', 'Ticket price updated successfully.');
    }

    public function destroy(TicketPrice $ticketPrice)
    {
        $temple = $this->assignedTemple();

        $this->authorizeTemple($ticketPrice, $temple);

        $ticketPrice->delete();

        return redirect()
            ->route('ticket-prices.index')
            ->with('success', 'Ticket price deleted successfully.');
    }
}