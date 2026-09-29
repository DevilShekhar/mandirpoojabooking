@can('roles.view')
@extends('admin.layouts.app')

@section('title', 'Role Management')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Role Management</h4>
                        <div class="card-header-action">
                            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Role
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
                                        <th>Role Name</th>
                                        <th>Guard</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($roles as $role)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            <strong>{{ $role->name }}</strong>
                                        </td>
                                        <td>
                                            {{ $role->guard_name ?? 'web' }}
                                        </td>
                                        <td>
                                            @if(($role->status ?? 1) == 1)
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
                                            {{ $role->created_at ? $role->created_at->format('d M Y') : '-' }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center">
                                                {{-- Manage Permissions --}}
                                                    <a href="{{ route('roles.permissions', $role->id) }}"
                                                        class="btn btn-info btn-sm mr-1"
                                                        title="Manage Permissions">
                                                        <i class="fas fa-key"></i>
                                                    </a>

                                                {{-- Edit --}}
                                                <a href="{{ route('roles.edit', $role->id) }}"
                                                    class="btn btn-primary btn-sm mr-1"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                {{-- Delete / Inactivate --}}
                                                    <form action="{{ route('roles.destroy', $role->id) }}"
                                                        method="POST"
                                                        class="d-inline delete-role-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            No roles found.
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
                    targets: [0, 5]
                }
            ]
        });

        $('.delete-role-form').on('submit', function(e) {
            e.preventDefault();
            let form = this;
            Swal.fire({
                title: 'Are you sure?',
                text: 'This role will be deactivated.',
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
