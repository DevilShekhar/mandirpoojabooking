@can('pandits.create')
@extends('admin.layouts.app')

@section('title', 'Create Pandit')

@section('content')

<section class="section">
    <div class="section-body">

        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>Create Pandit</h4>

                        <div class="card-header-action">
                            <a href="{{ route('pandits.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <form method="POST"
                          action="{{ route('pandits.store') }}"
                          enctype="multipart/form-data">

                        @csrf

                        <div class="card-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="section-title mt-0">
                                Personal Information
                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label for="name">
                                        Full Name <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           id="name"
                                           name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}"
                                           placeholder="Enter full name"
                                           maxlength="255"
                                           autocomplete="name"
                                           required>

                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="email">
                                        Email Address <span class="text-danger">*</span>
                                    </label>

                                    <input type="email"
                                           id="email"
                                           name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}"
                                           placeholder="Enter email address"
                                           maxlength="255"
                                           autocomplete="email"
                                           required>

                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="phone">
                                        Phone Number <span class="text-danger">*</span>
                                    </label>

                                    <input type="tel"
                                           id="phone"
                                           name="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone') }}"
                                           placeholder="Enter phone number"
                                           maxlength="20"
                                           autocomplete="tel"
                                           required>

                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="gender">Gender</label>

                                    <select id="gender"
                                            name="gender"
                                            class="form-control @error('gender') is-invalid @enderror">

                                        <option value="">Select Gender</option>

                                        <option value="male"
                                            {{ old('gender') === 'male' ? 'selected' : '' }}>
                                            Male
                                        </option>

                                        <option value="female"
                                            {{ old('gender') === 'female' ? 'selected' : '' }}>
                                            Female
                                        </option>

                                        <option value="other"
                                            {{ old('gender') === 'other' ? 'selected' : '' }}>
                                            Other
                                        </option>

                                    </select>

                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="birth_date">Date of Birth</label>

                                    <input type="date"
                                           id="birth_date"
                                           name="birth_date"
                                           class="form-control @error('birth_date') is-invalid @enderror"
                                           value="{{ old('birth_date') }}"
                                           max="{{ date('Y-m-d') }}">

                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="profile">Profile Image</label>

                                    <input type="file"
                                           id="profile"
                                           name="profile"
                                           class="form-control @error('profile') is-invalid @enderror"
                                           accept=".jpg,.jpeg,.png,.webp"
                                           aria-describedby="profile-help">

                                    <small id="profile-help" class="form-text text-muted">
                                        JPG, JPEG, PNG or WEBP. Maximum size: 2 MB.
                                    </small>

                                    @error('profile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <div id="profile-preview-container" class="mt-3" style="display: none;">
                                        <img id="profile-preview"
                                             src=""
                                             alt="Profile preview"
                                             width="100"
                                             height="100"
                                             style="object-fit: cover; border-radius: 8px;">

                                        <div class="mt-2">
                                            <button type="button"
                                                    id="remove-profile"
                                                    class="btn btn-danger btn-sm">
                                                <i class="fas fa-times"></i> Remove Image
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="section-title">
                                Account Information
                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label for="password">
                                        Password <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <input type="password"
                                               id="password"
                                               name="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               placeholder="Enter password"
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
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror

                                    <small class="form-text text-muted">
                                        Minimum 8 characters.
                                    </small>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="password_confirmation">
                                        Confirm Password <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <input type="password"
                                               id="password_confirmation"
                                               name="password_confirmation"
                                               class="form-control"
                                               placeholder="Confirm password"
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

                                <div class="form-group col-md-6">
                                    <label for="status">
                                        Account Status <span class="text-danger">*</span>
                                    </label>

                                    <select id="status"
                                            name="status"
                                            class="form-control @error('status') is-invalid @enderror"
                                            required>

                                        <option value="1"
                                            {{ old('status', '1') == '1' ? 'selected' : '' }}>
                                            Active
                                        </option>

                                        <option value="0"
                                            {{ old('status') === '0' ? 'selected' : '' }}>
                                            Inactive
                                        </option>

                                    </select>

                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="role">Assigned Role</label>

                                    <input type="text"
                                           id="role"
                                           class="form-control"
                                           value="Pandit"
                                           readonly>

                                    <small class="form-text text-muted">
                                        The Pandit role is assigned automatically when the account is created.
                                    </small>
                                </div>

                            </div>

                        </div>

                        <div class="card-footer text-right">

                            <a href="{{ route('pandits.index') }}"
                               class="btn btn-secondary mr-2">
                                <i class="fas fa-times"></i> Cancel
                            </a>

                            <button type="reset"
                                    id="reset-form"
                                    class="btn btn-light mr-2">
                                <i class="fas fa-undo"></i> Reset
                            </button>

                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Pandit
                            </button>

                        </div>

                    </form>

                </div>

            </div>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {

        $('.toggle-password').on('click', function () {
            const target = $('#' + $(this).data('target'));
            const icon = $(this).find('i');

            if (target.attr('type') === 'password') {
                target.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                target.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        $('#profile').on('change', function () {
            const file = this.files[0];

            if (!file) {
                clearProfilePreview();
                return;
            }

            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            if (!allowedTypes.includes(file.type)) {
                this.value = '';
                clearProfilePreview();

                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Image',
                    text: 'Please select a JPG, JPEG, PNG or WEBP image.'
                });

                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                this.value = '';
                clearProfilePreview();

                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'Profile image must not exceed 2 MB.'
                });

                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {
                $('#profile-preview').attr('src', event.target.result);
                $('#profile-preview-container').show();
            };

            reader.readAsDataURL(file);
        });

        $('#remove-profile').on('click', function () {
            $('#profile').val('');
            clearProfilePreview();
        });

        $('#reset-form').on('click', function () {
            setTimeout(function () {
                clearProfilePreview();
            }, 0);
        });

        $('form').on('submit', function (event) {
            const password = $('#password').val();
            const confirmation = $('#password_confirmation').val();

            if (password !== confirmation) {
                event.preventDefault();

                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'Password and confirm password must match.'
                });

                $('#password_confirmation').focus();
            }
        });

        function clearProfilePreview() {
            $('#profile-preview').attr('src', '');
            $('#profile-preview-container').hide();
        }

    });
</script>
@endpush

@else
    @php
        abort(403);
    @endphp
@endcan