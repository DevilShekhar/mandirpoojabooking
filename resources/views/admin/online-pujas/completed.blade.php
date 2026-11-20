@can('online-puja.completed')
@extends('admin.layouts.app')
@section('title', 'Completed Online Pujas')
@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Completed Online Pujas</h4>
                        <div class="card-header-action">
                            <a href="{{ route('online-pujas.index') }}"
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Back to All Pujas
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Puja Title</th>
                                        <th>Category</th>
                                        <th>Temple Name</th>
                                        <th>Puja Date & Time</th>
                                        <th>Total Seats</th>
                                        <th>Booked Seats</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($onlinePujas as $puja)
                                        <tr>
                                            <td class="text-center">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                <strong>
                                                    {{ $puja->title ?? 'N/A' }}
                                                </strong>
                                            </td>
                                            <td>
                                                {{ $puja->category->name ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $puja->temple_name ?? 'N/A' }}
                                            </td>
                                            <td>
                                                @if($puja->puja_datetime)
                                                    {{ \Illuminate\Support\Carbon::parse($puja->puja_datetime)->format('d-m-Y h:i A') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-primary">
                                                    {{ $puja->total_seat ?? 0 }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $puja->booked_seat ?? 0 }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">
                                                    Completed
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('online-pujas.bookings', $puja->id) }}" class="btn btn-success btn-sm" title="Customer Bookings">
                                                    <i class="fas fa-calendar-check"></i>
                                                </a>
                                                <a href="{{ route('online-pujas.show', $puja->id) }}" class="btn btn-info btn-sm" title="View Online Puja">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">
                                                No completed online pujas found.
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
    $(document).ready(function () {
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
                    targets: [0, 8]
                }
            ],
            order: [[1, 'asc']]
        });
    });
</script>
@endpush
@else
    @php
        abort(403);
    @endphp
@endcan