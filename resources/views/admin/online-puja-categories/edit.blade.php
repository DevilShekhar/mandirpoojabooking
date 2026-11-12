
@can('online-puja-categories.edit')
@extends('admin.layouts.app')
@section('title', 'Edit Online Puja Category')
@section('content')
    <div class="section">
        <div class="section-header">
            <h1>Edit Online Puja Category</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('online-puja-categories.index') }}">
                        Online Puja Categories
                    </a>
                </div>
                <div class="breadcrumb-item active">Edit</div>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Online Puja Category Details</h4>
                </div>
                <form action="{{ route('online-puja-categories.update', $online_puja_category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>
                                    Category Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name"  class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $online_puja_category->name) }}" placeholder="Enter online puja category name">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label>Slug</label>
                                <input type="text"  class="form-control" value="{{ $online_puja_category->slug }}" readonly>
                                <small class="text-muted">
                                    Slug is generated automatically from category name.
                                </small>
                            </div>
                            <div class="form-group col-md-6">
                                <label>
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="1"
                                        {{ old('status', $online_puja_category->status) == '1' ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="0"
                                        {{ old('status', $online_puja_category->status) == '0' ? 'selected' : '' }}>
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
                        <a href="{{ route('online-puja-categories.index') }}"
                            class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Update Category
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