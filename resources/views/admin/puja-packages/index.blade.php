@can('puja-packages')
@extends('admin.layouts.app')

@section('title', 'Puja Package Management')

@section('content')

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">

                <div class="card-header">
                    <h4>Puja Package Management</h4>

                    <div class="card-header-action">
                        <a href="{{ route('puja-packages.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Package
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
                                    <th>Puja</th>
                                    <th>Package</th>
                                    <th>Persons</th>
                                    <th>Price</th>
                                    <th>Discount Price</th>
                                    <th>Services</th>
                                    <th>Featured</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($packages as $package)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>
                                            <strong>
                                                {{ $package->puja->name ?? '-' }}
                                            </strong>
                                        </td>

                                        <td>
                                            {{ $package->name }}
                                        </td>

                                        <td>
                                            {{ $package->person_count }}
                                        </td>

                                        <td>
                                            ₹{{ number_format($package->price, 2) }}
                                        </td>

                                        <td>
                                            @if($package->discount_price)
                                                ₹{{ number_format($package->discount_price, 2) }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>
                                            @if($package->includes_sankalp)
                                                <span class="badge badge-info mr-1">Sankalp</span>
                                            @endif

                                            @if($package->includes_prasad)
                                                <span class="badge badge-success mr-1">Prasad</span>
                                            @endif

                                            @if($package->includes_video)
                                                <span class="badge badge-primary mr-1">Video</span>
                                            @endif

                                            @if($package->includes_certificate)
                                                <span class="badge badge-warning">Certificate</span>
                                            @endif

                                            @if(
                                                !$package->includes_sankalp &&
                                                !$package->includes_prasad &&
                                                !$package->includes_video &&
                                                !$package->includes_certificate
                                            )
                                                -
                                            @endif
                                        </td>

                                        <td>
                                            @if($package->is_featured)
                                                <div class="badge badge-warning badge-shadow">
                                                    Featured
                                                </div>
                                            @else
                                                <div class="badge badge-secondary badge-shadow">
                                                    No
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            @if($package->status)
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
                                                <a href="{{ route('puja-packages.show', $package->id) }}"
                                                    class="btn btn-info btn-sm mr-1"
                                                    title="View">
                                                    <i class="fas fa-eye"></i> </a>
                                                <a href="{{ route('puja-packages.edit', $package->id) }}"
                                                   class="btn btn-primary btn-sm mr-1"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('puja-packages.destroy', $package->id) }}"
                                                      method="POST"
                                                      class="d-inline delete-package-form">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-danger btn-sm"
                                                            title="Deactivate">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            No puja packages found.
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
                    targets: [0, 9]
                }
            ]
        });

        $('.delete-package-form').on('submit', function(e) {
            e.preventDefault();

            let form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: 'This package will be deactivated.',
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
        abort(403);
    @endphp
@endcan
