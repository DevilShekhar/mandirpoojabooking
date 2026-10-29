
@extends('admin.layouts.app')

@section('content')
    @can('store-managers.edit')
        <section class="section">

            <div class="section-header">
                <h1>
                    <i class="fas fa-user-edit"></i>
                    Edit Store Manager
                </h1>
            </div>

            <div class="section-body">

                {{-- Page heading --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="manager-icon mr-3">
                                    <i class="fas fa-user-shield"></i>
                                </div>

                                <div>
                                    <h4 class="mb-1">Update Store Manager</h4>
                                    <p class="text-muted mb-0">
                                        Update account details, temple assignment,
                                        profile image and account status.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Success message --}}
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

                {{-- Error message --}}
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

                <div class="row">
                    <div class="col-12">

                        <div class="card shadow-sm">

                            <div class="card-header">
                                <h4>
                                    <i class="fas fa-id-card mr-2 text-primary"></i>
                                    Store Manager Information
                                </h4>

                                <div class="card-header-action">
                                    @if($storeManager->status)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Active
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-ban mr-1"></i>
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <form action="{{ route('admin.store-managers.update', $storeManager->id) }}"
                                  method="POST"
                                  enctype="multipart/form-data"
                                  id="editStoreManagerForm">

                                @csrf
                                @method('PUT')

                                <div class="card-body">

                                    {{-- Temple assignment --}}
                                    <h6 class="section-title">
                                        <i class="fas fa-place-of-worship mr-2"></i>
                                        Temple Assignment
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
                                                        <i class="fas fa-landmark"></i>
                                                    </span>
                                                </div>

                                                <select name="temple_id"
                                                        id="temple_id"
                                                        class="form-control @error('temple_id') is-invalid @enderror"
                                                        required>

                                                    <option value="">
                                                        -- Select Temple --
                                                    </option>

                                                    @foreach($temples as $temple)
                                                        <option value="{{ $temple->id }}"
                                                            {{ (string) old('temple_id', $storeManager->temple_id) === (string) $temple->id
                                                                ? 'selected'
                                                                : '' }}>
                                                            {{ $temple->name }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>

                                            @error('temple_id')
                                                <div class="text-danger mt-1">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                            <small class="form-text text-muted">
                                                Choose a different temple to reassign this Store Manager.
                                            </small>
                                        </div>

                                    @else

                                        <div class="form-group">
                                            <label for="temple_display">
                                                Assigned Temple
                                            </label>

                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-landmark"></i>
                                                    </span>
                                                </div>

                                                <input type="text"
                                                       id="temple_display"
                                                       class="form-control"
                                                       value="{{ $storeManager->temple->name ?? 'Temple not assigned' }}"
                                                       readonly>
                                            </div>

                                            <small class="form-text text-muted">
                                                Temple assignment can only be changed by Admin or Super Admin.
                                            </small>
                                        </div>

                                    @endif

                                    {{-- Role --}}
                                    <div class="form-group">
                                        <label for="role">Assigned Role</label>

                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-user-shield"></i>
                                                </span>
                                            </div>

                                            <input type="text"
                                                   id="role"
                                                   class="form-control"
                                                   value="Store Manager"
                                                   readonly>
                                        </div>
                                    </div>

                                    <hr>

                                    {{-- Personal information --}}
                                    <h6 class="section-title">
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
                                                       value="{{ old('name', $storeManager->name) }}"
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
                                                       value="{{ old('email', $storeManager->email) }}"
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
                                                       value="{{ old('phone', $storeManager->phone) }}"
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
                                                       value="{{ old(
                                                           'birth_date',
                                                           $storeManager->birth_date
                                                               ? $storeManager->birth_date->format('Y-m-d')
                                                               : ''
                                                       ) }}"
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
                                            @foreach([
                                                'male' => 'Male',
                                                'female' => 'Female',
                                                'other' => 'Other'
                                            ] as $genderValue => $genderLabel)

                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio"
                                                           id="gender_{{ $genderValue }}"
                                                           name="gender"
                                                           value="{{ $genderValue }}"
                                                           class="custom-control-input"
                                                           {{ old('gender', $storeManager->gender) === $genderValue
                                                                ? 'checked'
                                                                : '' }}>

                                                    <label class="custom-control-label"
                                                           for="gender_{{ $genderValue }}">
                                                        {{ $genderLabel }}
                                                    </label>
                                                </div>

                                            @endforeach
                                        </div>

                                        @error('gender')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <hr>

                                    {{-- Profile image --}}
                                    <h6 class="section-title">
                                        <i class="fas fa-camera mr-2"></i>
                                        Profile Image
                                    </h6>

                                    <div class="form-group">

                                        <div class="d-flex align-items-center mb-3">
                                            <img id="profilePreview"
                                                 src="{{ $storeManager->profile
                                                     ? asset('storage/' . $storeManager->profile)
                                                     : asset('assets/img/users/user-1.png') }}"
                                                 alt="Profile preview"
                                                 class="rounded-circle mr-3"
                                                 style="width:90px;height:90px;object-fit:cover;border:3px solid #e9ecef;">

                                            <div>
                                                <strong>{{ $storeManager->name }}</strong>

                                                <p class="text-muted mb-1">
                                                    Current profile image
                                                </p>

                                                <small class="text-muted">
                                                    JPG, JPEG, PNG or WEBP. Maximum 2 MB.
                                                </small>
                                            </div>
                                        </div>

                                        <div class="custom-file">
                                            <input type="file"
                                                   id="profile"
                                                   name="profile"
                                                   class="custom-file-input @error('profile') is-invalid @enderror"
                                                   accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

                                            <label class="custom-file-label" for="profile">
                                                Choose new profile image
                                            </label>
                                        </div>

                                        @error('profile')
                                            <small class="text-danger d-block mt-1">
                                                {{ $message }}
                                            </small>
                                        @enderror

                                        <small class="form-text text-muted">
                                            Leave empty to keep the existing profile image.
                                        </small>
                                    </div>

                                    <hr>

                                    {{-- Password information --}}
                                    <h6 class="section-title">
                                        <i class="fas fa-lock mr-2"></i>
                                        Login Credentials
                                    </h6>

                                    <div class="alert alert-light border">
                                        <i class="fas fa-info-circle text-primary mr-1"></i>
                                        The existing password will remain unchanged.
                                        Use a separate password reset feature to change it.
                                    </div>

                                    <hr>

                                    {{-- Account status --}}
                                    <h6 class="section-title">
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
                                                   {{ (string) old('status', (int) $storeManager->status) === '1'
                                                       ? 'checked'
                                                       : '' }}>

                                            <label class="custom-control-label"
                                                   for="status">
                                                Active Account
                                            </label>
                                        </div>

                                        <small class="form-text text-muted">
                                            Uncheck to deactivate this Store Manager.
                                        </small>

                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                </div>

                                {{-- Footer actions --}}
                                <div class="card-footer text-right">

                                    <a href="{{ route('admin.store-managers.index') }}"
                                       class="btn btn-light mr-2">
                                        <i class="fas fa-arrow-left mr-1"></i>
                                        Cancel
                                    </a>

                                    <button type="submit"
                                            class="btn btn-primary"
                                            id="updateBtn">
                                        <i class="fas fa-save mr-1"></i>
                                        Update Store Manager
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
                    You do not have permission to edit a Store Manager.
                </div>
            </div>
        </section>
    @endcan
@endsection

@push('styles')
<style>
    .manager-icon {
        width: 58px;
        height: 58px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eef2ff;
        color: #6777ef;
        border-radius: 12px;
        font-size: 25px;
        flex-shrink: 0;
    }

    .card {
        border-radius: 10px;
    }

    .card-header h4 {
        font-weight: 600;
    }

    .section-title {
        color: #6777ef;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 18px;
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

        // Preview the new profile image.
        $('#profile').on('change', function () {
            const file = this.files && this.files[0];

            $(this).next('.custom-file-label').text(
                file ? file.name : 'Choose new profile image'
            );

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
                    .text('Choose new profile image');

                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Profile image must not exceed 2 MB.');
                this.value = '';

                $(this).next('.custom-file-label')
                    .text('Choose new profile image');

                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {
                $('#profilePreview').attr('src', event.target.result);
            };

            reader.readAsDataURL(file);
        });

        // Prevent accidental double submission.
        $('#editStoreManagerForm').on('submit', function () {
            if (this.checkValidity()) {
                $('#updateBtn')
                    .prop('disabled', true)
                    .html(
                        '<i class="fas fa-spinner fa-spin mr-1"></i> Updating...'
                    );
            }
        });

    });
</script>
@endpush
