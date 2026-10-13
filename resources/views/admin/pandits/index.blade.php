@can('pandits.index')

@extends('admin.layouts.app')

@section('title', 'Pandit Management')

@section('content')

<section class="section">
    <div class="section-body">

        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>Pandit Management</h4>

                        <div class="card-header-action">
                            @can('pandits.create')
                                <a href="{{ route('pandits.create') }}"
                                   class="btn btn-primary">
                                    <i class="fas fa-plus"></i>
                                    Add Pandit
                                </a>
                            @endcan
                        </div>
                    </div>

                    <div class="card-body">

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}

                                <button type="button"
                                        class="close"
                                        data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="table-responsive">

                            <table class="table table-striped" id="table-1">

                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Pandit</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Gender</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($pandits as $pandit)

                                        <tr>

                                            <td class="text-center">
                                                {{ $pandits->firstItem() + $loop->index }}
                                            </td>

                                            <td>
                                                <div class="d-flex align-items-center">

                                                    @if($pandit->profile)
                                                        <img src="{{ asset('storage/' . $pandit->profile) }}"
                                                             alt="{{ $pandit->name }}"
                                                             width="45"
                                                             height="45"
                                                             style="object-fit: cover; border-radius: 50%; margin-right: 10px;">
                                                    @else
                                                        <img src="{{ asset('assets/admin/img/avatar/avatar-1.png') }}"
                                                             alt="No Image"
                                                             width="45"
                                                             height="45"
                                                             style="object-fit: cover; border-radius: 50%; margin-right: 10px;">
                                                    @endif

                                                    <strong>
                                                        {{ $pandit->name }}
                                                    </strong>

                                                </div>
                                            </td>

                                            <td>
                                                {{ $pandit->phone ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $pandit->email ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $pandit->gender
                                                    ? ucfirst($pandit->gender)
                                                    : '-' }}
                                            </td>

                                            <td>

                                                @if($pandit->status)
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
                                                {{ optional($pandit->created_at)->format('d M Y') }}
                                            </td>

                                            <td>
                                                <div class="d-flex align-items-center justify-content-center">

                                                    @can('pandits.show')
                                                        <a href="{{ route('pandits.show', $pandit->id) }}"
                                                           class="btn btn-info btn-sm mr-1"
                                                           title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endcan

                                                    @can('pandits.edit')
                                                        <a href="{{ route('pandits.edit', $pandit->id) }}"
                                                           class="btn btn-primary btn-sm mr-1"
                                                           title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan

                                                    @if($pandit->status)

                                                        @can('pandits.delete')

                                                            <form action="{{ route('pandits.destroy', $pandit->id) }}"
                                                                  method="POST"
                                                                  class="d-inline delete-pandit-form">

                                                                @csrf
                                                                @method('DELETE')

                                                                <button type="submit"
                                                                        class="btn btn-danger btn-sm"
                                                                        title="Deactivate">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>

                                                            </form>

                                                        @endcan

                                                    @endif

                                                </div>
                                            </td>

                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="8" class="text-center">
                                                No pandits found.
                                            </td>
                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                        <div class="mt-3">
                            {{ $pandits->links() }}
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
    $(document).on('submit', '.delete-pandit-form', function(e) {

        e.preventDefault();

        let form = this;

        Swal.fire({
            title: 'Are you sure?',
            text: 'This pandit will be deactivated.',
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
</script>

@endpush

@else

    @php
        abort(403);
    @endphp

@endcan