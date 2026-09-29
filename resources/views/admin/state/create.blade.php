@can('states.view')
@extends('admin.layouts.app')

@section('title', 'Create State')

@section('content')

<section class="section">
    <div class="section-body">

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>Create State</h4>

                        <div class="card-header-action">
                            <a href="{{ route('states.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <form id="state-create-form" method="POST" action="{{ route('states.store') }}">
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
                                State Information
                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label for="country_id">
                                        Country <span class="text-danger">*</span>
                                    </label>

                                    <select name="country_id"
                                        id="country_id"
                                        class="form-control @error('country_id') is-invalid @enderror"
                                        required>

                                        <option value="">Select Country</option>

                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}"
                                                {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach

                                    </select>

                                    @error('country_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="name">
                                        State Name <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                        name="name"
                                        id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}"
                                        placeholder="Enter State Name"
                                        required>

                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="code">
                                        State Code
                                    </label>

                                    <input type="text"
                                        name="code"
                                        id="code"
                                        class="form-control @error('code') is-invalid @enderror"
                                        value="{{ old('code') }}"
                                        maxlength="10"
                                        placeholder="e.g. MH">

                                    @error('code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="card-footer text-right">

                            <a href="{{ route('states.index') }}" class="btn btn-secondary mr-1">
                                Cancel
                            </a>

                            <button type="reset" class="btn btn-light mr-1">
                                Reset
                            </button>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Create State
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
