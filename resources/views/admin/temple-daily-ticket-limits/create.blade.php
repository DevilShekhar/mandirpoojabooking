
@extends('admin.layouts.app')

@section('content')
@can('temple-daily-ticket-limits.create')
<div class="section">
    <div class="section-header">
        <h1>Create Daily Ticket Limit</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <a href="{{ route('temple-daily-ticket-limits.index') }}">Daily Ticket Limits</a>
            </div>
            <div class="breadcrumb-item active">Create</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Daily Ticket Limit Details</h4>
            </div>

            <form action="{{ route('temple-daily-ticket-limits.store') }}" method="POST">
                @csrf

                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="temple_id">Temple Name <span class="text-danger">*</span></label>

                            <select name="temple_id" id="temple_id" class="form-control @error('temple_id') is-invalid @enderror" required>
                                <option value="">Select Temple</option>

                                @foreach($temples as $temple)
                                    <option value="{{ $temple->id }}"
                                        {{ old('temple_id', $temples->count() === 1 ? $temples->first()->id : '') == $temple->id ? 'selected' : '' }}>
                                        {{ $temple->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('temple_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="ticket_type">Ticket Type <span class="text-danger">*</span></label>

                            <select name="ticket_type" id="ticket_type" class="form-control @error('ticket_type') is-invalid @enderror" required>
                                <option value="">Select Ticket Type</option>
                                <option value="normal" {{ old('ticket_type') == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="vip" {{ old('ticket_type') == 'vip' ? 'selected' : '' }}>VIP</option>
                            </select>

                            @error('ticket_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="daily_limit">Maximum Tickets Per Day <span class="text-danger">*</span></label>

                            <input
                                type="number"
                                name="daily_limit"
                                id="daily_limit"
                                class="form-control @error('daily_limit') is-invalid @enderror"
                                value="{{ old('daily_limit') }}"
                                min="1"
                                step="1"
                                placeholder="Enter maximum tickets per day"
                                required
                            >

                            @error('daily_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <a href="{{ route('temple-daily-ticket-limits.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Save Daily Limit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@else
    @php abort(403); @endphp
@endcan
@endsection