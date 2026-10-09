@extends('admin.layouts.app')
@section('title', 'Sanatan Events')
@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Sanatan Events</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.sanatan-events.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Add Sanatan Event
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Event Name</th>
                                        <th>Program Type</th>
                                        <th>Duration</th>
                                        <th>Price</th>
                                        <th>Online</th>
                                        <th>Offline</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sanatans as $sanatan)
                                        <tr>
                                            <td>{{ $sanatans->firstItem() + $loop->index }}</td>
                                            <td>
                                                @if($sanatan->image)
                                                    <img src="{{ asset('storage/' . $sanatan->image) }}" alt="{{ $sanatan->name }}" width="60" height="60" style="object-fit:cover;border-radius:5px;">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $sanatan->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $sanatan->slug }}</small>
                                            </td>
                                            <td>{{ $sanatan->program_type ?? '-' }}</td>
                                            <td>{{ $sanatan->duration ?? '-' }}</td>
                                            <td>₹{{ number_format((float) $sanatan->price, 2) }}</td>
                                            <td>
                                                @if($sanatan->is_online)
                                                    <span class="badge badge-success">Yes</span>
                                                @else
                                                    <span class="badge badge-secondary">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($sanatan->is_offline)
                                                    <span class="badge badge-success">Yes</span>
                                                @else
                                                    <span class="badge badge-secondary">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($sanatan->status)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a href="{{ route('admin.sanatan-events.show', $sanatan->id) }}" class="btn btn-sm btn-info mr-1" title="View">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.sanatan-events.edit', $sanatan->id) }}" class="btn btn-sm btn-primary mr-1" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.sanatan-events.destroy', $sanatan->id) }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">No Sanatan events found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($sanatans->hasPages())
                            <div class="d-flex justify-content-end mt-3">
                                {{ $sanatans->links() }}
                            </div>
                        @endif
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
    if ($.fn.DataTable.isDataTable('#table-1')) {
        $('#table-1').DataTable().destroy();
    }
    $('#table-1').DataTable({
        paging: false,
        searching: false,
        info: false,
        ordering: true,
        columnDefs: [
            { orderable: false, targets: [1, 9] }
        ]
    });
    $('.delete-form').on('submit', function (e) {
        e.preventDefault();
        let form = this;
        Swal.fire({
            title: 'Are you sure?',
            text: 'This Sanatan event will be deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6777ef',
            cancelButtonColor: '#fc544b',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush