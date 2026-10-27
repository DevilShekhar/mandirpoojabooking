<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Temple;
use App\Models\TempleDailyTicketLimit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class TempleDailyTicketLimitController extends Controller
{
    private function managerTemples()
    {
        return Temple::join(
            'temple_user',
            'temples.id',
            '=',
            'temple_user.temple_id'
        )
            ->where('temple_user.user_id', auth()->id())
            ->where('temple_user.approval_status', 'approved')
            ->select('temples.*')
            ->distinct();
    }

    private function authorizeTemple(TempleDailyTicketLimit $limit): void
    {
        $hasAccess = $this->managerTemples()
            ->where('temples.id', $limit->temple_id)
            ->exists();

        abort_unless($hasAccess, 403);
    }

    public function index()
    {
        $templeIds = $this->managerTemples()->pluck('temples.id');

        $limits = TempleDailyTicketLimit::with([
            'temple',
            'creator',
            'updater',
        ])
            ->whereIn('temple_id', $templeIds)
            ->latest()
            ->paginate(15);

        return view('admin.temple-daily-ticket-limits.index', compact('limits'));
    }

    public function create()
    {
        $temples = $this->managerTemples()
            ->orderBy('temples.name')
            ->get();

        abort_if($temples->isEmpty(), 403, 'No approved temple is assigned to your account.');

        return view('admin.temple-daily-ticket-limits.create', compact('temples'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'temple_id' => [
                'required',
                'integer',
                Rule::in($this->managerTemples()->pluck('temples.id')->all()),
            ],
            'ticket_type' => [
                'required',
                Rule::in(['normal', 'vip']),
            ],
            'daily_limit' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $exists = TempleDailyTicketLimit::where(
            'temple_id',
            $validated['temple_id']
        )
            ->where('ticket_type', $validated['ticket_type'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'ticket_type' => 'A daily limit for this ticket type already exists for this temple. Please edit the existing limit.',
                ]);
        }

        DB::transaction(function () use ($validated) {
            TempleDailyTicketLimit::create([
                'temple_id' => $validated['temple_id'],
                'ticket_type' => $validated['ticket_type'],
                'daily_limit' => $validated['daily_limit'],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('temple-daily-ticket-limits.index')
            ->with('success', 'Daily ticket limit created successfully.');
    }

    public function edit(TempleDailyTicketLimit $templeDailyTicketLimit)
    {
        $this->authorizeTemple($templeDailyTicketLimit);

        $temples = $this->managerTemples()
            ->orderBy('temples.name')
            ->get();

        return view(
            'admin.temple-daily-ticket-limits.edit',
            compact('templeDailyTicketLimit', 'temples')
        );
    }

    public function update(
        Request $request,
        TempleDailyTicketLimit $templeDailyTicketLimit
    ) {
        $this->authorizeTemple($templeDailyTicketLimit);

        $validated = $request->validate([
            'temple_id' => [
                'required',
                'integer',
                Rule::in($this->managerTemples()->pluck('temples.id')->all()),
            ],
            'ticket_type' => [
                'required',
                Rule::in(['normal', 'vip']),
                Rule::unique('temple_daily_ticket_limits', 'ticket_type')
                    ->where('temple_id', $request->input('temple_id'))
                    ->ignore($templeDailyTicketLimit->id),
            ],
            'daily_limit' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $templeDailyTicketLimit->update([
            'temple_id' => $validated['temple_id'],
            'ticket_type' => $validated['ticket_type'],
            'daily_limit' => $validated['daily_limit'],
            'updated_by' => auth()->id(),
        ]);

        return redirect()
            ->route('temple-daily-ticket-limits.index')
            ->with('success', 'Daily ticket limit updated successfully.');
    }

    public function destroy(TempleDailyTicketLimit $templeDailyTicketLimit)
    {
        $this->authorizeTemple($templeDailyTicketLimit);

        $templeDailyTicketLimit->delete();

        return redirect()
            ->route('temple-daily-ticket-limits.index')
            ->with('success', 'Daily ticket limit deleted successfully.');
    }
}