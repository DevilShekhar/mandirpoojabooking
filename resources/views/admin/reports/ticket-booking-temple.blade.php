@can('reports-ticket.index')
@extends('admin.layouts.app')
@section('content')
<style>
    .report-filter-card {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 3px 18px rgba(0, 0, 0, 0.05);
    }
    .report-filter-card .card-header {
        background: #fff;
        border-bottom: 1px solid #eee;
        padding: 20px 25px;
    }
    .report-filter-card .card-body {
        padding: 25px;
    }
    .report-filter-card label {
        font-weight: 600;
        color: #444;
        margin-bottom: 8px;
    }
    .report-filter-card .form-control {
        height: 44px;
        border-radius: 7px;
        border: 1px solid #dce1e7;
    }
    .report-filter-card .form-control:focus {
        border-color: #6777ef;
        box-shadow: 0 0 0 2px rgba(103, 119, 239, 0.10);
    }
    .filter-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .filter-actions .btn {
        min-width: 145px;
        height: 42px;
        border-radius: 7px;
        font-weight: 600;
    }
    .report-summary-card {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 3px 18px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        transition: all 0.2s ease;
    }
    .report-summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 22px rgba(0, 0, 0, 0.09);
    }
    .report-summary-card .card-body {
        padding: 23px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .summary-info h6 {
        color: #777;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 9px;
        text-transform: uppercase;
    }
    .summary-info h3 {
        font-size: 26px;
        font-weight: 700;
        color: #303442;
        margin: 0;
    }
    .summary-icon {
        width: 58px;
        height: 58px;
        border-radius: 12px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 24px;
    }
    .summary-booking .summary-icon {
        background: #e9efff;
        color: #4361ee;
    }
    .summary-amount .summary-icon {
        background: #e5f8ef;
        color: #159957;
    }
    .report-table-card {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 3px 18px rgba(0, 0, 0, 0.05);
    }
    .report-table-card .card-header {
        background: #fff;
        padding: 20px 22px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .report-table-card .card-header h4 {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }
    .report-export-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .report-export-buttons .btn {
        border-radius: 6px;
        font-weight: 600;
        padding: 8px 14px;
    }
    .report-table-card .card-body {
        padding: 22px;
    }
    .report-table {
        white-space: nowrap;
        margin-bottom: 0;
    }
    .report-table thead th {
        background: #f4f6fb;
        color: #42495b;
        font-size: 12px;
        font-weight: 700;
        border-bottom: 2px solid #e5e8ef;
        padding: 13px 10px;
        vertical-align: middle;
    }
    .report-table tbody td {
        padding: 12px 10px;
        vertical-align: middle;
        font-size: 13px;
        color: #555;
    }
    .report-table tbody tr:hover {
        background: #f8faff;
    }
    .booking-reference {
        font-weight: 700;
        color: #4361ee;
    }
    .status-badge {
        display: inline-block;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: capitalize;
    }
    .status-paid,
    .status-confirmed {
        color: #16834b;
        background: #e4f7ec;
    }
    .status-pending {
        color: #a87500;
        background: #fff3d7;
    }
    .status-failed,
    .status-cancelled {
        color: #d33b45;
        background: #fde9ea;
    }
    .status-refunded {
        color: #2875b9;
        background: #e5f1fc;
    }
    .status-default {
        color: #666;
        background: #eeeeee;
    }
    .report-total-row {
        background: #f4f6fb;
        font-weight: 700;
    }
    .report-total-row th {
        padding: 14px 10px;
    }
    .empty-report {
        padding: 35px 15px !important;
        text-align: center;
        color: #888 !important;
    }
    .empty-report i {
        display: block;
        font-size: 35px;
        color: #c4cad5;
        margin-bottom: 12px;
    }
    @media(max-width: 767px) {
        .filter-actions {
            flex-wrap: wrap;
        }
        .filter-actions .btn {
            flex: 1;
            min-width: 120px;
        }
        .report-table-card .card-header {
            align-items: flex-start;
        }
        .report-export-buttons {
            width: 100%;
        }
    }
</style>
<section class="section">
    <div class="section-header">
        <h1>Ticket Booking Temple Report</h1>
    </div>
    <div class="section-body">
        <div class="card report-filter-card">
            <div class="card-header">
                <h4>
                    <i class="fas fa-filter mr-2 text-primary"></i>
                    Generate Ticket Booking Report
                </h4>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('reports.ticket-booking-temple-reports.index') }}">
                    <input type="hidden" name="generate" value="1">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>
                                    Select Temple
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="temple_id" class="form-control select2" required>
                                    <option value="">Select Temple</option>
                                    @foreach($temples as $temple)
                                        <option value="{{ $temple->id }}"
                                            {{ request('temple_id') == $temple->id ? 'selected' : '' }}>
                                            {{ $temple->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('temple_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <label>
                                    From Date
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="from_date" class="form-control"  value="{{ request('from_date') }}"  required>
                                @error('from_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <label>
                                    To Date
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" required>
                                @error('to_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <label>Payment Status</label>
                                <select name="payment_status" class="form-control">
                                    <option value="">All Payment Status</option>
                                    <option value="paid"
                                        {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
                                        Paid
                                    </option>
                                    <option value="pending"
                                        {{ request('payment_status') == 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="failed"
                                        {{ request('payment_status') == 'failed' ? 'selected' : '' }}>
                                        Failed
                                    </option>
                                    <option value="refunded"
                                        {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>
                                        Refunded
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <label>Booking Status</label>
                                <select name="booking_status" class="form-control">
                                    <option value="">All Booking Status</option>
                                    <option value="confirmed"
                                        {{ request('booking_status') == 'confirmed' ? 'selected' : '' }}>
                                        Confirmed
                                    </option>
                                    <option value="cancelled"
                                        {{ request('booking_status') == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled
                                    </option>
                                    <option value="pending"
                                        {{ request('booking_status') == 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="filter-actions">
                                <button type="submit"
                                        class="btn btn-primary">
                                    <i class="fas fa-search mr-1"></i>
                                    Generate Report
                                </button>
                                <a href="{{ route('reports.ticket-booking-temple-reports.index') }}" class="btn btn-light border">
                                    <i class="fas fa-redo mr-1"></i>
                                    Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @if(request('generate') == 1)
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="card report-summary-card summary-booking">
                        <div class="card-body">
                            <div class="summary-info">
                                <h6>Total Ticket Bookings</h6>
                                <h3>
                                    {{ number_format($totalBookings) }}
                                </h3>
                                <small class="text-muted">
                                    Total matching bookings
                                </small>
                            </div>
                            <div class="summary-icon">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="card report-summary-card summary-amount">
                        <div class="card-body">
                            <div class="summary-info">
                                <h6>Total Booking Amount</h6>
                                <h3>
                                    ₹{{ number_format($totalAmount, 2) }}
                                </h3>
                                <small class="text-muted">
                                    Total amount of matching bookings
                                </small>
                            </div>
                            <div class="summary-icon">
                                <i class="fas fa-rupee-sign"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card report-table-card">
                <div class="card-header">
                    <h4>
                        <i class="fas fa-list-alt mr-2 text-primary"></i>
                        Ticket Booking Details
                    </h4>
                    <div class="report-export-buttons">
                        <a href="{{ route('reports.ticket-booking-temple-reports.pdf', request()->only([
                            'temple_id','from_date','to_date','payment_status','booking_status'])) }}" class="btn btn-danger">
                            <i class="fas fa-file-pdf mr-1"></i>
                            PDF
                        </a>
                        <a href="{{ route('reports.ticket-booking-temple-reports.csv', request()->only(['temple_id', 'from_date', 'to_date', 'payment_status','booking_status'])) }}" class="btn btn-success">
                            <i class="fas fa-file-csv mr-1"></i>
                            CSV
                        </a>
                        <a href="{{ route('reports.ticket-booking-temple-reports.excel', request()->only([
                            'temple_id','from_date','to_date','payment_status','booking_status'])) }}"
                           class="btn btn-primary">
                            <i class="fas fa-file-excel mr-1"></i>
                            Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover report-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Booking Reference</th>
                                    <th>Booking Date</th>
                                    <th>Member Name</th>
                                    <th>Aadhaar Number</th>
                                    <th>Ticket Type</th>
                                    <th>Type</th>
                                    <th>Age</th>
                                    <th>Price</th>
                                    <th>Payment Status</th>
                                    <th>Booking Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $key => $booking)
                                    @php
                                        $paymentStatus = strtolower($booking->payment_status ?? 'pending');
                                        $bookingStatus = strtolower($booking->booking_status ?? 'pending');
                                        $paymentBadge = match($paymentStatus) {
                                            'paid', 'success', 'captured', 'completed' => 'paid',
                                            'failed' => 'failed',
                                            'refunded' => 'refunded',
                                            'cancelled', 'canceled' => 'cancelled',
                                            default => 'pending',
                                        };
                                        $bookingBadge = match($bookingStatus) {
                                            'confirmed', 'confirm', 'completed' => 'confirmed',
                                            'cancelled', 'canceled' => 'cancelled',
                                            'pending' => 'pending',
                                            default => 'default',
                                        };
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $bookings->firstItem() + $key }}
                                        </td>
                                        <td>
                                            <span class="booking-reference">
                                                {{ $booking->booking_reference }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $booking->booking_date
                                                ? \Carbon\Carbon::parse($booking->booking_date)->format('d-m-Y')
                                                : 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $booking->member_name ?? 'N/A' }}
                                        </td>
                                        <td>
                                            @php
                                                $aadhaar = (string) ($booking->aadhaar_number ?? '');
                                                $maskedAadhaar = strlen($aadhaar) > 4
                                                    ? 'XXXX XXXX ' . substr($aadhaar, -4)
                                                    : $aadhaar;
                                            @endphp
                                            {{ $maskedAadhaar ?: 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $booking->ticket_type ?? 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $booking->type ?? 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $booking->age ?? 'N/A' }}
                                        </td>
                                        <td>
                                            <strong>
                                                ₹{{ number_format((float) $booking->price, 2) }}
                                            </strong>
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ $paymentBadge }}">
                                                {{ ucfirst($paymentStatus) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ $bookingBadge }}">
                                                {{ ucfirst($bookingStatus) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="empty-report">
                                            <i class="fas fa-folder-open"></i>
                                            <strong>No Ticket Booking Records Found</strong>
                                            <p class="mb-0 mt-2">
                                                No records match your selected temple,
                                                date range and status filters.
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($bookings->count() > 0)
                                <tfoot>
                                    <tr class="report-total-row">
                                        <th colspan="8" class="text-right">
                                            Total Amount
                                        </th>
                                        <th colspan="3">
                                            ₹{{ number_format($totalAmount, 2) }}
                                        </th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    @if($bookings instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap">
                            <div class="text-muted mb-2">
                                Showing
                                {{ $bookings->firstItem() ?? 0 }}
                                to
                                {{ $bookings->lastItem() ?? 0 }}
                                of
                                {{ $bookings->total() }}
                                bookings
                            </div>
                            <div>
                                {{ $bookings->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
@else
    @php
        abort(403);
    @endphp
@endcan