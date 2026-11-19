@extends('admin.layouts.app')
@section('title', 'Darshan Verification Officers')
@section('content')
@php
    $currentUser = auth()->user();
    $columnCount = 9;
@endphp
@canany(['darshan-verification-officers.index','darshan-verification-officers.view'])
<section class="section">
    <div class="section-header">
        <h1>
            <i class="fas fa-user-shield mr-2"></i>
            Darshan Verification Officers
        </h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                <a href="{{ url('/admin/dashboard') }}">Dashboard</a>
            </div>
            <div class="breadcrumb-item">
                Darshan Verification Officers
            </div>
        </div>
    </div>
    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle mr-1"></i>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle mr-1"></i>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
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
        <div class="card shadow-sm officer-card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h4 class="mb-1">
                        <i class="fas fa-users text-primary mr-2"></i>
                        Officer Listing
                    </h4>
                    <small class="text-muted">
                        Manage Darshan Verification Officers assigned to temples.
                    </small>
                </div>
                @can('darshan-verification-officers.create')
                    <div class="mt-2 mt-md-0">
                        <a href="{{ route('admin.darshan-verification-officers.create') }}"
                           class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i>
                            Add New Officer
                        </a>
                    </div>
                @endcan
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="officer-summary-box">
                            <div class="officer-summary-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <div class="officer-summary-label">
                                    Total Officers
                                </div>
                                <div class="officer-summary-value">
                                    {{ $officers->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="officer-summary-box">
                            <div class="officer-summary-icon active-icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div>
                                <div class="officer-summary-label">
                                    Active Officers
                                </div>
                                <div class="officer-summary-value">
                                    {{ $officers->where('status', 1)->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="officer-summary-box">
                            <div class="officer-summary-icon inactive-icon">
                                <i class="fas fa-user-slash"></i>
                            </div>
                            <div>
                                <div class="officer-summary-label">
                                    Inactive Officers
                                </div>
                                <div class="officer-summary-value">
                                    {{ $officers->where('status', 0)->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle" id="officers-table">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Profile</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Birth Date</th>
                                <th>Temple</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($officers as $officer)
                                <tr>
                                    <td class="text-center">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        @if($officer->profile)
                                            <img src="{{ asset('storage/' . $officer->profile) }}"
                                                 alt="{{ $officer->name }}"
                                                 class="officer-avatar"
                                                 onerror="this.onerror=null;this.src='{{ asset('images/default-user.png') }}';" height="50px" width="50px">
                                        @else
                                            <div class="officer-avatar-placeholder">
                                                {{ strtoupper(substr($officer->name ?? 'O', 0, 1)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="officer-name">
                                            {{ $officer->name }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="officer-contact">
                                            <i class="fas fa-envelope text-primary mr-1"></i>
                                            {{ $officer->email }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($officer->phone)
                                            <span class="officer-contact">
                                                <i class="fas fa-phone-alt text-success mr-1"></i>
                                                {{ $officer->phone }}
                                            </span>
                                        @else
                                            <span class="text-muted">
                                                Not provided
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($officer->gender)
                                            {{ ucfirst($officer->gender) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($officer->birth_date)
                                            {{ \Carbon\Carbon::parse($officer->birth_date)->format('d-m-Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($officer->temple)
                                            <span class="temple-label">
                                                <i class="fas fa-place-of-worship mr-1"></i>
                                                {{ $officer->temple->name }}
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                Not assigned
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($officer->status)
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
                                    <td class="text-center">
                                        <div class="officer-actions">
                                            @can('darshan-verification-officers.show')
                                                <a href="{{ route('admin.darshan-verification-officers.show', $officer->id) }}"
                                                   class="btn btn-info btn-sm"  title="View Officer" aria-label="View Officer">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endcan
                                            @can('darshan-verification-officers.edit')
                                                <a href="{{ route('admin.darshan-verification-officers.edit', $officer->id) }}"
                                                   class="btn btn-primary btn-sm" title="Edit Officer" aria-label="Edit Officer">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                            @can('darshan-verification-officers.delete')
                                                @if($officer->status)
                                                    <form action="{{ route('admin.darshan-verification-officers.destroy', $officer->id) }}"
                                                          method="POST" class="delete-officer-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Deactivate Officer"  aria-label="Deactivate Officer">
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
                                    <td colspan="10" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-user-shield"></i>
                                            </div>
                                            <h5>No Officers Found</h5>
                                            <p class="text-muted">
                                                No Darshan Verification Officers are currently available.
                                            </p>
                                            @can('darshan-verification-officers.create')
                                                <a href="{{ route('admin.darshan-verification-officers.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus mr-1"></i>
                                                    Create Officer
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@else
<section class="section">
    <div class="section-body">
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle mr-1"></i>
            You do not have permission to view Darshan Verification Officers.
        </div>
    </div>
</section>
@endcanany
@endsection
@push('styles')
<style>
.officer-card {
    border: none;
    border-radius: 10px;
    overflow: hidden;
}
.officer-card .card-header {
    padding: 20px 24px;
    background: #fff;
    border-bottom: 1px solid #eef0f5;
}
.officer-card .card-body {
    padding: 24px;
}
.officer-summary-box {
    display: flex;
    align-items: center;
    gap: 15px;
    min-height: 95px;
    padding: 18px;
    background: #f8f9fc;
    border: 1px solid #edf0f5;
    border-radius: 9px;
}
.officer-summary-icon {
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
.officer-summary-icon.active-icon {
    background: #e5f8ed;
    color: #28a745;
}
.officer-summary-icon.inactive-icon {
    background: #ffe9e8;
    color: #fc544b;
}
.officer-summary-label {
    margin-bottom: 5px;
    color: #6c757d;
    font-size: 12px;
}
.officer-summary-value {
    color: #34395e;
    font-size: 23px;
    font-weight: 700;
    line-height: 1.2;
}
.officer-avatar,
.officer-avatar-placeholder {
    width: 44px;
    height: 44px;
    border-radius: 50%;
}
.officer-avatar {
    object-fit: cover;
    border: 2px solid #edf0f5;
}
.officer-avatar-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e8edff;
    color: #6777ef;
    font-size: 17px;
    font-weight: 700;
}
/* Officer Details */
.officer-name {
    color: #34395e;
    font-weight: 600;
}
.officer-contact {
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
.status-label {
    padding: 6px 9px;
    border-radius: 5px;
    font-weight: 500;
}
/* Actions */
.officer-actions {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}
.officer-actions .btn {
    min-width: 32px;
}
.officer-actions form {
    margin: 0;
}
/* Table */
#officers-table thead th {
    padding: 14px 12px;
    color: #34395e;
    background: #f8f9fc;
    border-bottom: 1px solid #e9ecf2;
    white-space: nowrap;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .3px;
}
#officers-table tbody td {
    padding: 14px 12px;
    vertical-align: middle;
}
/* Empty State */
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
/* Responsive */
@media(max-width: 767px) {
    .officer-card .card-header,
    .officer-card .card-body {
        padding: 15px;
    }
    .officer-summary-box {
        min-height: 80px;
        padding: 14px;
    }
    .officer-card .card-header {
        align-items: flex-start !important;
    }
}
</style>
@endpush
@push('scripts')
<script>
$(document).ready(function () {
    if ($.fn.DataTable && $('#officers-table tbody tr').length &&
        $('#officers-table tbody tr:first td').length === 10
    ) {
        $('#officers-table').DataTable({
            ordering: true,
            order: [],
            searching: true,
            paging: true,
            info: true,
            lengthChange: true,
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'All']
            ],
            autoWidth: false,
            columnDefs: [
                {
                    orderable: false,
                    targets: [0, 1, 9]
                }
            ],
            language: {
                search: 'Search Officers:',
                zeroRecords: 'No matching Officers found',
                emptyTable: 'No Officers found',
                lengthMenu: 'Show _MENU_ entries',
                info: 'Showing _START_ to _END_ of _TOTAL_ Officers',
                infoEmpty: 'Showing 0 to 0 of 0 Officers'
            }
        });
    }
    $(document).on('submit', '.delete-officer-form', function (e) {
        e.preventDefault();
        const form = this;
        if (typeof Swal === 'undefined') {
            if (confirm('Are you sure you want to deactivate this Officer?')) {
                form.submit();
            }
            return;
        }
        Swal.fire({
            title: 'Deactivate Officer?',
            text: 'This Darshan Verification Officer will be marked as inactive.',
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