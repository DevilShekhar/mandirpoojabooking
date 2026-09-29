@can('festivals.index')
@extends('admin.layouts.app')

@section('title', 'Festival Management')

@section('content')

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">

            <div class="card">

                <div class="card-header">
                    <h4>Festival Management</h4>

                    <div class="card-header-action">
                        <a href="{{ route('festivals.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Festival
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
                                    <th>Festival Name</th>
                                    <th>Festival Date</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($festivals as $festival)

                                    <tr>

                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>
                                            @if($festival->image)
                                                <img src="{{ asset('storage/' . $festival->image) }}"
                                                     alt="{{ $festival->name }}"
                                                     width="60"
                                                     height="60"
                                                     style="object-fit: cover; border-radius: 5px;">
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>
                                            <strong>{{ $festival->name }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ $festival->slug }}
                                            </small>
                                        </td>

                                        <td>
                                            {{ $festival->festival_date
                                                ? \Carbon\Carbon::parse($festival->festival_date)->format('d M Y')
                                                : '-' }}
                                        </td>

                                        <td>
                                            @if($festival->start_date && $festival->end_date)

                                                {{ \Carbon\Carbon::parse($festival->start_date)->format('d M Y') }}
                                                -
                                                {{ \Carbon\Carbon::parse($festival->end_date)->format('d M Y') }}

                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>
                                            @if($festival->status == 1)

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

                                                <a href="{{ route('festivals.show', $festival->id) }}"
                                                   class="btn btn-info btn-sm mr-1"
                                                   title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <a href="{{ route('festivals.edit', $festival->id) }}"
                                                   class="btn btn-primary btn-sm mr-1"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('festivals.destroy', $festival->id) }}"
                                                      method="POST"
                                                      class="d-inline delete-festival-form">

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
                                            No festivals found.
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
                    targets: [0, 1, 6]
                }
            ]
        });

        $('.delete-festival-form').on('submit', function(e) {

            e.preventDefault();

            let form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: 'This festival will be deactivated.',
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
        abort(404);
    @endphp
@endcan
