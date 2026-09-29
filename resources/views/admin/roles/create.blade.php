@can('roles.create')
@extends('admin.layouts.app')
@section('title', 'Create Role')

@section('content')

<section class="section">
    <div class="section-body">

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>Create Role</h4>

                        <div class="card-header-action">
                            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <form id="role-create-form" method="POST" action="{{ route('roles.store') }}">
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
                                Role Information
                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label for="name">
                                        Role Name <span class="text-danger">*</span>
                                    </label>

                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}" placeholder="Enter Role Name" required>

                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                            </div>

                        </div>

                        <div class="card-footer text-right">

                            <a href="{{ route('roles.index') }}" class="btn btn-secondary mr-1">
                                Cancel
                            </a>

                            <button type="reset" class="btn btn-light mr-1">
                                Reset
                            </button>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Create Role
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
