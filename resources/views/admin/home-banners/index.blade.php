@can('home-banners.index')
@extends('admin.layouts.app')

@section('title', 'Home Banner Management')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Home Banner Management</h4>
                        <div class="card-header-action">
                            <a href="{{ route('home-banners.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Home Banner
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
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Short Description</th>
                                        <th>Button</th>
                                        <th>Created By</th>
                                        <th>Created At</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($homeBanners as $homeBanner)
                                        <tr>
                                            <td class="text-center">
                                                {{ $loop->iteration }}
                                            </td>

                                            <td>
                                                @if($homeBanner->image)
                                                    <img src="{{ asset('storage/' . $homeBanner->image) }}"
                                                        alt="{{ $homeBanner->title }}"
                                                        style="width: 100px; height: 60px; object-fit: cover; border-radius: 5px;">
                                                @else
                                                    <div style="width: 100px; height: 60px; background: #f1f1f1; display: flex; align-items: center; justify-content: center; border-radius: 5px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>

                                            <td>
                                                <strong>{{ $homeBanner->title }}</strong>
                                            </td>

                                            <td>
                                                @if($homeBanner->short_description)
                                                    {{ \Illuminate\Support\Str::limit($homeBanner->short_description, 80) }}
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td>
                                                @if($homeBanner->btn_name)
                                                    <span class="badge badge-primary badge-shadow">
                                                        {{ $homeBanner->btn_name }}
                                                    </span>

                                                    @if($homeBanner->btn_url)
                                                        <div class="mt-1">
                                                            <small class="text-muted">
                                                                {{ \Illuminate\Support\Str::limit($homeBanner->btn_url, 40) }}
                                                            </small>
                                                        </div>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td>
                                                {{ $homeBanner->createdBy->name ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $homeBanner->created_at ? $homeBanner->created_at->format('d M Y') : '-' }}
                                            </td>

                                            <td>
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <a href="{{ route('home-banners.show', $homeBanner->id) }}"
                                                        class="btn btn-info btn-sm mr-1"
                                                        title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <a href="{{ route('home-banners.edit', $homeBanner->id) }}"
                                                        class="btn btn-primary btn-sm mr-1"
                                                        title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <form action="{{ route('home-banners.destroy', $homeBanner->id) }}"
                                                        method="POST"
                                                        class="d-inline delete-home-banner-form">
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
                                                No home banners found.
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
                    targets: [0, 1, 7]
                }
            ]
        });

        $('.delete-home-banner-form').on('submit', function(e) {
            e.preventDefault();

            let form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: 'This home banner will be deleted.',
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