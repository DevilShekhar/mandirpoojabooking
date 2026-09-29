@can('puja-assignments.index')
@extends('admin.layouts.app')

@section('title', 'Puja Assignments')

@section('content')

<section class="section">
    <div class="section-body">

        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>Puja Assignments</h4>

                        <div class="card-header-action">
                            <a href="{{ route('puja-assignments.create') }}"
                               class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                                Assign Puja
                            </a>
                        </div>
                    </div>

                    <div class="card-body">

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">

                            <table class="table table-striped">

                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Booking</th>
                                        <th>Pandit</th>
                                        <th>Assigned By</th>
                                        <th>Assigned At</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($assignments as $assignment)

                                        <tr>

                                            <td class="text-center">
                                                {{ $assignments->firstItem() }}
                                            </td>

                                            <td>
                                                #{{ $assignment->booking_id }}
                                            </td>

                                            <td>
                                                <strong>
                                                    {{ $assignment->pandit->name ?? '-' }}
                                                </strong>
                                            </td>

                                            <td>
                                                {{ $assignment->assignedBy->name ?? 'Admin' }}
                                            </td>

                                            <td>
                                                {{ $assignment->assigned_at
                                                    ? $assignment->assigned_at->format('d M Y, h:i A')
                                                    : '-' }}
                                            </td>

                                            <td>

                                                @if($assignment->status == 'assigned')

                                                    <span class="badge badge-info">
                                                        Assigned
                                                    </span>

                                                @elseif($assignment->status == 'accepted')

                                                    <span class="badge badge-primary">
                                                        Accepted
                                                    </span>

                                                @elseif($assignment->status == 'started')

                                                    <span class="badge badge-warning">
                                                        Started
                                                    </span>

                                                @elseif($assignment->status == 'completed')

                                                    <span class="badge badge-success">
                                                        Completed
                                                    </span>

                                                @else

                                                    <span class="badge badge-danger">
                                                        Cancelled
                                                    </span>

                                                @endif

                                            </td>

                                            <td>

                                                <div class="d-flex align-items-center justify-content-center">

                                                    <a href="{{ route('puja-assignments.show', $assignment->id) }}"
                                                       class="btn btn-info btn-sm mr-1"
                                                       title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <a href="{{ route('puja-assignments.edit', $assignment->id) }}"
                                                       class="btn btn-primary btn-sm mr-1"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <form action="{{ route('puja-assignments.destroy', $assignment->id) }}"
                                                          method="POST"
                                                          class="d-inline delete-assignment-form">

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
                                                No puja assignments found.
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
    $('.delete-assignment-form').on('submit', function(e) {
        e.preventDefault();

        let form = this;

        Swal.fire({
            title: 'Are you sure?',
            text: 'This puja assignment will be deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#fc544b',
            cancelButtonColor: '#6777ef',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel'
        }).then(function(result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>

@endpush
@else
    @php
        abort(404);
    @endphp
@endcan
