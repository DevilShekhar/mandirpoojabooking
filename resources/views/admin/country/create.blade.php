@can('countries.view')
@extends('admin.layouts.app')

@section('title', 'Create Country')

@section('content')
    <div class="section">
        <div class="section-header">
            <h1>Create Country</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('countries.index') }}">Countries</a>
                </div>
                <div class="breadcrumb-item active">Create</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Country Details</h4>
                </div>

                <form action="{{ route('countries.store') }}" method="POST">
                    @csrf

                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Country Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" placeholder="Enter country name">

                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>Country Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                    value="{{ old('code') }}" maxlength="2" placeholder="e.g. IN">

                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>Phone Code <span class="text-danger">*</span></label>
                                <input type="text" name="phone_code"
                                    class="form-control @error('phone_code') is-invalid @enderror"
                                    value="{{ old('phone_code') }}" placeholder="e.g. +91">

                                @error('phone_code')
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
                            Save Country
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@else
    @php
        abort(403);
    @endphp
@endcan
