<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Temple;
use App\Models\TempleTiming;
use App\Models\TempleTimingCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class TempleTimingController extends Controller
{
    private function isAdmin(): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }
        return $user->hasAnyRole(['Super Admin', 'Admin']) || in_array($user->role->name ?? '', ['Super Admin', 'Admin']);
    }

    private function accessibleTemples()
    {
        $query = Temple::query();
        if ($this->isAdmin()) {
            return $query;
        }
        return $query->where(function ($q) {
            $q->where('created_by', Auth::id())
              ->orWhere('updated_by', Auth::id());
        });
    }

    private function authorizeTempleAccess(Temple $temple): void
    {
        abort_unless(Auth::check(), 401);
        if ($this->isAdmin()) {
            return;
        }
        $hasAccess = $this->accessibleTemples()->where('id', $temple->id)->exists();
        abort_if(!$hasAccess, 403, 'Unauthorized temple access.');
    }

    private function authorizeTimingAccess(TempleTiming $templeTiming): void
    {
        $temple = $templeTiming->temple;
        abort_if(!$temple, 404, 'Temple not found.');
        $this->authorizeTempleAccess($temple);
    }

    private function validateTempleCategory(int $templeId,int $categoryId): TempleTimingCategory {
        $this->accessibleTemples()->where('id', $templeId)->firstOrFail();
        return TempleTimingCategory::where('id', $categoryId)
            ->where('temple_id', $templeId)
            ->firstOrFail();
    }

    // INDEX
    public function index()
    {
        $templeTimings = TempleTiming::with([
                'temple',
                'category',
                'creator',
                'updater'
            ])
            ->whereIn(
                'temple_id',
                $this->accessibleTemples()->select('id')
            )
            ->latest()->get();
        return view('admin.temple-timings.index',compact('templeTimings'));
    }

    // CREATE
    public function create()
    {
        $temples = $this->accessibleTemples()
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);
        abort_if($temples->isEmpty(), 403, 'No accessible temple found.');
        $categories = TempleTimingCategory::whereIn(
                'temple_id',
                $temples->pluck('id')
            )
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'temple_id', 'name']);
        return view('admin.temple-timings.create',compact('temples', 'categories'));
    }

    // STORE
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'temple_id' => [
                'required',
                'integer',
                'exists:temples,id'
            ],
            'temple_timing_category_id' => [
                'required',
                'integer',
                'exists:temple_timing_categories,id'
            ],
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'description' => [
                'nullable',
                'string'
            ],
            'start_day' => [
                'required',
                'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'
            ],
            'end_day' => [
                'required',
                'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'
            ],
            'opening_time' => [
                'required',
                'date_format:H:i'
            ],
            'closing_time' => [
                'required',
                'date_format:H:i'
            ],
            'status' => [
                'required',
                'in:0,1'
            ],
        ]);
        $validator->validate();
        $this->validateTempleCategory(
            (int) $request->temple_id,
            (int) $request->temple_timing_category_id
        );
        try {
            TempleTiming::create([
                'temple_id' => $request->temple_id,
                'temple_timing_category_id' => $request->temple_timing_category_id,
                'name' => $request->name,
                'description' => $request->description,
                'start_day' => $request->start_day,
                'end_day' => $request->end_day,
                'opening_time' => $request->opening_time,
                'closing_time' => $request->closing_time,
                'status' => $request->status,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
            return redirect()->route('temple-timings.index')->with('success', 'Temple timing created successfully.');
        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Unable to create temple timing. Please try again.');
        }
    }

    // SHOW
    public function show(TempleTiming $templeTiming)
    {
        $this->authorizeTimingAccess($templeTiming);
        $templeTiming->load([
            'temple',
            'category',
            'creator',
            'updater'
        ]);
        return view('admin.temple-timings.show',compact('templeTiming'));
    }

    // EDIT
    public function edit(TempleTiming $templeTiming)
    {
        $this->authorizeTimingAccess($templeTiming);
        $temples = $this->accessibleTemples()
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);
        if (!$temples->contains('id', $templeTiming->temple_id)) {
            $selectedTemple = $this->accessibleTemples()
                ->where('id', $templeTiming->temple_id)
                ->firstOrFail(['id', 'name']);
            $temples->push($selectedTemple);
        }
        $categories = TempleTimingCategory::whereIn(
                'temple_id',
                $temples->pluck('id')
            )
            ->where(function ($q) use ($templeTiming) {
                $q->where('status', 1)
                  ->orWhere('id', $templeTiming->temple_timing_category_id);
            })
            ->orderBy('name')
            ->get(['id', 'temple_id', 'name']);
        return view('admin.temple-timings.edit',compact('templeTiming', 'temples', 'categories'));
    }

    // UPDATE
    public function update(Request $request, TempleTiming $templeTiming)
    {
        $this->authorizeTimingAccess($templeTiming);
        $validator = Validator::make($request->all(), [
            'temple_id' => [
                'required',
                'integer',
                'exists:temples,id'
            ],
            'temple_timing_category_id' => [
                'required',
                'integer',
                'exists:temple_timing_categories,id'
            ],
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'description' => [
                'nullable',
                'string'
            ],
            'start_day' => [
                'required',
                'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'
            ],
            'end_day' => [
                'required',
                'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'
            ],
            'opening_time' => [
                'required',
                'date_format:H:i'
            ],
            'closing_time' => [
                'required',
                'date_format:H:i'
            ],
            'status' => [
                'required',
                'in:0,1'
            ],
        ]);
        $validator->validate();
        $this->validateTempleCategory(
            (int) $request->temple_id,
            (int) $request->temple_timing_category_id
        );
        try {
            $templeTiming->update([
                'temple_id' => $request->temple_id,
                'temple_timing_category_id' => $request->temple_timing_category_id,
                'name' => $request->name,
                'description' => $request->description,
                'start_day' => $request->start_day,
                'end_day' => $request->end_day,
                'opening_time' => $request->opening_time,
                'closing_time' => $request->closing_time,
                'status' => $request->status,
                'updated_by' => Auth::id(),
            ]);
            return redirect()->route('temple-timings.index')->with('success', 'Temple timing updated successfully.');
        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Unable to update temple timing. Please try again.');
        }
    }

    // DESTROY - ONLY UPDATE STATUS TO 0
    public function destroy(TempleTiming $templeTiming)
    {
        $this->authorizeTimingAccess($templeTiming);
        try {
            $templeTiming->update([
                'status' => 0,
                'updated_by' => Auth::id()
            ]);
            return redirect()->route('temple-timings.index')->with('success', 'Temple timing deactivated successfully.');
        } catch (Throwable $e) {
            report($e);
            return redirect()->route('temple-timings.index')->with('error', 'Unable to deactivate temple timing. Please try again.');
        }
    }
}