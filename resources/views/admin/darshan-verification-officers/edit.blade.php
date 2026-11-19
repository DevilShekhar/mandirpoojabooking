@can('darshan-verification-officers.edit')
@extends('admin.layouts.app')
@section('title', 'Edit Darshan Verification Officer')
@section('content')
@php
    $isAdmin = auth()->user()->hasAnyRole(['Admin', 'Super Admin']);
    $profileImage = !empty($officer->profile)
        ? asset('storage/' . $officer->profile)
        : asset('images/default-user.png');
@endphp
<div class="section">
    <div class="section-header">
        <h1>
            <i class="fas fa-user-edit"></i>
            Edit Darshan Verification Officer
        </h1>      
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>
                            <i class="fas fa-user-circle"></i>
                            Officer Information
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center mb-4">
                            <div class="col-md-2 text-center">
                                <img src="{{ $profileImage }}"
                                    id="profilePreview"
                                    class="rounded-circle shadow"
                                    style="width:110px;height:110px;object-fit:cover;"
                                    onerror="this.src='{{ asset('images/default-user.png') }}'"
                                    alt="Officer Profile">
                            </div>
                            <div class="col-md-10">
                                <h5 class="mb-1">{{ $officer->name }}</h5>
                                <p class="text-muted mb-1">
                                    {{ $officer->email }}
                                </p>
                                <span class="badge badge-primary">
                                    Darshan Verification Officer
                                </span>
                                <span class="badge {{ $officer->status ? 'badge-success' : 'badge-danger' }}">
                                    {{ $officer->status ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                        <form action="{{ route('admin.darshan-verification-officers.update', $officer->id) }}"
                            method="POST"
                            enctype="multipart/form-data"
                            id="officerForm">
                            @csrf
                            @method('PUT')
                            <div class="form-section mb-4">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-id-card"></i>
                                    Personal Information
                                </h6>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>
                                            Full Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"  name="name" class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $officer->name) }}"
                                            placeholder="Enter full name"
                                            required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>
                                            Email Address <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $officer->email) }}"
                                            placeholder="Enter email address"
                                            required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Mobile Number</label>
                                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone', $officer->phone) }}" placeholder="Enter mobile number">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Gender</label>
                                        <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                            <option value="">Select Gender</option>
                                            <option value="male"
                                                {{ old('gender', $officer->gender) == 'male' ? 'selected' : '' }}>
                                                Male
                                            </option>
                                            <option value="female"
                                                {{ old('gender', $officer->gender) == 'female' ? 'selected' : '' }}>
                                                Female
                                            </option>
                                            <option value="other"
                                                {{ old('gender', $officer->gender) == 'other' ? 'selected' : '' }}>
                                                Other
                                            </option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Date of Birth</label>
                                        <input type="date"  name="birth_date" class="form-control @error('birth_date') is-invalid @enderror"
                                            value="{{ old('birth_date', $officer->birth_date ? \Carbon\Carbon::parse($officer->birth_date)->format('Y-m-d') : '') }}">
                                        @error('birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Profile Image</label>
                                        <input type="file"
                                            name="profile" id="profile"
                                            class="form-control @error('profile') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp">
                                        <small class="text-muted">
                                            JPG, JPEG, PNG or WEBP. Maximum 2MB.
                                        </small>
                                        @error('profile')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-section mb-4">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-place-of-worship"></i>
                                    Temple Information
                                </h6>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        @if($isAdmin)
                                            <label>
                                                Select Temple <span class="text-danger">*</span>
                                            </label>
                                            <select  name="temple_id" class="form-control @error('temple_id') is-invalid @enderror" required>
                                                <option value="">Select Temple</option>
                                                @foreach($temples as $temple)
                                                    <option value="{{ $temple->id }}" {{ (string) old('temple_id', $officer->temple_id) === (string) $temple->id ? 'selected' : '' }}>
                                                        {{ $temple->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('temple_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @else
                                            <label>Assigned Temple</label>
                                            <input type="text" class="form-control" value="{{ $officer->temple->name ?? 'Assigned Temple' }}" readonly>
                                            <small class="text-muted">
                                                Your assigned temple is managed by the administrator.
                                            </small>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Assigned Role</label>
                                        <input type="text" class="form-control" value="Darshan Verification Officer" readonly>
                                        <small class="text-muted">
                                            Role is managed automatically by the system.
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="form-section mb-4">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-user-shield"></i>
                                    Account Settings
                                </h6>
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input
                                            type="checkbox" class="custom-control-input"
                                            id="status" name="status"
                                            value="1" {{ old('status', $officer->status) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="status">
                                            Active Account
                                        </label>
                                    </div>
                                    <small class="text-muted">
                                        Enable or disable this officer's account.
                                    </small>
                                </div>
                            </div>
                            <div class="border-top pt-4 d-flex justify-content-between">
                                <a href="{{ route('admin.darshan-verification-officers.index') }}" class="btn btn-light">
                                    <i class="fas fa-arrow-left"></i>
                                    Back
                                </a>
                                <div>
                                    <button type="reset" class="btn btn-secondary mr-2">
                                        <i class="fas fa-undo"></i>
                                        Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-save"></i>
                                        Update Officer
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
    .form-section {
        padding: 5px 0;
    }
    .form-section h6 {
        font-weight: 600;
        font-size: 15px;
    }
    .form-group label {
        font-weight: 600;
        color: #34395e;
    }
    .form-control {
        border-radius: 5px;
    }
    .custom-control-label {
        cursor: pointer;
    }
    #profilePreview {
        border: 3px solid #fff;
    }
    .card-header h4 i {
        margin-right: 6px;
    }
</style>
@endpush
@push('scripts')
<script>
    $(document).ready(function () {
        // Profile image preview
        $('#profile').on('change', function () {
            const file = this.files[0];
            if (!file) {
                return;
            }
            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];
            if (!allowedTypes.includes(file.type)) {
                alert('Please select a JPG, JPEG, PNG or WEBP image.');
                this.value = '';
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                alert('Profile image must not exceed 2MB.');
                this.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#profilePreview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        });
        // Show / hide password
        $('.toggle-password').on('click', function () {
            const target = $(this).data('target');
            const input = $('#' + target);
            const icon = $(this).find('i');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
        // Password confirmation
        $('#officerForm').on('submit', function (e) {
            const password = $('#password').val();
            const confirmation = $('#password_confirmation').val();
            $('#passwordMessage').text('');
            if (password !== '' && password !== confirmation) {
                e.preventDefault();
                $('#passwordMessage').text('Password and confirmation password do not match.');
                $('#password_confirmation').addClass('is-invalid');
                return false;
            }
            $('#password_confirmation').removeClass('is-invalid');
            $('#submitBtn')
                .prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        });
        $('#password_confirmation').on('input', function () {
            $('#passwordMessage').text('');
            $(this).removeClass('is-invalid');
        });
    });
</script>
@endpush
@endcan