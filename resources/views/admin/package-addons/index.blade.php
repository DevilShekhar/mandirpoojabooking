@can('package-addons.index')
@extends('admin.layouts.app')

@section('title', 'Package Addons')

@section('content')

<section class="section">
    <div class="section-body">

        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>Package Addons</h4>

                        <div class="card-header-action">
                            <a href="{{ route('package-addons.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Addon
                            </a>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-striped" id="table-1">

                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Image</th>
                                        <th>Package</th>
                                        <th>Addon Name</th>
                                        <th>Price</th>
                                        <th>Max Quantity</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($packageAddons as $addon)

                                        <tr>

                                            <td class="text-center">
                                                {{ $loop->iteration }}
                                            </td>

                                            <td>
                                                @if($addon->image)
                                                    <img src="{{ asset('storage/' . $addon->image) }}"
                                                         alt="{{ $addon->name }}"
                                                         width="60"
                                                         height="60"
                                                         style="object-fit: cover; border-radius: 5px;">
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td>
                                                {{ $addon->pujaPackage->name ?? '-' }}
                                            </td>

                                            <td>
                                                <strong>{{ $addon->name }}</strong>
                                            </td>

                                            <td>
                                                ₹{{ number_format($addon->price, 2) }}
                                            </td>

                                            <td>
                                                {{ $addon->max_quantity }}
                                            </td>

                                            <td>
                                                @if($addon->status)
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

                                                    <a href="{{ route('package-addons.show', $addon->id) }}"
                                                       class="btn btn-info btn-sm mr-1"
                                                       title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <a href="{{ route('package-addons.edit', $addon->id) }}"
                                                       class="btn btn-primary btn-sm mr-1"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <form action="{{ route('package-addons.destroy', $addon->id) }}"
                                                          method="POST"
                                                          class="d-inline delete-addon-form">

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
                                            <td colspan="8" class="text-center">
                                                No package addons found.
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

        $('.delete-addon-form').on('submit', function(e) {

            e.preventDefault();

            let form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: 'This addon will be deactivated.',
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
</script>

@endpush
@else
    @php
        abort(403);
    @endphp
@endcan
