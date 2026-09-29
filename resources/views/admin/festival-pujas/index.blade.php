@can('festival-pujas.index')
@extends('admin.layouts.app')

@section('title', 'Festival Puja Management')

@section('content')

<section class="section">
    <div class="section-body">

        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>Festival Puja Management</h4>

                        <div class="card-header-action">
                            <a href="{{ route('festival-pujas.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Festival Puja
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
                                        <th>Festival</th>
                                        <th>Puja</th>
                                        <th>Special Price</th>
                                        <th>Sort Order</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($festivalPujas as $festivalPuja)

                                        <tr>

                                            <td class="text-center">
                                                {{ $loop->iteration }}
                                            </td>

                                            <td>
                                                <strong>
                                                    {{ $festivalPuja->festival->name ?? '-' }}
                                                </strong>
                                            </td>

                                            <td>
                                                {{ $festivalPuja->puja->name ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $festivalPuja->special_price !== null
                                                    ? '₹' . number_format($festivalPuja->special_price, 2)
                                                    : '-' }}
                                            </td>

                                            <td>
                                                {{ $festivalPuja->sort_order }}
                                            </td>

                                            <td>
                                                @if($festivalPuja->status == 1)
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
                                                <div class="d-flex align-items-center justify-content-center">

                                                    <a href="{{ route('festival-pujas.edit', $festivalPuja->id) }}"
                                                        class="btn btn-primary btn-sm mr-1"
                                                        title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    @if($festivalPuja->status == 1)
                                                        <form action="{{ route('festival-pujas.destroy', $festivalPuja->id) }}"
                                                            method="POST"
                                                            class="d-inline delete-festival-puja-form">

                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm"
                                                                title="Deactivate">
                                                                <i class="fas fa-trash"></i>
                                                            </button>

                                                        </form>
                                                    @endif

                                                </div>
                                            </td>

                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="7" class="text-center">
                                                No festival puja found.
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
                    targets: [0, 6]
                }
            ]
        });

        $('.delete-festival-puja-form').on('submit', function(e) {

            e.preventDefault();

            let form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: 'This festival puja will be deactivated.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#fc544b',
                cancelButtonColor: '#6777ef',
                confirmButtonText: 'Yes, deactivate',
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
        abort(404);
    @endphp
@endcan
