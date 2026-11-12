@can('online-puja-categories.create')
@extends('admin.layouts.app')
@section('title', 'Create Online Puja Category')
@section('content')
    <div class="section">
        <div class="section-header">
            <h1>Create Online Puja Category</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('online-puja-categories.index') }}">Online Puja Categories</a>
                </div>
                <div class="breadcrumb-item active">Create</div>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Online Puja Category Details</h4>
                </div>
                <form action="{{ route('online-puja-categories.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>
                                    Category Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}"  placeholder="Enter online puja category name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('online-puja-categories.index') }}"  class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Save Category
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