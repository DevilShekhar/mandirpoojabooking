@can('reports-store.index')
@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h4 class="mb-1">Store Manager Completed Samagri Report</h4>
        <p class="text-muted mb-0">
            Completed bookings with verified Samagri OTP
        </p>
    </div>
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">Report Filters</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.store-manager-completed.index') }}" id="reportFilterForm">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="temple_id" class="form-label">
                            Select Temple <span class="text-danger">*</span>
                        </label>
                        <select name="temple_id" id="temple_id" class="form-control" required>
                            <option value="">-- Select Temple --</option>
                            @foreach($temples as $temple)
                                <option value="{{ $temple->id }}"
                                    {{ (string) request('temple_id') === (string) $temple->id ? 'selected' : '' }}>
                                    {{ $temple->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('temple_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            Store Manager Name
                        </label>
                        <input type="text"
                               id="store_manager_name"
                               class="form-control"
                               value="{{ $storeManager->name ?? '' }}"
                               placeholder="Select temple first"
                               readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            Store Manager Email
                        </label>
                        <input type="text"
                               id="store_manager_email"
                               class="form-control"
                               value="{{ $storeManager->email ?? '' }}"
                               placeholder="Store Manager email"
                               readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            Store Manager Phone
                        </label>
                        <input type="text"
                               id="store_manager_phone"
                               class="form-control"
                               value="{{ $storeManager->phone ?? '' }}"
                               placeholder="Store Manager phone"
                               readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="from_date" class="form-label">
                            From Date
                        </label>
                        <input type="date"
                               name="from_date"
                               id="from_date"
                               class="form-control"
                               value="{{ request('from_date') }}">
                        @error('from_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="to_date" class="form-label">
                            To Date
                        </label>
                        <input type="date"
                               name="to_date"
                               id="to_date"
                               class="form-control"
                               value="{{ request('to_date') }}">
                        @error('to_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="submit"
                                name="generate_report"
                                value="1"
                                class="btn btn-primary w-100">
                            Generate Report
                        </button>
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <a href="{{ route('reports.store-manager-completed.index') }}"
                           class="btn btn-secondary w-100">
                            Reset
                        </a>
                    </div>
                </div>
                <div id="manager_message" class="mt-1"></div>
            </form>
        </div>
    </div>
    @if($reportGenerated)
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
            <div>
                <h5 class="mb-1">Completed Booking Report</h5>
                <p class="text-muted mb-0">
                    Temple:
                    <strong>{{ $selectedTemple->name }}</strong>
                    <span class="mx-2">|</span>
                    Store Manager:
                    <strong>{{ $storeManager->name ?? 'Not Assigned' }}</strong>
                </p>
                @if(request('from_date') || request('to_date'))
                    <p class="text-muted mb-0 mt-1">
                        Period:
                        {{ request('from_date')
                            ? \Carbon\Carbon::parse(request('from_date'))->format('d-m-Y')
                            : 'Beginning' }}
                        to
                        {{ request('to_date')
                            ? \Carbon\Carbon::parse(request('to_date'))->format('d-m-Y')
                            : 'No end date' }}
                    </p>
                @endif
            </div>
            <div class="mt-2">
                <a href="{{ route('reports.store-manager-completed.csv', request()->query()) }}"
                   class="btn btn-success btn-sm">
                    Download CSV
                </a>
                <a href="{{ route('reports.store-manager-completed.pdf', request()->query()) }}"
                   class="btn btn-danger btn-sm">
                    Download PDF
                </a>
                <a href="{{ route('reports.store-manager-completed.excel', request()->query()) }}"
                   class="btn btn-primary btn-sm">
                    Download Excel
                </a>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <p class="text-muted mb-1">
                            Total Completed Bookings
                        </p>
                        <h3 class="mb-0">
                            {{ number_format($summary['total_bookings']) }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <p class="text-muted mb-1">
                            Total Item Quantity
                        </p>
                        <h3 class="mb-0">
                            {{ number_format($summary['total_quantity'], 2) }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <p class="text-muted mb-1">
                            Total Item Amount
                        </p>
                        <h3 class="mb-0">
                            ₹{{ number_format($summary['total_amount'], 2) }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Verified Samagri Details</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle">
                       <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Booking Number</th>
                                <th>Customer Name</th>
                                <th>Temple</th>
                                <th>Store Manager</th>
                                <th>Item Name</th>
                                <th>Unit</th>
                                <th>Quantity</th>
                                <th>Item Price</th>
                                <th>Item Total</th>
                                <th>Completed Date</th>
                                <th>Verified Date</th>
                                <th>Verification</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $rowNumber = 1;
                            @endphp
                            @forelse($bookings as $booking)
                                @forelse($booking->report_items as $item)
                                    <tr>
                                        <td>{{ $rowNumber++ }}</td>
                                        <td>
                                            <strong>
                                                {{ $booking->booking_number }}
                                            </strong>
                                        </td>
                                        <td>
                                            {{ $booking->customer_name ?? 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $booking->report_temple_name }}
                                        </td>
                                        <td>
                                            {{ $booking->report_store_manager ?? 'Not Assigned' }}
                                        </td>
                                        <td>{{ $item['name'] }}</td>
                                        <td>{{ $item['unit'] }}</td>
                                        <td>
                                           {{ number_format($item['quantity'], 2) }}
                                        </td>
                                        <td>
                                            ₹{{ number_format($item['price'], 2) }}
                                        </td>
                                        <td>
                                            <strong>
                                                ₹{{ number_format($item['total'], 2) }}
                                            </strong>
                                        </td>
                                        <td>
                                            {{ $booking->completed_at
                                                ? \Carbon\Carbon::parse($booking->completed_at)->format('d-m-Y h:i A')
                                                : '-' }}
                                        </td>
                                        <td>
                                            {{ $booking->samgri_otp_verified_at
                                                ? \Carbon\Carbon::parse($booking->samgri_otp_verified_at)->format('d-m-Y h:i A')
                                                : '-' }}
                                        </td>
                                        <td>
                                            <span class="badge bg-success">
                                                Verified
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>{{ $rowNumber++ }}</td>
                                        <td>{{ $booking->booking_number }}</td>
                                        <td>{{ $booking->customer_name ?? 'N/A' }}</td>
                                        <td>{{ $booking->report_temple_name }}</td>
                                        <td>{{ $booking->report_store_manager ?? 'Not Assigned' }}</td>
                                        <td colspan="6" class="text-muted">
                                            No item details found.
                                        </td>
                                        <td>
                                            <span class="badge bg-success">
                                                Verified
                                            </span>
                                        </td>
                                    </tr>
                                @endforelse
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center py-4">
                                        No completed and verified bookings found for the selected temple and dates.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($bookings->isNotEmpty())
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="7" class="text-end">
                                        Total
                                    </th>
                                    <th>
                                        {{ number_format($summary['total_quantity'], 2) }}
                                    </th>
                                    <th></th>
                                    <th>
                                        ₹{{ number_format($summary['total_amount'], 2) }}
                                    </th>
                                    <th colspan="3"></th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    @elseif(request()->boolean('generate_report'))
        <div class="alert alert-info">
            No completed and verified bookings found for the selected temple and dates.
        </div>
    @endif
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const templeSelect = document.getElementById('temple_id');
    const managerName = document.getElementById('store_manager_name');
    const managerEmail = document.getElementById('store_manager_email');
    const managerPhone = document.getElementById('store_manager_phone');
    const managerMessage = document.getElementById('manager_message');
    const managerUrlTemplate = @json(
        route(
            'reports.store-manager-completed.manager',
            ['templeId' => '__TEMPLE_ID__']
        )
    );
    function clearManagerFields() {
        managerName.value = '';
        managerEmail.value = '';
        managerPhone.value = '';
    }
    function fetchStoreManager(templeId) {
        clearManagerFields();
        managerMessage.innerHTML = '';
        if (!templeId) {
            return;
        }
        managerName.value = 'Loading...';
        const url = managerUrlTemplate.replace(
            '__TEMPLE_ID__',
            encodeURIComponent(templeId)
        );
        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Unable to fetch Store Manager.');
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.manager) {
                managerName.value = data.manager.name ?? '';
                managerEmail.value = data.manager.email ?? '';
                managerPhone.value = data.manager.phone ?? '';
                managerMessage.innerHTML =
                    '<span class="text-success">Store Manager found.</span>';

            } else {
                clearManagerFields();
                managerMessage.innerHTML =
                    '<span class="text-danger">' +
                    (data.message ?? 'No Store Manager assigned to this temple.') +
                    '</span>';
            }
        })
        .catch(error => {
            clearManagerFields();
            managerMessage.innerHTML =
                '<span class="text-danger">' +
                error.message +
                '</span>';
        });
    }
    templeSelect.addEventListener('change', function () {
        fetchStoreManager(this.value);
    });
    if (templeSelect.value) {
        fetchStoreManager(templeSelect.value);
    }
});
</script>
@endsection
@else
    @php
        abort(403);
    @endphp
@endcan