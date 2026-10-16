
@can('puja-bookings.index')
    @extends('admin.layouts.app')
    @section('title', 'Failed Booking Orders')
    @section('content')
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Failed Booking Orders</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('booking-puja-order.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> All Booking Orders
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @if(session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif

                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="failed-bookings-table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Order ID</th>
                                                <th>Customer</th>
                                                <th>Mobile</th>
                                                <th>Puja</th>
                                                <th>Package</th>
                                                <th>Schedule Date</th>
                                                <th>Schedule Time</th>
                                                <th>Amount</th>
                                                <th>Payment Status</th>
                                                <th>Failure Reason</th>
                                                <th>Created At</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($payments as $payment)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $loop->iteration + (($payments->currentPage() - 1) * $payments->perPage()) }}
                                                    </td>
                                                    <td>
                                                        <strong>{{ $payment->razorpay_order_id ?? ('Payment #' . $payment->id) }}</strong>
                                                        @if($payment->razorpay_payment_id)
                                                            <br>
                                                            <small class="text-muted">Payment ID: {{ $payment->razorpay_payment_id }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $payment->user?->name ?? $payment->booking?->customer_name ?? '-' }}
                                                        @if($payment->booking?->booking_number)
                                                            <br>
                                                            <small class="text-muted">Booking: {{ $payment->booking->booking_number }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $payment->booking?->customer_mobile ?? $payment->user?->phone ?? $payment->user?->mobile ?? '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $payment->puja?->name ?? $payment->booking?->puja?->name ?? '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $payment->package?->name ?? $payment->booking?->package_name ?? $payment->booking?->pujaPackage?->name ?? '-' }}
                                                    </td>
                                                    <td>
                                                        @if($payment->booking?->schedule_date)
                                                            {{ \Illuminate\Support\Carbon::parse($payment->booking->schedule_date)->format('d M Y') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($payment->booking?->schedule_time)
                                                            {{ \Illuminate\Support\Carbon::parse($payment->booking->schedule_time)->format('h:i A') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        ₹{{ number_format(((float) ($payment->amount_paise ?? 0)) / 100, 2) }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $status = strtolower($payment->status ?? 'failed');
                                                            $badge = $status === 'failed' ? 'danger' : 'warning';
                                                        @endphp
                                                        <span class="badge badge-{{ $badge }}">{{ ucfirst($status) }}</span>
                                                    </td>
                                                    <td>
                                                        {{ $payment->error_description ?? $payment->error_reason ?? $payment->error_code ?? '-' }}
                                                        @if($payment->error_step)
                                                            <br>
                                                            <small class="text-muted">Step: {{ $payment->error_step }}</small>
                                                        @endif
                                                    </td>
                                                    <td>{{ $payment->created_at?->format('d-m-Y h:i A') ?? '-' }}</td>
                                                    <td class="text-center">
                                                        @if($payment->booking)
                                                            <a href="{{ route('booking-puja-order.show', $payment->booking->id) }}"
                                                               class="btn btn-info btn-sm"
                                                               title="View All Booking Details">
                                                                <i class="fas fa-eye"></i> View
                                                            </a>
                                                        @else
                                                            <span class="text-muted" title="This payment is not linked to a booking">
                                                                No booking linked
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="13" class="text-center">
                                                        <i class="fas fa-info-circle"></i>
                                                        No failed payment attempts found.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3">
                                    {{ $payments->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endsection

    @push('scripts')
        <script>
            $(document).ready(function () {
                if ($.fn.DataTable.isDataTable('#failed-bookings-table')) {
                    $('#failed-bookings-table').DataTable().destroy();
                }

                $('#failed-bookings-table').DataTable({
                    ordering: true,
                    searching: true,
                    paging: false,
                    info: false,
                    scrollX: true,
                    columnDefs: [{
                        orderable: false,
                        targets: [0, 12]
                    }]
                });
            });

            @if(session('success'))
                Swal.fire({
                    title: 'Success!',
                    text: @json(session('success')),
                    icon: 'success',
                    confirmButtonColor: '#6777ef',
                    confirmButtonText: 'OK'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    title: 'Error!',
                    text: @json(session('error')),
                    icon: 'error',
                    confirmButtonColor: '#6777ef',
                    confirmButtonText: 'OK'
                });
            @endif
        </script>
    @endpush
@else
    @php
        abort(403);
    @endphp
@endcan