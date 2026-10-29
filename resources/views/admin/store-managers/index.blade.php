
@extends('admin.layouts.app')

@section('content')
    @canany(['store-managers.view', 'store-managers.index'])

        @php
            $currentUser = auth()->user();

            $isAdminUser = $currentUser->hasAnyRole([
                'Admin',
                'Super Admin',
            ]);

            $columnCount = $isAdminUser ? 9 : 8;
        @endphp

        <section class="section">

            <div class="section-header">
                <h1>
                    <i class="fas fa-users mr-2"></i>
                    Store Managers
                </h1>

                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active">
                        <a href="{{ url('/admin/dashboard') }}">Dashboard</a>
                    </div>

                    <div class="breadcrumb-item">
                        Store Managers
                    </div>
                </div>
            </div>

            <div class="section-body">

                {{-- Success message --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle mr-1"></i>
                        {{ session('success') }}

                        <button type="button"
                                class="close"
                                data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- Error message --}}
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ session('error') }}

                        <button type="button"
                                class="close"
                                data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- Validation errors --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Please correct the following errors:
                        </strong>

                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Summary and listing card --}}
                <div class="card shadow-sm store-manager-card">

                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">

                        <div>
                            <h4 class="mb-1">
                                <i class="fas fa-user-tie text-primary mr-2"></i>
                                Store Manager Listing
                            </h4>

                            <small class="text-muted">
                                Manage Store Managers assigned to temples.
                            </small>
                        </div>

                        {{-- Add button only when an eligible temple exists --}}
                        @if($canAddStoreManager)
                            @can('store-managers.create')
                                <div class="mt-2 mt-md-0">
                                    <a href="{{ route('admin.store-managers.create') }}"
                                       class="btn btn-primary">
                                        <i class="fas fa-plus mr-1"></i>
                                        Add Store Manager
                                    </a>
                                </div>
                            @endcan
                        @endif

                    </div>

                    <div class="card-body">

                        {{-- Summary --}}
                        <div class="row mb-4">

                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="manager-summary-box">
                                    <div class="manager-summary-icon">
                                        <i class="fas fa-users"></i>
                                    </div>

                                    <div>
                                        <div class="manager-summary-label">
                                            Store Managers Listed
                                        </div>

                                        <div class="manager-summary-value">
                                            {{ $storeManagers->total() }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="manager-summary-box">
                                    <div class="manager-summary-icon">
                                        <i class="fas fa-user-check"></i>
                                    </div>

                                    <div>
                                        <div class="manager-summary-label">
                                            Active on This Page
                                        </div>

                                        <div class="manager-summary-value">
                                            {{ $storeManagers->getCollection()->where('status', true)->count() }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="manager-summary-box">
                                    <div class="manager-summary-icon">
                                        <i class="fas fa-user-slash"></i>
                                    </div>

                                    <div>
                                        <div class="manager-summary-label">
                                            Inactive on This Page
                                        </div>

                                        <div class="manager-summary-value">
                                            {{ $storeManagers->getCollection()->where('status', false)->count() }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- One-manager-per-temple notice --}}
                        @if(!$canAddStoreManager)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-1"></i>

                                @if($isAdminUser)
                                    All temples already have a Store Manager.
                                    You cannot create another Store Manager until a temple becomes eligible.
                                @else
                                    Your temple already has a Store Manager.
                                    Only one Store Manager is allowed per temple.
                                @endif
                            </div>
                        @endif

                        {{-- Store Managers table --}}
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle"
                                   id="table-1">

                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Profile</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>

                                        @if($isAdminUser)
                                            <th>Temple</th>
                                        @endif

                                        <th>Role</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($storeManagers as $manager)

                                        @php
                                            /*
                                             * Admins can manage all Store Managers.
                                             * Temple Managers can manage only those
                                             * they created for their own temple.
                                             */
                                            $canManageThisManager = $isAdminUser
                                                || (
                                                    (int) $manager->created_by === (int) $currentUser->id
                                                    && (int) $manager->temple_id === (int) (
                                                        DB::table('temple_user')
                                                            ->where('user_id', $currentUser->id)
                                                            ->value('temple_id')
                                                    )
                                                );
                                        @endphp

                                        <tr>

                                            {{-- Row number --}}
                                            <td class="text-center">
                                                {{ $loop->iteration + (($storeManagers->currentPage() - 1) * $storeManagers->perPage()) }}
                                            </td>

                                            {{-- Profile --}}
                                            <td>
                                                @if($manager->profile)
                                                    <img
                                                        src="{{ asset('storage/' . $manager->profile) }}"
                                                        alt="{{ $manager->name }}"
                                                        class="manager-avatar" height="50px" width="50px">
                                                @else
                                                    <div class="manager-avatar-placeholder">
                                                        {{ strtoupper(substr($manager->name ?? 'S', 0, 1)) }}
                                                    </div>
                                                @endif
                                            </td>

                                            {{-- Name --}}
                                            <td>
                                                <div class="manager-name">
                                                    {{ $manager->name }}
                                                </div>

                                               
                                            </td>

                                            {{-- Email --}}
                                            <td>
                                                <span class="manager-contact">
                                                    <i class="fas fa-envelope text-primary mr-1"></i>
                                                    {{ $manager->email }}
                                                </span>
                                            </td>

                                            {{-- Phone --}}
                                            <td>
                                                @if($manager->phone)
                                                    <span class="manager-contact">
                                                        <i class="fas fa-phone-alt text-success mr-1"></i>
                                                        {{ $manager->phone }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">
                                                        Not provided
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- Temple for Admin/Super Admin --}}
                                            @if($isAdminUser)
                                                <td>
                                                    @if($manager->temple)
                                                        <span class="temple-label">
                                                            <i class="fas fa-place-of-worship mr-1"></i>
                                                            {{ $manager->temple->name }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-warning">
                                                            Not assigned
                                                        </span>
                                                    @endif
                                                </td>
                                            @endif

                                            {{-- Role --}}
                                            <td>
                                                <span class="badge badge-primary role-label">
                                                    <i class="fas fa-user-tie mr-1"></i>
                                                    Store Manager
                                                </span>
                                            </td>

                                            {{-- Status --}}
                                            <td>
                                                @if($manager->status)
                                                    <span class="badge badge-success status-label">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger status-label">
                                                        <i class="fas fa-times-circle mr-1"></i>
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- Actions --}}
                                            <td class="text-center">
                                                <div class="manager-actions">

                                                    {{-- View --}}
                                                    @can('store-managers.show')
                                                        @if($canManageThisManager)
                                                            <a
                                                                href="{{ route('admin.store-managers.show', $manager->id) }}"
                                                                class="btn btn-info btn-sm"
                                                                title="View Store Manager"
                                                                aria-label="View Store Manager">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @endif
                                                    @endcan

                                                    {{-- Edit --}}
                                                    @can('store-managers.edit')
                                                      
                                                            <a
                                                                href="{{ route('admin.store-managers.edit', $manager->id) }}"
                                                                class="btn btn-primary btn-sm"
                                                                title="Edit Store Manager"
                                                                aria-label="Edit Store Manager">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                    
                                                    @endcan

                                                    {{-- Deactivate --}}
                                                    @can('store-managers.delete')
                                                        @if($canManageThisManager && $manager->status)
                                                            <form
                                                                action="{{ route('admin.store-managers.destroy', $manager->id) }}"
                                                                method="POST"
                                                                class="delete-store-manager-form">

                                                                @csrf
                                                                @method('DELETE')

                                                                <button
                                                                    type="submit"
                                                                    class="btn btn-danger btn-sm"
                                                                    title="Deactivate Store Manager"
                                                                    aria-label="Deactivate Store Manager">
                                                                    <i class="fas fa-user-slash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endcan

                                                    {{-- Read-only notice --}}
                                                    @if(!$canManageThisManager)
                                                        <span class="text-muted"
                                                              title="This Store Manager was created by another user">
                                                            <i class="fas fa-lock mr-1"></i>
                                                            View only
                                                        </span>
                                                    @endif

                                                </div>
                                            </td>

                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="{{ $columnCount }}"
                                                class="text-center py-5">

                                                <div class="empty-state">
                                                    <div class="empty-state-icon">
                                                        <i class="fas fa-users"></i>
                                                    </div>

                                                    <h5>No Store Managers Found</h5>

                                                    <p class="text-muted">
                                                        No Store Managers are currently assigned to your view.
                                                    </p>

                                                    @if($canAddStoreManager)
                                                        @can('store-managers.create')
                                                            <a
                                                                href="{{ route('admin.store-managers.create') }}"
                                                                class="btn btn-primary">
                                                                <i class="fas fa-plus mr-1"></i>
                                                                Create Store Manager
                                                            </a>
                                                        @endcan
                                                    @endif
                                                </div>

                                            </td>
                                        </tr>

                                    @endforelse
                                </tbody>

                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if($storeManagers->hasPages())
                            <div class="d-flex justify-content-between align-items-center flex-wrap mt-4">

                                <div class="text-muted mb-2">
                                    Showing
                                    {{ $storeManagers->firstItem() ?? 0 }}
                                    to
                                    {{ $storeManagers->lastItem() ?? 0 }}
                                    of
                                    {{ $storeManagers->total() }}
                                    Store Managers
                                </div>

                                <div>
                                    {{ $storeManagers->links() }}
                                </div>

                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </section>

    @else

        <section class="section">
            <div class="section-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    You do not have permission to view Store Managers.
                </div>
            </div>
        </section>

    @endcanany
@endsection

@push('styles')
    <style>
        .store-manager-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
        }

        .store-manager-card .card-header {
            padding: 20px 24px;
            background: #fff;
            border-bottom: 1px solid #eef0f5;
        }

        .store-manager-card .card-body {
            padding: 24px;
        }

        .manager-summary-box {
            display: flex;
            align-items: center;
            gap: 15px;
            min-height: 95px;
            padding: 18px;
            background: #f8f9fc;
            border: 1px solid #edf0f5;
            border-radius: 9px;
        }

        .manager-summary-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            flex-shrink: 0;
            background: #e8edff;
            color: #6777ef;
            border-radius: 10px;
            font-size: 20px;
        }

        .manager-summary-label {
            margin-bottom: 5px;
            color: #6c757d;
            font-size: 12px;
        }

        .manager-summary-value {
            color: #34395e;
            font-size: 23px;
            font-weight: 700;
            line-height: 1.2;
        }

        .manager-avatar,
        .manager-avatar-placeholder {
            width: 44px;
            height: 44px;
            border-radius: 50%;
        }

        .manager-avatar {
            object-fit: cover;
            border: 2px solid #edf0f5;
        }

        .manager-avatar-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e8edff;
            color: #6777ef;
            font-size: 17px;
            font-weight: 700;
        }

        .manager-name {
            color: #34395e;
            font-weight: 600;
        }

        .manager-contact {
            white-space: nowrap;
        }

        .temple-label {
            display: inline-block;
            padding: 6px 9px;
            color: #3abaf4;
            background: #e7f8ff;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
        }

        .role-label,
        .status-label {
            padding: 6px 9px;
            border-radius: 5px;
            font-weight: 500;
        }

        .manager-actions {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .manager-actions .btn {
            min-width: 32px;
        }

        .manager-actions form {
            margin: 0;
        }

        #table-1 thead th {
            padding: 14px 12px;
            color: #34395e;
            background: #f8f9fc;
            border-bottom: 1px solid #e9ecf2;
            white-space: nowrap;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        #table-1 tbody td {
            padding: 14px 12px;
            vertical-align: middle;
        }

        .empty-state {
            max-width: 420px;
            margin: 0 auto;
        }

        .empty-state-icon {
            margin-bottom: 15px;
            color: #b5b9d1;
            font-size: 42px;
        }

        .empty-state h5 {
            color: #34395e;
            font-weight: 600;
        }

        @media (max-width: 767px) {
            .store-manager-card .card-header,
            .store-manager-card .card-body {
                padding: 15px;
            }

            .manager-summary-box {
                min-height: 80px;
                padding: 14px;
            }

            .store-manager-card .card-header {
                align-items: flex-start !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            // Do not enable DataTables pagination because Laravel
            // pagination is already handled by the controller.
            if ($.fn.DataTable) {
                $('#table-1').DataTable({
                    ordering: true,
                    searching: true,
                    paging: false,
                    info: false,
                    lengthChange: false,
                    autoWidth: false,
                    columnDefs: [
                        {
                            orderable: false,
                            targets: [0, 1, -1]
                        }
                    ],
                    language: {
                        search: 'Search Store Managers:',
                        zeroRecords: 'No matching Store Managers found'
                    }
                });
            }

            // Deactivation confirmation.
            $('.delete-store-manager-form').on('submit', function (e) {
                e.preventDefault();

                const form = this;

                if (typeof Swal === 'undefined') {
                    if (confirm('Are you sure you want to deactivate this Store Manager?')) {
                        form.submit();
                    }

                    return;
                }

                Swal.fire({
                    title: 'Deactivate Store Manager?',
                    text: 'This Store Manager will be marked inactive. They will still count toward the one-manager-per-temple rule.',
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
