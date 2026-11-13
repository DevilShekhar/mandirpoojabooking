@can('my-online-pujabooking.index')
@extends('admin.layouts.app')

@section('title', 'My Online Puja Bookings')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>My Online Puja Bookings</h4>
                    </div>

                    <div class="card-body">

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">

                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Puja Name</th>
                                        <th>Package</th>
                                        <th>Amount</th>
                                        <th>Puja Start Date</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($bookings as $booking)

                                    <tr>

                                        {{-- Serial Number --}}
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>

                                        {{-- Puja Name --}}
                                        <td>
                                            <strong>
                                                {{ $booking->puja->title
                                                    ?? $booking->puja->name
                                                    ?? '-' }}
                                            </strong>
                                        </td>

                                        {{-- Package --}}
                                        <td>
                                            {{ $booking->package_name ?? '-' }}
                                        </td>

                                        {{-- Amount --}}
                                        <td>
                                            {{ $booking->currency ?? 'INR' }}
                                            {{ number_format((float) $booking->amount, 2) }}
                                        </td>

                                        {{-- Puja Start Date --}}
                                        <td>
                                            @if($booking->puja && $booking->puja->puja_datetime)
                                                {{ \Carbon\Carbon::parse(
                                                    $booking->puja->puja_datetime
                                                )->format('d M Y h:i A') }}

                                                @if($booking->puja->puja_day)
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $booking->puja->puja_day }}
                                                    </small>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>

                                        {{-- Action --}}
                                        <td class="text-center">
                                            <a href="{{ route(
                                                'admin.my-online-pujabooking.show',
                                                $booking->id
                                            ) }}"
                                                class="btn btn-primary btn-sm"
                                                title="View Details">

                                                <i class="fas fa-eye"></i>
                                                View Details

                                            </a>
                                        </td>

                                    </tr>

                                    @empty

                                    <tr>
                                        <td colspan="6" class="text-center">
                                            No online puja bookings found.
                                        </td>
                                    </tr>

                                    @endforelse

                                </tbody>

                            </table>
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
    $(document).ready(function() {

        $('#table-1').DataTable({
            ordering: true,
            searching: true,
            paging: true,
            info: true,
            pageLength: 10,

            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'All']
            ],

            columnDefs: [
                {
                    orderable: false,
                    targets: [0, 5]
                }
            ]
        });

    });
</script>
@endpush

@else
    @php
        abort(403);
    @endphp
@endcan