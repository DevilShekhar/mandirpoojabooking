@extends('admin.layouts.app')

@section('content')

<section class="section">
    <div class="section-body">

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>Edit User</h4>

                        <div class="card-header-action">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST"
                        enctype="multipart/form-data">

                        @csrf
                        @method('PUT')

                        <div class="card-body">

                            @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
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
                                        Name <span class="text-danger">*</span>
                                    </label>

                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $user->name) }}" placeholder="Enter full name" required>

                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="email">
                                        Email <span class="text-danger">*</span>
                                    </label>

                                    <input type="email" name="email" id="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email) }}" placeholder="Enter email address"
                                        required>

                                    @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label for="phone">
                                        Phone Number
                                    </label>

                                    <input type="tel" name="phone" id="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $user->phone) }}" placeholder="Enter phone number">

                                    @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="birth_date">
                                        Birth Date
                                    </label>

                                    <input type="date" name="birth_date" id="birth_date"
                                        class="form-control @error('birth_date') is-invalid @enderror"
                                        value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}">

                                    @error('birth_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-6">

                                    <label class="d-block">
                                        Gender
                                    </label>

                                    <div class="custom-control custom-radio custom-control-inline">

                                        <input type="radio" id="gender_male" name="gender" value="male"
                                            class="custom-control-input"
                                            {{ old('gender', $user->gender) == 'male' ? 'checked' : '' }}>

                                        <label class="custom-control-label" for="gender_male">
                                            Male
                                        </label>

                                    </div>

                                    <div class="custom-control custom-radio custom-control-inline">

                                        <input type="radio" id="gender_female" name="gender" value="female"
                                            class="custom-control-input"
                                            {{ old('gender', $user->gender) == 'female' ? 'checked' : '' }}>

                                        <label class="custom-control-label" for="gender_female">
                                            Female
                                        </label>

                                    </div>

                                    <div class="custom-control custom-radio custom-control-inline">

                                        <input type="radio" id="gender_other" name="gender" value="other"
                                            class="custom-control-input"
                                            {{ old('gender', $user->gender) == 'other' ? 'checked' : '' }}>

                                        <label class="custom-control-label" for="gender_other">
                                            Other
                                        </label>

                                    </div>

                                    @error('gender')
                                    <div class="text-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                </div>
                                <div class="form-group col-md-6">
                                    <label>
                                        Role <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">

                                        <select name="role_id" class="form-control">
                                            <option value="">Select Role</option>

                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}"
                                                    @selected(old('role_id', $user->role_id) == $role->id)>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @error('role_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">

                                    <label for="status">
                                        Status <span class="text-danger">*</span>
                                    </label>

                                    <select name="status" id="status"
                                        class="form-control @error('status') is-invalid @enderror" required>

                                        <option value="1" {{ old('status', $user->status) == '1' ? 'selected' : '' }}>
                                            Active
                                        </option>

                                        <option value="0" {{ old('status', $user->status) == '0' ? 'selected' : '' }}>
                                            Inactive
                                        </option>

                                    </select>

                                    @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                </div>

                            </div>

                            <div class="section-title">
                                Profile Image
                            </div>

                            <div class="form-group">

                                <label>
                                    Current Profile
                                </label>

                                <div class="mb-3">

                                    @if($user->profile)

                                    <img src="{{ asset('storage/' . $user->profile) }}" alt="{{ $user->name }}"
                                        width="100" height="100" class="rounded-circle" style="object-fit: cover;">

                                    @else

                                    <img src="{{ asset('assets/img/users/user-1.png') }}" alt="{{ $user->name }}"
                                        width="100" height="100" class="rounded-circle">

                                    @endif

                                </div>

                                <label for="profile">
                                    Change Profile Image
                                </label>

                                <div class="custom-file">

                                    <input type="file" name="profile" id="profile"
                                        class="custom-file-input @error('profile') is-invalid @enderror"
                                        accept="image/jpeg,image/png,image/webp">

                                    <label class="custom-file-label" for="profile">
                                        Choose new profile image
                                    </label>

                                </div>

                                @error('profile')
                                <div class="text-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror

                                <small class="form-text text-muted">
                                    JPG, JPEG, PNG or WEBP. Maximum size: 2MB.
                                </small>

                            </div>


                        </div>

                        <div class="card-footer text-right">

                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mr-1">
                                Cancel
                            </a>

                            <button type="reset" class="btn btn-light mr-1">
                                Reset
                            </button>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Update User
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>
</section>

@endsection
