@can('users.index')
@extends('admin.layouts.app')
@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>User Listing</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add User
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Profile</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Gender</th>
                                            <th>Role</th>
                                            <th>Birth Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $user)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    @if($user->profile)
                                                        <img src="{{ asset('storage/' . $user->profile) }}" alt="image" width="40"
                                                            height="40" class="rounded-circle" style="object-fit: cover;">
                                                    @else
                                                        <img src="{{ asset('assets/img/users/user-1.png') }}" alt="image" width="40"
                                                            height="40" class="rounded-circle">
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>{{ $user->name }}</strong>
                                                </td>
                                                <td>
                                                    {{ $user->email }}
                                                </td>
                                                <td>
                                                    {{ $user->phone ?? '-' }}
                                                </td>
                                                <td>
                                                    {{ $user->gender ? ucfirst($user->gender) : '-' }}
                                                </td>
                                                <td>
                                                    @if($user->role)
                                                        <span class="badge badge-primary">
                                                            {{ $user->role->name }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-secondary">
                                                            -
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $user->birth_date ? $user->birth_date->format('d-m-Y') : '-' }}
                                                </td>
                                                <td>
                                                    @if($user->status)
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
                                                    <div class="d-flex align-items-center">
                                                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info btn-sm mr-1" title="View">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm mr-1" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            @if($user->status)
                                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="delete-user-form">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                                        title="Deactivate">
                                                                        <i class="fas fa-user-slash"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">
                                                    No users found.
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
                        targets: [0, 1, 8]
                    }
                ]
            });
            $('.delete-user-form').on('submit', function (e) {
                e.preventDefault();
                let form = this;
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This user will be deactivated.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#fc544b',
                    cancelButtonColor: '#6777ef',
                    confirmButtonText: 'Yes, deactivate',
                    cancelButtonText: 'Cancel'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
            $('.activate-user-form').on('submit', function (e) {
                e.preventDefault();
                let form = this;
                Swal.fire({
                    title: 'Activate User?',
                    text: 'This user will be activated.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#47c363',
                    cancelButtonColor: '#6777ef',
                    confirmButtonText: 'Yes, activate',
                    cancelButtonText: 'Cancel'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
    @if(session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: @json(session('success')),
                icon: 'success',
                confirmButtonColor: '#6777ef',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
@endpush

@else
    @php
        abort(403);
    @endphp
@endcan
