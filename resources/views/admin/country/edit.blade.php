@can('countries.edit')
@extends('admin.layouts.app')

@section('title', 'Edit Country')

@section('content')
<div class="section">
    <div class="section-header">
        <h1>Edit Country</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <a href="{{ route('countries.index') }}">Countries</a>
            </div>
            <div class="breadcrumb-item active">Edit</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Country Details</h4>
            </div>

            <form action="{{ route('countries.update', $country->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Country Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $country->name) }}"
                                   placeholder="Enter country name">

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label>Country Code <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="code"
                                   class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code', $country->code) }}"
                                   maxlength="2"
                                   placeholder="e.g. IN">

                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label>Phone Code <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="phone_code"
                                   class="form-control @error('phone_code') is-invalid @enderror"
                                   value="{{ old('phone_code', $country->phone_code) }}"
                                   placeholder="e.g. +91">

                            @error('phone_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status"
                                    class="form-control @error('status') is-invalid @enderror">
                                <option value="1" {{ old('status', $country->status) == 1 ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="0" {{ old('status', $country->status) == 0 ? 'selected' : '' }}>
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
                    <a href="{{ route('countries.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Update Country
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@else
    @php
        abort(404);
    @endphp
@endcan
