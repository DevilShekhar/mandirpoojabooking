@extends('admin.layouts.app')
@section('title', 'Temple Timings')
@section('content')
@can('temple-timings.index')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Temple Timings</h4>
                        <div class="card-header-action">
                            @can('temple-timings.create')
                                <a href="{{ route('temple-timings.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add Temple Timing
                                </a>
                            @endcan
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
                                        <th>#</th>
                                        <th>Temple</th>
                                        <th>Timing Category</th>
                                        <th>Timing Name</th>
                                        <th>Days</th>
                                        <th>Opening Time</th>
                                        <th>Closing Time</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($templeTimings as $timing)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $timing->temple->name ?? '-' }}</strong>
                                            </td>
                                            <td>
                                                {{ $timing->category->name ?? '-' }}
                                            </td>
                                            <td>
                                                <strong>{{ $timing->name ?? '-' }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $timing->start_day }} - {{ $timing->end_day }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $timing->opening_time
                                                    ? \Carbon\Carbon::parse($timing->opening_time)->format('h:i A')
                                                    : '-' }}
                                            </td>
                                            <td>
                                                {{ $timing->closing_time
                                                    ? \Carbon\Carbon::parse($timing->closing_time)->format('h:i A')
                                                    : '-' }}
                                            </td>
                                            <td>
                                                @if($timing->status == 1)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('temple-timings.show')
                                                        <a href="{{ route('temple-timings.show', $timing->id) }}"
                                                           class="btn btn-sm btn-info mr-1"
                                                           title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endcan
                                                    @can('temple-timings.edit')
                                                        <a href="{{ route('temple-timings.edit', $timing->id) }}"
                                                           class="btn btn-sm btn-primary mr-1"
                                                           title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan                                                 
                                                    @can('temple-timings.delete')
                                                        @if($timing->status == 1)
                                                            <form action="{{ route('temple-timings.destroy', $timing->id) }}" method="POST" class="delete-timing-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" title="Deactivate">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="badge badge-secondary">
                                                                Deactivated
                                                            </span>
                                                        @endif
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">
                                                No temple timings found.
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
@else
    @php abort(403); @endphp
@endcan
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        // DataTable Initialization
        $('#table-1').DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            ordering: true,
            searching: true,
            paging: true,
            info: true,
            order: [[0, 'asc']],
            columnDefs: [
                {
                    orderable: false,
                    searchable: false,
                    targets: [8]
                }
            ],
            language: {
                search: "Search:",
                searchPlaceholder: "Search temple timings...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ temple timings",
                infoEmpty: "Showing 0 to 0 of 0 temple timings",
                infoFiltered: "(filtered from _MAX_ total temple timings)",
                zeroRecords: "No matching temple timings found",
                emptyTable: "No temple timings found",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });
        // Deactivate Confirmation
        $('.delete-timing-form').on('submit', function (e) {
            e.preventDefault();
            let form = this;
            Swal.fire({
                title: 'Are you sure?',
                text: 'This temple timing will be deactivated!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, deactivate it!',
                cancelButtonText: 'Cancel'
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
    // Success Alert
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: @json(session('success')),
            timer: 2000,
            showConfirmButton: false
        });
    @endif
    // Error Alert
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: @json(session('error'))
        });
    @endif
</script>
@endpush