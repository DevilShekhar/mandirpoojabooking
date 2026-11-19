@extends('admin.layouts.app')
@section('title', 'Create Darshan Verification Officer')
@section('content')
@can('darshan-verification-officers.create')
<section class="section">
    <div class="section-header">
        <h1>
            <i class="fas fa-user-plus mr-2"></i>
            Create Darshan Verification Officer
        </h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                <a href="{{ url('/admin/dashboard') }}">Dashboard</a>
            </div>
            <div class="breadcrumb-item">
                <a href="{{ route('admin.darshan-verification-officers.index') }}">
                    Darshan Verification Officers
                </a>
            </div>
            <div class="breadcrumb-item">
                Create Officer
            </div>
        </div>
    </div>
    <div class="section-body">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm officer-intro-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="officer-intro-icon mr-3">
                            <i class="fas fa-user-shield fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">
                                Officer Registration
                            </h4>
                            <p class="text-muted mb-0">
                                Create a Darshan Verification Officer account
                                and assign temple access.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
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
        <div class="card shadow-sm officer-form-card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h4 class="mb-1">
                        <i class="fas fa-id-card mr-2 text-primary"></i>
                        Officer Account Information
                    </h4>
                    <small class="text-muted">
                        Enter the officer's personal details and login credentials.
                    </small>
                </div>
                <div class="mt-2 mt-md-0">
                    <span class="badge badge-primary officer-role-badge">
                        <i class="fas fa-user-shield mr-1"></i>
                        Darshan Verification Officer
                    </span>
                </div>
            </div>
            <form action="{{ route('admin.darshan-verification-officers.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="officerForm">
                @csrf
                <div class="card-body">
                    <h6 class="form-section-title">
                        <i class="fas fa-place-of-worship mr-2"></i>
                        Temple Information
                    </h6>
                    @if(auth()->user()->hasAnyRole(['Admin', 'Super Admin']))
                        <div class="form-group">
                            <label for="temple_id">
                                Select Temple
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-place-of-worship"></i>
                                    </span>
                                </div>
                                <select name="temple_id" id="temple_id" class="form-control @error('temple_id') is-invalid @enderror" required>
                                    <option value="">
                                        -- Select Temple --
                                    </option>
                                    @foreach($temples as $temple)
                                        <option value="{{ $temple->id }}"
                                            {{ (string) old('temple_id') === (string) $temple->id ? 'selected' : '' }}>
                                            {{ $temple->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('temple_id')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                            <small class="form-text text-muted">
                                Select the temple where this officer will perform Darshan verification.
                            </small>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-1"></i>
                            This officer will automatically be assigned to your temple.
                        </div>
                    @endif
                    <hr>
                    <h6 class="form-section-title">
                        <i class="fas fa-user mr-2"></i>
                        Personal Information
                    </h6>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">
                                Full Name
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                                <input type="text" name="name" id="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}"
                                       placeholder="Enter officer full name"
                                       maxlength="255"
                                       autocomplete="name"
                                       required>
                            </div>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">
                                Email Address
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                                <input type="email" name="email" id="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}"
                                       placeholder="example@email.com"
                                       maxlength="255"
                                       autocomplete="email"
                                       required>
                            </div>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="phone">
                                Phone Number
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone-alt"></i>
                                    </span>
                                </div>
                                <input type="text" name="phone" id="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}"
                                       placeholder="Enter phone number"
                                       maxlength="20"
                                       autocomplete="tel">
                            </div>
                            @error('phone')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="birth_date">
                                Birth Date
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="date" name="birth_date" id="birth_date"
                                       max="{{ date('Y-m-d') }}" class="form-control @error('birth_date') is-invalid @enderror"
                                       value="{{ old('birth_date') }}">
                            </div>
                            @error('birth_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="gender">
                            <i class="fas fa-venus-mars mr-1 text-primary"></i>
                            Gender
                        </label>
                        <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                            <option value="">-- Select Gender --</option>
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>
                                Male
                            </option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>
                                Female
                            </option>
                            <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>
                                Other
                            </option>
                        </select>
                        @error('gender')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <hr>
                    <h6 class="form-section-title">
                        <i class="fas fa-user-shield mr-2"></i>
                        Role Information
                    </h6>
                    <div class="form-group">
                        <label for="role">
                            Assigned Role
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-shield-alt"></i>
                                </span>
                            </div>
                            <input type="text"
                                   id="role"
                                   class="form-control"
                                   value="Darshan Verification Officer"
                                   readonly>
                        </div>
                        <small class="form-text text-muted">
                            The Darshan Verification Officer role should be assigned automatically by the controller.
                        </small>
                    </div>
                    <hr>
                    <h6 class="form-section-title">
                        <i class="fas fa-camera mr-2"></i>
                        Profile Image
                    </h6>
                    <div class="form-group">
                        <div class="d-flex align-items-center mb-3">
                            <img id="profilePreview"
                                 src="{{ asset('images/default-user.png') }}"
                                 alt="Officer Profile Preview"
                                 class="officer-profile-preview mr-3"
                                 onerror="this.onerror=null;this.src='{{ asset('assets/img/users/user-1.png') }}';">
                            <div>
                                <strong>Upload Officer Profile Photo</strong>
                                <p class="text-muted mb-0">
                                    JPG, JPEG, PNG or WEBP.
                                    Maximum size: 2 MB.
                                </p>
                            </div>
                        </div>
                        <div class="custom-file">
                            <input type="file" name="profile" id="profile" class="custom-file-input @error('profile') is-invalid @enderror"
                                   accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                            <label class="custom-file-label" for="profile">
                                Choose profile image
                            </label>
                        </div>
                        @error('profile')
                            <small class="text-danger d-block mt-1">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <hr>
                    <h6 class="form-section-title">
                        <i class="fas fa-lock mr-2"></i>
                        Login Credentials
                    </h6>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="password">
                                Password
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </span>
                                </div>
                                <input type="password"
                                       name="password"
                                       id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Minimum 8 characters"
                                       minlength="8"
                                       autocomplete="new-password"
                                       required>
                                <div class="input-group-append">
                                    <button type="button"
                                            class="btn btn-outline-secondary toggle-password"
                                            data-target="password"
                                            aria-label="Show password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password_confirmation">
                                Confirm Password
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                                <input type="password"
                                       name="password_confirmation"
                                       id="password_confirmation"
                                       class="form-control"
                                       placeholder="Re-enter password"
                                       minlength="8"
                                       autocomplete="new-password"
                                       required>
                                <div class="input-group-append">
                                    <button type="button"
                                            class="btn btn-outline-secondary toggle-password"
                                            data-target="password_confirmation"
                                            aria-label="Show confirm password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h6 class="form-section-title">
                        <i class="fas fa-toggle-on mr-2"></i>
                        Account Status
                    </h6>
                    <div class="form-group mb-0">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox"
                                   name="status"
                                   id="status"
                                   value="1"
                                   class="custom-control-input"
                                   {{ old('status', '1') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="status">
                                Active Account
                            </label>
                        </div>
                        <small class="form-text text-muted">
                            Active officers can access their account according to their assigned permissions.
                        </small>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('admin.darshan-verification-officers.index') }}"
                       class="btn btn-light mr-2">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Cancel
                    </a>
                    <button type="reset"
                            class="btn btn-outline-secondary mr-2"
                            id="resetForm">
                        <i class="fas fa-undo mr-1"></i>
                        Reset
                    </button>
                    <button type="submit"
                            class="btn btn-primary"
                            id="submitBtn">
                        <i class="fas fa-save mr-1"></i>
                        Create Officer
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@else
<section class="section">
    <div class="section-body">
        <div class="alert alert-danger">
            <i class="fas fa-ban mr-2"></i>
            You do not have permission to create a Darshan Verification Officer.
        </div>
    </div>
</section>
@endcan
@endsection
@push('styles')
<style>
.officer-intro-card,
.officer-form-card {
    border: none;
    border-radius: 10px;
    overflow: hidden;
}
.officer-intro-card .card-body {
    padding: 22px 24px;
}
.officer-intro-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    flex-shrink: 0;
    background: #eef2ff;
    border-radius: 12px;
}

.officer-form-card .card-header {
    padding: 20px 24px;
    background: #fff;
    border-bottom: 1px solid #eef0f5;
}

.officer-form-card .card-body {
    padding: 24px;
}

.officer-form-card .card-footer {
    padding: 18px 24px;
    background: #fff;
    border-top: 1px solid #e9ecef;
}

.officer-role-badge {
    padding: 8px 12px;
    border-radius: 5px;
    font-size: 12px;
    font-weight: 500;
}

.form-section-title {
    margin-bottom: 20px;
    color: #6777ef;
    font-size: 14px;
    font-weight: 700;
}

.form-group label {
    color: #34395e;
    font-weight: 600;
}

.form-control,
.input-group-text,
.custom-file-label {
    border-radius: 5px;
}

.input-group-text {
    min-width: 42px;
    justify-content: center;
    background: #f8f9fa;
}

.custom-control-label {
    cursor: pointer;
    font-weight: 500;
}

.custom-file-label {
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}

.officer-profile-preview {
    width: 85px;
    height: 85px;
    object-fit: cover;
    border: 3px solid #e9ecef;
    border-radius: 50%;
    background: #f8f9fa;
}

.card-footer .btn {
    min-width: 110px;
}

@media(max-width: 767px) {

    .officer-intro-card .card-body,
    .officer-form-card .card-header,
    .officer-form-card .card-body {
        padding: 15px;
    }

    .officer-form-card .card-footer {
        padding: 15px;
    }

    .officer-form-card .card-footer .btn {
        margin: 4px 0;
    }

    .officer-role-badge {
        display: inline-block;
    }

}
</style>
@endpush
@push('scripts')
<script>
$(document).ready(function () {
    $('#profile').on('change', function () {
        const file = this.files && this.files[0];
        const defaultImage = "{{ asset('images/default-user.png') }}";
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
            $(this).next('.custom-file-label')
                .text('Choose profile image');
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            alert('Profile image must not exceed 2 MB.');
            this.value = '';
            $(this).next('.custom-file-label')
                .text('Choose profile image');
            return;
        }
        $(this).next('.custom-file-label').text(file.name);
        // Preview image
        const reader = new FileReader();
        reader.onload = function (event) {
            $('#profilePreview').attr('src', event.target.result);
        };
        reader.readAsDataURL(file);
    });
    $('.toggle-password').on('click', function () {
        const target = $('#' + $(this).data('target'));
        const icon = $(this).find('i');
        if (target.attr('type') === 'password') {
            target.attr('type', 'text');
            icon.removeClass('fa-eye')
               .addClass('fa-eye-slash');
            $(this).attr('aria-label', 'Hide password');
        } else {
            target.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
            $(this).attr('aria-label', 'Show password');
        }
    });
    $('#officerForm').on('submit', function (e) {
        const password = $('#password').val();
        const confirmation = $('#password_confirmation').val();
        if (password !== confirmation) {
            e.preventDefault();
            alert('Password and Confirm Password do not match.');
            $('#password_confirmation').focus();
            return false;
        }
    });
    $('#resetForm').on('click', function () {
        setTimeout(function () {
            $('#profile').next('.custom-file-label')
                .text('Choose profile image');
            $('#profilePreview').attr(
                'src',
                "{{ asset('images/default-user.png') }}"
            );
            $('.toggle-password i')
                .removeClass('fa-eye-slash')
                .addClass('fa-eye');
            $('.toggle-password').attr(
                'aria-label',
                'Show password'
            );
            $('#password, #password_confirmation')
                .attr('type', 'password');
            $('#submitBtn')
                .prop('disabled', false)
                .html('<i class="fas fa-save mr-1"></i> Create Officer');

        }, 0);
    });
    $('#officerForm').on('submit', function () {
        const form = this;
        const button = $('#submitBtn');
        if (form.checkValidity()) {
            if ($('#password').val() !== $('#password_confirmation').val()) {
                return;
            }
            button.prop('disabled', true);
            button.html(
                '<i class="fas fa-spinner fa-spin mr-1"></i> Creating...'
            );
        }
    });
});
</script>
@endpush