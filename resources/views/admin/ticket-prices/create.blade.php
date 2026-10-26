@can('ticket-prices.create')
    @extends('admin.layouts.app')
    @section('content')
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Create Ticket Price</h4>
                            <a href="{{ route('ticket-prices.index') }}"
                            class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                        <form action="{{ route('ticket-prices.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <strong>Please correct the following errors:</strong>
                                        <ul class="mb-0 mt-2">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <h5 class="mb-3">Temple Information</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="temple_id">
                                                Temple <span class="text-danger">*</span>
                                            </label>
                                            <select name="temple_id"
                                                    id="temple_id"
                                                    class="form-control @error('temple_id') is-invalid @enderror"
                                                    required>
                                                <option value="">Select Temple</option>
                                                @foreach ($temples as $temple)
                                                    <option value="{{ $temple->id }}"
                                                        {{ old('temple_id') == $temple->id ? 'selected' : '' }}>
                                                        {{ $temple->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('temple_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h5 class="mb-3">Ticket Information</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="ticket_type">
                                                Ticket Type <span class="text-danger">*</span>
                                            </label>
                                            <select name="ticket_type"
                                                    id="ticket_type"
                                                    class="form-control @error('ticket_type') is-invalid @enderror"
                                                    required>
                                                <option value="">Select Ticket Type</option>
                                                <option value="normal"
                                                    {{ old('ticket_type') == 'normal' ? 'selected' : '' }}>
                                                    Normal Ticket
                                                </option>
                                                <option value="vip"
                                                    {{ old('ticket_type') == 'vip' ? 'selected' : '' }}>
                                                    VIP Ticket
                                                </option>
                                            </select>
                                            @error('ticket_type')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="type">
                                                Visitor Category <span class="text-danger">*</span>
                                            </label>
                                            <select name="type"
                                                    id="type"
                                                    class="form-control @error('type') is-invalid @enderror"
                                                    required>
                                                <option value="">Select Visitor Category</option>
                                                <option value="Child"
                                                    {{ old('type') == 'Child' ? 'selected' : '' }}>
                                                    Child
                                                </option>
                                                <option value="Adult"
                                                    {{ old('type') == 'Adult' ? 'selected' : '' }}>
                                                    Adult
                                                </option>
                                                <option value="Senior Citizen"
                                                    {{ old('type') == 'Senior Citizen' ? 'selected' : '' }}>
                                                    Senior Citizen
                                                </option>
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="min_age">
                                                Minimum Age (Years)
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="number"
                                                name="min_age"
                                                id="min_age"
                                                class="form-control @error('min_age') is-invalid @enderror"
                                                value="{{ old('min_age', 0) }}"
                                                min="0"
                                                max="150"
                                                step="1"
                                                placeholder="Enter minimum age"
                                                required>
                                            @error('min_age')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <small class="text-muted">
                                                Example: 0 for children, 6 for adults.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="max_age">
                                                Maximum Age (Years)
                                            </label>
                                            <input type="number"
                                                name="max_age"
                                                id="max_age"
                                                class="form-control @error('max_age') is-invalid @enderror"
                                                value="{{ old('max_age') }}"
                                                min="0"
                                                max="150"
                                                step="1"
                                                placeholder="Enter maximum age">
                                            @error('max_age')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <small class="text-muted">
                                                Leave blank if there is no maximum age.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="price">
                                                Ticket Price (₹)
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="number"
                                                name="price"
                                                id="price"
                                                class="form-control @error('price') is-invalid @enderror"
                                                value="{{ old('price') }}"
                                                min="0"
                                                step="0.01"
                                                placeholder="Enter ticket price"
                                                required>
                                            @error('price')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <small class="text-muted">
                                                Enter 0 if the ticket is free.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-end">
                                <a href="{{ route('ticket-prices.index') }}"
                                class="btn btn-secondary mr-2">
                                    Cancel
                                </a>
                                <button type="reset"
                                        class="btn btn-warning mr-2">
                                    <i class="fas fa-redo"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Ticket Price
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@else
    @php abort(403); @endphp
@endcan