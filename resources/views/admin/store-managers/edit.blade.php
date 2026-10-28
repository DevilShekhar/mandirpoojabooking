
@extends('admin.layouts.app')

@section('content')
    @can('store-managers.edit')
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Edit Store Manager</h4>
                            </div>

                            <div class="card-body">
                                @if(session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
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

                                <form action="{{ route('admin.store-managers.update', $storeManager->id) }}"
                                      method="POST"
                                      enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="name">Full Name <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   id="name"
                                                   name="name"
                                                   class="form-control"
                                                   value="{{ old('name', $storeManager->name) }}"
                                                   required>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="email">Email Address <span class="text-danger">*</span></label>
                                            <input type="email"
                                                   id="email"
                                                   name="email"
                                                   class="form-control"
                                                   value="{{ old('email', $storeManager->email) }}"
                                                   required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="phone">Phone</label>
                                            <input type="text"
                                                   id="phone"
                                                   name="phone"
                                                   class="form-control"
                                                   value="{{ old('phone', $storeManager->phone) }}">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="birth_date">Birth Date</label>
                                            <input type="date"
                                                   id="birth_date"
                                                   name="birth_date"
                                                   class="form-control"
                                                   value="{{ old('birth_date', $storeManager->birth_date ? $storeManager->birth_date->format('Y-m-d') : '') }}"
                                                   max="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Gender</label>
                                        <div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio"
                                                       id="gender_male"
                                                       name="gender"
                                                       value="male"
                                                       class="custom-control-input"
                                                       {{ old('gender', $storeManager->gender) === 'male' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="gender_male">Male</label>
                                            </div>

                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio"
                                                       id="gender_female"
                                                       name="gender"
                                                       value="female"
                                                       class="custom-control-input"
                                                       {{ old('gender', $storeManager->gender) === 'female' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="gender_female">Female</label>
                                            </div>

                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio"
                                                       id="gender_other"
                                                       name="gender"
                                                       value="other"
                                                       class="custom-control-input"
                                                       {{ old('gender', $storeManager->gender) === 'other' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="gender_other">Other</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Role</label>
                                        <input type="text" class="form-control" value="Store Manager" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>Current Profile</label>
                                        <div class="mb-3">
                                            @if($storeManager->profile)
                                                <img src="{{ asset('storage/' . $storeManager->profile) }}"
                                                     alt="Profile"
                                                     width="90"
                                                     height="90"
                                                     class="rounded-circle"
                                                     style="object-fit: cover;">
                                            @else
                                                <img src="{{ asset('assets/img/users/user-1.png') }}"
                                                     alt="Default profile"
                                                     width="90"
                                                     height="90"
                                                     class="rounded-circle">
                                            @endif
                                        </div>

                                        <label for="profile">Change Profile Image</label>
                                        <div class="custom-file">
                                            <input type="file"
                                                   id="profile"
                                                   name="profile"
                                                   class="custom-file-input"
                                                   accept=".jpg,.jpeg,.png,.webp">
                                            <label class="custom-file-label" for="profile">Choose image</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select id="status" name="status" class="form-control" required>
                                            <option value="1" {{ (string) old('status', (int) $storeManager->status) === '1' ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="0" {{ (string) old('status', (int) $storeManager->status) === '0' ? 'selected' : '' }}>
                                                Inactive
                                            </option>
                                        </select>
                                    </div>

                                    <div class="card-footer text-right">
                                        <a href="{{ route('admin.store-managers.index') }}"
                                           class="btn btn-secondary">
                                            Cancel
                                        </a>

                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Store Manager
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="section">
            <div class="section-body">
                <div class="alert alert-danger">
                    You do not have permission to edit a Store Manager.
                </div>
            </div>
        </section>
    @endcan
@endsection

@push('scripts')
    <script>
        $('.custom-file-input').on('change', function () {
            const fileName = this.files[0] ? this.files[0].name : 'Choose image';
            $(this).next('.custom-file-label').text(fileName);
        });
    </script>
@endpush
