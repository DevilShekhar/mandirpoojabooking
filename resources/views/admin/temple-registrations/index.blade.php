@can('all-templae-register.list')

@extends('admin.layouts.app')

@section('title', 'Temple Registration Management')

@section('content')

<section class="section">
    <div class="section-body">

        {{-- Page Heading --}}
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h4>Temple Registration Management</h4>

                        <div class="card-header-action">
                            <a href="{{ route('all-templae-register') }}"
                               class="btn btn-primary">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </a>
                        </div>
                    </div>

                    <div class="card-body">

                        {{-- Success Message --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle"></i>
                                {{ session('success') }}

                                <button type="button"
                                        class="close"
                                        data-dismiss="alert"
                                        aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        {{-- Error Message --}}
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ session('error') }}

                                <button type="button"
                                        class="close"
                                        data-dismiss="alert"
                                        aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        {{-- Validation Errors --}}
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <strong>Please check the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Registration Statistics --}}
                        <div class="row mb-4">

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-primary">
                                        <i class="fas fa-place-of-worship"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Total Registrations</h4>
                                        </div>
                                        <div class="card-body">
                                            {{ $totalCount ?? 0 }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-warning">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Pending</h4>
                                        </div>
                                        <div class="card-body">
                                            {{ $pendingCount ?? 0 }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-success">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Approved</h4>
                                        </div>
                                        <div class="card-body">
                                            {{ $approvedCount ?? 0 }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-danger">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Rejected</h4>
                                        </div>
                                        <div class="card-body">
                                            {{ $rejectedCount ?? 0 }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Search and Status Filter --}}
                        <form action="{{ route('all-templae-register') }}"
                              method="GET"
                              class="mb-4">

                            <div class="row">

                                <div class="col-md-5 mb-2">
                                    <label for="search">Search Registration</label>

                                    <input type="text"
                                           name="search"
                                           id="search"
                                           class="form-control"
                                           placeholder="Manager, email, phone, temple name..."
                                           value="{{ request('search') }}">
                                </div>

                                <div class="col-md-3 mb-2">
                                    <label for="status">Registration Status</label>

                                    <select name="status"
                                            id="status"
                                            class="form-control">
                                        <option value="">All Registrations</option>

                                        <option value="pending"
                                            {{ request('status') === 'pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>

                                        <option value="approved"
                                            {{ request('status') === 'approved' ? 'selected' : '' }}>
                                            Approved
                                        </option>

                                        <option value="rejected"
                                            {{ request('status') === 'rejected' ? 'selected' : '' }}>
                                            Rejected
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary mr-2">
                                        <i class="fas fa-search"></i> Search
                                    </button>

                                    <a href="{{ route('all-templae-register') }}"
                                       class="btn btn-light">
                                        <i class="fas fa-redo"></i> Reset
                                    </a>
                                </div>

                            </div>
                        </form>

                        {{-- Registration Table --}}
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">

                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Temple</th>
                                        <th>Manager Details</th>
                                        <th>Contact</th>
                                        <th>Location</th>
                                        <th>Registered On</th>
                                        <th>Registration Status</th>
                                        <th>Account Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($registrations as $registration)

                                        <tr>
                                            {{-- Number --}}
                                            <td>
                                                {{ $registrations->firstItem() + $loop->index }}
                                            </td>

                                            {{-- Temple Details --}}
                                            <td>
                                                <strong>
                                                    {{ $registration->temple_name ?? '-' }}
                                                </strong>

                                                <br>

                                                <small class="text-muted">
                                                    Code:
                                                    {{ $registration->temple_code ?? 'Not assigned' }}
                                                </small>

                                                <br>

                                                <small class="text-muted">
                                                    Temple ID:
                                                    {{ $registration->temple_id }}
                                                </small>
                                            </td>

                                            {{-- Manager Details --}}
                                            <td>
                                                <strong>
                                                    {{ $registration->manager_name ?? '-' }}
                                                </strong>

                                                <br>

                                                <small class="text-muted">
                                                    User ID:
                                                    {{ $registration->user_id }}
                                                </small>
                                            </td>

                                            {{-- Contact Details --}}
                                            <td>
                                                <div>
                                                    <i class="fas fa-envelope text-primary"></i>
                                                    {{ $registration->email ?? '-' }}
                                                </div>

                                                <div class="mt-1">
                                                    <i class="fas fa-phone text-success"></i>
                                                    {{ $registration->phone ?? '-' }}
                                                </div>
                                            </td>

                                            {{-- Location --}}
                                            <td>
                                                {{ $registration->city_name ?? '-' }}

                                                <br>

                                                <small class="text-muted">
                                                    {{ $registration->district_name ?? '-' }},
                                                    {{ $registration->state_name ?? '-' }}
                                                </small>

                                                <br>

                                                <small class="text-muted">
                                                    {{ $registration->country_name ?? '-' }}
                                                </small>

                                                @if(!empty($registration->address))
                                                    <br>
                                                    <small>
                                                        {{ $registration->address }}
                                                    </small>
                                                @endif
                                            </td>

                                            {{-- Registration Date --}}
                                            <td>
                                                @if($registration->registration_date)
                                                    {{ \Carbon\Carbon::parse($registration->registration_date)->format('d M Y') }}

                                                    <br>

                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($registration->registration_date)->format('h:i A') }}
                                                    </small>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            {{-- Approval Status --}}
                                            <td>
                                                @if($registration->approval_status === 'approved')

                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check"></i>
                                                        Approved
                                                    </span>

                                                @elseif($registration->approval_status === 'rejected')

                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-times"></i>
                                                        Rejected
                                                    </span>

                                                @else

                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-clock"></i>
                                                        Pending
                                                    </span>

                                                @endif
                                            </td>

                                            {{-- Account Status --}}
                                            <td>
                                                @if($registration->user_status && $registration->temple_status)

                                                    <span class="badge badge-success">
                                                        Active
                                                    </span>

                                                @else

                                                    <span class="badge badge-secondary">
                                                        Inactive
                                                    </span>

                                                @endif

                                                <br>

                                                @if($registration->is_verified)
                                                    <small class="text-success">
                                                        <i class="fas fa-check-circle"></i>
                                                        Verified
                                                    </small>
                                                @else
                                                    <small class="text-warning">
                                                        <i class="fas fa-exclamation-circle"></i>
                                                        Not Verified
                                                    </small>
                                                @endif
                                            </td>

                                            {{-- Actions --}}
                                            <td>
                                                <div class="d-flex align-items-center">

                                                    @if($registration->approval_status === 'pending')

                                                        {{-- Approve Button --}}
                                                        <form action="{{ route(
                                                            'all-templae-register.approve',
                                                            $registration->registration_id
                                                        ) }}"
                                                              method="POST"
                                                              class="approve-registration-form mr-1">

                                                            @csrf

                                                            <button type="submit"
                                                                    class="btn btn-sm btn-success"
                                                                    title="Approve Registration">
                                                                <i class="fas fa-check"></i>
                                                                Approve
                                                            </button>
                                                        </form>

                                                        {{-- Reject Button --}}
                                                        <form action="{{ route(
                                                            'all-templae-register.reject',
                                                            $registration->registration_id
                                                        ) }}"
                                                              method="POST"
                                                              class="reject-registration-form">

                                                            @csrf

                                                            <button type="submit"
                                                                    class="btn btn-sm btn-danger"
                                                                    title="Reject Registration">
                                                                <i class="fas fa-times"></i>
                                                                Reject
                                                            </button>
                                                        </form>

                                                    @elseif($registration->approval_status === 'approved')

                                                        <span class="text-success">
                                                            <i class="fas fa-check-circle"></i>
                                                            Approved
                                                        </span>

                                                    @elseif($registration->approval_status === 'rejected')

                                                        <span class="text-danger">
                                                            <i class="fas fa-ban"></i>
                                                            Rejected
                                                        </span>

                                                    @else

                                                        <span class="text-muted">
                                                            Unknown Status
                                                        </span>

                                                    @endif

                                                </div>
                                            </td>
                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <i class="fas fa-place-of-worship fa-2x text-muted mb-2"></i>

                                                <p class="mb-0">
                                                    No temple registrations found.
                                                </p>
                                            </td>
                                        </tr>

                                    @endforelse
                                </tbody>

                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if(method_exists($registrations, 'links'))
                            <div class="mt-3">
                                {{ $registrations->links() }}
                            </div>
                        @endif

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

        // Initialize DataTables only when pagination is not being used.
        // Laravel pagination is handled by the controller.
        @if(!$registrations->hasPages())
            $('#table-1').DataTable({
                paging: false,
                searching: false,
                info: false,
                columnDefs: [
                    {
                        sortable: false,
                        targets: [8]
                    }
                ]
            });
        @endif

        // Approve confirmation.
        $('.approve-registration-form').on('submit', function (e) {
            e.preventDefault();

            let form = this;

            Swal.fire({
                title: 'Approve Registration?',
                text: 'This will activate the temple and manager account.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Approve',
                cancelButtonText: 'Cancel'
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Reject confirmation.
        $('.reject-registration-form').on('submit', function (e) {
            e.preventDefault();

            let form = this;

            Swal.fire({
                title: 'Reject Registration?',
                text: 'Are you sure you want to reject this temple registration?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Reject',
                cancelButtonText: 'Cancel'
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

    });

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: @json(session('success')),
            timer: 2500,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: @json(session('error'))
        });
    @endif
</script>

@endpush
@else
    @php
        abort(403);
    @endphp
@endcan