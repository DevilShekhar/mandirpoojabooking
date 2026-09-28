@extends('admin.layouts.app')

@section('title', 'Booking Sankalps')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Booking Sankalp List</h4>
                        <div class="card-header-action">
                            <a href="{{ route('booking-sankalps.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Sankalp
                            </a>
                        </div>
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
                                        <th>Booking</th>
                                        <th>Pandit</th>
                                        <th>Sankalp Date</th>
                                        <th>Status</th>
                                        <th>Completed At</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bookingSankalps as $sankalp)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            <strong>
                                                {{ $sankalp->booking->booking_number ?? 'Booking #'.$sankalp->booking_id }}
                                            </strong>
                                        </td>
                                        <td>
                                            {{ $sankalp->pandit->name ?? 'Not Assigned' }}
                                        </td>
                                        <td>
                                            {{ $sankalp->sankalp_date?->format('d M Y') ?? '-' }}
                                        </td>
                                        <td>
                                            @if($sankalp->status == 'pending')
                                                <div class="badge badge-warning badge-shadow">Pending</div>
                                            @elseif($sankalp->status == 'in_progress')
                                                <div class="badge badge-info badge-shadow">In Progress</div>
                                            @else
                                                <div class="badge badge-success badge-shadow">Completed</div>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $sankalp->completed_at?->format('d M Y h:i A') ?? '-' }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <a href="{{ route('booking-sankalps.show', $sankalp->id) }}"
                                                   class="btn btn-info btn-sm mr-1"
                                                   title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <a href="{{ route('booking-sankalps.edit', $sankalp->id) }}"
                                                   class="btn btn-primary btn-sm mr-1"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('booking-sankalps.destroy', $sankalp->id) }}"
                                                      method="POST"
                                                      class="d-inline delete-sankalp-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-danger btn-sm"
                                                            title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            No booking sankalp found.
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
                    targets: [0, 6]
                }
            ]
        });

        $('.delete-sankalp-form').on('submit', function(e) {
            e.preventDefault();
            let form = this;
            Swal.fire({
                title: 'Are you sure?',
                text: 'This sankalp will be deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#fc544b',
                cancelButtonColor: '#6777ef',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
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
