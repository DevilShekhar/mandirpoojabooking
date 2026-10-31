@can('news.index')
@extends('admin.layouts.app')
@section('title', 'News Management')
@section('content')

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>News Management</h4>
                        <div class="card-header-action">
                            <a href="{{ route('my-news.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add News
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
                                        <th>News Title</th>
                                        <th>Short Description</th>
                                        <th>News Image</th>
                                        <th>Article Image</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($news as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td>{{ $item->temple->name ?? '-' }}</td>

                                        <td>{{ $item->title }}</td>

                                        <td>{{ Str::limit($item->short_description, 60) }}</td>

                                        <td>
                                            @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}"
                                                alt="{{ $item->title }}"
                                                width="55"
                                                height="45"
                                                class="rounded"
                                                style="object-fit: cover;">
                                            @else
                                            <span class="text-muted">No Image</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($item->artical_img)
                                            <img src="{{ asset('storage/' . $item->artical_img) }}"
                                                alt="Article Image"
                                                width="55"
                                                height="45"
                                                class="rounded"
                                                style="object-fit: cover;">
                                            @else
                                            <span class="text-muted">No Image</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($item->status)
                                            <span class="badge badge-success">Active</span>
                                            @else
                                            <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>

                                        <td>{{ $item->created_at->format('d-m-Y') }}</td>

                                        <td>
                                            <div class="d-flex">
                                                @can('news.edit')
                                                <a href="{{ route('my-news.edit', $item->id) }}"
                                                    class="btn btn-sm btn-primary mr-1"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endcan
                                                @can('news.show')
                                                <a href="{{ route('my-news.show', $item->id) }}" class="btn btn-info btn-sm" title="View News">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @endcan
                                                @can('news.delete')
                                                <form action="{{ route('my-news.destroy', $item->id) }}"
                                                    method="POST"
                                                    class="delete-news-form">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endcan
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

        $('.delete-news-form').on('submit', function(e) {
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

@else
    @php
        abort(403);
    @endphp
@endcan