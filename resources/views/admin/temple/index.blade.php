@extends('admin.layouts.app')

@section('title', 'Temple Management')

@section('content')

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Temple Management</h4>
                        <div class="card-header-action">
                            <a href="{{ route('temples.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Temple
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
                                    <th>#</th>
                                    <th>Temple</th>
                                    <th>Code</th>
                                    <th>State</th>
                                    <th>District</th>
                                    <th>City</th>
                                    <th>Verified</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($temples as $temple)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($temple->main_image)
                                            <img src="{{ asset('storage/' . $temple->main_image) }}"
                                                alt="{{ $temple->name }}"
                                                width="45"
                                                height="45"
                                                class="rounded mr-2"
                                                style="object-fit: cover;">
                                            @endif

                                            <span>{{ $temple->name }}</span>
                                        </div>
                                    </td>

                                    <td>{{ $temple->temple_code }}</td>

                                    <td>{{ $temple->state->name ?? '-' }}</td>

                                    <td>{{ $temple->district->name ?? '-' }}</td>

                                    <td>{{ $temple->city->name ?? '-' }}</td>

                                    <td>
                                        @if($temple->is_verified)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> Verified
                                        </span>
                                        @else
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($temple->status)
                                        <span class="badge badge-success">Active</span>
                                        @else
                                        <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('temples.edit', $temple->id) }}"
                                                class="btn btn-sm btn-primary mr-1"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('temples.destroy', $temple->id) }}"
                                                method="POST"
                                                class="delete-temple-form">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
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
    $(document).ready(function() {
        $('#table-1').DataTable({
            "columnDefs": [
                {
                    "sortable": false,
                    "targets": [8]
                }
            ]
        });

        $('.delete-temple-form').on('submit', function(e) {
            e.preventDefault();

            let form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}'
    });
    @endif
</script>

@endpush
