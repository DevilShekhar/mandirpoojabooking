@can('puja-categories.index')
@extends('admin.layouts.app')

@section('title', 'Puja Categories')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Puja Categories</h4>

                        <div class="card-header-action">
                            <a href="{{ route('puja-categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Puja Category
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
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Sort Order</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($pujaCategories as $pujaCategory)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>

                                            <td>
                                                @if($pujaCategory->image)
                                                    <img src="{{ asset('storage/' . $pujaCategory->image) }}"
                                                         alt="{{ $pujaCategory->name }}"
                                                         width="60"
                                                         height="60"
                                                         style="object-fit: cover; border-radius: 5px;">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>

                                            <td>{{ $pujaCategory->name }}</td>

                                            <td>{{ $pujaCategory->slug }}</td>

                                            <td>{{ $pujaCategory->sort_order }}</td>

                                            <td>
                                                @if($pujaCategory->status)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>

                                            <td>
                                                <a href="{{ route('puja-categories.edit', $pujaCategory->id) }}"
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('puja-categories.destroy', $pujaCategory->id) }}"
                                                      method="POST"
                                                      class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
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
            text: "This puja category will be deactivated.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, deactivate it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
@else
    @php
        abort(404);
    @endphp
@endcan
