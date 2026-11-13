@can('reports-online-puja.index')
@extends('admin.layouts.app')

@section('content')
<style>
    .opr-filter-card,.opr-summary-card,.opr-table-card{border:0;border-radius:12px;box-shadow:0 3px 18px rgba(0,0,0,.05);margin-bottom:22px}
    .opr-filter-card .card-header,.opr-table-card .card-header{background:#fff;border-bottom:1px solid #eee;padding:18px 22px}
    .opr-filter-card .card-body,.opr-table-card .card-body{padding:22px}
    .opr-filter-card label{font-weight:600;color:#444;margin-bottom:7px}
    .opr-filter-card .form-control{height:43px;border-radius:7px;border:1px solid #dce1e7}
    .opr-filter-grid{display:grid;grid-template-columns:repeat(4,minmax(150px,1fr));gap:15px}
    .opr-actions,.opr-export{display:flex;gap:8px;flex-wrap:wrap;margin-top:17px}
    .opr-summary-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:15px;margin-bottom:22px}
    .opr-summary-card .card-body{padding:20px}
    .opr-summary-card small{display:block;color:#777;font-weight:600;text-transform:uppercase;margin-bottom:8px}
    .opr-summary-card h3{font-size:24px;font-weight:700;margin:0;color:#303442;overflow-wrap:anywhere}
    .opr-summary-card .sub{font-size:11px;color:#89909c;margin-top:7px}
    .opr-table-card .card-header{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px}
    .opr-table-card .card-header h4{font-size:16px;font-weight:700;margin:0}
    .opr-tabs{display:flex;gap:8px;border-bottom:1px solid #e9ebf0;margin-bottom:20px}
    .opr-tabs a{padding:12px 16px;text-decoration:none;color:#667085;font-weight:600;border-bottom:3px solid transparent}
    .opr-tabs a.active{color:#6777ef;border-color:#6777ef}
    .opr-table{white-space:nowrap;margin-bottom:0}
    .opr-table thead th{background:#f4f6fb;color:#42495b;font-size:12px;font-weight:700;padding:12px 10px;border-bottom:2px solid #e5e8ef}
    .opr-table tbody td{font-size:13px;color:#555;padding:11px 10px;vertical-align:middle}
    .opr-table tbody tr:hover{background:#f8faff}
    .opr-badge{display:inline-block;padding:5px 10px;border-radius:20px;font-size:11px;font-weight:700;text-transform:capitalize}
    .opr-paid,.opr-completed{color:#16834b;background:#e4f7ec}
    .opr-pending{color:#a87500;background:#fff3d7}
    .opr-failed,.opr-cancelled{color:#d33b45;background:#fde9ea}
    .opr-refunded{color:#2875b9;background:#e5f1fc}
    .opr-default{color:#666;background:#eee}
    .opr-empty{text-align:center;padding:30px!important;color:#888}
    .opr-money{font-weight:700;color:#16834b}
    @media(max-width:991px){.opr-filter-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.opr-summary-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
    @media(max-width:575px){.opr-filter-grid,.opr-summary-grid{grid-template-columns:1fr}.opr-filter-card .card-body,.opr-table-card .card-body{padding:14px}.opr-table-card .card-header{align-items:flex-start}}
</style>

<section class="section">
    <div class="section-header">
        <h1>Reports Online Puja</h1>
    </div>

    <div class="section-body">
        <div class="card opr-filter-card">
            <div class="card-header">
                <h4><i class="fas fa-filter mr-2 text-primary"></i> Generate Online Puja Report</h4>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('reports.online-puja-report.index') }}">
                    <input type="hidden" name="type" value="{{ $filters['type'] }}">

                    <div class="opr-filter-grid">
                        <div class="form-group mb-0">
                            <label>Report Period</label>
                            <select name="period" id="oprPeriod" class="form-control">
                                @foreach(['daily'=>'Daily','weekly'=>'Weekly','monthly'=>'Monthly','yearly'=>'Yearly','custom'=>'Custom Date Range'] as $value=>$label)
                                    <option value="{{ $value }}" {{ $filters['period']===$value?'selected':'' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label>From Date</label>
                            <input type="date" name="from_date" id="oprFrom" class="form-control" value="{{ $filters['from_input'] }}">
                        </div>
                        <div class="form-group mb-0">
                            <label>To Date</label>
                            <input type="date" name="to_date" id="oprTo" class="form-control" value="{{ $filters['to_input'] }}">
                        </div>
                        <div class="form-group mb-0">
                            <label>Payment Status</label>
                            <select name="payment_status" class="form-control">
                                <option value="">All Payment Status</option>
                                @foreach(['paid','success','successful','captured','pending','failed','refunded','partially_refunded'] as $status)
                                    <option value="{{ $status }}" {{ $filters['payment_status']===$status?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$status)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label>Booking Status</label>
                            <select name="booking_status" class="form-control">
                                <option value="">All Booking Status</option>
                                @foreach(['pending','confirmed','completed','cancelled'] as $status)
                                    <option value="{{ $status }}" {{ $filters['booking_status']===$status?'selected':'' }}>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label>Select Pandit</label>
                            <select name="pandit_id" class="form-control">
                                <option value="">All Pandits</option>
                                @foreach($pandits as $pandit)
                                    <option value="{{ $pandit->id }}" {{ (string) $filters['pandit_id'] === (string) $pandit->id ? 'selected' : '' }}>
                                        {{ $pandit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label>Select Customer</label>
                            <select name="customer_id" class="form-control">
                                <option value="">All Customers</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->customer_id }}" {{ (string) $filters['customer_id'] === (string) $customer->customer_id ? 'selected' : '' }}>
                                        {{ $customer->customer_name ?: 'Customer #' . $customer->customer_id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="opr-actions">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search mr-1"></i> Generate Report</button>
                        <a href="{{ route('reports.online-puja-report.index', ['type'=>$filters['type']]) }}" class="btn btn-light border"><i class="fas fa-redo mr-1"></i> Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="opr-summary-grid">
            @php
                $cards = [
                    ['Total Bookings',$summary['total_bookings'],'fa-ticket-alt'],
                    ['Successful Payments',$summary['successful_bookings'],'fa-check-circle'],
                    ['Pending Payments',$summary['pending_bookings'],'fa-clock'],
                    ['Completed Pujas',$summary['completed_bookings'],'fa-om'],
                    ['Cancelled Bookings',$summary['cancelled_bookings'],'fa-times-circle'],
                    ['Gross Revenue',$summary['gross_revenue'],'fa-rupee-sign'],
                    ['Refund Amount',$summary['refund_amount'],'fa-undo'],
                    ['Net Revenue',$summary['net_revenue'],'fa-wallet'],
                ];
            @endphp
            @foreach($cards as $card)
                <div class="card opr-summary-card">
                    <div class="card-body">
                        <small>{{ $card[0] }}</small>
                        <h3>
                            @if(in_array($card[0],['Gross Revenue','Refund Amount','Net Revenue']))
                                ₹{{ number_format((float)$card[1],2) }}
                            @else
                                {{ number_format((float)$card[1]) }}
                            @endif
                        </h3>
                        <div class="sub"><i class="fas {{ $card[2] }} mr-1"></i> Selected date range</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card opr-table-card">
            <div class="card-header">
                <h4><i class="fas fa-chart-bar mr-2 text-primary"></i>
                    {{ $filters['type']==='pandit'?'Pandit Wise Report':'Online Puja Wise Report' }}
                </h4>
                <div class="opr-export">
                    <a class="btn btn-danger btn-sm" href="{{ route('reports.online-puja-report.pdf', request()->query()) }}"><i class="fas fa-file-pdf mr-1"></i> PDF</a>
                    <a class="btn btn-success btn-sm" href="{{ route('reports.online-puja-report.excel', request()->query()) }}"><i class="fas fa-file-excel mr-1"></i> Excel</a>
                    <a class="btn btn-info btn-sm" href="{{ route('reports.online-puja-report.csv', request()->query()) }}"><i class="fas fa-file-csv mr-1"></i> CSV</a>
                </div>
            </div>
            <div class="card-body">
                <div class="opr-tabs">
                    <a class="{{ $filters['type']==='pandit'?'active':'' }}" href="{{ route('reports.online-puja-report.index', array_merge(request()->query(),['type'=>'pandit'])) }}">Pandit Wise</a>
                    <a class="{{ $filters['type']==='puja'?'active':'' }}" href="{{ route('reports.online-puja-report.index', array_merge(request()->query(),['type'=>'puja'])) }}">Online Puja Wise</a>
                </div>

                <p class="text-muted small">
                    Report period: {{ $filters['from_input'] }} to {{ $filters['to_input'] }}.
                    Revenue is grouped by booking currency.
                </p>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover opr-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                @if($filters['type']==='pandit')
                                    <th>Pandit Name</th><th>Total Pujas</th>
                                @else
                                    <th>Online Puja</th><th>Pandit / Creator</th>
                                @endif
                                <th>Bookings</th><th>Successful</th><th>Pending</th><th>Completed</th><th>Cancelled</th>
                                <th>Gross Revenue</th><th>Refund</th><th>Net Revenue</th><th>Currency</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($report as $key=>$row)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td><strong>{{ $row->group_name ?? 'N/A' }}</strong></td>
                                    @if($filters['type']==='pandit')
                                        <td>{{ number_format($row->total_pujas) }}</td>
                                    @else
                                        <td>{{ $row->pandit_name ?? 'Unknown Pandit' }}</td>
                                    @endif
                                    <td>{{ number_format($row->total_bookings) }}</td>
                                    <td>{{ number_format($row->successful_bookings) }}</td>
                                    <td>{{ number_format($row->pending_bookings) }}</td>
                                    <td>{{ number_format($row->completed_bookings) }}</td>
                                    <td>{{ number_format($row->cancelled_bookings) }}</td>
                                    <td class="opr-money">{{ $row->currency }} {{ number_format((float)$row->gross_revenue,2) }}</td>
                                    <td>{{ $row->currency }} {{ number_format((float)$row->refund_amount,2) }}</td>
                                    <td class="opr-money">{{ $row->currency }} {{ number_format((float)$row->net_revenue,2) }}</td>
                                    <td>{{ $row->currency ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="13" class="opr-empty"><i class="fas fa-folder-open fa-2x mb-2"></i><br>No report records found for the selected filters.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card opr-table-card">
            <div class="card-header">
                <h4><i class="fas fa-list-alt mr-2 text-primary"></i> All Online Puja Booking Details</h4>
                <span class="text-muted small">{{ number_format($bookings->total()) }} Records</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover opr-table">
                        <thead>
                            <tr>
                                <th>#</th><th>Booking Reference</th><th>Booking Date</th><th>Customer</th>
                                <th>Mobile</th><th>Email</th><th>Online Puja</th><th>Pandit / Creator</th>
                                <th>Package</th><th>Amount</th><th>Payment Status</th><th>Booking Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $key=>$booking)
                                @php
                                    $payment = strtolower($booking->payment_status ?? 'unknown');
                                    $bookingStatus = strtolower($booking->booking_status ?? 'unknown');
                                    $paymentClass = in_array($payment,['paid','success','successful','captured','completed'])?'opr-paid':($payment==='pending'?'opr-pending':(in_array($payment,['failed','cancelled','canceled'])?'opr-failed':($payment==='refunded'?'opr-refunded':'opr-default')));
                                    $bookingClass = $bookingStatus==='completed'?'opr-completed':($bookingStatus==='pending'?'opr-pending':(in_array($bookingStatus,['cancelled','canceled'])?'opr-cancelled':'opr-default'));
                                @endphp
                                <tr>
                                    <td>{{ $bookings->firstItem()+$key }}</td>
                                    <td><strong>{{ $booking->order_number ?? 'N/A' }}</strong></td>
                                    <td>{{ $booking->created_at ? \Carbon\Carbon::parse($booking->created_at)->format('d-m-Y h:i A') : 'N/A' }}</td>
                                    <td>{{ $booking->customer_name ?? 'N/A' }}</td>
                                    <td>{{ $booking->customer_phone ?? 'N/A' }}</td>
                                    <td>{{ $booking->customer_email ?? 'N/A' }}</td>
                                    <td>{{ $booking->puja_title ?? 'N/A' }}</td>
                                    <td>{{ $booking->pandit_name ?? 'Unknown Pandit' }}</td>
                                    <td>{{ $booking->package_name ?? 'N/A' }}</td>
                                    <td><strong>{{ $booking->currency ?? 'INR' }} {{ number_format((float)$booking->amount,2) }}</strong></td>
                                    <td><span class="opr-badge {{ $paymentClass }}">{{ ucfirst(str_replace('_',' ',$payment)) }}</span></td>
                                    <td><span class="opr-badge {{ $bookingClass }}">{{ ucfirst($bookingStatus) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="12" class="opr-empty"><i class="fas fa-folder-open fa-2x mb-2"></i><br>No online puja bookings found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap">
                    <small class="text-muted">
                        Showing {{ $bookings->firstItem() ?? 0 }} to {{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }} bookings
                    </small>
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const period = document.getElementById('oprPeriod');
    const from = document.getElementById('oprFrom');
    const to = document.getElementById('oprTo');

    function toggleDates() {
        const custom = period.value === 'custom';
        from.disabled = !custom;
        to.disabled = !custom;
    }

    period.addEventListener('change', toggleDates);
    toggleDates();
});
</script>
@endsection
@else
    @php abort(403); @endphp
@endcan
