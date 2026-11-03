@can('reports.index')
@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h3 class="mb-1">Mandir / Puja / Pandit Report</h3>
            <p class="text-muted mb-0">
                View and download completed puja booking reports.
            </p>
        </div>
    </div>
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Report Filters</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.mandir-puja-pandit.index') }}" id="reportFilterForm">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="temple_id">
                            Select Temple <span class="text-danger">*</span>
                        </label>
                        <select name="temple_id"  id="temple_id" class="form-control" required>
                            <option value="">Select Temple</option>
                            @foreach($temples as $temple)
                                <option value="{{ $temple->id }}"
                                    {{ (string) request('temple_id') === (string) $temple->id ? 'selected' : '' }}>
                                    {{ $temple->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="pandit_id">
                            Select Pandit
                        </label>
                        <select name="pandit_id" id="pandit_id" class="form-control">
                            <option value="">All Pandits</option>
                        </select>
                        <small class="text-muted">
                            Only approved Pandits assigned to the selected temple will be displayed.
                        </small>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="from_date">
                            Completed From
                        </label>
                        <input type="date"  name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="to_date">
                            Completed To
                        </label>
                        <input type="date"  name="to_date" id="to_date"  class="form-control" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="submit"
                                class="btn btn-primary w-100">
                            Search Report
                        </button>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('reports.mandir-puja-pandit.index') }}"
                       class="btn btn-outline-secondary btn-sm">
                        Reset Filters
                    </a>
                </div>
            </form>
        </div>
    </div>
    @if(request()->filled('temple_id'))
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-1">
                            Total Completed Bookings
                        </p>
                        <h3 class="mb-0">
                            {{ $bookings->total() }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-1">
                            Selected Temple
                        </p>
                        <h5 class="mb-0">
                            {{ optional($temples->firstWhere('id', request('temple_id')))->name ?? 'N/A' }}
                        </h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-1">
                            Selected Pandit
                        </p>
                        <h5 class="mb-0" id="selectedPanditName">
                            All Pandits
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h5 class="mb-1">
                            Completed Puja Bookings
                        </h5>
                        <small class="text-muted">
                            Download all records matching your selected filters.
                        </small>
                    </div>
                    <div class="d-flex flex-wrap gap-2 mt-3 mt-md-0">
                        <button type="submit"
                                form="reportFilterForm"
                                formaction="{{ route('reports.mandir-puja-pandit.pdf') }}"
                                class="btn btn-danger btn-sm">
                            Download PDF
                        </button>
                        <button type="submit"
                                form="reportFilterForm"
                                formaction="{{ route('reports.mandir-puja-pandit.csv') }}"
                                class="btn btn-success btn-sm">
                            Download CSV
                        </button>
                        <button type="submit"
                                form="reportFilterForm"
                                formaction="{{ route('reports.mandir-puja-pandit.excel') }}"
                                class="btn btn-primary btn-sm">
                            Download Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    Completed Booking Details
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Booking Number</th>
                                <th>Customer Details</th>
                                <th>Mobile</th>
                                <th>Temple</th>
                                <th>Puja</th>
                                <th>Pandit</th>
                                <th>Package</th>
                                <th>Schedule Date</th>
                                <th>Completed Date</th>
                                <th>Persons</th>
                                <th>Amount</th>
                                <th>Completed By</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $key => $booking)
                                <tr>
                                    <td>
                                        {{ $bookings->firstItem() + $key }}
                                    </td>
                                    <td>
                                        <strong>
                                            {{ $booking->booking_number ?? 'N/A' }}
                                        </strong>
                                    </td>
                                    <td>
                                        <strong>
                                            {{ $booking->customer_name ?? 'N/A' }}
                                        </strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $booking->customer_email ?? '' }}
                                        </small>
                                    </td>
                                    <td>
                                        {{ $booking->customer_mobile ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $booking->temple->name ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $booking->puja->name ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $booking->pandit->name ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $booking->package_name
                                            ?? ($booking->pujaPackage->name ?? 'N/A') }}
                                    </td>
                                    <td>
                                        {{ $booking->schedule_date
                                            ? $booking->schedule_date->format('d-m-Y')
                                            : 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $booking->completed_at
                                            ? $booking->completed_at->format('d-m-Y h:i A')
                                            : 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $booking->person_count ?? 0 }}
                                    </td>
                                    <td>
                                        <strong>
                                            ₹{{ number_format((float) $booking->total_amount, 2) }}
                                        </strong>
                                    </td>
                                    <td>
                                        {{ $booking->completedBy->name ?? 'N/A' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            Completed
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="text-center py-4">
                                        <h6 class="text-muted mb-1">
                                            No Completed Puja Bookings Found
                                        </h6>
                                        <small>
                                            Try changing your temple, pandit or date filters.
                                        </small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($bookings->hasPages())
                    <div class="mt-3">
                        {{ $bookings->links() }}
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <h5>
                   Select Temple to Generate Report
                </h5>
                <p class="text-muted mb-0">
                    Select a temple, pandit and completion date range
                    to view completed puja bookings.
                </p>
            </div>
        </div>
    @endif
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function () {

    function loadPandits(selectedPandit = '') {
        let templeId = $('#temple_id').val();
        let panditDropdown = $('#pandit_id');
        if (!templeId) {
            panditDropdown.html(
               '<option value="">All Pandits</option>'
            );
            $('#selectedPanditName').text('All Pandits');
            return;
        }
        panditDropdown.html(
            '<option value="">Loading Pandits...</option>'
        );
        $.ajax({
            url: @json(route('reports.mandir-puja-pandit.pandits')),
            type: 'GET',
            dataType: 'json',
            data: {
               temple_id: templeId
            },
            success: function (response) {
                let options = '<option value="">All Pandits</option>';
                if (
                    response.success &&
                    Array.isArray(response.data) &&
                    response.data.length > 0
                ) {
                    $.each(response.data, function (index, pandit) {
                        let selected =
                            String(selectedPandit) === String(pandit.id)
                                ? 'selected'
                                : '';
                        options += `
                            <option value="${pandit.id}" ${selected}>
                                ${$('<div>').text(pandit.name).html()}
                            </option>
                        `;
                    });
                } else {
                    options = '<option value="">No approved Pandits found</option>';
                }
                panditDropdown.html(options);
                updatePanditName();
            },
            error: function (xhr) {
                console.error('Pandit API Error:', xhr.status);
                console.error(xhr.responseText);
                panditDropdown.html(
                   '<option value="">Unable to load Pandits</option>'
                );
            }
        });
    }
    function updatePanditName() {
        let panditId = $('#pandit_id').val();
        if (panditId) {
            $('#selectedPanditName').text(
                $('#pandit_id option:selected').text()
            );
        } else {
            $('#selectedPanditName').text('All Pandits');
        }
    }
    $('#temple_id').on('change', function () {
        loadPandits('');
    });
    $('#pandit_id').on('change', function () {
        updatePanditName();
    });
    let selectedPandit = @json(request('pandit_id'));
    if ($('#temple_id').val()) {
        loadPandits(selectedPandit);
    }
});
</script>
@endpush
@else
    @php
        abort(403);
    @endphp
@endcan