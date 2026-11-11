@can('reports.city-wise-revenue')
@extends('admin.layouts.app')

@section('content')

@php
    $hasReport = !is_null($report);
    $filters = $hasReport ? $report['filters'] : [];
    $summary = $hasReport ? $report['summary'] : [];
@endphp

<div class="container-fluid">

    <div class="row mb-3">
        <div class="col-md-12">
            <h4 class="mb-1">
                <i data-feather="bar-chart-2"></i>
                City-wise Revenue & Booking Report
            </h4>
            <p class="text-muted mb-0">
                State, City and Date-wise Puja & Ticket Booking Revenue
            </p>
        </div>
    </div>

    {{-- FILTER CARD --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i data-feather="filter"></i>
                Report Filters
            </h5>
        </div>

        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form
                method="GET"
                action="{{ route('reports.city-wise-revenue-booking.index') }}"
                id="reportFilterForm"
            >
                <div class="row">

                    <div class="col-lg-3 col-md-6 mb-3">
                        <label class="form-label">
                            State <span class="text-danger">*</span>
                        </label>

                        <select
                            name="state_id"
                            id="state_id"
                            class="form-control"
                            required
                        >
                            <option value="">Select State</option>

                            @foreach($states as $state)
                                <option
                                    value="{{ $state->id }}"
                                    {{ (string) request('state_id') === (string) $state->id ? 'selected' : '' }}
                                >
                                    {{ $state->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <label class="form-label">
                            City <span class="text-danger">*</span>
                        </label>

                        <select
                            name="city_id"
                            id="city_id"
                            class="form-control"
                            required
                        >
                            <option value="">Select City</option>

                            @foreach($cities as $city)
                                <option
                                    value="{{ $city->id }}"
                                    {{ (string) request('city_id') === (string) $city->id ? 'selected' : '' }}
                                >
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <label class="form-label">From Date</label>

                        <input
                            type="date"
                            name="from_date"
                            id="from_date"
                            class="form-control"
                            value="{{ request('from_date') }}"
                            max="{{ date('Y-m-d') }}"
                        >
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <label class="form-label">To Date</label>

                        <input
                            type="date"
                            name="to_date"
                            id="to_date"
                            class="form-control"
                            value="{{ request('to_date') }}"
                            max="{{ date('Y-m-d') }}"
                        >
                    </div>

                </div>

                <div class="row mt-2">
                    <div class="col-md-12 d-flex flex-wrap" style="gap: 10px;">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i data-feather="search"></i>
                            Apply Filter
                        </button>

                        <a
                            href="{{ route('reports.city-wise-revenue-booking.index') }}"
                            class="btn btn-secondary"
                        >
                            Reset
                        </a>

                    </div>
                </div>

            </form>
        </div>
    </div>

    @if($hasReport)

        {{-- SELECTED LOCATION --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">

                    <div class="col-lg-3 col-md-6 mb-2">
                        <strong>State:</strong>
                        {{ $report['state']->name }}
                    </div>

                    <div class="col-lg-3 col-md-6 mb-2">
                        <strong>City:</strong>
                        {{ $report['city']->name }}
                    </div>

                    <div class="col-lg-3 col-md-6 mb-2">
                        <strong>From:</strong>
                        {{ $filters['from_date'] ?? 'All Dates' }}
                    </div>

                    <div class="col-lg-3 col-md-6 mb-2">
                        <strong>To:</strong>
                        {{ $filters['to_date'] ?? 'All Dates' }}
                    </div>

                </div>
            </div>
        </div>

        {{-- SUMMARY CARDS --}}
        <div class="row">

            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card h-100 border-primary">
                    <div class="card-body">
                        <div class="text-muted mb-2">Total Bookings</div>
                        <h3 class="mb-0">
                            {{ number_format($summary['total_bookings']) }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card h-100 border-success">
                    <div class="card-body">
                        <div class="text-muted mb-2">Paid Bookings</div>
                        <h3 class="mb-0">
                            {{ number_format($summary['paid_bookings']) }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card h-100 border-info">
                    <div class="card-body">
                        <div class="text-muted mb-2">Total Temples</div>
                        <h3 class="mb-0">
                            {{ number_format($summary['total_temples']) }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted mb-2">Puja Bookings</div>
                        <h3 class="mb-0">
                            {{ number_format($summary['puja_bookings']) }}
                        </h3>
                        <small class="text-muted">
                            All Puja booking statuses
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted mb-2">Ticket Bookings</div>
                        <h3 class="mb-0">
                            {{ number_format($summary['ticket_bookings']) }}
                        </h3>
                        <small class="text-muted">
                            Distinct ticket orders
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card h-100 border-success">
                    <div class="card-body">
                        <div class="text-muted mb-2">Total Revenue</div>
                        <h3 class="mb-0">
                            ₹{{ number_format($summary['total_revenue'], 2) }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted mb-2">Puja Revenue</div>
                        <h4 class="mb-0">
                            ₹{{ number_format($summary['puja_revenue'], 2) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted mb-2">Ticket Revenue</div>
                        <h4 class="mb-0">
                            ₹{{ number_format($summary['ticket_revenue'], 2) }}
                        </h4>
                    </div>
                </div>
            </div>

        </div>

        {{-- REPORT TABLE --}}
        <div class="card">

            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap"
                     style="gap: 12px;">

                    <h5 class="mb-0">
                        Temple-wise Booking & Revenue Details
                    </h5>

                    <div class="d-flex flex-wrap" style="gap: 8px;">

                        <a
                            href="{{ route('reports.city-wise-revenue-booking.pdf', request()->query()) }}"
                            class="btn btn-danger btn-sm"
                        >
                            <i data-feather="file-text"></i>
                            PDF
                        </a>

                        <a
                            href="{{ route('reports.city-wise-revenue-booking.excel', request()->query()) }}"
                            class="btn btn-success btn-sm"
                        >
                            <i data-feather="file"></i>
                            Excel
                        </a>

                        <a
                            href="{{ route('reports.city-wise-revenue-booking.csv', request()->query()) }}"
                            class="btn btn-info btn-sm"
                        >
                            <i data-feather="download"></i>
                            CSV
                        </a>

                    </div>
                </div>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table
                        id="cityRevenueTable"
                        class="table table-bordered table-striped table-hover"
                        style="width: 100%;"
                    >
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Temple Name</th>
                                <th>Module</th>
                                <th>Total Bookings</th>
                                <th>Paid Bookings</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($report['rows'] as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        {{ $row['temple_name'] }}
                                    </td>

                                    <td>
                                        @if($row['module'] === 'Puja')
                                            <span class="badge bg-primary text-white">
                                                Puja
                                            </span>
                                        @else
                                            <span class="badge bg-info text-white">
                                                Ticket
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ number_format($row['total_bookings']) }}
                                    </td>

                                    <td>
                                        {{ number_format($row['paid_bookings']) }}
                                    </td>

                                    <td>
                                        ₹{{ number_format($row['revenue'], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr class="font-weight-bold">
                                <th></th>
                                <th>GRAND TOTAL</th>
                                <th></th>
                                <th>
                                    {{ number_format($summary['total_bookings']) }}
                                </th>
                                <th>
                                    {{ number_format($summary['paid_bookings']) }}
                                </th>
                                <th>
                                    ₹{{ number_format($summary['total_revenue'], 2) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>

    @else

        <div class="card">
            <div class="card-body text-center py-5">
                <h5>Select State and City</h5>
                <p class="text-muted mb-0">
                    Choose filters and click Apply Filter to view the report.
                </p>
            </div>
        </div>

    @endif

</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {

    // STATE -> CITY
    $('#state_id').on('change', function () {

        let stateId = $(this).val();
        let cityDropdown = $('#city_id');

        cityDropdown.html(
            '<option value="">Select City</option>'
        );

        if (!stateId) {
            return;
        }

        cityDropdown.html(
            '<option value="">Loading cities...</option>'
        );

        $.ajax({
            url: "{{ route('reports.city-wise-revenue-booking.cities') }}",
            type: "GET",
            data: {
                state_id: stateId
            },
            success: function (cities) {

                cityDropdown.html(
                    '<option value="">Select City</option>'
                );

                $.each(cities, function (index, city) {
                    cityDropdown.append(
                        $('<option>', {
                            value: city.id,
                            text: city.name
                        })
                    );
                });
            },
            error: function () {
                cityDropdown.html(
                    '<option value="">Unable to load cities</option>'
                );
            }
        });
    });

    // DATE VALIDATION
    $('#from_date').on('change', function () {
        let fromDate = $(this).val();

        $('#to_date').attr('min', fromDate);

        if ($('#to_date').val() &&
            $('#to_date').val() < fromDate) {
            $('#to_date').val('');
        }
    });

    $('#from_date').trigger('change');

    // DATATABLE
    @if($hasReport)
        if ($.fn.DataTable) {
            $('#cityRevenueTable').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'All']
                ],
                ordering: true,
                searching: true,
                paging: true,
                info: true,
                order: [[1, 'asc']],
                columnDefs: [
                    {
                        targets: 0,
                        orderable: false
                    }
                ],
                language: {
                    emptyTable: 'No booking records found'
                }
            });
        }
    @endif

});
</script>
@endpush

@else
    @php
        abort(403);
    @endphp
@endcan