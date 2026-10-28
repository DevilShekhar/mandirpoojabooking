@extends('admin.layouts.app')
@section('content')
    @canany(['store-managers.view', 'store-managers.index'])
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Store Manager Listing</h4>

                                @can('store-managers.create')
                                    @if($storeManagers->isEmpty())
                                        <div class="card-header-action">
                                            <a href="{{ route('admin.store-managers.create') }}"
                                               class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Add Store Manager
                                            </a>
                                        </div>
                                    @endif
                                @endcan
                            </div>

                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show">
                                        {{ session('success') }}
                                        <button type="button"
                                                class="close"
                                                data-dismiss="alert"
                                                aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show">
                                        {{ session('error') }}
                                        <button type="button"
                                                class="close"
                                                data-dismiss="alert"
                                                aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-1">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Profile</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Role</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse($storeManagers as $manager)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $loop->iteration + (($storeManagers->currentPage() - 1) * $storeManagers->perPage()) }}
                                                    </td>

                                                    <td>
                                                        @if($manager->profile)
                                                            <img src="{{ asset('storage/' . $manager->profile) }}"
                                                                 alt="Profile"
                                                                 width="40"
                                                                 height="40"
                                                                 class="rounded-circle"
                                                                 style="object-fit: cover;">
                                                        @else
                                                            <img src="{{ asset('assets/img/users/user-1.png') }}"
                                                                 alt="Default profile"
                                                                 width="40"
                                                                 height="40"
                                                                 class="rounded-circle">
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <strong>{{ $manager->name }}</strong>
                                                    </td>

                                                    <td>{{ $manager->email }}</td>

                                                    <td>{{ $manager->phone ?? '-' }}</td>

                                                    <td>
                                                        <span class="badge badge-primary">Store Manager</span>
                                                    </td>

                                                    <td>
                                                        @if($manager->status)
                                                            <span class="badge badge-success badge-shadow">Active</span>
                                                        @else
                                                            <span class="badge badge-danger badge-shadow">Inactive</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @can('store-managers.show')
                                                                <a href="{{ route('admin.store-managers.show', $manager->id) }}"
                                                                   class="btn btn-info btn-sm mr-1"
                                                                   title="View">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            @endcan

                                                            @can('store-managers.edit')
                                                                <a href="{{ route('admin.store-managers.edit', $manager->id) }}"
                                                                   class="btn btn-primary btn-sm mr-1"
                                                                   title="Edit">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            @endcan

                                                            @can('store-managers.delete')
                                                                @if($manager->status)
                                                                    <form action="{{ route('admin.store-managers.destroy', $manager->id) }}"
                                                                          method="POST"
                                                                          class="delete-store-manager-form">
                                                                        @csrf
                                                                        @method('DELETE')

                                                                        <button type="submit"
                                                                                class="btn btn-danger btn-sm"
                                                                                title="Deactivate">
                                                                            <i class="fas fa-user-slash"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            @endcan
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center">
                                                        No Store Manager found.

                                                        @can('store-managers.create')
                                                            <a href="{{ route('admin.store-managers.create') }}">
                                                                Create Store Manager
                                                            </a>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3">
                                    {{ $storeManagers->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="section">
            <div class="section-body">
                <div class="alert alert-danger">
                    You do not have permission to view Store Managers.
                </div>
            </div>
        </section>
    @endcanany
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#table-1').DataTable({
                ordering: true,
                searching: true,
                paging: false,
                info: false,
                lengthChange: false,
                columnDefs: [
                    {
                        orderable: false,
                        targets: [0, 1, 9]
                    }
                ]
            });

            $('.delete-store-manager-form').on('submit', function (e) {
                e.preventDefault();

                const form = this;

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This Store Manager will be deactivated.',
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
        });
    </script>
@endpush