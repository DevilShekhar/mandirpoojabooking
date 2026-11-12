@can('online-puja.create')
@extends('admin.layouts.app')

@section('title', 'Add Online Puja')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Add New Online Puja</h4>
                    </div>

                    <form action="{{ route('online-pujas.store') }}" method="POST" enctype="multipart/form-data" id="onlinePujaForm">
                        @csrf

                        @if(session('Success'))
                            <div class="alert alert-success alert-dismissible fade show m-3">
                                {{ session('Success') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        @if(session('Error'))
                            <div class="alert alert-danger alert-dismissible fade show m-3">
                                <strong>Warning:</strong> {{ session('Error') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger m-3">
                                <strong>Please correct the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="card-body">
                            <div id="wizard_horizontal">

                                {{-- STEP 1: BASIC INFORMATION --}}
                                <h2>Basic Information</h2>
                                <section>
                                    <div class="row">

                                        <div class="form-group col-md-6">
                                            <label>Online Puja Category <span class="text-danger">*</span></label>
                                            <select name="online_puja_category_id"
                                                class="form-control @error('online_puja_category_id') is-invalid @enderror">
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('online_puja_category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('online_puja_category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Puja Title <span class="text-danger">*</span></label>
                                            <input type="text" name="title"
                                                value="{{ old('title') }}"
                                                class="form-control @error('title') is-invalid @enderror"
                                                placeholder="Enter online puja title"
                                                id="puja_title">
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- AUTO GENERATED SLUG --}}
                                        <div class="form-group col-md-12">
                                            <label>Slug</label>
                                            <input type="text"
                                                name="slug"
                                                id="puja_slug"
                                                value="{{ old('slug') }}"
                                                class="form-control @error('slug') is-invalid @enderror"
                                                placeholder="Slug will be generated automatically"
                                                readonly>
                                            <small class="text-muted">
                                                Slug is automatically generated from the Puja Title.
                                            </small>
                                            @error('slug')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Status</label>
                                            <select name="status" class="form-control">
                                                <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Short Description</label>
                                            <textarea name="short_description"
                                                class="form-control @error('short_description') is-invalid @enderror"
                                                rows="4"
                                                placeholder="Enter short description">{{ old('short_description') }}</textarea>
                                            @error('short_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Thumbnail Image</label>
                                            <input type="file" name="thumbnail_image"
                                                class="form-control @error('thumbnail_image') is-invalid @enderror"
                                                accept="image/*">
                                            <small class="text-muted">Upload online puja thumbnail.</small>
                                            @error('thumbnail_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Banner Image</label>
                                            <input type="file" name="banner_image"
                                                class="form-control @error('banner_image') is-invalid @enderror"
                                                accept="image/*">
                                            <small class="text-muted">Upload online puja banner.</small>
                                            @error('banner_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Gallery Images</label>
                                            <input type="file" name="gallery_images[]"
                                                class="form-control"
                                                accept="image/*" multiple>
                                            <small class="text-muted">You can select multiple images.</small>
                                        </div>

                                    </div>
                                </section>

                                {{-- STEP 2: PUJA DETAILS --}}
                                <h2>Puja Details</h2>
                                <section>
                                    <div class="row">

                                        <div class="form-group col-md-12">
                                            <label>Puja Significance</label>
                                            <textarea name="puja_significance"
                                                class="form-control summernote @error('puja_significance') is-invalid @enderror"
                                                rows="5"
                                                placeholder="Enter puja significance">{{ old('puja_significance') }}</textarea>
                                            @error('puja_significance')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Puja Benefits</label>
                                            <textarea name="puja_benefits"
                                                class="form-control summernote @error('puja_benefits') is-invalid @enderror"
                                                rows="5"
                                                placeholder="Enter puja benefits">{{ old('puja_benefits') }}</textarea>
                                            @error('puja_benefits')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Puja Procedure</label>
                                            <textarea name="puja_procedure"
                                                class="form-control summernote @error('puja_procedure') is-invalid @enderror"
                                                rows="5"
                                                placeholder="Enter puja procedure">{{ old('puja_procedure') }}</textarea>
                                            @error('puja_procedure')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>What You Will Receive</label>
                                            <textarea name="what_you_will_receive"
                                                class="form-control summernote @error('what_you_will_receive') is-invalid @enderror"
                                                rows="5"
                                                placeholder="Enter what devotees will receive">{{ old('what_you_will_receive') }}</textarea>
                                            @error('what_you_will_receive')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                </section>

                                {{-- STEP 3: TEMPLE INFORMATION --}}
                                <h2>Temple Information</h2>
                                <section>
                                    <div class="row">

                                        <div class="form-group col-md-6">
                                            <label>Temple Name</label>
                                            <input type="text" name="temple_name"
                                                value="{{ old('temple_name') }}"
                                                class="form-control"
                                                placeholder="Enter temple name">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Temple Location</label>
                                            <input type="text" name="temple_location"
                                                value="{{ old('temple_location') }}"
                                                class="form-control"
                                                placeholder="Enter temple location">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Temple City</label>
                                            <input type="text" name="temple_city"
                                                value="{{ old('temple_city') }}"
                                                class="form-control"
                                                placeholder="Enter city">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Temple State</label>
                                            <input type="text" name="temple_state"
                                                value="{{ old('temple_state') }}"
                                                class="form-control"
                                                placeholder="Enter state">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Temple Address</label>
                                            <textarea name="temple_address"
                                                class="form-control"
                                                rows="3"
                                                placeholder="Enter complete temple address">{{ old('temple_address') }}</textarea>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Temple Description</label>
                                            <textarea name="temple_description"
                                                class="form-control"
                                                rows="5"
                                                placeholder="Enter temple description">{{ old('temple_description') }}</textarea>
                                        </div>

                                    </div>
                                </section>

                                {{-- STEP 4: PUJA SCHEDULE --}}
                                <h2>Puja Schedule</h2>
                                <section>
                                    <div class="row">

                                        <div class="form-group col-md-6">
                                            <label>Puja Date & Time</label>
                                            <input type="datetime-local"
                                                name="puja_datetime"
                                                value="{{ old('puja_datetime') }}"
                                                class="form-control">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Puja Day</label>
                                            <input type="text" name="puja_day"
                                                value="{{ old('puja_day') }}"
                                                class="form-control"
                                                placeholder="Example: Monday">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Tithi</label>
                                            <input type="text" name="tithi"
                                                value="{{ old('tithi') }}"
                                                class="form-control"
                                                placeholder="Enter tithi">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Muhurat</label>
                                            <input type="text" name="muhurat"
                                                value="{{ old('muhurat') }}"
                                                class="form-control"
                                                placeholder="Example: 06:00 AM - 08:00 AM">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Muhurat End At</label>
                                            <input type="datetime-local"
                                                name="muhurat_end_at"
                                                value="{{ old('muhurat_end_at') }}"
                                                class="form-control">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Booking Start At</label>
                                            <input type="datetime-local"
                                                name="booking_start_at"
                                                value="{{ old('booking_start_at') }}"
                                                class="form-control">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Booking End At</label>
                                            <input type="datetime-local"
                                                name="booking_end_at"
                                                value="{{ old('booking_end_at') }}"
                                                class="form-control">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Total Seats</label>
                                            <input type="number" min="0"
                                                name="total_seat"
                                                value="{{ old('total_seat', 0) }}"
                                                class="form-control"
                                                placeholder="Enter total seats">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Booked Seats</label>
                                            <input type="number" min="0"
                                                name="booked_seat"
                                                value="{{ old('booked_seat', 0) }}"
                                                class="form-control"
                                                placeholder="Enter booked seats">
                                        </div>

                                    </div>
                                </section>

                                {{-- STEP 5: VIDEO & DELIVERY --}}
                                <h2>Video & Delivery</h2>
                                <section>
                                    <div class="row">

                                        <div class="form-group col-md-6">
                                            <label>Video Delivery Hours</label>
                                            <input type="number" min="0"
                                                name="video_delivery_hours"
                                                value="{{ old('video_delivery_hours') }}"
                                                class="form-control"
                                                placeholder="Example: 24">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>WhatsApp Delivery Enabled</label>
                                            <select name="whatsapp_delivery_enabled"
                                                class="form-control">
                                                <option value="1" {{ old('whatsapp_delivery_enabled', 0) == 1 ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ old('whatsapp_delivery_enabled', 0) == 0 ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Video URL</label>
                                            <input type="url" name="video_url"
                                                value="{{ old('video_url') }}"
                                                class="form-control"
                                                placeholder="Enter video URL">
                                        </div>

                                    </div>
                                </section>

                                {{-- STEP 6: PACKAGES --}}
                                <h2>Packages</h2>
                                <section>
                                    <div class="card">

                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <div>
                                                <h4 class="mb-0">Online Puja Packages</h4>
                                                <small class="text-muted">Add one or more packages.</small>
                                            </div>

                                            <button type="button" class="btn btn-primary" id="addPackageBtn">
                                                <i class="fas fa-plus"></i> Add Package
                                            </button>
                                        </div>

                                        <div class="card-body">

                                            <div id="noPackageMessage"
                                                class="text-center py-5 {{ old('packages') ? 'd-none' : '' }}">

                                                <i class="fas fa-box-open package-empty-icon"></i>

                                                <h5 class="mt-3">No packages added yet</h5>

                                                <p class="text-muted">Click Add Package to create a package.</p>

                                                <button type="button"
                                                    class="btn btn-outline-primary"
                                                    id="addFirstPackageBtn">
                                                    <i class="fas fa-plus"></i> Add First Package
                                                </button>
                                            </div>

                                            <div id="packageContainer">

                                                @foreach(old('packages', []) as $index => $package)

                                                    <div class="package-card border rounded p-3 mb-3"
                                                        data-package-index="{{ $index }}">

                                                        @foreach(['package_name', 'devotees_count', 'price', 'description', 'is_recommended', 'status', 'sort_order'] as $field)
                                                            <input type="hidden"
                                                                name="packages[{{ $index }}][{{ $field }}]"
                                                                value="{{ $package[$field] ?? '' }}">
                                                        @endforeach

                                                        <div class="d-flex justify-content-between align-items-start">

                                                            <div>
                                                                <h6 class="package-title mb-1">
                                                                    {{ $package['package_name'] ?? 'Package' }}
                                                                </h6>

                                                                <small class="package-person-count text-muted">
                                                                    {{ $package['devotees_count'] ?? 1 }} Devotees
                                                                </small>
                                                            </div>

                                                            <div>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-outline-primary edit-package mr-1">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>

                                                                <button type="button"
                                                                    class="btn btn-sm btn-outline-danger removePackage">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>

                                                        </div>

                                                        <div class="row mt-3">

                                                            <div class="col-md-4">
                                                                <strong>Price:</strong>
                                                                <span class="package-price">
                                                                    ₹{{ number_format((float)($package['price'] ?? 0), 2) }}
                                                                </span>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <strong>Status:</strong>
                                                                <span class="package-status">
                                                                    {{ ($package['status'] ?? 1) == 1 ? 'Active' : 'Inactive' }}
                                                                </span>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <strong>Sort Order:</strong>
                                                                <span class="package-sort-order">
                                                                    {{ $package['sort_order'] ?? 0 }}
                                                                </span>
                                                            </div>

                                                        </div>

                                                        <div class="mt-2 package-short-description">
                                                            {{ $package['description'] ?? '' }}
                                                        </div>

                                                        <div class="mt-2 package-featured">
                                                            {{ ($package['is_recommended'] ?? 0) == 1 ? 'Recommended Package' : '' }}
                                                        </div>

                                                    </div>

                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </section>

                                {{-- STEP 7: FAQ --}}
                                <h2>FAQ</h2>
                                <section>
                                    <div class="row">

                                        <div class="col-md-12">

                                            <div class="d-flex justify-content-between align-items-center mb-3">

                                                <div>
                                                    <h6 class="mb-1">Frequently Asked Questions</h6>

                                                    <small class="text-muted">
                                                        Add common questions and answers related to this online puja.
                                                    </small>
                                                </div>

                                                <button type="button"
                                                    class="btn btn-primary btn-sm"
                                                    id="addFaq">
                                                    <i class="fas fa-plus"></i> Add FAQ
                                                </button>

                                            </div>

                                            <div id="faqContainer">

                                                @foreach(old('faq', []) as $index => $faq)

                                                    <div class="faq-item border rounded p-3 mb-3">

                                                        <div class="row">

                                                            <div class="form-group col-md-5">
                                                                <label>Question <span class="text-danger">*</span></label>

                                                                <input type="text"
                                                                    name="faq[{{ $index }}][question]"
                                                                    value="{{ $faq['question'] ?? '' }}"
                                                                    class="form-control"
                                                                    placeholder="Enter FAQ question">
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label>Answer <span class="text-danger">*</span></label>

                                                                <textarea name="faq[{{ $index }}][answer]"
                                                                    class="form-control"
                                                                    rows="3"
                                                                    placeholder="Enter FAQ answer">{{ $faq['answer'] ?? '' }}</textarea>
                                                            </div>

                                                            <div class="form-group col-md-1">
                                                                <label>&nbsp;</label>

                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm removeFaq">
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

                                {{-- STEP 8: SEO & SETTINGS --}}
                                <h2>SEO & Settings</h2>
                                <section>
                                    <div class="row">

                                        <div class="form-group col-md-12">
                                            <label>Meta Title</label>
                                            <input type="text" name="meta_title"
                                                value="{{ old('meta_title') }}"
                                                class="form-control"
                                                placeholder="Enter meta title">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Meta Description</label>
                                            <textarea name="meta_description"
                                                class="form-control"
                                                rows="3"
                                                placeholder="Enter meta description">{{ old('meta_description') }}</textarea>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Meta Keywords</label>
                                            <textarea name="meta_keywords"
                                                class="form-control"
                                                rows="3"
                                                placeholder="Enter meta keywords">{{ old('meta_keywords') }}</textarea>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label>Featured Puja</label>
                                            <select name="is_featured" class="form-control">
                                                <option value="1" {{ old('is_featured', 0) == 1 ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ old('is_featured', 0) == 0 ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label>Popular Puja</label>
                                            <select name="is_popular" class="form-control">
                                                <option value="1" {{ old('is_popular', 0) == 1 ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ old('is_popular', 0) == 0 ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label>Sort Order</label>
                                            <input type="number" min="0"
                                                name="sort_order"
                                                value="{{ old('sort_order', 0) }}"
                                                class="form-control"
                                                placeholder="Enter sort order">
                                        </div>

                                    </div>
                                </section>

                                {{-- STEP 9: REVIEW --}}
                                <h2>Review & Submit</h2>
                                <section>
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="alert alert-info">
                                                <strong>Review Your Online Puja Details</strong><br>
                                                Please verify all information before submitting.
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="table-responsive">

                                                <table class="table table-bordered table-striped">
                                                    <tbody>

                                                        <tr>
                                                            <th width="30%">Puja Title</th>
                                                            <td id="review_title">{{ old('title') ?: 'Puja Title' }}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Slug</th>
                                                            <td id="review_slug">{{ old('slug') ?: 'Not specified' }}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Temple Name</th>
                                                            <td id="review_temple">{{ old('temple_name') ?: 'Not specified' }}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Temple City</th>
                                                            <td id="review_city">{{ old('temple_city') ?: 'Not specified' }}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Puja Date</th>
                                                            <td id="review_datetime">{{ old('puja_datetime') ?: 'Not specified' }}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Total Seats</th>
                                                            <td id="review_seats">{{ old('total_seat', 0) }}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Status</th>
                                                            <td id="review_status">{{ old('status', 1) == 1 ? 'Active' : 'Inactive' }}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Featured</th>
                                                            <td>{{ old('is_featured', 0) == 1 ? 'Yes' : 'No' }}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Popular</th>
                                                            <td>{{ old('is_popular', 0) == 1 ? 'Yes' : 'No' }}</td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>

                                        <div class="col-md-12 text-right mt-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Save Online Puja
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

{{-- PACKAGE MODAL --}}
<div class="modal fade" id="packageModal" tabindex="-1" role="dialog"
    aria-labelledby="packageModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="packageModalLabel">Add Package</h5>

                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div id="packageModalError" class="alert alert-danger d-none"></div>

                <div class="row">

                    <div class="form-group col-md-8">
                        <label>Package Name <span class="text-danger">*</span></label>

                        <input type="text" class="form-control"
                            id="package_name"
                            placeholder="Example: Individual / Couple / Family">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Devotees Count <span class="text-danger">*</span></label>

                        <input type="number" min="1"
                            class="form-control"
                            id="package_person_count"
                            value="1">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Package Price <span class="text-danger">*</span></label>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">₹</span>
                            </div>

                            <input type="number" min="0" step="0.01"
                                class="form-control"
                                id="package_price"
                                value="0.00">
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Sort Order</label>

                        <input type="number" min="0"
                            class="form-control"
                            id="package_sort_order"
                            value="0">
                    </div>

                    <div class="form-group col-md-12">
                        <label>Description</label>

                        <textarea class="form-control"
                            id="package_description"
                            rows="4"
                            placeholder="Enter package description"></textarea>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Status</label>

                        <select class="form-control" id="package_status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Recommended Package</label>

                        <select class="form-control" id="package_is_featured">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Cancel
                </button>

                <button type="button" class="btn btn-primary" id="savePackageBtn">
                    <i class="fas fa-save"></i> Save Package
                </button>

            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function ($) {
    'use strict';

    $(function () {

        /* =====================================================
           AUTOMATIC SLUG GENERATION
        ===================================================== */

        function generateSlug(value) {

            return value
                .toString()
                .toLowerCase()
                .trim()
                .replace(/[\s\W-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        function updateSlug() {

            let title = $('#puja_title').val();

            let slug = generateSlug(title);

            $('#puja_slug').val(slug);

            $('#review_slug').text(slug || 'Not specified');
        }

        // Generate slug whenever title changes.
        $('#puja_title').on('input keyup change', function () {
            updateSlug();
            updateReview();
        });

        // Generate slug on page load.
        updateSlug();


        /* =====================================================
           SUMMERNOTE EDITOR
        ===================================================== */

        function initializeSummernote() {

            if (!$.fn.summernote) {
                console.error('Summernote library is not loaded.');
                return;
            }

            $('.summernote').each(function () {

                if (!$(this).next('.note-editor').length) {

                    $(this).summernote({
                        height: 250,
                        minHeight: 200,
                        maxHeight: 500,
                        placeholder: 'Enter details here...',
                        dialogsInBody: true,

                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'italic', 'underline', 'clear']],
                            ['fontname', ['fontname']],
                            ['fontsize', ['fontsize']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['height', ['height']],
                            ['insert', ['link', 'picture', 'video', 'table']],
                            ['view', ['fullscreen', 'codeview', 'help']]
                        ]
                    });
                }
            });
        }

        initializeSummernote();

        function syncSummernote() {

            $('.summernote').each(function () {

                if ($(this).next('.note-editor').length) {
                    $(this).val($(this).summernote('code'));
                }
            });
        }

        $('#onlinePujaForm').on('submit', function () {
            syncSummernote();
        });


        /* =====================================================
           PACKAGE MANAGEMENT
        ===================================================== */

        let packageIndex = 0;
        let editingPackage = null;

        function escapeHtml(value) {
            return $('<div>').text(value == null ? '' : value).html();
        }

        function getPackageValue(card, field) {
            return card.find('input[name$="[' + field + ']"]').val() || '';
        }

        function updatePackageVisibility() {

            if ($('#packageContainer .package-card').length > 0) {
                $('#noPackageMessage').addClass('d-none');
            } else {
                $('#noPackageMessage').removeClass('d-none');
            }
        }

        $('#packageContainer .package-card').each(function () {

            let index = parseInt($(this).attr('data-package-index'), 10);

            if (!isNaN(index) && index >= packageIndex) {
                packageIndex = index + 1;
            }
        });

        function resetPackageModal() {

            editingPackage = null;

            $('#packageModalLabel').text('Add Package');

            $('#packageModalError').addClass('d-none').html('');

            $('#package_name').val('');
            $('#package_person_count').val(1);
            $('#package_price').val('0.00');
            $('#package_sort_order').val(0);
            $('#package_description').val('');
            $('#package_status').val('1');
            $('#package_is_featured').val('0');
        }

        $('#addPackageBtn, #addFirstPackageBtn').on('click', function (e) {

            e.preventDefault();

            resetPackageModal();

            $('#packageModal').modal('show');
        });

        $('#savePackageBtn').on('click', function (e) {

            e.preventDefault();

            let packageName = $('#package_name').val().trim();

            let devoteesCount = $('#package_person_count').val();

            let price = $('#package_price').val();

            let errors = [];

            if (!packageName) {
                errors.push('Package name is required.');
            }

            if (!devoteesCount || parseInt(devoteesCount, 10) < 1) {
                errors.push('Devotees count must be at least 1.');
            }

            if (price === '' || isNaN(parseFloat(price)) || parseFloat(price) < 0) {
                errors.push('Please enter a valid package price.');
            }

            if (errors.length) {

                $('#packageModalError')
                    .removeClass('d-none')
                    .html(errors.map(escapeHtml).join('<br>'));

                return;
            }

            let packageData = {

                package_name: packageName,

                devotees_count: parseInt(devoteesCount, 10),

                price: parseFloat(price).toFixed(2),

                description: $('#package_description').val().trim(),

                status: $('#package_status').val(),

                is_recommended: $('#package_is_featured').val(),

                sort_order: $('#package_sort_order').val() || 0
            };

            let index = editingPackage !== null
                ? editingPackage
                : packageIndex++;

            let statusText = packageData.status == 1
                ? 'Active'
                : 'Inactive';

            let recommendedText = packageData.is_recommended == 1
                ? 'Recommended Package'
                : '';

            let packageHtml = `
                <div class="package-card border rounded p-3 mb-3"
                    data-package-index="${index}">

                    <input type="hidden"
                        name="packages[${index}][package_name]"
                        value="${escapeHtml(packageData.package_name)}">

                    <input type="hidden"
                        name="packages[${index}][devotees_count]"
                        value="${packageData.devotees_count}">

                    <input type="hidden"
                        name="packages[${index}][price]"
                        value="${packageData.price}">

                    <input type="hidden"
                        name="packages[${index}][description]"
                        value="${escapeHtml(packageData.description)}">

                    <input type="hidden"
                        name="packages[${index}][is_recommended]"
                        value="${packageData.is_recommended}">

                    <input type="hidden"
                        name="packages[${index}][status]"
                        value="${packageData.status}">

                    <input type="hidden"
                        name="packages[${index}][sort_order]"
                        value="${escapeHtml(packageData.sort_order)}">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <h6 class="package-title mb-1">
                                ${escapeHtml(packageData.package_name)}
                            </h6>

                            <small class="package-person-count text-muted">
                                ${packageData.devotees_count} Devotees
                            </small>
                        </div>

                        <div>
                            <button type="button"
                                class="btn btn-sm btn-outline-primary edit-package mr-1">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button type="button"
                                class="btn btn-sm btn-outline-danger removePackage">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                    </div>

                    <div class="row mt-3">

                        <div class="col-md-4">
                            <strong>Price:</strong>
                            <span class="package-price">₹${packageData.price}</span>
                        </div>

                        <div class="col-md-4">
                            <strong>Status:</strong>
                            <span class="package-status">${statusText}</span>
                        </div>

                        <div class="col-md-4">
                            <strong>Sort Order:</strong>
                            <span class="package-sort-order">
                                ${escapeHtml(packageData.sort_order)}
                            </span>
                        </div>

                    </div>

                    <div class="mt-2 package-short-description">
                        ${escapeHtml(packageData.description)}
                    </div>

                    <div class="mt-2 package-featured">
                        ${recommendedText}
                    </div>

                </div>
            `;

            if (editingPackage !== null) {

                $('#packageContainer .package-card[data-package-index="' + index + '"]')
                    .replaceWith(packageHtml);

            } else {

                $('#packageContainer').append(packageHtml);
            }

            updatePackageVisibility();

            $('#packageModal').modal('hide');

            resetPackageModal();
        });

        $(document).on('click', '.edit-package', function (e) {

            e.preventDefault();

            let card = $(this).closest('.package-card');

            editingPackage = String(card.attr('data-package-index'));

            $('#packageModalLabel').text('Edit Package');

            $('#packageModalError').addClass('d-none').html('');

            $('#package_name').val(getPackageValue(card, 'package_name'));

            $('#package_person_count').val(getPackageValue(card, 'devotees_count'));

            $('#package_price').val(getPackageValue(card, 'price'));

            $('#package_description').val(getPackageValue(card, 'description'));

            $('#package_status').val(getPackageValue(card, 'status'));

            $('#package_is_featured').val(getPackageValue(card, 'is_recommended'));

            $('#package_sort_order').val(getPackageValue(card, 'sort_order'));

            $('#packageModal').modal('show');
        });

        $(document).on('click', '.removePackage', function (e) {

            e.preventDefault();

            if (!confirm('Are you sure you want to remove this package?')) {
                return;
            }

            $(this).closest('.package-card').remove();

            updatePackageVisibility();
        });

        $('#packageModal').on('hidden.bs.modal', function () {
            resetPackageModal();
        });

        updatePackageVisibility();


        /* =====================================================
           FAQ MANAGEMENT
        ===================================================== */

        let faqIndex = 0;

        $('#faqContainer .faq-item').each(function () {

            $(this).find('input, textarea').each(function () {

                let name = $(this).attr('name');

                if (name) {

                    let match = name.match(/^faq\[(\d+)\]/);

                    if (match) {

                        let index = parseInt(match[1], 10);

                        if (index >= faqIndex) {
                            faqIndex = index + 1;
                        }
                    }
                }
            });
        });

        $('#addFaq').on('click', function (e) {

            e.preventDefault();

            let faqHtml = `
                <div class="faq-item border rounded p-3 mb-3">

                    <div class="row">

                        <div class="form-group col-md-5">
                            <label>Question <span class="text-danger">*</span></label>

                            <input type="text"
                                name="faq[${faqIndex}][question]"
                                class="form-control"
                                placeholder="Enter FAQ question">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Answer <span class="text-danger">*</span></label>

                            <textarea name="faq[${faqIndex}][answer]"
                                class="form-control"
                                rows="3"
                                placeholder="Enter FAQ answer"></textarea>
                        </div>

                        <div class="form-group col-md-1">
                            <label>&nbsp;</label>

                            <button type="button"
                                class="btn btn-danger btn-sm removeFaq">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                    </div>
                </div>
            `;

            $('#faqContainer').append(faqHtml);

            faqIndex++;
        });

        $(document).on('click', '.removeFaq', function (e) {

            e.preventDefault();

            if (!confirm('Are you sure you want to remove this FAQ?')) {
                return;
            }

            $(this).closest('.faq-item').remove();
        });


        /* =====================================================
           REVIEW SECTION
        ===================================================== */

        function updateReview() {

            $('#review_title').text(
                $('input[name="title"]').val() || 'Puja Title'
            );

            $('#review_slug').text(
                $('#puja_slug').val() || 'Not specified'
            );

            $('#review_temple').text(
                $('input[name="temple_name"]').val() || 'Not specified'
            );

            $('#review_city').text(
                $('input[name="temple_city"]').val() || 'Not specified'
            );

            $('#review_datetime').text(
                $('input[name="puja_datetime"]').val() || 'Not specified'
            );

            $('#review_seats').text(
                $('input[name="total_seat"]').val() || '0'
            );

            $('#review_status').text(
                $('select[name="status"]').val() == 1
                    ? 'Active'
                    : 'Inactive'
            );
        }

        $(
            'input[name="temple_name"], ' +
            'input[name="temple_city"], ' +
            'input[name="puja_datetime"], ' +
            'input[name="total_seat"], ' +
            'select[name="status"]'
        ).on('input change', updateReview);

        updateReview();


        /* =====================================================
           ORIGINAL JQUERY STEPS WIZARD
        ===================================================== */

        if ($.fn.steps && !$('#wizard_horizontal').data('steps')) {

            $('#wizard_horizontal').steps({

                headerTag: 'h2',

                bodyTag: 'section',

                transitionEffect: 'slideLeft',

                autoFocus: true,

                enableAllSteps: false,

                enablePagination: true,

                titleTemplate: '<span class="number">#index#</span> #title#',

                labels: {
                    finish: 'Save Online Puja',
                    next: 'Next',
                    previous: 'Previous',
                    cancel: 'Cancel'
                },

                onStepChanging: function (event, currentIndex, newIndex) {

                    syncSummernote();

                    updateSlug();

                    return true;
                },

                onStepChanged: function () {

                    updateReview();
                },

                onFinishing: function () {

                    syncSummernote();

                    updateSlug();

                    return true;
                },

                onFinished: function () {

                    $('#onlinePujaForm').trigger('submit');
                }
            });
        }

    });

})(jQuery);
</script>
@endpush
@else
    @php
        abort(403);
    @endphp
@endcan