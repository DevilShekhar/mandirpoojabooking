@extends('admin.layouts.app')

@section('title', 'Pujas')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Pujas</h4>
                        <div class="card-header-action">
                            <a href="{{ route('pujas.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Puja
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Puja Name</th>
                                        <th>Puja Code</th>
                                        <th>Temple</th>
                                        <th>Deity</th>
                                        <th>Category</th>
                                        <th>Duration</th>
                                        <th>Online</th>
                                        <th>Offline</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($pujas as $puja)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td>
                                            @if($puja->image)
                                                <img src="{{ asset('storage/' . $puja->image) }}"
                                                     width="60"
                                                     height="60"
                                                     style="object-fit: cover; border-radius: 5px;">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>

                                        <td>
                                            <strong>{{ $puja->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $puja->slug }}</small>
                                        </td>

                                        <td>{{ $puja->puja_code }}</td>

                                        <td>{{ $puja->temple->name ?? '-' }}</td>

                                        <td>{{ $puja->deity->name ?? '-' }}</td>

                                        <td>{{ $puja->pujaCategory->name ?? '-' }}</td>

                                        <td>{{ $puja->duration ?? '-' }}</td>

                                        <td>
                                            @if($puja->is_online)
                                                <span class="badge badge-success">Yes</span>
                                            @else
                                                <span class="badge badge-secondary">No</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($puja->is_offline)
                                                <span class="badge badge-success">Yes</span>
                                            @else
                                                <span class="badge badge-secondary">No</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($puja->status)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ route('pujas.edit', $puja->id) }}"
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            @if($puja->status)
                                                <form action="{{ route('pujas.destroy', $puja->id) }}"
                                                      method="POST"
                                                      class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
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
    $('#table-1').DataTable();

    $('.delete-form').on('submit', function (e) {
        e.preventDefault();

        let form = this;

        Swal.fire({
            title: 'Are you sure?',
            text: "This puja will be deactivated.",
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
