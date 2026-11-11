@can('reports.pujari-assignment-performance')
@extends('admin.layouts.app')
@section('content')
<style>
    .report-page { padding-bottom: 25px; }
    .report-heading { margin-bottom: 22px; }
    .report-heading h4 { font-size: 22px; font-weight: 700; color: #263445; margin-bottom: 5px; }
    .report-heading p { color: #7b8794; font-size: 13px; margin: 0; }
    .report-card { background: #fff; border: 1px solid #e8edf3; border-radius: 10px; box-shadow: 0 2px 10px rgba(25,42,70,.04); margin-bottom: 22px; overflow: hidden; }
    .report-card-header { padding: 17px 20px; border-bottom: 1px solid #edf0f4; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
    .report-card-header h5 { color: #263445; font-size: 16px; font-weight: 700; margin: 0; }
    .report-card-body { padding: 22px 20px; }
    .report-label { display: block; color: #475569; font-size: 13px; font-weight: 600; margin-bottom: 9px; }
    .report-required { color: #dc3545; }
    .report-control { width: 100%; height: 46px; border: 1px solid #dce3ec; border-radius: 7px; padding: 0 13px; color: #344054; background: #fff; font-size: 14px; }
    .report-control:focus { border-color: #6478ee; box-shadow: 0 0 0 3px rgba(100,120,238,.12); outline: none; }
    .report-control:disabled { background: #f3f5f8; color: #8792a2; cursor: not-allowed; }
    .report-help { display: block; margin-top: 7px; font-size: 12px; color: #8491a3; min-height: 17px; }
    .report-filter-actions { display: flex; gap: 10px; margin-top: 24px; padding-top: 18px; border-top: 1px solid #edf0f4; }
    .report-btn { min-height: 42px; padding: 9px 18px; border-radius: 7px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 7px; text-decoration: none; border: 1px solid transparent; cursor: pointer; }
    .report-btn-primary { background: #596fe8; border-color: #596fe8; color: #fff; }
    .report-btn-primary:hover { background: #455bd0; color: #fff; }
    .report-btn-secondary { background: #f1f3f6; border-color: #e2e6ec; color: #475569; }
    .report-btn-secondary:hover { background: #e5e9ef; color: #263445; }
    .report-btn-danger { background: #fff; border-color: #f1c5c8; color: #c83e49; }
    .report-btn-success { background: #fff; border-color: #b9dfca; color: #198754; }
    .report-btn-info { background: #fff; border-color: #b9dce9; color: #1683a5; }
    .report-summary-card { height: 100%; background: #fff; border: 1px solid #e8edf3; border-radius: 10px; padding: 18px; box-shadow: 0 2px 10px rgba(25,42,70,.04); display: flex; justify-content: space-between; align-items: center; gap: 12px; }
    .report-summary-label { color: #7b8794; font-size: 12px; font-weight: 600; margin-bottom: 9px; }
    .report-summary-value { color: #263445; font-size: 24px; font-weight: 700; line-height: 1.2; margin: 0; }
    .report-summary-icon { width: 44px; height: 44px; min-width: 44px; border-radius: 10px; display: flex; justify-content: center; align-items: center; font-size: 17px; font-weight: 700; }
    .summary-blue { background: #edf1ff; color: #596fe8; }
    .summary-green { background: #eaf8f0; color: #22965a; }
    .summary-orange { background: #fff5e8; color: #dc8a24; }
    .summary-red { background: #fff0f0; color: #d94b55; }
    .summary-purple { background: #f3edff; color: #8558c9; }
    .summary-cyan { background: #eaf8fc; color: #2694b6; }
    .report-section-subtitle { color: #8792a2; font-size: 12px; margin: 5px 0 0; }
    .report-table { margin-bottom: 0; vertical-align: middle; white-space: nowrap; }
    .report-table thead th { background: #f5f7fa; color: #475569; font-size: 12px; font-weight: 700; padding: 13px 12px; border-bottom: 1px solid #e5eaf0; }
    .report-table tbody td { color: #475569; font-size: 13px; padding: 12px; border-color: #edf0f4; }
    .report-table tbody tr:hover { background: #fafbfe; }
    .report-name { color: #263445; font-weight: 600; }
    .report-badge { display: inline-block; padding: 5px 9px; border-radius: 5px; font-size: 11px; font-weight: 700; text-transform: capitalize; }
    .badge-paid, .badge-completed, .badge-confirmed, .badge-processed { background: #e9f8ef; color: #198754; }
    .badge-pending { background: #fff4df; color: #a76b0c; }
    .badge-cancelled, .badge-failed { background: #ffeded; color: #c43d49; }
    .badge-default { background: #eef1f5; color: #64748b; }
    .report-empty { padding: 35px 15px !important; text-align: center; color: #8792a2 !important; white-space: normal; }
    .report-empty strong { display: block; color: #475569; font-size: 14px; margin-bottom: 5px; }
    .report-empty span { font-size: 12px; }
    .report-context { display: flex; align-items: center; flex-wrap: wrap; gap: 8px 20px; }
    .report-context-item { color: #64748b; font-size: 12px; }
    .report-context-item strong { color: #263445; }
    @media (max-width: 767px) {
        .report-heading h4 { font-size: 19px; }
        .report-card-body { padding: 17px 14px; }
        .report-card-header { padding: 15px; }
        .report-filter-actions { flex-direction: column; }
        .report-filter-actions .report-btn { width: 100%; }
        .report-summary-card { padding: 15px; }
        .report-summary-value { font-size: 21px; }
        .report-export-actions { width: 100%; }
        .report-export-actions .report-btn { flex: 1; }
    }
</style>
<div class="container-fluid report-page">
    <div class="report-heading">
        <h4>Pujari Assignment & Performance Report</h4>
        <p>Track Puja assignments, Pujari performance, booking status and refunds.</p>
    </div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="report-card">
        <div class="report-card-header">
            <h5>Report Filters</h5>
        </div>
        <div class="report-card-body">
            <form method="GET"
                  action="{{ route('reports.pujari-assignment-performance.index') }}"
                  id="reportFilterForm">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <label for="temple_id" class="report-label">
                            Select Temple <span class="report-required">*</span>
                        </label>
                        <select name="temple_id" id="temple_id" class="report-control" required>
                            <option value="">Select Temple</option>
                            @foreach($temples as $temple)
                                <option value="{{ $temple->id }}"
                                    {{ (string)($filters['temple_id'] ?? '') === (string)$temple->id ? 'selected' : '' }}>
                                    {{ $temple->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="pandit_id" class="report-label">Select Pujari</label>
                        <select name="pandit_id" id="pandit_id" class="report-control"
                            {{ !empty($filters['temple_id']) ? '' : 'disabled' }}>
                            <option value="">All Pujaris</option>
                            @foreach($pandits as $pandit)
                                <option value="{{ $pandit->id }}"
                                    {{ (string)($filters['pandit_id'] ?? '') === (string)$pandit->id ? 'selected' : '' }}>
                                    {{ $pandit->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="report-help" id="pandit_help">
                            {{ !empty($filters['temple_id']) ? 'Select a Pujari or All Pujaris.' : 'Select a temple to load its Pujaris.' }}
                        </small>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="status" class="report-label">Select Status</label>
                        <select name="status" id="status" class="report-control">
                            <option value="all" {{ ($filters['status'] ?? 'all') === 'all' ? 'selected' : '' }}>All</option>
                            <option value="confirmed" {{ ($filters['status'] ?? '') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="cancelled" {{ ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="refund" {{ ($filters['status'] ?? '') === 'refund' ? 'selected' : '' }}>Refund</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="from_date" class="report-label">From Date</label>
                        <input type="date" name="from_date" id="from_date"
                               class="report-control"
                               value="{{ $filters['from_date'] ?? '' }}">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="to_date" class="report-label">To Date</label>
                        <input type="date" name="to_date" id="to_date"
                               class="report-control"
                               value="{{ $filters['to_date'] ?? '' }}">
                    </div>
                </div>
                <div class="report-filter-actions">
                    <button type="submit" class="report-btn report-btn-primary">
                        Apply Filter
                    </button>
                    <a href="{{ route('reports.pujari-assignment-performance.index') }}"
                       class="report-btn report-btn-secondary">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
    @if($hasFilter)
        <div class="report-card">
            <div class="report-card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="mb-2" style="font-size:16px;font-weight:700;color:#263445;">
                            {{ $selectedTemple->name ?? 'Selected Temple' }}
                        </h5>
                        <div class="report-context">
                            <div class="report-context-item">
                                Pujari: <strong>{{ $selectedPandit->name ?? 'All Pujaris' }}</strong>
                            </div>
                            <div class="report-context-item">
                                Status: <strong>{{ $selectedStatus }}</strong>
                            </div>
                            <div class="report-context-item">
                                From: <strong>{{ !empty($filters['from_date']) ? \Carbon\Carbon::parse($filters['from_date'])->format('d-m-Y') : 'All Dates' }}</strong>
                            </div>
                            <div class="report-context-item">
                                To: <strong>{{ !empty($filters['to_date']) ? \Carbon\Carbon::parse($filters['to_date'])->format('d-m-Y') : 'All Dates' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2 report-export-actions">
                        <a href="{{ route('reports.pujari-assignment-performance.pdf', request()->query()) }}"
                           class="report-btn report-btn-danger">PDF</a>
                        <a href="{{ route('reports.pujari-assignment-performance.excel', request()->query()) }}"
                           class="report-btn report-btn-success">Excel</a>
                        <a href="{{ route('reports.pujari-assignment-performance.csv', request()->query()) }}"
                           class="report-btn report-btn-info">CSV</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="report-summary-card">
                    <div>
                        <div class="report-summary-label">Total Assignments</div>
                        <h3 class="report-summary-value">{{ number_format($summary['total_assignments']) }}</h3>
                    </div>
                    <div class="report-summary-icon summary-blue">TA</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="report-summary-card">
                    <div>
                        <div class="report-summary-label">Confirmed</div>
                        <h3 class="report-summary-value">{{ number_format($summary['confirmed'] ?? 0) }}</h3>
                    </div>
                    <div class="report-summary-icon summary-green">CO</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="report-summary-card">
                    <div>
                        <div class="report-summary-label">Cancelled</div>
                        <h3 class="report-summary-value">{{ number_format($summary['cancelled']) }}</h3>
                    </div>
                    <div class="report-summary-icon summary-red">CA</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="report-summary-card">
                    <div>
                        <div class="report-summary-label">Refund Bookings</div>
                        <h3 class="report-summary-value">{{ number_format($summary['refund_bookings']) }}</h3>
                    </div>
                    <div class="report-summary-icon summary-orange">RF</div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="report-summary-card">
                    <div>
                        <div class="report-summary-label">Paid Bookings</div>
                        <h3 class="report-summary-value">{{ number_format($summary['paid_bookings']) }}</h3>
                    </div>
                    <div class="report-summary-icon summary-purple">PB</div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="report-summary-card">
                    <div>
                        <div class="report-summary-label">Total Revenue</div>
                        <h3 class="report-summary-value">₹{{ number_format($summary['revenue'], 2) }}</h3>
                    </div>
                    <div class="report-summary-icon summary-green">₹</div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="report-summary-card">
                    <div>
                        <div class="report-summary-label">Refund Amount</div>
                        <h3 class="report-summary-value">₹{{ number_format($summary['refund_amount'], 2) }}</h3>
                    </div>
                    <div class="report-summary-icon summary-orange">RA</div>
                </div>
            </div>
        </div>
        <div class="report-card">
            <div class="report-card-header">
                <div>
                    <h5>Pujari-wise Performance</h5>
                    <p class="report-section-subtitle">Performance summary based on selected filters.</p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover report-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pujari Name</th>
                            <th>Email</th>
                            <th>Total Assignments</th>
                            <th>Confirmed</th>
                            <th>Pending</th>
                            <th>Cancelled</th>
                            <th>Paid Bookings</th>
                            <th>Revenue</th>
                            <th>Completion %</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($performanceRows as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="report-name">{{ $row->pandit_name }}</td>
                                <td>{{ $row->pandit_email }}</td>
                                <td>{{ number_format($row->total_assignments) }}</td>
                                <td><span class="report-badge badge-confirmed">{{ $row->confirmed ?? 0 }}</span></td>
                                <td><span class="report-badge badge-pending">{{ $row->pending }}</span></td>
                                <td><span class="report-badge badge-cancelled">{{ $row->cancelled }}</span></td>
                                <td>{{ number_format($row->paid_bookings) }}</td>
                                <td><strong>₹{{ number_format($row->revenue, 2) }}</strong></td>
                                <td>{{ number_format($row->completion_percentage, 2) }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="report-empty">
                                    <strong>No Pujari performance records found</strong>
                                    <span>Try changing the selected filters.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="report-card">
            <div class="report-card-header">
                <div>
                    <h5>Puja Assignment Details</h5>
                    <p class="report-section-subtitle">Booking records matching the selected Temple, Pujari and Status.</p>
                </div>
                <span class="report-badge badge-default">
                    {{ number_format($bookingRows->count()) }} Records
                </span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover report-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Booking Number</th>
                            <th>Temple</th>
                            <th>Puja</th>
                            <th>Pujari</th>
                            <th>Customer</th>
                            <th>Mobile</th>
                            <th>Schedule Date</th>
                            <th>Time</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Booking Status</th>
                            <th>Refund Status</th>
                            <th>Refund Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookingRows as $index => $booking)
                            @php
                                $paymentStatus = strtolower($booking->payment_status ?? '');
                                $bookingStatus = strtolower($booking->booking_status ?? '');
                                $refundStatus = strtolower($booking->refund_status ?? '');
                                $paymentBadge = match($paymentStatus) {
                                    'paid' => 'badge-paid',
                                    'failed' => 'badge-failed',
                                    'pending' => 'badge-pending',
                                    default => 'badge-default',
                                };
                                $bookingBadge = match($bookingStatus) {
                                    'completed' => 'badge-completed',
                                    'cancelled' => 'badge-cancelled',
                                    'confirmed' => 'badge-confirmed',
                                    'pending' => 'badge-pending',
                                    default => 'badge-default',
                                };
                                $refundBadge = $refundStatus === 'processed'
                                    ? 'badge-processed'
                                    : 'badge-default';
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="report-name">{{ $booking->booking_number ?? 'N/A' }}</td>
                                <td>{{ $booking->temple_name ?? 'N/A' }}</td>
                                <td>{{ $booking->puja_name ?? 'N/A' }}</td>
                                <td>{{ $booking->pandit_name ?? 'N/A' }}</td>
                                <td>{{ $booking->customer_name ?? 'N/A' }}</td>
                                <td>{{ $booking->customer_mobile ?? 'N/A' }}</td>
                                <td>
                                    {{ $booking->schedule_date ? \Carbon\Carbon::parse($booking->schedule_date)->format('d-m-Y') : 'N/A' }}
                                </td>
                                <td>{{ $booking->schedule_time ?? 'N/A' }}</td>
                                <td>₹{{ number_format((float)$booking->total_amount, 2) }}</td>
                                <td>
                                    <span class="report-badge {{ $paymentBadge }}">
                                        {{ ucfirst($paymentStatus ?: 'N/A') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="report-badge {{ $bookingBadge }}">
                                        {{ ucfirst(str_replace('_', ' ', $bookingStatus ?: 'N/A')) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="report-badge {{ $refundBadge }}">
                                        {{ ucfirst($refundStatus ?: 'N/A') }}
                                    </span>
                                </td>
                                <td>₹{{ number_format((float)($booking->refund_amount ?? 0), 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="report-empty">
                                    <strong>No booking records found</strong>
                                    <span>There are no records matching the selected filters.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="report-card">
            <div class="report-card-body text-center py-5">
                <h5 class="mb-2">Select a Temple to View Report</h5>
                <p class="text-muted mb-0">
                    Select a Temple first. Its Pujaris will load automatically.
                    Choose a status and click Apply Filter.
                </p>
            </div>
        </div>
    @endif
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const templeSelect = document.getElementById('temple_id');
    const panditSelect = document.getElementById('pandit_id');
    const panditHelp = document.getElementById('pandit_help');
    if (!templeSelect || !panditSelect || !panditHelp) {
        return;
    }
    const selectedPanditId = @json($filters['pandit_id'] ?? '');
    const panditUrl = @json(
        route(
            'reports.pujari-assignment-performance.pandits',
            ['templeId' => '__TEMPLE_ID__']
        )
    );
    function loadPandits(templeId, selectedId = '') {
        panditSelect.innerHTML = '<option value="">Loading Pujaris...</option>';
        panditSelect.disabled = true;
        if (!templeId) {
            panditSelect.innerHTML = '<option value="">All Pujaris</option>';
            panditHelp.textContent = 'Select a temple to load its Pujaris.';
            return;
        }
        panditHelp.textContent = 'Loading Pujaris...';
        fetch(panditUrl.replace('__TEMPLE_ID__', encodeURIComponent(templeId)), {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Unable to load Pujaris.');
            }
            return response.json();
        })
        .then(data => {
            panditSelect.innerHTML = '<option value="">All Pujaris</option>';
            data.forEach(function (pandit) {
                const option = document.createElement('option');
                option.value = pandit.id;
                option.textContent = pandit.name;
                if (String(pandit.id) === String(selectedId)) {
                    option.selected = true;
                }
                panditSelect.appendChild(option);
            });
            panditSelect.disabled = false;
            panditHelp.textContent = data.length
                ? 'Select a Pujari or All Pujaris.'
                : 'No Pujaris found for this temple.';
        })
        .catch(error => {
            panditSelect.innerHTML = '<option value="">All Pujaris</option>';
            panditSelect.disabled = true;
            panditHelp.textContent = error.message;
            console.error(error);
        });
    }
    templeSelect.addEventListener('change', function () {
        loadPandits(this.value);
    });
    if (templeSelect.value) {
        loadPandits(templeSelect.value, selectedPanditId);
    }
});
</script>
@endsection
@else
    @php
        abort(403);
    @endphp
@endcan