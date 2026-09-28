@extends('admin.layouts.app')

@section('title', 'Add Temple Deity')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Add Temple Deity</h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('temple-deities.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="temple_id">
                                    Temple <span class="text-danger">*</span>
                                </label>

                                <select name="temple_id"
                                        id="temple_id"
                                        class="form-control @error('temple_id') is-invalid @enderror">
                                    <option value="">Select Temple</option>

                                    @foreach($temples as $temple)
                                        <option value="{{ $temple->id }}"
                                            {{ old('temple_id') == $temple->id ? 'selected' : '' }}>
                                            {{ $temple->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('temple_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="deity_id">
                                    Deity <span class="text-danger">*</span>
                                </label>

                                <select name="deity_id"
                                        id="deity_id"
                                        class="form-control @error('deity_id') is-invalid @enderror">
                                    <option value="">Select Deity</option>

                                    @foreach($deities as $deity)
                                        <option value="{{ $deity->id }}"
                                            {{ old('deity_id') == $deity->id ? 'selected' : '' }}>
                                            {{ $deity->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('deity_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="is_primary">
                                    Is Primary? <span class="text-danger">*</span>
                                </label>

                                <select name="is_primary"
                                        id="is_primary"
                                        class="form-control @error('is_primary') is-invalid @enderror">
                                    <option value="0" {{ old('is_primary', 0) == 0 ? 'selected' : '' }}>
                                        No
                                    </option>
                                    <option value="1" {{ old('is_primary') == 1 ? 'selected' : '' }}>
                                        Yes
                                    </option>
                                </select>

                                @error('is_primary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="display_order">
                                    Display Order <span class="text-danger">*</span>
                                </label>

                                <input type="number"
                                       name="display_order"
                                       id="display_order"
                                       min="0"
                                       class="form-control @error('display_order') is-invalid @enderror"
                                       value="{{ old('display_order', 0) }}"
                                       placeholder="Enter display order">

                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Temple Deity
                            </button>

                            <a href="{{ route('temple-deities.index') }}" class="btn btn-secondary">
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
