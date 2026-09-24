@extends('admin.layouts.app')

@section('title', 'Puja Schedule Management')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Puja Schedule Management</h4>
                        <div class="card-header-action">
                            <a href="{{ route('puja-schedules.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Schedule
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Temple</th>
                                        <th>Puja</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Capacity</th>
                                        <th>Booked</th>
                                        <th>Remaining</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($pujaSchedules as $schedule)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>
                                            {{ $schedule->temple->name ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $schedule->puja->name ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $schedule->puja_date ? $schedule->puja_date->format('d M Y') : '-' }}
                                        </td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}
                                            -
                                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                        </td>

                                        <td>
                                            {{ $schedule->capacity }}
                                        </td>

                                        <td>
                                            {{ $schedule->booked_capacity }}
                                        </td>

                                        <td>
                                            <strong>{{ $schedule->remaining_capacity }}</strong>
                                        </td>

                                        <td>
                                            @if($schedule->status)
                                                <div class="badge badge-success badge-shadow">
                                                    Active
                                                </div>
                                            @else
                                                <div class="badge badge-danger badge-shadow">
                                                    Inactive
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="d-flex align-items-center justify-content-center">

                                                <a href="{{ route('puja-schedules.show', $schedule->id) }}"
                                                   class="btn btn-info btn-sm mr-1"
                                                   title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <a href="{{ route('puja-schedules.edit', $schedule->id) }}"
                                                   class="btn btn-primary btn-sm mr-1"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                @if($schedule->status)
                                                <form action="{{ route('puja-schedules.destroy', $schedule->id) }}"
                                                      method="POST"
                                                      class="d-inline delete-schedule-form">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-danger btn-sm"
                                                            title="Deactivate">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>

                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            No puja schedules found.
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
                    targets: [0, 9]
                }
            ]
        });

        $('.delete-schedule-form').on('submit', function(e) {
            e.preventDefault();

            let form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: 'This schedule will be deactivated.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#fc544b',
                cancelButtonColor: '#6777ef',
                confirmButtonText: 'Yes, deactivate',
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
            confirmButtonColor: '#6777ef'
        });
    @endif
</script>
@endpush
