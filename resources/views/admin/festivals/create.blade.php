@extends('admin.layouts.app')

@section('title', 'Create Festival')

@section('content')

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">

                <div class="card-header">
                    <h4>Create Festival</h4>

                    <div class="card-header-action">
                        <a href="{{ route('festivals.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <form method="POST"
                      action="{{ route('festivals.store') }}"
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
                            Festival Information
                        </div>

                        <div class="form-row">

                            <div class="form-group col-md-6">
                                <label>
                                    Festival Name <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}"
                                       placeholder="Enter Festival Name"
                                       required>

                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>Festival Date</label>

                                <input type="date"
                                       name="festival_date"
                                       class="form-control @error('festival_date') is-invalid @enderror"
                                       value="{{ old('festival_date') }}">

                                @error('festival_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>Start Date</label>

                                <input type="date"
                                       name="start_date"
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date') }}">

                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>End Date</label>

                                <input type="date"
                                       name="end_date"
                                       class="form-control @error('end_date') is-invalid @enderror"
                                       value="{{ old('end_date') }}">

                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-12">
                                <label>Description</label>

                                <textarea name="description"
                                          class="form-control @error('description') is-invalid @enderror"
                                          rows="5"
                                          placeholder="Enter festival description">{{ old('description') }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>Festival Image</label>

                                <input type="file"
                                       name="image"
                                       class="form-control @error('image') is-invalid @enderror"
                                       accept="image/*">

                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>Banner Image</label>

                                <input type="file"
                                       name="banner_image"
                                       class="form-control @error('banner_image') is-invalid @enderror"
                                       accept="image/*">

                                @error('banner_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>
                                    Status <span class="text-danger">*</span>
                                </label>

                                <select name="status"
                                        class="form-control @error('status') is-invalid @enderror">

                                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>
                                        Inactive
                                    </option>

                                </select>

                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                    </div>

                    <div class="card-footer text-right">

                        <a href="{{ route('festivals.index') }}"
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
                            Create Festival
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

</section>

@endsection
