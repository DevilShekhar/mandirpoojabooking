@extends('admin.layouts.app')

@section('title', 'Add Deity')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Add Deity</h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('deities.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="name">Deity Name <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}"
                                       placeholder="Enter deity name">

                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description"
                                          id="description"
                                          rows="5"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Enter deity description">{{ old('description') }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file"
                                       name="image"
                                       id="image"
                                       class="form-control @error('image') is-invalid @enderror"
                                       accept="image/*">

                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Deity
                            </button>

                            <a href="{{ route('deities.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
