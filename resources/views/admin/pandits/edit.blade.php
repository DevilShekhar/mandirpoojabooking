@extends('admin.layouts.app')

@section('title', 'Edit Pandit')

@section('content')

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

                    <form method="POST"
                          action="{{ route('pandits.update', $pandit->id) }}"
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
                                           value="{{ old('name', $pandit->name) }}"
                                           placeholder="Enter Pandit Name"
                                           required>

                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>


                                <div class="form-group col-md-6">
                                    <label>
                                        Mobile <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           name="mobile"
                                           class="form-control @error('mobile') is-invalid @enderror"
                                           value="{{ old('mobile', $pandit->mobile) }}"
                                           placeholder="Enter Mobile Number"
                                           required>

                                    @error('mobile')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>


                                <div class="form-group col-md-6">
                                    <label>Email</label>

                                    <input type="email"
                                           name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $pandit->email) }}"
                                           placeholder="Enter Email">

                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>


                                <div class="form-group col-md-6">
                                    <label>Temple</label>

                                    <select name="temple_id"
                                            class="form-control @error('temple_id') is-invalid @enderror">

                                        <option value="">Select Temple</option>

                                        @foreach($temples as $temple)

                                            <option value="{{ $temple->id }}"
                                                {{ old('temple_id', $pandit->temple_id) == $temple->id ? 'selected' : '' }}>
                                                {{ $temple->name }}
                                            </option>

                                        @endforeach

                                    </select>

                                    @error('temple_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>


                                <div class="form-group col-md-6">
                                    <label>
                                        Experience (Years) <span class="text-danger">*</span>
                                    </label>

                                    <input type="number"
                                           name="experience"
                                           min="0"
                                           class="form-control @error('experience') is-invalid @enderror"
                                           value="{{ old('experience', $pandit->experience) }}"
                                           placeholder="Enter Experience"
                                           required>

                                    @error('experience')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>


                                <div class="form-group col-md-6">
                                    <label>Profile Image</label>

                                    @if($pandit->profile_image)

                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $pandit->profile_image) }}"
                                                 width="100"
                                                 height="100"
                                                 style="object-fit: cover; border-radius: 5px;">
                                        </div>

                                    @endif

                                    <input type="file"
                                           name="profile_image"
                                           class="form-control @error('profile_image') is-invalid @enderror"
                                           accept="image/*">

                                    @error('profile_image')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <small class="text-muted">
                                        Leave empty to keep the current image.
                                    </small>
                                </div>


                                <div class="form-group col-md-6">
                                    <label>Languages</label>

                                    <textarea name="languages"
                                              class="form-control @error('languages') is-invalid @enderror"
                                              rows="3"
                                              placeholder="e.g. Marathi, Hindi, Sanskrit">{{ old('languages', $pandit->languages) }}</textarea>

                                    @error('languages')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>


                                <div class="form-group col-md-6">
                                    <label>Specialization</label>

                                    <textarea name="specialization"
                                              class="form-control @error('specialization') is-invalid @enderror"
                                              rows="3"
                                              placeholder="e.g. Ganesh Puja, Rudrabhishek, Havan">{{ old('specialization', $pandit->specialization) }}</textarea>

                                    @error('specialization')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>


                                <div class="form-group col-md-6">
                                    <label>
                                        Verification Status <span class="text-danger">*</span>
                                    </label>

                                    <select name="verification_status"
                                            class="form-control @error('verification_status') is-invalid @enderror"
                                            required>

                                        <option value="pending"
                                            {{ old('verification_status', $pandit->verification_status) == 'pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>

                                        <option value="verified"
                                            {{ old('verification_status', $pandit->verification_status) == 'verified' ? 'selected' : '' }}>
                                            Verified
                                        </option>

                                        <option value="rejected"
                                            {{ old('verification_status', $pandit->verification_status) == 'rejected' ? 'selected' : '' }}>
                                            Rejected
                                        </option>

                                    </select>

                                    @error('verification_status')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>


                                <div class="form-group col-md-6">
                                    <label>
                                        Status <span class="text-danger">*</span>
                                    </label>

                                    <select name="status"
                                            class="form-control @error('status') is-invalid @enderror"
                                            required>

                                        <option value="1"
                                            {{ old('status', $pandit->status) == 1 ? 'selected' : '' }}>
                                            Active
                                        </option>

                                        <option value="0"
                                            {{ old('status', $pandit->status) == 0 ? 'selected' : '' }}>
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

                        </div>

                        <div class="card-footer text-right">

                            <a href="{{ route('pandits.index') }}"
                               class="btn btn-secondary mr-1">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Update Pandit
                            </button>

                        </div>

                    </form>

                </div>

            </div>
        </div>

    </div>
</section>

@endsection
