@can('temple-timing-categories.create')
@extends('admin.layouts.app')
@section('title', 'Create Temple Timing Category')
@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Create Temple Timing Category</h4>
                        <div class="card-header-action">
                            <a href="{{ route('temple-timing-categories.index') }}"
                                class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('temple-timing-categories.store') }}">
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
                                Temple Information
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="temple_id">
                                        Select Temple
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="temple_id" id="temple_id" class="form-control @error('temple_id') is-invalid @enderror" required>
                                        @foreach($temples as $temple)
                                            <option value="{{ $temple->id }}"
                                                {{ old('temple_id', $temples->first()->id) == $temple->id ? 'selected' : '' }}>
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
                            </div>
                            <div class="section-title">
                                Category Information
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="name">
                                        Category Name
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter Category Name" required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="slug">
                                        Category Slug
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="slug"  id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="category-slug" required>
                                    @error('slug')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="section-title">
                                Category Status
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="status">
                                        Status
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="status"  id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="1"
                                            {{ old('status', 1) == 1 ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="0"
                                            {{ old('status') == '0' ? 'selected' : '' }}>
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
                            <a href="{{ route('temple-timing-categories.index') }}"
                                class="btn btn-secondary mr-1">
                                Cancel
                            </a>
                            <button type="reset" class="btn btn-light mr-1">
                                Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Create Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        $('#name').on('input', function () {
            let slug = $(this).val()
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            $('#slug').val(slug);
        });
    });
</script>
@endpush
@else
    @php
        abort(403);
    @endphp
@endcan