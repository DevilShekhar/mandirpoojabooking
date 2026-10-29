
@extends('admin.layouts.app')

@section('content')
    @can('store-managers.create')
        <section class="section">
            <div class="section-header">
                <h1>
                    <i class="fas fa-user-plus"></i>
                    Create Store Manager
                </h1>
            </div>

            <div class="section-body">

                {{-- Page Heading --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="mr-3 d-flex align-items-center justify-content-center"
                                     style="width:60px;height:60px;background:#eef2ff;border-radius:12px;">
                                    <i class="fas fa-user-shield fa-2x text-primary"></i>
                                </div>

                                <div>
                                    <h4 class="mb-1">Store Manager Registration</h4>
                                    <p class="text-muted mb-0">
                                        Create a Store Manager account and assign temple access.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Alerts --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}

                        <button type="button" class="close"
                                data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}

                        <button type="button" class="close"
                                data-dismiss="alert" aria-label="Close">
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

                <div class="row">
                    <div class="col-12">

                        <div class="card shadow-sm">
                            <div class="card-header">
                                <h4>
                                    <i class="fas fa-id-card mr-2 text-primary"></i>
                                    Account Information
                                </h4>

                                <div class="card-header-action">
                                    <span class="badge badge-primary">
                                        <i class="fas fa-user-shield mr-1"></i>
                                        Store Manager
                                    </span>
                                </div>
                            </div>

                            <form action="{{ route('admin.store-managers.store') }}"
                                  method="POST"
                                  enctype="multipart/form-data">

                                @csrf

                                <div class="card-body">

                                    {{-- Temple Selection --}}
                                    @if(auth()->user()->hasAnyRole(['Admin', 'Super Admin']))
                                        <div class="form-group">
                                            <label for="temple_id">
                                                <i class="fas fa-place-of-worship mr-1 text-primary"></i>
                                                Select Temple
                                                <span class="text-danger">*</span>
                                            </label>

                                            <select name="temple_id"
                                                    id="temple_id"
                                                    class="form-control @error('temple_id') is-invalid @enderror"
                                                    required>

                                                <option value="">
                                                    -- Select Temple --
                                                </option>

                                                @foreach($temples as $temple)
                                                    <option value="{{ $temple->id }}"
                                                        {{ (string) old('temple_id') === (string) $temple->id
                                                            ? 'selected'
                                                            : '' }}>
                                                        {{ $temple->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @error('temple_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                            <small class="form-text text-muted">
                                                Select the temple where this Store Manager will work.
                                            </small>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            This Store Manager will be assigned to your temple automatically.
                                        </div>
                                    @endif

                                    <hr>

                                    {{-- Personal Information --}}
                                    <h6 class="text-primary mb-3">
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

                                                <input type="text"
                                                       id="name"
                                                       name="name"
                                                       class="form-control @error('name') is-invalid @enderror"
                                                       placeholder="Enter full name"
                                                       value="{{ old('name') }}"
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

                                                <input type="email"
                                                       id="email"
                                                       name="email"
                                                       class="form-control @error('email') is-invalid @enderror"
                                                       placeholder="example@email.com"
                                                       value="{{ old('email') }}"
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
                                            <label for="phone">Phone Number</label>

                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-phone-alt"></i>
                                                    </span>
                                                </div>

                                                <input type="text"
                                                       id="phone"
                                                       name="phone"
                                                       class="form-control @error('phone') is-invalid @enderror"
                                                       placeholder="Enter phone number"
                                                       value="{{ old('phone') }}"
                                                       maxlength="20"
                                                       autocomplete="tel">
                                            </div>

                                            @error('phone')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="birth_date">Birth Date</label>

                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </span>
                                                </div>

                                                <input type="date"
                                                       id="birth_date"
                                                       name="birth_date"
                                                       class="form-control @error('birth_date') is-invalid @enderror"
                                                       value="{{ old('birth_date') }}"
                                                       max="{{ date('Y-m-d') }}">
                                            </div>

                                            @error('birth_date')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                    </div>

                                    {{-- Gender --}}
                                    <div class="form-group">
                                        <label>
                                            <i class="fas fa-venus-mars mr-1 text-primary"></i>
                                            Gender
                                        </label>

                                        <div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio"
                                                       id="gender_male"
                                                       name="gender"
                                                       value="male"
                                                       class="custom-control-input"
                                                       {{ old('gender') === 'male' ? 'checked' : '' }}>

                                                <label class="custom-control-label"
                                                       for="gender_male">
                                                    Male
                                                </label>
                                            </div>

                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio"
                                                       id="gender_female"
                                                       name="gender"
                                                       value="female"
                                                       class="custom-control-input"
                                                       {{ old('gender') === 'female' ? 'checked' : '' }}>

                                                <label class="custom-control-label"
                                                       for="gender_female">
                                                    Female
                                                </label>
                                            </div>

                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio"
                                                       id="gender_other"
                                                       name="gender"
                                                       value="other"
                                                       class="custom-control-input"
                                                       {{ old('gender') === 'other' ? 'checked' : '' }}>

                                                <label class="custom-control-label"
                                                       for="gender_other">
                                                    Other
                                                </label>
                                            </div>
                                        </div>

                                        @error('gender')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <hr>

                                    {{-- Role Information --}}
                                    <h6 class="text-primary mb-3">
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
                                                   value="Store Manager"
                                                   readonly>
                                        </div>

                                        <small class="form-text text-muted">
                                            The Store Manager role will be assigned automatically.
                                        </small>
                                    </div>

                                    {{-- Profile Image --}}
                                    <hr>

                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-camera mr-2"></i>
                                        Profile Image
                                    </h6>

                                    <div class="form-group">
                                        <div class="d-flex align-items-center mb-3">
                                            <img id="profilePreview"
                                                 src="{{ asset('assets/img/users/user-1.png') }}"
                                                 alt="Profile preview"
                                                 class="rounded-circle mr-3"
                                                 style="width:80px;height:80px;object-fit:cover;border:3px solid #e9ecef;">

                                            <div>
                                                <strong>Upload Profile Photo</strong>
                                                <p class="text-muted mb-0">
                                                    JPG, JPEG, PNG or WEBP. Maximum 2 MB.
                                                </p>
                                            </div>
                                        </div>

                                        <div class="custom-file">
                                            <input type="file"
                                                   id="profile"
                                                   name="profile"
                                                   class="custom-file-input @error('profile') is-invalid @enderror"
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

                                    {{-- Login Credentials --}}
                                    <h6 class="text-primary mb-3">
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
                                                       id="password"
                                                       name="password"
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
                                                       id="password_confirmation"
                                                       name="password_confirmation"
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

                                    {{-- Account Status --}}
                                    <hr>

                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-toggle-on mr-2"></i>
                                        Account Status
                                    </h6>

                                    <div class="form-group mb-0">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox"
                                                   id="status"
                                                   name="status"
                                                   value="1"
                                                   class="custom-control-input"
                                                   {{ old('status', '1') ? 'checked' : '' }}>

                                            <label class="custom-control-label"
                                                   for="status">
                                                Active Account
                                            </label>
                                        </div>

                                        <small class="form-text text-muted">
                                            Active accounts can sign in according to their assigned permissions.
                                        </small>
                                    </div>

                                </div>

                                {{-- Form Actions --}}
                                <div class="card-footer text-right">
                                    <a href="{{ route('admin.store-managers.index') }}"
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
                                        Create Store Manager
                                    </button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </section>
    @else
        <section class="section">
            <div class="section-body">
                <div class="alert alert-danger">
                    <i class="fas fa-ban mr-2"></i>
                    You do not have permission to create a Store Manager.
                </div>
            </div>
        </section>
    @endcan
@endsection

@push('styles')
<style>
    .card {
        border-radius: 10px;
    }

    .card-header h4 {
        font-weight: 600;
    }

    .form-group label {
        font-weight: 600;
        color: #34395e;
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

    .custom-file-label {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .custom-control-label {
        cursor: pointer;
    }

    .card-footer {
        background: #fff;
        border-top: 1px solid #e9ecef;
    }

    #profilePreview {
        background: #f8f9fa;
    }

    @media (max-width: 576px) {
        .card-footer .btn {
            margin: 4px 0;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {
        // Display selected profile image filename and preview.
        $('#profile').on('change', function () {
            const file = this.files && this.files[0];

            $(this).next('.custom-file-label').text(
                file ? file.name : 'Choose profile image'
            );

            if (file) {
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

                const reader = new FileReader();

                reader.onload = function (event) {
                    $('#profilePreview').attr('src', event.target.result);
                };

                reader.readAsDataURL(file);
            }
        });

        // Toggle password visibility.
        $('.toggle-password').on('click', function () {
            const target = $('#' + $(this).data('target'));
            const icon = $(this).find('i');

            if (target.attr('type') === 'password') {
                target.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
                $(this).attr('aria-label', 'Hide password');
            } else {
                target.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
                $(this).attr('aria-label', 'Show password');
            }
        });

        // Reset the custom filename and image preview.
        $('#resetForm').on('click', function () {
            setTimeout(function () {
                $('#profile').next('.custom-file-label')
                    .text('Choose profile image');

                $('#profilePreview').attr(
                    'src',
                    "{{ asset('assets/img/users/user-1.png') }}"
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
            }, 0);
        });

        // Prevent accidental double submission.
        $('form[action="{{ route('admin.store-managers.store') }}"]')
            .on('submit', function () {
                const button = $('#submitBtn');

                if (this.checkValidity()) {
                    button.prop('disabled', true);
                    button.html(
                        '<i class="fas fa-spinner fa-spin mr-1"></i> Creating...'
                    );
                }
            });
    });
</script>
@endpush