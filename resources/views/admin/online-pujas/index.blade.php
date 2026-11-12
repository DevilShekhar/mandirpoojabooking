@can('online-puja.index')
@extends('admin.layouts.app')

@section('title', 'Online Puja Management')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Online Puja Management</h1>

        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </div>
            <div class="breadcrumb-item active">Online Pujas</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h4>Online Puja List</h4>

                        <div class="card-header-action">
                            <a href="{{ route('online-pujas.create') }}"
                               class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Online Puja
                            </a>
                        </div>
                    </div>

                    <div class="card-body">

                        {{-- Success Message --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle"></i>
                                {{ session('success') }}

                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        {{-- Error Message --}}
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ session('error') }}

                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered"
                                   id="table-1">

                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Temple Name</th>
                                        <th>Puja Date</th>
                                        <th>Seats</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($pujas as $key => $puja)

                                        <tr>
                                            {{-- ID --}}
                                            <td>{{ $key + 1 }}</td>

                                            {{-- Title --}}
                                            <td>
                                                <strong>{{ $puja->title }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $puja->slug }}
                                                </small>
                                            </td>

                                            {{-- Category --}}
                                            <td>
                                                {{ $puja->category->name ?? '-' }}
                                            </td>

                                            {{-- Temple --}}
                                            <td>
                                                {{ $puja->temple_name ?? '-' }}
                                            </td>

                                            {{-- Puja Date --}}
                                            <td>
                                                @if($puja->puja_datetime)
                                                    {{ \Carbon\Carbon::parse($puja->puja_datetime)->format('d-m-Y h:i A') }}
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            {{-- Seats --}}
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $puja->booked_seat ?? 0 }}
                                                    /
                                                    {{ $puja->total_seat ?? 0 }}
                                                </span>
                                            </td>

                                            {{-- Status --}}
                                            <td>
                                                @if($puja->status == 1)
                                                    <span class="badge badge-success">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- Created By --}}
                                            <td>
                                                {{ $puja->creator->name ?? '-' }}
                                            </td>

                                            {{-- Actions --}}
                                            <td>
                                                <div class="d-flex align-items-center">

                                                    {{-- View Button --}}
                                                    <a href="{{ route('online-pujas.show', $puja->id) }}"
                                                       class="btn btn-sm btn-info mr-2"
                                                       title="View Online Puja">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    {{-- Edit Button --}}
                                                    <a href="{{ route('online-pujas.edit', $puja->id) }}"
                                                       class="btn btn-sm btn-primary mr-2"
                                                       title="Edit Online Puja">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    {{-- Delete / Deactivate Button --}}
                                                    @if($puja->status == 1)

                                                        <form action="{{ route('online-pujas.destroy', $puja->id) }}"
                                                              method="POST"
                                                              class="delete-form">

                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit"
                                                                    class="btn btn-sm btn-danger"
                                                                    title="Delete Online Puja">
                                                                <i class="fas fa-trash"></i>
                                                            </button>

                                                        </form>

                                                    @endif

                                                </div>
                                            </td>

                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="9" class="text-center">
                                                <div class="py-3">
                                                    <i class="fas fa-folder-open fa-2x text-muted mb-2"></i>
                                                    <p class="mb-0">
                                                        No Online Puja Records Found.
                                                    </p>
                                                </div>
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
    $(document).ready(function () {

        // Initialize DataTable
        $('#table-1').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    searchable: false,
                    targets: [8]
                }
            ],
            order: [],
            responsive: true,
            autoWidth: false
        });


        // SweetAlert Delete Confirmation
        $(document).on('submit', '.delete-form', function (e) {

            e.preventDefault();

            let form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete/deactivate this Online Puja?',
                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',

                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',

                reverseButtons: true,

                focusCancel: true

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
        abort(403);
    @endphp
@endcan