@can('pujas.edit')
@extends('admin.layouts.app')
@section('title', 'Edit Puja')
    @section('content')
        <section class="section">
            <div class="section-body">
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Edit Puja</h4>
                            </div>
                            <form id="pujaEditForm" action="{{ route('pujas.update', $puja->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                @if($errors->any())
                                <div class="alert alert-danger m-3">
                                    <strong>Please fix the following errors:</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                @if(session('Error'))
                                <div class="alert alert-danger m-3">{{ session('Error') }}</div>
                                @endif
                                @if(session('Success'))
                                <div class="alert alert-success m-3">{{ session('Success') }}</div>
                                @endif
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
                                                            {{ old('temple_id', $puja->temple_id) == $temple->id ? 'selected' : '' }}>
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
                                                            {{ old('deity_id', $puja->deity_id) == $deity->id ? 'selected' : '' }}>
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
                                                            {{ old('puja_category_id', $puja->puja_category_id) == $category->id ? 'selected' : '' }}>
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
                                                    <input type="text" name="name" value="{{ old('name', $puja->name) }}"
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
                                                    <input type="text" name="duration"
                                                        value="{{ old('duration', $puja->duration) }}"
                                                        class="form-control @error('duration') is-invalid @enderror"
                                                        placeholder="Example: 60 Minutes">
                                                    @error('duration')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label>
                                                        Description
                                                    </label>
                                                    <textarea id="puja_description" name="description"
                                                        class="form-control @error('description') is-invalid @enderror "
                                                        rows="5"
                                                        placeholder="Enter puja description">{{ old('description', $puja->description ?? '') }}</textarea>
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
                                                        placeholder="Enter puja benefits">{{ old('benefits', $puja->benefits) }}</textarea>
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
                                                        placeholder="Enter ritual details">{{ old('ritual_details', $puja->ritual_details) }}</textarea>
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
                                                        placeholder="Enter required puja samagri">{{ old('samagri_details', $puja->samagri_details) }}</textarea>
                                                    @error('samagri_details')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>
                                                        Puja Image
                                                    </label>
                                                    <input type="file" name="image"
                                                        class="form-control @error('image') is-invalid @enderror"
                                                        accept="image/*">
                                                    @if($puja->image)
                                                    <div class="mt-2">
                                                        <small class="text-muted d-block mb-1">Current Image</small>
                                                        <img src="{{ asset('storage/' . $puja->image) }}" alt="Puja Image"
                                                            width="120" height="120"
                                                            style="object-fit:cover;border-radius:6px;">
                                                    </div>
                                                    @endif
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
                                                    @if($puja->banner_image)
                                                    <div class="mt-2">
                                                        <small class="text-muted d-block mb-1">Current Banner</small>
                                                        <img src="{{ asset('storage/' . $puja->banner_image) }}"
                                                            alt="Banner Image" width="220" height="90"
                                                            style="object-fit:cover;border-radius:6px;">
                                                    </div>
                                                    @endif
                                                    <small class="text-muted">
                                                        Upload puja banner image.
                                                    </small>
                                                    @error('banner_image')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>
                                                        Online Puja
                                                    </label>
                                                    <select name="is_online"
                                                        class="form-control @error('is_online') is-invalid @enderror">
                                                        <option value="1"
                                                            {{ old('is_online', $puja->is_online) == 1 ? 'selected' : '' }}>
                                                            Yes
                                                        </option>
                                                        <option value="0"
                                                            {{ old('is_online', $puja->is_online) == 0 ? 'selected' : '' }}>
                                                            No
                                                        </option>
                                                    </select>
                                                    @error('is_online')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>
                                                        Status <span class="text-danger">*</span>
                                                    </label>
                                                    <select name="status"
                                                        class="form-control @error('status') is-invalid @enderror" required>
                                                        <option value="1"
                                                            {{ old('status', $puja->status) == 1 ? 'selected' : '' }}>
                                                            Active
                                                        </option>
                                                        <option value="0"
                                                            {{ old('status', $puja->status) == 0 ? 'selected' : '' }}>
                                                            Inactive
                                                        </option>
                                                    </select>
                                                    @error('status')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>
                                                        Offline Puja
                                                    </label>
                                                    <select name="is_offline"
                                                        class="form-control @error('is_offline') is-invalid @enderror">
                                                        <option value="1"
                                                            {{ old('is_offline', $puja->is_offline) == 1 ? 'selected' : '' }}>
                                                            Yes
                                                        </option>
                                                        <option value="0"
                                                            {{ old('is_offline', $puja->is_offline) == 0 ? 'selected' : '' }}>
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
                                        <h2>Packages</h2>
                                        <section>
                                            <div class="card">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h4 class="mb-0">Puja Packages</h4>
                                                        <small class="text-muted">
                                                            Add one or more package options for this Puja.
                                                        </small>
                                                    </div>
                                                    <button type="button" class="btn btn-primary" id="addPackageBtn"
                                                        data-toggle="modal" data-target="#packageModal">
                                                        <i class="fas fa-plus"></i>
                                                        Add Package
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    @php
                                                    $oldPackages = old('packages');
                                                    if ($oldPackages === null) {
                                                    $oldPackages = $puja->packages->map(function ($package) {
                                                    return $package->toArray();
                                                    })->toArray();
                                                    }
                                                    @endphp
                                                    <div id="noPackageMessage"
                                                        class="text-center py-5 {{ count($oldPackages ?? []) ? 'd-none' : '' }}">
                                                        <i class="fas fa-box-open package-empty-icon"></i>
                                                        <h5 class="mt-3">
                                                            No packages added yet
                                                        </h5>
                                                        <p class="text-muted">
                                                            Click "Add Package" to create a package for this Puja.
                                                        </p>
                                                        <button type="button" class="btn btn-outline-primary"
                                                            id="addFirstPackageBtn" data-toggle="modal"
                                                            data-target="#packageModal">
                                                            <i class="fas fa-plus"></i>
                                                            Add First Package
                                                        </button>
                                                    </div>
                                                    <div id="packageContainer">
                                                        @foreach($oldPackages as $index => $package)
                                                        <div class="package-card" data-package-index="{{ $index }}">
                                                            <input type="hidden" name="packages[{{ $index }}][name]"
                                                                value="{{ $package['name'] ?? '' }}">
                                                            <input type="hidden" name="packages[{{ $index }}][person_count]"
                                                            value="{{ $package['person_count'] ?? '' }}">
                                                            <input type="hidden" name="packages[{{ $index }}][sort_order]"
                                                                value="{{ $package['sort_order'] ?? 0 }}">
                                                            <input type="hidden" name="packages[{{ $index }}][price]"
                                                                value="{{ $package['price'] ?? '' }}">
                                                            <input type="hidden" name="packages[{{ $index }}][discount_price]"
                                                                value="{{ $package['discount_price'] ?? '' }}">
                                                            <input type="hidden"
                                                                name="packages[{{ $index }}][short_description]"
                                                                value="{{ $package['short_description'] ?? '' }}">
                                                            <input type="hidden" name="packages[{{ $index }}][description]"
                                                                value="{{ $package['description'] ?? '' }}">
                                                            <input type="hidden" name="packages[{{ $index }}][includes_sankalp]"
                                                                value="{{ $package['includes_sankalp'] ?? 0 }}">
                                                            <input type="hidden" name="packages[{{ $index }}][includes_prasad]"
                                                                value="{{ $package['includes_prasad'] ?? 0 }}">
                                                            <input type="hidden" name="packages[{{ $index }}][includes_video]"
                                                                value="{{ $package['includes_video'] ?? 0 }}">
                                                            <input type="hidden"
                                                                name="packages[{{ $index }}][includes_certificate]"
                                                                value="{{ $package['includes_certificate'] ?? 0 }}">
                                                            <input type="hidden" name="packages[{{ $index }}][is_featured]"
                                                                value="{{ $package['is_featured'] ?? 0 }}">
                                                            @if(!empty($package['package_image']))
                                                            <input type="hidden" class="existing-package-image"
                                                                name="packages[{{ $index }}][existing_package_image]" value="{{ $package['package_image'] }}">
                                                            @endif
                                                            <input type="file" name="packages[{{ $index }}][package_image]"
                                                                class="package-image-input d-none" accept="image/*">
                                                            @if(!empty($package['package_image']))
                                                            <div class="mt-2">
                                                                <small class="text-muted">Current Package Image</small><br>
                                                                <img src="{{ asset('storage/' . $package['package_image']) }}"
                                                                    width="90" height="60"
                                                                    style="object-fit:cover;border-radius:5px;">
                                                            </div>
                                                            @endif
                                                            <div class="package-card-header">
                                                                <div>
                                                                    <span class="package-number">
                                                                        Package {{ $index + 1 }}
                                                                    </span>
                                                                    @if(!empty($package['is_featured']))
                                                                    <span class="badge badge-warning ml-2">
                                                                        Featured
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                                <div>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-primary edit-package">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-danger removePackage">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="package-card-body">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <strong>Package Name</strong>
                                                                        <div class="package-value package-title">
                                                                            {{ $package['name'] ?? '-' }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <strong>Persons</strong>
                                                                        <div class="package-value package-person-count">
                                                                            {{ $package['person_count'] ?? '-' }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <strong>Price</strong>
                                                                        <div class="package-value package-price">
                                                                            ₹{{ $package['price'] ?? '0' }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <strong>Discount Price</strong>
                                                                        <div class="package-value package-discount-price">
                                                                            ₹{{ $package['discount_price'] ?? '0' }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <strong>Short Description</strong>
                                                                        <div class="package-value package-short-description">
                                                                            {{ $package['short_description'] ?? '-' }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-3">
                                                                    @if(!empty($package['includes_sankalp']))
                                                                    <div class="col-md-3">
                                                                        <span class="badge badge-success">
                                                                            <i class="fas fa-check"></i>
                                                                            Sankalp
                                                                        </span>
                                                                    </div>
                                                                    @endif
                                                                    @if(!empty($package['includes_prasad']))
                                                                    <div class="col-md-3">
                                                                        <span class="badge badge-success">
                                                                            <i class="fas fa-check"></i>
                                                                            Prasad
                                                                        </span>
                                                                    </div>
                                                                    @endif
                                                                    @if(!empty($package['includes_video']))
                                                                    <div class="col-md-3">
                                                                        <span class="badge badge-success">
                                                                            <i class="fas fa-check"></i>
                                                                            Video
                                                                        </span>
                                                                    </div>
                                                                    @endif
                                                                    @if(!empty($package['includes_certificate']))
                                                                    <div class="col-md-3">
                                                                        <span class="badge badge-success">
                                                                            <i class="fas fa-check"></i>
                                                                            Certificate
                                                                        </span>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
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
                                                        @php
                                                        $editFaqs = old('faq');
                                                        if ($editFaqs === null) {
                                                        $editFaqs = $puja->faqs->map(function ($faq) {
                                                        return $faq->toArray();
                                                        })->toArray();
                                                        }
                                                        @endphp
                                                        @foreach($editFaqs as $index => $faq)
                                                        <div class="faq-item border rounded p-3 mb-3">
                                                            <div class="row">
                                                                <div class="form-group col-md-5">
                                                                    <label>Question <span class="text-danger">*</span></label>
                                                                    <input type="text" name="faq[{{ $index }}][question]"
                                                                        value="{{ $faq['question'] ?? '' }}"
                                                                        class="form-control @error('faq.' . $index . '.question') is-invalid @enderror"
                                                                        placeholder="Enter FAQ question">
                                                                    @error('faq.' . $index . '.question')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label>Answer <span class="text-danger">*</span></label>
                                                                    <textarea name="faq[{{ $index }}][answer]"
                                                                        class="form-control @error('faq.' . $index . '.answer') is-invalid @enderror"
                                                                        rows="3"
                                                                        placeholder="Enter FAQ answer">{{ $faq['answer'] ?? '' }}</textarea>
                                                                    @error('faq.' . $index . '.answer')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group col-md-1">
                                                                    <label class="d-block">&nbsp;</label>
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm removeFaq"
                                                                        title="Remove FAQ">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
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
                                                                        {{ old('name', $puja->name) ?: 'Puja Name' }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>
                                                                        Duration
                                                                    </th>
                                                                    <td>
                                                                        {{ old('duration', $puja->duration) ?: 'Not specified' }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>
                                                                        Online Puja
                                                                    </th>
                                                                    <td>
                                                                        {{ old('is_online', $puja->is_online) == 1 ? 'Yes' : 'No' }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>
                                                                        Offline Puja
                                                                    </th>
                                                                    <td>
                                                                        {{ old('is_offline', $puja->is_offline) == 1 ? 'Yes' : 'No' }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Status</th>
                                                                    <td>
                                                                        {{ old('status', $puja->status) == 1 ? 'Active' : 'Inactive' }}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 text-right mt-3">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-save"></i>
                                                        Update Puja
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
        <div class="modal fade" id="packageModal" tabindex="-1" role="dialog" aria-labelledby="packageModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="packageModalLabel">
                            Add Package
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="packageModalError" class="alert alert-danger d-none">
                    </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>
                                        Package Name
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="package_name" placeholder="Enter package name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>
                                        Person Count
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" min="1" class="form-control" id="package_person_count" value="1"
                                        placeholder="Number of persons">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>
                                        Price
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₹</span>
                                        </div>
                                        <input type="number" min="0" step="0.01" class="form-control" id="package_price"
                                            placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>
                                        Discount Price
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₹</span>
                                        </div>
                                        <input type="number" min="0" step="0.01" class="form-control"
                                            id="package_discount_price" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>
                                        Sort Order
                                    </label>
                                    <input type="number" min="0" class="form-control" id="package_sort_order" value="0">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>
                                        Package Image
                                    </label>
                                    <input type="file" class="form-control" id="package_image" accept="image/*">
                                    <small class="text-muted">
                                        JPG, JPEG, PNG.
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>
                                        Short Description
                                    </label>
                                    <textarea class="form-control" id="package_short_description" rows="3"
                                        placeholder="Enter short description"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>
                                        Description
                                    </label>
                                    <textarea class="form-control" id="package_description" rows="5"
                                        placeholder="Enter package description"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>
                                    Package Includes
                                </label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="package_sankalp">
                                            <label class="custom-control-label" for="package_sankalp">
                                                Sankalp
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="package_prasad">
                                            <label class="custom-control-label" for="package_prasad">
                                                Prasad
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="package_video">
                                            <label class="custom-control-label" for="package_video">
                                                Video
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="package_certificate">
                                            <label class="custom-control-label" for="package_certificate">
                                                Certificate
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="package_is_featured" value="1">
                                    <label class="custom-control-label" for="package_is_featured">
                                        <strong>
                                            Featured Package
                                        </strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary" id="savePackageBtn">
                            <i class="fas fa-save"></i>
                            Save Package
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('scripts')
        <script>
            $(document).ready(function() {
                var $description = $('#puja_description');
                function initDescriptionEditor() {
                    if (!$description.length) {
                        console.error('Puja description textarea not found.');
                        return;
                    }
                    if (typeof $.fn.summernote !== 'function') {
                        console.error('Summernote is not loaded. Check summernote-bs4.js.');
                        return;
                    }
                    if (!$description.next('.note-editor').length) {
                        $description.summernote({
                            height: 200,
                            callbacks: {
                                onInit: function() {

                                    $description.val($(this).summernote('code'));
                                },
                                onChange: function(contents) {

                                    $description.val(contents);
                                }
                            }
                        });
                    }
                }
                initDescriptionEditor();
                function syncSummernote() {
                    if (!$description.length || typeof $.fn.summernote !== 'function') {
                        return;
                    }
                    if ($description.next('.note-editor').length) {
                        var content = $description.summernote('code');
                        $description.val(content);
                    }
                }
                $('#pujaEditForm').on('submit', function() {
                    syncSummernote();
                });
                $('#wizard_horizontal').on('click', '.actions a', function() {
                    syncSummernote();
                });
                $(document).on('click', '#wizard_horizontal .actions a[href="#finish"]', function() {
                    syncSummernote();
                });
                let packageIndex = $('#packageContainer .package-card').length;
                let editingPackageIndex = null;
                let faqIndex = @json(count($editFaqs));
                $('#addFaq').on('click', function() {
                    let faqHtml = `
                        <div class="faq-item border rounded p-3 mb-3">
                            <div class="row">
                                <div class="form-group col-md-5">
                                    <label>Question <span class="text-danger">*</span></label>
                                    <input type="text" name="faq[${faqIndex}][question]" class="form-control" placeholder="Enter FAQ question">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Answer <span class="text-danger">*</span></label>
                                    <textarea name="faq[${faqIndex}][answer]" class="form-control" rows="3" placeholder="Enter FAQ answer"></textarea>
                                </div>
                                <div class="form-group col-md-1">
                                    <label class="d-block">&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-sm removeFaq" title="Remove FAQ">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>`;
                    $('#faqContainer').append(faqHtml);
                    faqIndex++;
                });
                $(document).on('click', '.removeFaq', function() {
                    $(this).closest('.faq-item').remove();
                });
                function resetPackageForm() {
                    $('#packageModalError').addClass('d-none').html('');
                    $('#package_name').val('');
                    $('#package_person_count').val(1);
                    $('#package_sort_order').val(0);
                    $('#package_price').val('');
                    $('#package_discount_price').val('');
                    $('#package_short_description').val('');
                    $('#package_description').val('');
                    $('#package_sankalp, #package_prasad, #package_video, #package_certificate, #package_is_featured').prop(
                        'checked', false);
                    $('#package_image').val('');
                    editingPackageIndex = null;
                    $('#packageModalLabel').text('Add Package');
                    $('#savePackageBtn').html('<i class="fas fa-save"></i> Save Package');
                }
                function escapeHtml(value) {
                    return $('<div>').text(value == null ? '' : value).html();
                }
                function getCardValue(card, field) {
                    return $(card).find('input[name*="[' + field + ']"]').val() || '';
                }
                $(document).on('click', '#addPackageBtn, #addFirstPackageBtn', function(e) {
                    e.preventDefault();
                    resetPackageForm();
                    $('#packageModal').modal('show');
                });
                $(document).on('click', '.edit-package', function(e) {
                    e.preventDefault();
                    let card = $(this).closest('.package-card');
                    editingPackageIndex = card.attr('data-package-index');
                    $('#packageModalLabel').text('Edit Package');
                    $('#savePackageBtn').html('<i class="fas fa-save"></i> Update Package');
                    $('#packageModalError').addClass('d-none').html('');
                    $('#package_name').val(getCardValue(card, 'name'));
                    $('#package_person_count').val(getCardValue(card, 'person_count') || 1);
                    $('#package_price').val(getCardValue(card, 'price'));
                    $('#package_discount_price').val(getCardValue(card, 'discount_price'));
                    $('#package_sort_order').val(getCardValue(card, 'sort_order') || 0);
                    $('#package_short_description').val(getCardValue(card, 'short_description'));
                    $('#package_description').val(getCardValue(card, 'description'));
                    $('#package_sankalp').prop('checked', getCardValue(card, 'includes_sankalp') == '1');
                    $('#package_prasad').prop('checked', getCardValue(card, 'includes_prasad') == '1');
                    $('#package_video').prop('checked', getCardValue(card, 'includes_video') == '1');
                    $('#package_certificate').prop('checked', getCardValue(card, 'includes_certificate') == '1');
                    $('#package_is_featured').prop('checked', getCardValue(card, 'is_featured') == '1');
                    $('#package_image').val('');
                    $('#packageModal').modal('show');
                });
                $('#savePackageBtn').on('click', function() {
                    let name = $.trim($('#package_name').val());
                    let personCount = $('#package_person_count').val();
                    let price = $('#package_price').val();
                    let discountPrice = $('#package_discount_price').val();
                    let sortOrder = $('#package_sort_order').val();
                    let shortDescription = $('#package_short_description').val();
                    let description = $('#package_description').val();
                    let sankalp = $('#package_sankalp').is(':checked') ? 1 : 0;
                    let prasad = $('#package_prasad').is(':checked') ? 1 : 0;
                    let video = $('#package_video').is(':checked') ? 1 : 0;
                    let certificate = $('#package_certificate').is(':checked') ? 1 : 0;
                    let featured = $('#package_is_featured').is(':checked') ? 1 : 0;
                    let errors = [];
                    if (!name) errors.push('Package name is required.');
                    if (!personCount || parseInt(personCount) < 1) errors.push('Person count must be at least 1.');
                    if (price === '' || parseFloat(price) < 0 || isNaN(parseFloat(price))) errors.push(
                        'Package price is required.');
                    if (discountPrice !== '' && parseFloat(discountPrice) > parseFloat(price)) errors.push(
                        'Discount price cannot be greater than package price.');
                    if (errors.length) {
                        $('#packageModalError').html(errors.join('<br>')).removeClass('d-none');
                        return;
                    }
                    $('#noPackageMessage').remove();
                    if (editingPackageIndex !== null) {
                        let card = $('.package-card[data-package-index="' + editingPackageIndex + '"]');
                        card.find('input[name*="[name]"]').val(name);
                        card.find('input[name*="[person_count]"]').val(personCount);
                        card.find('input[name*="[price]"]').val(price);
                        card.find('input[name*="[discount_price]"]').val(discountPrice);
                        card.find('input[name*="[sort_order]"]').val(sortOrder);
                        card.find('input[name*="[short_description]"]').val(shortDescription);
                        card.find('input[name*="[description]"]').val(description);
                        card.find('input[name*="[includes_sankalp]"]').val(sankalp);
                        card.find('input[name*="[includes_prasad]"]').val(prasad);
                        card.find('input[name*="[includes_video]"]').val(video);
                        card.find('input[name*="[includes_certificate]"]').val(certificate);
                        card.find('input[name*="[is_featured]"]').val(featured);
                        card.find('.package-title').text(name);
                        card.find('.package-person-count').text(personCount + (personCount == 1 ? ' Person' :
                            ' Persons'));
                        card.find('.package-price').text('₹' + parseFloat(price).toFixed(2));
                        card.find('.package-discount-price').text(discountPrice !== '' ? '₹' + parseFloat(
                            discountPrice).toFixed(2) : '—');
                        card.find('.package-sort-order').text(sortOrder);
                        card.find('.package-short-description').html(shortDescription ?
                            '<strong>Short Description:</strong> ' + escapeHtml(shortDescription) : '');
                        card.find('.package-badges').html(
                            (sankalp ? '<span class="badge badge-info mr-1">Sankalp</span>' : '') +
                            (prasad ? '<span class="badge badge-success mr-1">Prasad</span>' : '') +
                            (video ? '<span class="badge badge-primary mr-1">Video</span>' : '') +
                            (certificate ? '<span class="badge badge-warning mr-1">Certificate</span>' : '') +
                            (featured ? '<span class="badge badge-dark mr-1">Featured</span>' : '')
                        );
                        card.find('.package-card-header .badge-warning').remove();
                        if (featured) {
                            card.find('.package-card-header > div:first-child').append(
                                '<span class="badge badge-warning ml-2">Featured</span>');
                        }
                        let selectedFile = $('#package_image')[0].files[0];
                        if (selectedFile) {
                            let input = card.find('.package-image-input')[0];
                            if (!input) {
                                input = $('<input type="file" class="package-image-input d-none" accept="image/*">').appendTo(card)[0];
                            }
                            let dt = new DataTransfer();
                            dt.items.add(selectedFile);
                            input.files = dt.files;
                        }
                    } else {
                        let index = packageIndex++;
                        let packageHtml = `
                            <div class="package-card border rounded p-3 mb-3" data-package-index="${index}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1 package-title">${escapeHtml(name)}</h6>
                                        <small class="text-muted package-person-count">${escapeHtml(personCount)} ${personCount == 1 ? 'Person' : 'Persons'}</small>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary btn-sm edit-package mr-1"><i class="fas fa-edit"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm removePackage"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4"><strong>Price:</strong> <span class="package-price">₹${parseFloat(price).toFixed(2)}</span></div>
                                    <div class="col-md-4"><strong>Discount Price:</strong> <span class="package-discount-price">${discountPrice ? '₹' + parseFloat(discountPrice).toFixed(2) : '—'}</span></div>
                                    <div class="col-md-4"><strong>Sort Order:</strong> <span class="package-sort-order">${escapeHtml(sortOrder)}</span></div>
                                </div>
                                <div class="mt-2 package-short-description">${shortDescription ? '<strong>Short Description:</strong> ' + escapeHtml(shortDescription) : ''}</div>
                                <div class="mt-2 package-badges">
                                    ${sankalp ? '<span class="badge badge-info mr-1">Sankalp</span>' : ''}
                                    ${prasad ? '<span class="badge badge-success mr-1">Prasad</span>' : ''}
                                    ${video ? '<span class="badge badge-primary mr-1">Video</span>' : ''}
                                    ${certificate ? '<span class="badge badge-warning mr-1">Certificate</span>' : ''}
                                    ${featured ? '<span class="badge badge-dark mr-1">Featured</span>' : ''}
                                </div>
                                <input type="hidden" name="packages[${index}][name]" value="${escapeHtml(name)}">
                                <input type="hidden" name="packages[${index}][person_count]" value="${escapeHtml(personCount)}">
                                <input type="hidden" name="packages[${index}][price]" value="${escapeHtml(price)}">
                                <input type="hidden" name="packages[${index}][discount_price]" value="${escapeHtml(discountPrice)}">
                                <input type="hidden" name="packages[${index}][sort_order]" value="${escapeHtml(sortOrder)}">
                                <input type="hidden" name="packages[${index}][short_description]" value="${escapeHtml(shortDescription)}">
                                <input type="hidden" name="packages[${index}][description]" value="${escapeHtml(description)}">
                                <input type="hidden" name="packages[${index}][includes_sankalp]" value="${sankalp}">
                                <input type="hidden" name="packages[${index}][includes_prasad]" value="${prasad}">
                                <input type="hidden" name="packages[${index}][includes_video]" value="${video}">
                                <input type="hidden" name="packages[${index}][includes_certificate]" value="${certificate}">
                                <input type="hidden" name="packages[${index}][is_featured]" value="${featured}">
                                <input type="file" name="packages[${index}][package_image]" class="package-image-input d-none" accept="image/*">
                            </div>`;
                        let $card = $(packageHtml);
                        let selectedFile = $('#package_image')[0].files[0];
                        if (selectedFile) {
                            let dt = new DataTransfer();
                            dt.items.add(selectedFile);
                            $card.find('.package-image-input')[0].files = dt.files;
                        }
                        $('#packageContainer').append($card);
                    }
                    $('#packageModal').modal('hide');
                    resetPackageForm();
                });
                $(document).on('click', '.removePackage', function() {
                    $(this).closest('.package-card').remove();
                    if ($('#packageContainer .package-card').length === 0) {
                        $('#packageContainer').html(`
                            <div id="noPackageMessage" class="text-center py-5">
                                <i class="fas fa-box-open package-empty-icon"></i>
                                <h5 class="mt-3">No packages added yet</h5>
                                <p class="text-muted">Click "Add Package" to create a package for this Puja.</p>
                                <button type="button" class="btn btn-outline-primary" id="addFirstPackageBtn" data-toggle="modal" data-target="#packageModal">
                                    <i class="fas fa-plus"></i> Add First Package
                                </button>
                            </div>`);
                    }
                });
            });
        </script>
        <style>
            .package-card {
                background: #fff;
                transition: 0.2s ease;
            }
            .package-card:hover {
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            }
            .package-card h6 {
                font-weight: 600;
            }
            #packageContainer {
                min-height: 100px;
            }
            #packageModal .modal-header {
                background: #f8f9fa;
            }
            #packageModal .form-group {
                margin-bottom: 15px;
            }
        </style>
    @endpush
    @else
        @php
            abort(403);
        @endphp
@endcan
