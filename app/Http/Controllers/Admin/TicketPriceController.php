<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Temple;
use App\Models\TicketPrice;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TicketPriceController extends Controller
{
    public function index()
    {
        $ticketPrices = TicketPrice::with([
            'temple',
            'creator',
            'updater',
        ])->latest()->paginate(10);

        return view('admin.ticket-prices.index', compact('ticketPrices'));
    }

    public function create()
    {
        $temples = Temple::orderBy('name')->get();

        return view('admin.ticket-prices.create', compact('temples'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'temple_id' => [
                'required',
                'integer',
                'exists:temples,id',
            ],
            'ticket_type' => [
                'required',
                Rule::in(['normal', 'vip']),
            ],
            'type' => [
                'required',
                'string',
                'max:100',
                Rule::unique('ticket_prices', 'type')
                    ->where('temple_id', $request->input('temple_id'))
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
        $ticketPrice->load(['temple', 'creator', 'updater']);

        return view('admin.ticket-prices.show', compact('ticketPrice'));
    }

    public function edit(TicketPrice $ticketPrice)
    {
        $temples = Temple::orderBy('name')->get();

        return view(
            'admin.ticket-prices.edit',
            compact('ticketPrice', 'temples')
        );
    }

    public function update(Request $request, TicketPrice $ticketPrice)
    {
        $validated = $request->validate([
            'temple_id' => [
                'required',
                'integer',
                'exists:temples,id',
            ],
            'ticket_type' => [
                'required',
                Rule::in(['normal', 'vip']),
            ],
            'type' => [
                'required',
                'string',
                'max:100',
                Rule::unique('ticket_prices', 'type')
                    ->where('temple_id', $request->input('temple_id'))
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

        // Preserve the existing status if the form does not submit it.
        $validated['status'] = $validated['status'] ?? $ticketPrice->status;

        $ticketPrice->update($validated);

        return redirect()
            ->route('ticket-prices.index')
            ->with('success', 'Ticket price updated successfully.');
    }

    public function destroy(TicketPrice $ticketPrice)
    {
        $ticketPrice->delete();

        return redirect()
            ->route('ticket-prices.index')
            ->with('success', 'Ticket price deleted successfully.');
    }
}