@extends('admin.layouts.app')

@section('title', 'Edit Pandit')

@section('content')
@can('pandits.edit')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Pandit</h4>
                        <div class="card-header-action">
                            <a href="{{ route('pandits.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <form id="edit-pandit-form"
                          method="POST"
                          action="{{ route('pandits.update', $pandit->id) }}"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

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

                            <div class="section-title mt-0">Personal Information</div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="name">Full Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                           id="name"
                                           name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $pandit->name) }}"
                                           maxlength="255"
                                           autocomplete="name"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="email">Email Address <span class="text-danger">*</span></label>
                                    <input type="email"
                                           id="email"
                                           name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $pandit->email) }}"
                                           maxlength="255"
                                           autocomplete="email"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="phone">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel"
                                           id="phone"
                                           name="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $pandit->phone) }}"
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
                                        <option value="male" {{ old('gender', $pandit->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $pandit->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $pandit->gender) === 'other' ? 'selected' : '' }}>Other</option>
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
                                           value="{{ old('birth_date', $pandit->birth_date ? $pandit->birth_date->format('Y-m-d') : '') }}"
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
                                           class="form-control-file @error('profile') is-invalid @enderror"
                                           accept=".jpg,.jpeg,.png,.webp">
                                    <small class="form-text text-muted">
                                        JPG, JPEG, PNG or WEBP. Maximum size: 2 MB.
                                    </small>
                                    @error('profile')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror

                                    <div id="profile-preview-container"
                                         class="mt-3"
                                         style="{{ $pandit->profile ? '' : 'display:none;' }}">
                                        <img id="profile-preview"
                                             src="{{ $pandit->profile ? asset('storage/' . $pandit->profile) : '' }}"
                                             alt="Pandit Profile"
                                             width="100"
                                             height="100"
                                             style="object-fit:cover;border-radius:8px;">

                                        <div class="mt-2">
                                            <button type="button"
                                                    id="remove-profile"
                                                    class="btn btn-danger btn-sm">
                                                <i class="fas fa-times"></i> Remove Image
                                            </button>
                                        </div>
                                    </div>

                                    <input type="hidden"
                                           name="remove_profile"
                                           id="remove-profile-value"
                                           value="{{ old('remove_profile', '0') }}">
                                </div>
                            </div>

                            <div class="section-title">Account Information</div>

                            <div class="form-row">                               

                                <div class="form-group col-md-6">
                                    <label for="status">Account Status <span class="text-danger">*</span></label>
                                    <select id="status"
                                            name="status"
                                            class="form-control @error('status') is-invalid @enderror"
                                            required>
                                        <option value="1" {{ (string) old('status', (int) $pandit->status) === '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ (string) old('status', (int) $pandit->status) === '0' ? 'selected' : '' }}>Inactive</option>
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
                                        The Pandit role is managed automatically.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <a href="{{ route('pandits.index') }}" class="btn btn-secondary mr-2">
                                <i class="fas fa-times"></i> Cancel
                            </a>

                            <button type="reset" id="reset-form" class="btn btn-light mr-2">
                                <i class="fas fa-undo"></i> Reset
                            </button>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Pandit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@else
    @php abort(403); @endphp
@endcan
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    const originalProfile = @json($pandit->profile ? asset('storage/' . $pandit->profile) : '');
    const hasOriginalProfile = @json((bool) $pandit->profile);

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
            return;
        }

        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

        if (!allowedTypes.includes(file.type)) {
            this.value = '';
            if (typeof Swal !== 'undefined') {
                Swal.fire('Invalid Image', 'Please select a JPG, JPEG, PNG or WEBP image.', 'error');
            } else {
                alert('Please select a JPG, JPEG, PNG or WEBP image.');
            }
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            this.value = '';
            if (typeof Swal !== 'undefined') {
                Swal.fire('File Too Large', 'Profile image must not exceed 2 MB.', 'error');
            } else {
                alert('Profile image must not exceed 2 MB.');
            }
            return;
        }

        const reader = new FileReader();

        reader.onload = function (event) {
            $('#profile-preview').attr('src', event.target.result);
            $('#profile-preview-container').show();
            $('#remove-profile-value').val('0');
        };

        reader.readAsDataURL(file);
    });

    $('#remove-profile').on('click', function () {
        $('#profile').val('');
        $('#profile-preview').attr('src', '');
        $('#profile-preview-container').hide();
        $('#remove-profile-value').val('1');
    });

    $('#reset-form').on('click', function () {
        setTimeout(function () {
            $('#profile').val('');
            $('#remove-profile-value').val('0');
            $('#profile-preview').attr('src', originalProfile);
            $('#profile-preview-container').toggle(hasOriginalProfile);
        }, 0);
    });

    $('#edit-pandit-form').on('submit', function (event) {
        const password = $('#password').val();
        const confirmation = $('#password_confirmation').val();

        if (password !== '' && password !== confirmation) {
            event.preventDefault();

            if (typeof Swal !== 'undefined') {
                Swal.fire('Password Mismatch', 'New password and confirmation must match.', 'error');
            } else {
                alert('New password and confirmation must match.');
            }

            $('#password_confirmation').focus();
        }
    });
});
</script>
@endpush