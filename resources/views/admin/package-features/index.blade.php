@can('package-features')
@extends('admin.layouts.app')

@section('title', 'Package Features')

@section('content')

<section class="section">
    <div class="section-body">


    <div class="row">
        <div class="col-12">

            <div class="card">

                <div class="card-header">
                    <h4>Package Features</h4>

                    <div class="card-header-action">
                        <a href="{{ route('package-features.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Feature
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
                                    <th>Puja Package</th>
                                    <th>Feature Name</th>
                                    <th>Feature Value</th>
                                    <th>Sort Order</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($packageFeatures as $packageFeature)

                                    <tr>

                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>
                                            <strong>
                                                {{ $packageFeature->pujaPackage->name ?? '-' }}
                                            </strong>
                                        </td>

                                        <td>
                                            {{ $packageFeature->feature_name }}
                                        </td>

                                        <td>
                                            {{ $packageFeature->feature_value ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $packageFeature->sort_order }}
                                        </td>

                                        <td>
                                            @if($packageFeature->status)
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

                                                <a href="{{ route('package-features.edit', $packageFeature->id) }}"
                                                   class="btn btn-primary btn-sm mr-1"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('package-features.destroy', $packageFeature->id) }}"
                                                      method="POST"
                                                      class="d-inline delete-feature-form">
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
                                        <td colspan="7" class="text-center">
                                            No package features found.
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

        $('.delete-feature-form').on('submit', function(e) {

            e.preventDefault();

            let form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: 'This feature will be deactivated.',
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
            confirmButtonColor: '#6777ef'
        });
    @endif
</script>

@endpush
@else
    @php
        abort(403);
    @endphp
@endcan
