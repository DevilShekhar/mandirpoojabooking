@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Profile</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <label class="form-label">Profile Photo</label>
                        <div class="mb-3">
                            @if($user->profile)
                                <img src="{{ asset('storage/' . $user->profile) }}" alt="Profile" width="120" height="120" class="rounded-circle border" style="object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width:120px;height:120px;font-size:40px;">
                                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <input type="file" name="profile" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                        <small class="text-muted">Maximum file size: 2MB</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" maxlength="15" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-control">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender', $user->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Birth Date</label>
                        <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $user->birth_date) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <input type="text" class="form-control" value="{{ $user->status ? 'Active' : 'Inactive' }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control" value="{{ $user->role->name ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('admin.profile.index') }}" class="btn btn-secondary">
                        <i data-feather="arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="save"></i> Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection