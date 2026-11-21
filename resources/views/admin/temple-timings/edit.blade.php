@extends('admin.layouts.app')
@section('title', 'Edit Temple Timing')
@section('content')
@can('temple-timings.edit')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Temple Timing</h4>
                        <div class="card-header-action">
                            <a href="{{ route('temple-timings.index') }}"
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('temple-timings.update', $templeTiming->id) }}">
                        @csrf
                        @method('PUT')
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
                                    <select name="temple_id" id="temple_id"  class="form-control @error('temple_id') is-invalid @enderror" required>
                                        <option value="">Select Temple</option>
                                        @foreach($temples as $temple)
                                            <option value="{{ $temple->id }}"
                                                {{ old('temple_id', $templeTiming->temple_id) == $temple->id ? 'selected' : '' }}>
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
                                Timing Category Information
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="temple_timing_category_id">
                                        Select Category
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="temple_timing_category_id"
                                            id="temple_timing_category_id"
                                            class="form-control @error('temple_timing_category_id') is-invalid @enderror"
                                            required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                    data-temple="{{ $category->temple_id }}"
                                                {{ old('temple_timing_category_id', $templeTiming->temple_timing_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('temple_timing_category_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="section-title">
                                Timing Information
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="name">
                                        Timing Name
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name"
                                           id="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $templeTiming->name) }}"
                                           placeholder="Enter Timing Name"
                                           maxlength="255"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="start_day">
                                        Start Day
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="start_day" id="start_day"  class="form-control @error('start_day') is-invalid @enderror"  required>
                                        <option value="">Select Start Day</option>
                                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                            <option value="{{ $day }}"
                                                {{ old('start_day', $templeTiming->start_day) == $day ? 'selected' : '' }}>
                                                {{ $day }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('start_day')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="end_day">
                                        End Day
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="end_day" id="end_day" class="form-control @error('end_day') is-invalid @enderror" required>
                                        <option value="">Select End Day</option>
                                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                            <option value="{{ $day }}"
                                                {{ old('end_day', $templeTiming->end_day) == $day ? 'selected' : '' }}>
                                                {{ $day }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('end_day')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="opening_time">
                                        Opening Time
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" name="opening_time" id="opening_time"
                                           class="form-control @error('opening_time') is-invalid @enderror"
                                           value="{{ old('opening_time', $templeTiming->opening_time ? \Carbon\Carbon::parse($templeTiming->opening_time)->format('H:i') : '') }}"
                                           required>
                                    @error('opening_time')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="closing_time">
                                        Closing Time
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" name="closing_time"  id="closing_time"
                                           class="form-control @error('closing_time') is-invalid @enderror"
                                           value="{{ old('closing_time', $templeTiming->closing_time ? \Carbon\Carbon::parse($templeTiming->closing_time)->format('H:i') : '') }}"
                                           required>
                                    @error('closing_time')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="section-title">
                                Description
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="description">
                                        Description
                                    </label>
                                    <textarea name="description"
                                              id="description" rows="4"
                                              class="form-control @error('description') is-invalid @enderror"
                                              placeholder="Enter Timing Description">{{ old('description', $templeTiming->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="section-title">
                                Timing Status
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="status">
                                        Status
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="1"
                                            {{ old('status', $templeTiming->status) == '1' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="0"
                                            {{ old('status', $templeTiming->status) == '0' ? 'selected' : '' }}>
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
                            <a href="{{ route('temple-timings.index') }}"  class="btn btn-secondary mr-1">
                                Cancel
                            </a>
                            <button type="reset" class="btn btn-light mr-1">
                                Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Update Timing
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@else
    @php
        abort(403);
    @endphp
@endcan
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        let $temple = $('#temple_id');
        let $category = $('#temple_timing_category_id');
        function filterCategories() {
            let selectedTemple = $temple.val();
            let selectedCategory = $category.val();
            $category.find('option').each(function () {
                let $option = $(this);
                if (!$option.val()) {
                    $option.prop('hidden', false);
                    return;
                }
                let categoryTemple = String($option.data('temple'));
                $option.prop(
                    'hidden',
                    categoryTemple !== String(selectedTemple)
                );
            });
            let selectedOption = $category.find('option:selected');
            if (selectedCategory && String(selectedOption.data('temple')) !== String(selectedTemple)) {
                $category.val('');
            }
        }
        $temple.on('change', function () {
            filterCategories();
        });
        // Initial category filtering.
        filterCategories();
    });
</script>
@endpush