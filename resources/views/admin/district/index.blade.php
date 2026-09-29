@can('districts.index')
@extends('admin.layouts.app')

@section('title', 'District Management')

@section('content')

<section class="section">
    <div class="section-body">

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>District Management</h4>

                        <div class="card-header-action">
                            <a href="{{ route('districts.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add District
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
                                        <th>Country</th>
                                        <th>State</th>
                                        <th>District Name</th>
                                        <th>Slug</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($districts as $district)

                                    <tr>

                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>
                                            {{ $district->state->country->name ?? '-' }}
                                        </td>

                                        <td>
                                            <strong>
                                                {{ $district->state->name ?? '-' }}
                                            </strong>
                                        </td>

                                        <td>
                                            <strong>
                                                {{ $district->name }}
                                            </strong>
                                        </td>

                                        <td>
                                            {{ $district->slug }}
                                        </td>

                                        <td>
                                            @if($district->status == 1)
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
                                            {{ $district->created_at ? $district->created_at->format('d M Y') : '-' }}
                                        </td>

                                        <td>

                                            <div class="d-flex align-items-center justify-content-center">

                                                <a href="{{ route('districts.edit', $district->id) }}"
                                                    class="btn btn-primary btn-sm mr-1"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('districts.destroy', $district->id) }}"
                                                    method="POST"
                                                    class="d-inline delete-district-form">

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
                                        <td colspan="8" class="text-center">
                                            No districts found.
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
                    targets: [0, 7]
                }
            ]
        });

        $('.delete-district-form').on('submit', function(e) {
            e.preventDefault();

            let form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: 'This district will be deleted.',
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
