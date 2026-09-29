@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">My Profile</h4>
            <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">
                <i data-feather="edit"></i> Edit Profile
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card border h-100">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                @if($user->profile)
                                    <img src="{{ asset('storage/' . $user->profile) }}" alt="Profile" width="140" height="140" class="rounded-circle border" style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto" style="width:140px;height:140px;font-size:50px;">
                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <h4 class="mb-1">{{ $user->name }}</h4>
                            <p class="text-muted mb-3">{{ $user->role->name ?? 'N/A' }}</p>
                            @if($user->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                            <hr>
                            <div class="text-start">
                                <div class="d-flex align-items-center mb-3">
                                    <i data-feather="mail" class="me-3 text-primary"></i>
                                    <div>
                                        <small class="text-muted d-block">Email</small>
                                        <span>{{ $user->email }}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i data-feather="phone" class="me-3 text-primary"></i>
                                    <div>
                                        <small class="text-muted d-block">Phone</small>
                                        <span>{{ $user->phone ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 mb-4">
                    <div class="card border h-100">
                        <div class="card-header">
                            <h5 class="mb-0">Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3 text-primary">
                                            <i data-feather="user"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Full Name</small>
                                            <strong>{{ $user->name }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3 text-primary">
                                            <i data-feather="mail"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Email Address</small>
                                            <strong>{{ $user->email }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3 text-primary">
                                            <i data-feather="phone"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Phone Number</small>
                                            <strong>{{ $user->phone ?? 'N/A' }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3 text-primary">
                                            <i data-feather="users"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Gender</small>
                                            <strong>{{ $user->gender ?? 'N/A' }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3 text-primary">
                                            <i data-feather="calendar"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Birth Date</small>
                                            <strong>{{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('d-m-Y') : 'N/A' }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3 text-primary">
                                            <i data-feather="shield"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Role</small>
                                            <strong>{{ $user->role->name ?? 'N/A' }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4 mb-md-0">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3 text-primary">
                                            <i data-feather="check-circle"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Account Status</small>
                                            <strong>{{ $user->status ? 'Active' : 'Inactive' }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3 text-primary">
                                            <i data-feather="clock"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Member Since</small>
                                            <strong>{{ $user->created_at?->format('d-m-Y h:i A') }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    @if(session('status') === 'profile-updated')
        Swal.fire({
            icon: 'success',
            title: 'Profile Updated!',
            text: 'Your profile has been updated successfully.',
            confirmButtonText: 'OK'
        });
    @endif
</script>
@endpush