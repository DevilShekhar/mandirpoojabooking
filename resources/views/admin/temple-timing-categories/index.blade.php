@extends('admin.layouts.app')
@section('title', 'Temple Timing Categories')
@section('content')
@can('temple-timing-categories.index')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Temple Timing Categories</h4>
                        <div class="card-header-action">
                            @can('temple-timing-categories.create')
                            <a href="{{ route('temple-timing-categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Timing Category
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
                                        <th>Category Name</th>
                                        <th>Slug</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories as $category)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $category->temple->name ?? '-' }}
                                        </td>
                                        <td>
                                            <strong>{{ $category->name }}</strong>
                                        </td>
                                        <td>
                                            {{ $category->slug }}
                                        </td>
                                        <td>
                                            @if($category->status)
                                            <span class="badge badge-success">Active</span>
                                            @else
                                            <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $category->creator->name ?? '-' }}
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                @can('temple-timing-categories.edit')
                                                <a href="{{ route('temple-timing-categories.edit', $category->id) }}"
                                                    class="btn btn-sm btn-primary mr-1"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endcan
                                                @can('temple-timing-categories.delete')
                                                <form action="{{ route('temple-timing-categories.destroy', $category->id) }}"  method="POST" class="delete-category-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"  class="btn btn-sm btn-danger"  title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            No temple timing categories found.
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
    $(document).ready(function() {
        $('#table-1').DataTable({
            "columnDefs": [
                {
                    "sortable": false,
                    "targets": [6]
                }
            ]
        });
        $('.delete-category-form').on('submit', function(e) {
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
        text: @json(session('success')),
        timer: 2000,
        showConfirmButton: false
    });
    @endif
    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: @json(session('error'))
    });
    @endif
</script>
@endpush