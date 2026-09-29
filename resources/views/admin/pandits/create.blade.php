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
                                Pandit Information
                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label>
                                        Name <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}"
                                           placeholder="Enter Pandit Name"
                                           required>

                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>
                                        Mobile <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           name="mobile"
                                           class="form-control @error('mobile') is-invalid @enderror"
                                           value="{{ old('mobile') }}"
                                           placeholder="Enter Mobile Number"
                                           required>

                                    @error('mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Email</label>

                                    <input type="email"
                                           name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}"
                                           placeholder="Enter Email">

                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Temple</label>

                                    <select name="temple_id"
                                            class="form-control @error('temple_id') is-invalid @enderror">

                                        <option value="">Select Temple</option>

                                        @foreach($temples as $temple)
                                            <option value="{{ $temple->id }}"
                                                {{ old('temple_id') == $temple->id ? 'selected' : '' }}>
                                                {{ $temple->name }}
                                            </option>
                                        @endforeach

                                    </select>

                                    @error('temple_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Experience (Years) <span class="text-danger">*</span></label>

                                    <input type="number"
                                           name="experience"
                                           min="0"
                                           class="form-control @error('experience') is-invalid @enderror"
                                           value="{{ old('experience', 0) }}"
                                           required>

                                    @error('experience')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Profile Image</label>

                                    <input type="file"
                                           name="profile_image"
                                           class="form-control @error('profile_image') is-invalid @enderror"
                                           accept="image/*">

                                    @error('profile_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Languages</label>

                                    <textarea name="languages"
                                              class="form-control"
                                              rows="3"
                                              placeholder="e.g. Marathi, Hindi, Sanskrit">{{ old('languages') }}</textarea>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Specialization</label>

                                    <textarea name="specialization"
                                              class="form-control"
                                              rows="3"
                                              placeholder="e.g. Ganesh Puja, Rudrabhishek, Havan">{{ old('specialization') }}</textarea>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Verification Status <span class="text-danger">*</span></label>

                                    <select name="verification_status"
                                            class="form-control"
                                            required>

                                        <option value="pending"
                                            {{ old('verification_status', 'pending') == 'pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>

                                        <option value="verified"
                                            {{ old('verification_status') == 'verified' ? 'selected' : '' }}>
                                            Verified
                                        </option>

                                        <option value="rejected"
                                            {{ old('verification_status') == 'rejected' ? 'selected' : '' }}>
                                            Rejected
                                        </option>

                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Status <span class="text-danger">*</span></label>

                                    <select name="status"
                                            class="form-control"
                                            required>

                                        <option value="1"
                                            {{ old('status', 1) == 1 ? 'selected' : '' }}>
                                            Active
                                        </option>

                                        <option value="0"
                                            {{ old('status') === '0' ? 'selected' : '' }}>
                                            Inactive
                                        </option>

                                    </select>
                                </div>

                            </div>

                        </div>

                        <div class="card-footer text-right">

                            <a href="{{ route('pandits.index') }}"
                               class="btn btn-secondary mr-1">
                                Cancel
                            </a>

                            <button type="reset"
                                    class="btn btn-light mr-1">
                                Reset
                            </button>

                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Create Pandit
                            </button>

                        </div>

                    </form>

                </div>

            </div>
        </div>

    </div>
</section>

@endsection
@else
    @php
        abort(403);
    @endphp
@endcan

