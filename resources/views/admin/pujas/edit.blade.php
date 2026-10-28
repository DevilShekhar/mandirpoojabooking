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
                                                    <select name="temple_id" class="form-control @error('temple_id') is-invalid @enderror">
                                                        <option value="">Select Temple</option>
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
                                                            @php
                                                                $packageProducts = $package['products'] ?? [];
                                                                if (is_string($packageProducts)) {
                                                                    $packageProducts = json_decode($packageProducts, true) ?: [];
                                                                }
                                                            @endphp
                                                            <div class="package-card border rounded p-3 mb-3" data-package-index="{{ $index }}">
                                                                <div class="d-flex justify-content-between align-items-start">
                                                                    <div>
                                                                        <h6 class="mb-1 package-title">{{ $package['name'] ?? 'Package' }}</h6>
                                                                        <small class="text-muted package-person-count">{{ $package['person_count'] ?? 1 }} {{ (int)($package['person_count'] ?? 1) === 1 ? 'Person' : 'Persons' }}</small>
                                                                    </div>
                                                                    <div>
                                                                        <button type="button" class="btn btn-outline-primary btn-sm edit-package mr-1"><i class="fas fa-edit"></i></button>
                                                                        <button type="button" class="btn btn-outline-danger btn-sm removePackage"><i class="fas fa-trash"></i></button>
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-3">
                                                                    <div class="col-md-3"><strong>Pandit Price:</strong> <span class="package-pandit-price">₹{{ number_format((float)($package['pandit_price'] ?? 0), 2) }}</span></div>
                                                                    <div class="col-md-3"><strong>Package Total:</strong> <span class="package-price">₹{{ number_format((float)($package['price'] ?? 0), 2) }}</span></div>
                                                                    <div class="col-md-3"><strong>Discount Price:</strong> <span class="package-discount-price">{{ !empty($package['discount_price']) ? '₹'.number_format((float)$package['discount_price'], 2) : '—' }}</span></div>
                                                                    <div class="col-md-3"><strong>Sort Order:</strong> <span class="package-sort-order">{{ $package['sort_order'] ?? 0 }}</span></div>
                                                                </div>
                                                                <div class="mt-2 package-short-description">{{ $package['short_description'] ?? '' }}</div>
                                                                <div class="mt-2 package-badges">@if(!empty($package['is_featured']))<span class="badge badge-dark mr-1">Featured</span>@endif</div>
                                                                <div class="mt-3 package-products-summary">
                                                                    <strong>Selected Products:</strong>
                                                                    @forelse($packageProducts as $product)
                                                                        <span class="badge badge-light border mr-1">{{ $product['name'] ?? ('Product #'.($product['product_id'] ?? '')) }} × {{ $product['quantity'] ?? 1 }} (₹{{ number_format((float)($product['line_total'] ?? (($product['price'] ?? 0) * ($product['quantity'] ?? 1))), 2) }})</span>
                                                                    @empty
                                                                        <span class="text-muted">No products selected</span>
                                                                    @endforelse
                                                                </div>
                                                                <input type="hidden" name="packages[{{ $index }}][name]" value="{{ $package['name'] ?? '' }}">
                                                                <input type="hidden" name="packages[{{ $index }}][person_count]" value="{{ $package['person_count'] ?? 1 }}">
                                                                <input type="hidden" name="packages[{{ $index }}][pandit_price]" value="{{ $package['pandit_price'] ?? 0 }}">
                                                                <input type="hidden" name="packages[{{ $index }}][price]" value="{{ $package['price'] ?? 0 }}">
                                                                <input type="hidden" name="packages[{{ $index }}][discount_price]" value="{{ $package['discount_price'] ?? '' }}">
                                                                <input type="hidden" name="packages[{{ $index }}][sort_order]" value="{{ $package['sort_order'] ?? 0 }}">
                                                                <input type="hidden" name="packages[{{ $index }}][short_description]" value="{{ $package['short_description'] ?? '' }}">
                                                                <input type="hidden" name="packages[{{ $index }}][description]" value="{{ $package['description'] ?? '' }}">
                                                                <input type="hidden" name="packages[{{ $index }}][is_featured]" value="{{ $package['is_featured'] ?? 0 }}">
                                                                @foreach($packageProducts as $productIndex => $product)
                                                                    <input type="hidden" name="packages[{{ $index }}][products][{{ $productIndex }}][product_id]" value="{{ $product['product_id'] ?? $product['id'] ?? '' }}">
                                                                    <input type="hidden" name="packages[{{ $index }}][products][{{ $productIndex }}][quantity]" value="{{ $product['quantity'] ?? 1 }}">
                                                                    <input type="hidden" name="packages[{{ $index }}][products][{{ $productIndex }}][name]" value="{{ $product['name'] ?? '' }}">
                                                                    <input type="hidden" name="packages[{{ $index }}][products][{{ $productIndex }}][price]" value="{{ $product['price'] ?? 0 }}">
                                                                    <input type="hidden" name="packages[{{ $index }}][products][{{ $productIndex }}][unit]" value="{{ $product['unit'] ?? 'piece' }}">
                                                                @endforeach
                                                                @if(!empty($package['package_image']))
                                                                    <input type="hidden" class="existing-package-image" name="packages[{{ $index }}][existing_package_image]" value="{{ $package['package_image'] }}">
                                                                    <div class="mt-2"><small class="text-muted">Current Package Image</small><br><img src="{{ asset('storage/' . $package['package_image']) }}" width="90" height="60" style="object-fit:cover;border-radius:5px;"></div>
                                                                @endif
                                                                <input type="file" name="packages[{{ $index }}][package_image]" class="package-image-input d-none" accept="image/*">
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
                                    <label>Pandit Price <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">₹</span></div>
                                        <input type="number" min="0" step="0.01" class="form-control" id="package_pandit_price" value="0.00" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Calculated Package Total</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">₹</span></div>
                                        <input type="number" min="0" step="0.01" class="form-control" id="package_price" value="0.00" readonly>
                                    </div>
                                    <small class="form-text text-muted">Pandit price + selected product totals.</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="package_image">Package Image</label>
                                    <input type="file" class="form-control-file" id="package_image" accept="image/jpeg,image/png,image/webp">
                                    <small class="form-text text-muted">Leave empty to keep the current image.</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Discount Price</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">₹</span></div>
                                        <input type="number" min="0" step="0.01" class="form-control" id="package_discount_price" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label>Temple Products</label>
                                <small class="form-text text-muted mb-2">Select products from the temple product list and enter quantities.</small>
                                <div class="form-row align-items-end">
                                    <div class="form-group col-md-8">
                                        <label for="package_product_select">Product</label>
                                        <select class="form-control" id="package_product_select">
                                            <option value="">Select product</option>
                                            @foreach(($templeProducts ?? collect()) as $product)
                                                <option value="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-qty="{{ $product->qty ?? 0 }}" data-unit="{{ $product->unit ?? 'piece' }}" data-temple-id="{{ $product->temple_id ?? '' }}">{{ $product->name }} — ₹{{ number_format((float)$product->price, 2) }} / {{ $product->unit ?? 'piece' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="package_product_quantity">Quantity</label>
                                        <input type="number" min="1" step="1" class="form-control" id="package_product_quantity" value="1">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <button type="button" class="btn btn-outline-primary btn-block" id="addPackageProduct"><i class="fas fa-plus"></i> Add</button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm" id="packageProductsTable">
                                        <thead>
                                            <tr><th>Product</th><th>Unit Price</th><th>Quantity</th><th>Total</th><th width="70">Action</th></tr>
                                        </thead>
                                        <tbody id="packageProductsBody">
                                            <tr class="no-products-row"><td colspan="5" class="text-center text-muted">No products selected.</td></tr>
                                        </tbody>
                                        <tfoot>
                                            <tr><th colspan="3" class="text-right">Products Total</th><th colspan="2" id="package_products_total">₹0.00</th></tr>
                                        </tfoot>
                                    </table>
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
        $(function () {
            const templeProducts = @json($templeProducts->values());
            let packageIndex = 0;
            $('#packageContainer .package-card').each(function () {
                let idx = parseInt($(this).attr('data-package-index') ?? $(this).attr('data-index') ?? '-1', 10);
                if (Number.isFinite(idx) && idx >= packageIndex) packageIndex = idx + 1;
                $(this).attr('data-package-index', Number.isFinite(idx) && idx >= 0 ? idx : packageIndex++);
            });
            let editingPackageIndex = null;
            let selectedProducts = [];
            let faqIndex = @json(count($editFaqs));
            const money = value => '₹' + (Number(value || 0)).toFixed(2);
            function escapeHtml(value) {
                return String(value == null ? '' : value).replace(/[&<>"']/g, function (c) {
                    return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c];
                });
            }
            function currentTempleId() { return String($('[name="temple_id"]').val() || ''); }
            function refreshProductOptions() {
                const templeId = currentTempleId();
                const $select = $('#package_product_select');
                const previous = $select.val();
                $select.html('<option value="">Select product</option>');
                templeProducts.filter(p => String(p.temple_id) === templeId).forEach(p => {
                    $select.append($('<option>').val(p.id).text(p.name + ' — ' + money(p.price) + ' / ' + (p.unit || 'piece'))
                        .attr('data-name', p.name).attr('data-price', p.price).attr('data-unit', p.unit || 'piece'));
                });
                if (previous && $select.find('option[value="' + previous + '"]').length) $select.val(previous);
            }
            function productInfo(id, saved) {
                const p = templeProducts.find(item => String(item.id) === String(id));
                return {product_id: String(id), name: p ? p.name : ((saved && saved.name) || ('Product #' + id)), price: p ? Number(p.price) : Number((saved && saved.price) || 0), unit: p ? (p.unit || 'piece') : ((saved && saved.unit) || 'piece')};
            }
            function renderProducts() {
                const $body = $('#packageProductsBody').empty();
                let total = 0;
                if (!selectedProducts.length) $body.append('<tr class="no-products-row"><td colspan="5" class="text-center text-muted">No products selected.</td></tr>');
                selectedProducts.forEach((p, i) => {
                    const line = Number(p.price || 0) * Number(p.quantity || 0); total += line;
                    $body.append('<tr><td>' + escapeHtml(p.name) + ' / ' + escapeHtml(p.unit) + '</td><td>' + money(p.price) + '</td><td><input type="number" min="1" step="1" class="form-control form-control-sm selected-product-quantity" data-index="' + i + '" value="' + Number(p.quantity) + '"></td><td>' + money(line) + '</td><td><button type="button" class="btn btn-sm btn-outline-danger remove-selected-product" data-index="' + i + '">Remove</button></td></tr>');
                });
                $('#package_products_total').text(money(total));
                $('#package_price').val((Number($('#package_pandit_price').val() || 0) + total).toFixed(2));
                return total;
            }
            function addProduct(id, quantity, saved) {
                if (!id || Number(quantity) < 1) return;
                const found = selectedProducts.find(p => String(p.product_id) === String(id));
                if (found) found.quantity = Number(found.quantity) + Number(quantity);
                else selectedProducts.push(Object.assign(productInfo(id, saved), {quantity: Number(quantity)}));
                renderProducts();
            }
            function resetPackageForm() {
                $('#packageModalError').addClass('d-none').empty();
                $('#package_name').val(''); $('#package_person_count').val(1); $('#package_pandit_price').val('0.00');
                $('#package_price').val('0.00'); $('#package_discount_price').val(''); $('#package_sort_order').val(0);
                $('#package_short_description').val(''); $('#package_description').val(''); if ($('#package_image').length) $('#package_image').val('');
                $('#package_product_select').val(''); $('#package_product_quantity').val(1); $('#package_is_featured').prop('checked', false);
                selectedProducts = []; renderProducts(); editingPackageIndex = null;
                $('#packageModalLabel').text('Add Package'); $('#savePackageBtn').html('<i class="fas fa-save"></i> Save Package');
            }
            function cardValue($card, field) { const idx = $card.attr('data-package-index'); return $card.find('input[name="packages[' + idx + '][' + field + ']"]').val() || ''; }
            function setHidden($card, idx, field, value) {
                let $input = $card.find('input[name="packages[' + idx + '][' + field + ']"]');
                if (!$input.length) $input = $('<input type="hidden">').attr('name', 'packages[' + idx + '][' + field + ']').appendTo($card);
                $input.val(value == null ? '' : value);
            }
            function saveProductHidden($card, idx) {
                $card.find('input[name^="packages[' + idx + '][products]"]').remove();
                selectedProducts.forEach((p, i) => {
                    const base = 'packages[' + idx + '][products][' + i + ']';
                    [['product_id',p.product_id],['quantity',p.quantity]].forEach(pair => $card.append($('<input type="hidden">').attr('name', base + '[' + pair[0] + ']').val(pair[1])));
                });
            }
            function packageCardHtml(idx, data) {
                return $('<div class="package-card border rounded p-3 mb-3" data-package-index="' + idx + '">' +
                    '<div class="d-flex justify-content-between align-items-start"><div><h6 class="package-title mb-1"></h6><small class="package-person-count text-muted"></small></div><div><button type="button" class="btn btn-sm btn-outline-primary edit-package mr-1"><i class="fas fa-edit"></i></button><button type="button" class="btn btn-sm btn-outline-danger removePackage"><i class="fas fa-trash"></i></button></div></div>' +
                    '<div class="row mt-3"><div class="col-md-3"><strong>Pandit Price:</strong> <span class="package-pandit-price"></span></div><div class="col-md-3"><strong>Package Total:</strong> <span class="package-price"></span></div><div class="col-md-3"><strong>Discount Price:</strong> <span class="package-discount-price"></span></div><div class="col-md-3"><strong>Sort Order:</strong> <span class="package-sort-order"></span></div></div>' +
                    '<div class="mt-2 package-short-description"></div><div class="mt-3 package-products-summary"></div></div>');
            }
            function renderCard($card, idx, data) {
                ['name','person_count','pandit_price','price','discount_price','sort_order','short_description','description','is_featured'].forEach(field => setHidden($card, idx, field, data[field]));
                $card.find('.package-title').text(data.name);
                $card.find('.package-person-count').text(data.person_count + (Number(data.person_count) === 1 ? ' Person' : ' Persons'));
                $card.find('.package-pandit-price').text(money(data.pandit_price));
                $card.find('.package-price').text(money(data.price));
                $card.find('.package-discount-price').text(data.discount_price !== '' && data.discount_price != null ? money(data.discount_price) : '—');
                $card.find('.package-sort-order').text(data.sort_order || 0);
                $card.find('.package-short-description').text(data.short_description || '');
                const summary = selectedProducts.length ? selectedProducts.map(p => '<span class="badge badge-light border mr-1">' + escapeHtml(p.name) + ' × ' + p.quantity + ' (' + money(Number(p.price) * Number(p.quantity)) + ')</span>').join(' ') : '<span class="text-muted">No products selected</span>';
                $card.find('.package-products-summary').html('<strong>Selected Products:</strong> ' + summary);
                saveProductHidden($card, idx);
                let $file = $card.find('input.package-image-input');
                if (!$file.length) $file = $('<input type="file" class="package-image-input d-none" accept="image/*">').appendTo($card);
                $file.attr('name', 'packages[' + idx + '][package_image]');
                if (data.existing_package_image) {
                    let $existing = $card.find('input[name="packages[' + idx + '][existing_package_image]"]');
                    if (!$existing.length) $existing = $('<input type="hidden">').attr('name','packages[' + idx + '][existing_package_image]').appendTo($card);
                    $existing.val(data.existing_package_image);
                }
                if (data.file) { const dt = new DataTransfer(); dt.items.add(data.file); $file[0].files = dt.files; }
            }
            refreshProductOptions(); renderProducts();
            $('[name="temple_id"]').on('change', function() { refreshProductOptions(); });
            $('#addPackageProduct').on('click', function() {
                const id = $('#package_product_select').val(); const qty = parseInt($('#package_product_quantity').val(), 10);
                if (!id) { $('#packageModalError').text('Please select a product.').removeClass('d-none'); return; }
                if (!qty || qty < 1) { $('#packageModalError').text('Quantity must be at least 1.').removeClass('d-none'); return; }
                addProduct(id, qty); $('#package_product_select').val(''); $('#package_product_quantity').val(1); $('#packageModalError').addClass('d-none').empty();
            });
            $(document).on('change', '.selected-product-quantity', function() { const i = Number($(this).data('index')); const q = parseInt($(this).val(),10); if (selectedProducts[i] && q > 0) { selectedProducts[i].quantity = q; renderProducts(); } });
            $(document).on('click', '.remove-selected-product', function() { selectedProducts.splice(Number($(this).data('index')),1); renderProducts(); });
            $('#package_pandit_price').on('input change', renderProducts);
            $(document).on('click', '#addPackageBtn, #addFirstPackageBtn', function(e) { e.preventDefault(); resetPackageForm(); refreshProductOptions(); $('#packageModal').modal('show'); });
            $(document).on('click', '.edit-package', function() {
                const $card = $(this).closest('.package-card'); editingPackageIndex = String($card.attr('data-package-index'));
                $('#packageModalLabel').text('Edit Package'); $('#savePackageBtn').html('<i class="fas fa-save"></i> Update Package');
                $('#package_name').val(cardValue($card,'name')); $('#package_person_count').val(cardValue($card,'person_count') || 1);
                $('#package_pandit_price').val(cardValue($card,'pandit_price') || 0); $('#package_discount_price').val(cardValue($card,'discount_price'));
                $('#package_sort_order').val(cardValue($card,'sort_order') || 0); $('#package_short_description').val(cardValue($card,'short_description'));
                $('#package_description').val(cardValue($card,'description')); $('#package_is_featured').prop('checked', cardValue($card,'is_featured') == '1');
                $('#packageModalError').addClass('d-none').empty(); selectedProducts = [];
                const grouped = {};
                $card.find('input[name^="packages[' + editingPackageIndex + '][products]"]').each(function() {
                    const m = $(this).attr('name').match(/\[products\]\[(\d+)\]\[(product_id|quantity|name|price|unit)\]/); if (!m) return;
                    grouped[m[1]] = grouped[m[1]] || {}; grouped[m[1]][m[2]] = $(this).val();
                });
                Object.keys(grouped).sort((a,b)=>Number(a)-Number(b)).forEach(k => { const p = grouped[k]; addProduct(p.product_id, p.quantity || 1, p); });
                if ($('#package_image').length) $('#package_image').val(''); renderProducts(); $('#packageModal').modal('show');
            });
            $('#savePackageBtn').on('click', function() {
                const name = $.trim($('#package_name').val()); const person = parseInt($('#package_person_count').val(),10);
                const pandit = Number($('#package_pandit_price').val()); const productTotal = renderProducts(); const price = Number($('#package_price').val());
                const discount = $('#package_discount_price').val(); const sort = $('#package_sort_order').val() || 0;
                const shortDesc = $('#package_short_description').val(); const description = $('#package_description').val(); const featured = $('#package_is_featured').is(':checked') ? 1 : 0;
                const errors = []; if (!name) errors.push('Package name is required.'); if (!person || person < 1) errors.push('Person count must be at least 1.');
                if (!Number.isFinite(pandit) || pandit < 0) errors.push('Pandit price must be zero or greater.');
                if (discount !== '' && (!Number.isFinite(Number(discount)) || Number(discount) < 0 || Number(discount) > price)) errors.push('Discount price cannot exceed the calculated package total.');
                if (errors.length) { $('#packageModalError').html(errors.map(escapeHtml).join('<br>')).removeClass('d-none'); return; }
                $('#noPackageMessage').remove();
                let idx = editingPackageIndex;
                let $card;
                let existingImage = '';
                if (idx !== null) {
                    $card = $('.package-card[data-package-index="' + idx + '"]');
                    existingImage = $card.find('input[name="packages[' + idx + '][existing_package_image]"]').val() || '';
                    const oldFile = $card.find('input.package-image-input')[0];
                    const packageImageInput = $('#package_image')[0];
                    if ((!packageImageInput || !packageImageInput.files.length) && oldFile && oldFile.files && oldFile.files.length) {
                        const dt = new DataTransfer();
                        dt.items.add(oldFile.files[0]);
                        var preservedFile = dt.files[0];
                    }
                } else {
                    idx = packageIndex++;
                    $card = packageCardHtml(idx);
                    $('#packageContainer').append($card);
                }
                const packageImageInput = $('#package_image')[0];
                const file = (packageImageInput && packageImageInput.files.length ? packageImageInput.files[0] : null) || (typeof preservedFile !== 'undefined' ? preservedFile : null);
                $card.find('input[name^="packages[' + idx + ']" ]').not('[type="file"]').remove();
                $card.find('input.package-image-input').remove();
                $card.find('input[name="packages[' + idx + '][existing_package_image]"]').remove();
                renderCard($card, idx, {name:name,person_count:person,pandit_price:pandit.toFixed(2),price:price.toFixed(2),discount_price:discount,sort_order:sort,short_description:shortDesc,description:description,is_featured:featured,existing_package_image:existingImage,file:file});
                $('#packageModal').modal('hide'); resetPackageForm();
            });
            $(document).on('click', '.removePackage, .remove-package', function() { $(this).closest('.package-card').remove(); if (!$('#packageContainer .package-card').length) $('#packageContainer').html('<div id="noPackageMessage" class="text-center py-5"><i class="fas fa-box-open package-empty-icon"></i><h5 class="mt-3">No packages added yet</h5><p class="text-muted">Click Add Package to create a package.</p><button type="button" class="btn btn-outline-primary" id="addFirstPackageBtn"><i class="fas fa-plus"></i> Add First Package</button></div>'); });
            $('#addFaq').on('click', function() { const html = '<div class="faq-item border rounded p-3 mb-3"><div class="row"><div class="form-group col-md-5"><label>Question *</label><input type="text" name="faq['+faqIndex+'][question]" class="form-control" required placeholder="Enter FAQ question"></div><div class="form-group col-md-6"><label>Answer *</label><textarea name="faq['+faqIndex+'][answer]" class="form-control" rows="3" required placeholder="Enter FAQ answer"></textarea></div><div class="form-group col-md-1"><label>&nbsp;</label><button type="button" class="btn btn-danger btn-sm removeFaq"><i class="fas fa-trash"></i></button></div></div></div>'; $('#faqContainer').append(html); faqIndex++; });
            $(document).on('click', '.removeFaq', function() { $(this).closest('.faq-item').remove(); });
            const $description = $('#puja_description');
            if ($description.length && typeof $.fn.summernote === 'function' && !$description.next('.note-editor').length) $description.summernote({height:200,callbacks:{onChange:function(contents){$description.val(contents);}}});
            $('#pujaEditForm').on('submit', function() { if ($description.length && typeof $.fn.summernote === 'function' && $description.next('.note-editor').length) $description.val($description.summernote('code')); });
        });
        </script>
        <style>.package-card{background:#fff;transition:.2s ease}.package-card:hover{box-shadow:0 2px 8px rgba(0,0,0,.08)}#packageContainer{min-height:100px}#packageModal .modal-header{background:#f8f9fa}#packageModal .form-group{margin-bottom:15px}</style>
    @endpush
    @else
        @php
            abort(403);
        @endphp
@endcan
