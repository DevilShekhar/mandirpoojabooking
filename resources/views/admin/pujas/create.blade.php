@can('pujas.view')
@extends('admin.layouts.app')
@section('title', 'Add Puja')
@section('content')
<section class="section">
    <div class="section-body">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Add New Puja</h4>
                    </div>
                    <form action="{{ route('pujas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div id="wizard_horizontal">
                                <h2>Basic Information</h2>
                                <section>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label>
                                                Temple
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="temple_id"
                                                class="form-control @error('temple_id') is-invalid @enderror">
                                                <option value="">
                                                    Select Temple
                                                </option>
                                                @foreach($temples as $temple)
                                                <option value="{{ $temple->id }}"
                                                    {{ old('temple_id') == $temple->id ? 'selected' : '' }}>
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
                                        <div class="form-group col-md-4">
                                            <label>
                                                Deity
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="deity_id"
                                                class="form-control @error('deity_id') is-invalid @enderror">
                                                <option value="">
                                                    Select Deity
                                                </option>
                                                @foreach($deities as $deity)
                                                <option value="{{ $deity->id }}"
                                                    {{ old('deity_id') == $deity->id ? 'selected' : '' }}>
                                                    {{ $deity->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('deity_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>
                                                Puja Category
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="puja_category_id"
                                                class="form-control @error('puja_category_id') is-invalid @enderror">
                                                <option value="">
                                                    Select Puja Category
                                                </option>
                                                @foreach($pujaCategories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('puja_category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('puja_category_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>
                                                Puja Name
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="name" value="{{ old('name') }}"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="Enter puja name">
                                            @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>
                                                Duration
                                            </label>
                                            <input type="text" name="duration" value="{{ old('duration') }}"
                                                class="form-control @error('duration') is-invalid @enderror"
                                                placeholder="Example: 60 Minutes">
                                            @error('duration')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </section>
                                <h2>Puja Content</h2>
                                <section>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label>
                                                Description
                                            </label>
                                            <textarea name="description"
                                                class="form-control @error('description') is-invalid @enderror summernote"
                                                rows="5"
                                                placeholder="Enter puja description">{{ old('description') }}</textarea>
                                            @error('description')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>
                                                Benefits
                                            </label>
                                            <textarea name="benefits"
                                                class="form-control @error('benefits') is-invalid @enderror" rows="5"
                                                placeholder="Enter puja benefits">{{ old('benefits') }}</textarea>
                                            @error('benefits')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>
                                                Ritual Details
                                            </label>
                                            <textarea name="ritual_details"
                                                class="form-control @error('ritual_details') is-invalid @enderror"
                                                rows="5"
                                                placeholder="Enter ritual details">{{ old('ritual_details') }}</textarea>
                                            @error('ritual_details')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>
                                                Samagri Details
                                            </label>
                                            <textarea name="samagri_details"
                                                class="form-control @error('samagri_details') is-invalid @enderror"
                                                rows="5"
                                                placeholder="Enter required puja samagri">{{ old('samagri_details') }}</textarea>
                                            @error('samagri_details')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </section>
                                <h2>FAQ</h2>
                                <section>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div>
                                                    <h6 class="mb-1">
                                                        Frequently Asked Questions
                                                    </h6>
                                                    <small class="text-muted">
                                                        Add common questions and answers related to this puja.
                                                    </small>
                                                </div>
                                                <button type="button" class="btn btn-primary btn-sm" id="addFaq">
                                                    <i class="fas fa-plus"></i>
                                                    Add FAQ
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div id="faqContainer">
                                                @if(old('faq'))
                                                @foreach(old('faq') as $index => $faq)
                                                <div class="faq-item border rounded p-3 mb-3">
                                                    <div class="row">
                                                        <div class="form-group col-md-5">
                                                            <label>
                                                                Question
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                            <input type="text" name="faq[{{ $index }}][question]"
                                                                value="{{ $faq['question'] ?? '' }}"
                                                                class="form-control @error('faq.' . $index . '.question') is-invalid @enderror"
                                                                placeholder="Enter FAQ question">
                                                            @error('faq.' . $index . '.question')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>
                                                                Answer
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                            <textarea name="faq[{{ $index }}][answer]"
                                                                class="form-control @error('faq.' . $index . '.answer') is-invalid @enderror"
                                                                rows="3"
                                                                placeholder="Enter FAQ answer">{{ $faq['answer'] ?? '' }}</textarea>
                                                            @error('faq.' . $index . '.answer')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-1">
                                                            <label class="d-block">
                                                                &nbsp;
                                                            </label>
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm removeFaq"
                                                                title="Remove FAQ">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                @else
                                                <div class="faq-item border rounded p-3 mb-3">
                                                    <div class="row">
                                                        <div class="form-group col-md-5">
                                                            <label>
                                                                Question
                                                            </label>
                                                            <input type="text" name="faq[0][question]"
                                                                class="form-control" placeholder="Enter FAQ question">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>
                                                                Answer
                                                            </label>
                                                            <textarea name="faq[0][answer]" class="form-control"
                                                                rows="3" placeholder="Enter FAQ answer"></textarea>
                                                        </div>
                                                        <div class="form-group col-md-1">
                                                            <label class="d-block">
                                                                &nbsp;
                                                            </label>
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm removeFaq"
                                                                title="Remove FAQ">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <h2>Images & Puja Type</h2>
                                <section>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>
                                                Puja Image
                                            </label>
                                            <input type="file" name="image"
                                                class="form-control @error('image') is-invalid @enderror"
                                                accept="image/*">
                                            <small class="text-muted">
                                                Upload main puja image.
                                            </small>
                                            @error('image')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>
                                                Banner Image
                                            </label>
                                            <input type="file" name="banner_image"
                                                class="form-control @error('banner_image') is-invalid @enderror"
                                                accept="image/*">
                                            <small class="text-muted">
                                                Upload puja banner image.
                                            </small>
                                            @error('banner_image')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>
                                                Online Puja
                                            </label>
                                            <select name="is_online"
                                                class="form-control @error('is_online') is-invalid @enderror">
                                                <option value="1" {{ old('is_online', 0) == 1 ? 'selected' : '' }}>
                                                    Yes
                                                </option>
                                                <option value="0" {{ old('is_online', 0) == 0 ? 'selected' : '' }}>
                                                    No
                                                </option>
                                            </select>
                                            @error('is_online')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>
                                                Offline Puja
                                            </label>
                                            <select name="is_offline"
                                                class="form-control @error('is_offline') is-invalid @enderror">
                                                <option value="1" {{ old('is_offline', 1) == 1 ? 'selected' : '' }}>
                                                    Yes
                                                </option>
                                                <option value="0" {{ old('is_offline', 1) == 0 ? 'selected' : '' }}>
                                                    No
                                                </option>
                                            </select>
                                            @error('is_offline')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </section>
                                <h2>Review & Submit</h2>
                                <section>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-info">
                                                <strong>
                                                    Review Your Puja Details
                                                </strong>
                                                <br>
                                                Please verify all information before submitting the puja.
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <th width="30%">
                                                                Temple
                                                            </th>
                                                            <td>
                                                                Selected Temple
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                Deity
                                                            </th>
                                                            <td>
                                                                Selected Deity
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                Puja Category
                                                            </th>
                                                            <td>
                                                                Selected Puja Category
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                Puja Name
                                                            </th>
                                                            <td>
                                                                {{ old('name') ?: 'Puja Name' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                Duration
                                                            </th>
                                                            <td>
                                                                {{ old('duration') ?: 'Not specified' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                Online Puja
                                                            </th>
                                                            <td>
                                                                {{ old('is_online', 0) == 1 ? 'Yes' : 'No' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                Offline Puja
                                                            </th>
                                                            <td>
                                                                {{ old('is_offline', 1) == 1 ? 'Yes' : 'No' }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-right mt-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i>
                                                Save Puja
                                            </button>
                                        </div>
                                    </div>
                                </section>
                            </div>
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
$(document).ready(function() {
    let faqIndex = {
        {
            old('faq') ? count(old('faq')) : 1
        }
    };
    $('#addFaq').on('click', function() {
        let faqHtml = `
                <div class="faq-item border rounded p-3 mb-3">
                    <div class="row">
                        <div class="form-group col-md-5">
                            <label>
                                Question
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="faq[${faqIndex}][question]"
                                   class="form-control"
                                   placeholder="Enter FAQ question">
                        </div>
                        <div class="form-group col-md-6">
                            <label>
                                Answer
                                <span class="text-danger">*</span>
                            </label>
                            <textarea name="faq[${faqIndex}][answer]"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Enter FAQ answer"></textarea>
                        </div>
                        <div class="form-group col-md-1">
                            <label class="d-block">
                                &nbsp;
                            </label>
                            <button type="button"
                                    class="btn btn-danger btn-sm removeFaq"
                                    title="Remove FAQ">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        $('#faqContainer').append(faqHtml);
        faqIndex++;
    });
    $(document).on('click', '.removeFaq', function() {
        $(this).closest('.faq-item').remove();
    });
});
</script>
@endpush
@else
@php
abort(403);
@endphp
@endcan