@can('online-puja-categories.index')
@extends('admin.layouts.app')
@section('title', 'Online Puja Category Management')
@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Online Puja Category Management</h4>
                        <div class="card-header-action">
                            @can('online-puja-categories.create')
                                <a href="{{ route('online-puja-categories.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add Category
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
                                        <th class="text-center">#</th>
                                        <th>Category Name</th>
                                        <th>Slug</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th>Created At</th>
                                        <th>Updated By</th>
                                        <th>Updated At</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories as $category)
                                        <tr>
                                            <td class="text-center">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                <strong>{{ $category->name }}</strong>
                                            </td>
                                            <td>
                                                {{ $category->slug }}
                                            </td>
                                            <td>
                                                @if($category->status == 1)
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
                                                {{ $category->creator?->name ?? '-' }}
                                            </td>
                                            <td>
                                                {{ $category->created_at ? $category->created_at->format('d M Y') : '-' }}
                                            </td>
                                            <td>
                                                {{ $category->updater?->name ?? '-' }}
                                            </td>
                                            <td>
                                                {{ $category->updated_by ? ($category->updated_at?->format('d M Y') ?? '-'): '-' }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-center">
                                                    @can('online-puja-categories.edit')
                                                        <a href="{{ route('online-puja-categories.edit', $category->id) }}" class="btn btn-primary btn-sm mr-1" title="Edit Category">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                    @can('online-puja-categories.delete')
                                                        <form id="delete-form-{{ $category->id }}"  action="{{ route('online-puja-categories.destroy', $category->id) }}"
                                                              method="POST" class="d-inline delete-category-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" title="Deactivate Category">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">
                                                No online puja categories found.
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
                    targets: [0, 8]
                }
            ]
        });
        $('.delete-category-form').on('submit', function(e) {
            e.preventDefault();
            let form = this;
            Swal.fire({
                title: 'Are you sure?',
                text: 'This will set the category status to inactive!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#fc544b',
                cancelButtonColor: '#6777ef',
                confirmButtonText: 'Yes, change status!',
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