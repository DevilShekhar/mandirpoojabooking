@can('puja-categories.edit')
@extends('admin.layouts.app')

@section('title', 'Edit Puja Category')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Puja Category</h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('puja-categories.update', $pujaCategory->id) }}"
                              method="POST"
                              enctype="multipart/form-data">

                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">
                                    Category Name <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="name"
                                       id="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $pujaCategory->name) }}"
                                       placeholder="Enter category name">

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
                                          placeholder="Enter category description">{{ old('description', $pujaCategory->description) }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Current Image</label>

                                @if($pujaCategory->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $pujaCategory->image) }}"
                                             alt="{{ $pujaCategory->name }}"
                                             width="100"
                                             height="100"
                                             style="object-fit: cover; border-radius: 5px;">
                                    </div>
                                @else
                                    <p class="text-muted">No image uploaded.</p>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="image">Change Image</label>

                                <input type="file"
                                       name="image"
                                       id="image"
                                       class="form-control @error('image') is-invalid @enderror"
                                       accept="image/*">

                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="sort_order">
                                    Sort Order <span class="text-danger">*</span>
                                </label>

                                <input type="number"
                                       name="sort_order"
                                       id="sort_order"
                                       min="0"
                                       class="form-control @error('sort_order') is-invalid @enderror"
                                       value="{{ old('sort_order', $pujaCategory->sort_order) }}"
                                       placeholder="Enter sort order">

                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="status">
                                    Status <span class="text-danger">*</span>
                                </label>

                                <select name="status"
                                        id="status"
                                        class="form-control @error('status') is-invalid @enderror">

                                    <option value="1"
                                        {{ old('status', $pujaCategory->status) == 1 ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="0"
                                        {{ old('status', $pujaCategory->status) == 0 ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>

                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Puja Category
                            </button>

                            <a href="{{ route('puja-categories.index') }}" class="btn btn-secondary">
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
@else
    @php
        abort(403);
    @endphp
@endcan
