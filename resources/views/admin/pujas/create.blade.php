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
                                                <div class="form-group col-md-12">
                                                    <label>
                                                        Description
                                                    </label>
                                                    <textarea id="puja_description" name="description"
                                                        class="form-control @error('description') is-invalid @enderror" rows="5"
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
                                                    <div id="noPackageMessage"
                                                        class="text-center py-5 {{ old('packages') ? 'd-none' : '' }}">
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
                                                        @php
                                                        $oldPackages = old('packages', []);
                                                        @endphp
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
                                                                        class="btn btn-sm btn-outline-danger remove-package">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="package-card-body">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <strong>Package Name</strong>
                                                                        <div class="package-value">
                                                                            {{ $package['name'] ?? '-' }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <strong>Persons</strong>
                                                                        <div class="package-value">
                                                                            {{ $package['person_count'] ?? '-' }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <strong>Price</strong>
                                                                        <div class="package-value">
                                                                            ₹{{ $package['price'] ?? '0' }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <strong>Discount Price</strong>
                                                                        <div class="package-value">
                                                                            ₹{{ $package['discount_price'] ?? '0' }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <strong>Short Description</strong>
                                                                        <div class="package-value">
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
                                                        @endif
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
                let faqIndex = {
                    {
                        old('faq') ? count(old('faq')) : 0
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
            $(document).ready(function() {
                let packageIndex = 0;
                $(document).on(
                    'click',
                    '#addPackageBtn, #addFirstPackageBtn',
                    function(e) {
                        e.preventDefault();
                        resetPackageForm();
                        $('#packageModalLabel').text('Add Package');
                        $('#packageModalError')
                            .addClass('d-none')
                            .html('');
                        $('#packageModal').modal('show');
                    }
                );

                function resetPackageForm() {
                    $('#packageModalError')
                        .addClass('d-none')
                        .html('');
                    $('#package_name').val('');
                    $('#package_person_count').val(1);
                    $('#package_sort_order').val(0);
                    $('#package_price').val('');
                    $('#package_discount_price').val('');
                    $('#package_short_description').val('');
                    $('#package_description').val('');
                    $('#package_sankalp').prop('checked', false);
                    $('#package_prasad').prop('checked', false);
                    $('#package_video').prop('checked', false);
                    $('#package_certificate').prop('checked', false);
                    $('#package_is_featured').prop('checked', false);
                    $('#package_image').val('');
                }
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
                    let featured = $('#package_is_featured').val();
                    let errors = [];
                    if (!name) {
                        errors.push('Package name is required.');
                    }
                    if (!personCount || personCount < 1) {
                        errors.push('Person count must be at least 1.');
                    }
                    if (price === '' || price < 0) {
                        errors.push('Package price is required.');
                    }
                    if (
                        discountPrice !== '' &&
                        parseFloat(discountPrice) > parseFloat(price)
                    ) {
                        errors.push(
                            'Discount price cannot be greater than package price.'
                        );
                    }
                    if (errors.length > 0) {
                        $('#packageModalError')
                            .html(errors.join('<br>'))
                            .removeClass('d-none');
                        return;
                    }
                    $('#noPackageMessage').remove();
                    let packageHtml = `
                        <div class="package-card border rounded p-3 mb-3"
                            data-index="${packageIndex}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">
                                        ${name}
                                    </h6>
                                    <small class="text-muted">
                                        ${personCount}
                                        ${personCount == 1 ? 'Person' : 'Persons'}
                                    </small>
                                </div>
                                <button type="button"
                                        class="btn btn-danger btn-sm removePackage">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <strong>Price:</strong>
                                    ₹${parseFloat(price).toFixed(2)}
                                </div>
                                <div class="col-md-4">
                                    <strong>Discount Price:</strong>
                                    ${
                                        discountPrice
                                        ? '₹' + parseFloat(discountPrice).toFixed(2)
                                        : '—'
                                    }
                                </div>
                                <div class="col-md-4">
                                    <strong>Sort Order:</strong>
                                    ${sortOrder}
                                </div>
                            </div>
                            ${
                                shortDescription
                                ? `
                                    <div class="mt-2">
                                        <strong>Short Description:</strong>
                                        ${shortDescription}
                                    </div>
                                `
                                : ''
                            }
                            <div class="mt-2">
                                ${
                                    sankalp
                                    ? '<span class="badge badge-info mr-1">Sankalp</span>'
                                    : ''
                                }
                                ${
                                    prasad
                                    ? '<span class="badge badge-success mr-1">Prasad</span>'
                                    : ''
                                }
                                ${
                                    video
                                    ? '<span class="badge badge-primary mr-1">Video</span>'
                                    : ''
                                }
                                ${
                                    certificate
                                    ? '<span class="badge badge-warning mr-1">Certificate</span>'
                                    : ''
                                }
                                ${
                                    featured == 1
                                    ? '<span class="badge badge-dark mr-1">Featured</span>'
                                    : ''
                                }
                            </div>
                            <input type="hidden"
                                name="packages[${packageIndex}][name]"
                                value="${name}">
                            <input type="hidden"
                                name="packages[${packageIndex}][person_count]"
                                value="${personCount}">
                            <input type="hidden"
                                name="packages[${packageIndex}][price]"
                                value="${price}">
                            <input type="hidden"
                                name="packages[${packageIndex}][discount_price]"
                                value="${discountPrice}">
                            <input type="hidden"
                                name="packages[${packageIndex}][sort_order]"
                                value="${sortOrder}">
                            <input type="hidden"
                                name="packages[${packageIndex}][short_description]"
                                value="${shortDescription}">
                            <input type="hidden"
                                name="packages[${packageIndex}][description]"
                                value="${description}">
                            <input type="hidden"
                                name="packages[${packageIndex}][includes_sankalp]"
                                value="${sankalp}">
                            <input type="hidden"
                                name="packages[${packageIndex}][includes_prasad]"
                                value="${prasad}">
                            <input type="hidden"
                                name="packages[${packageIndex}][includes_video]"
                                value="${video}">
                            <input type="hidden"
                                name="packages[${packageIndex}][includes_certificate]"
                                value="${certificate}">
                            <input type="hidden"
                                name="packages[${packageIndex}][is_featured]"
                                value="${featured}">
                        </div>
                    `;
                    $('#packageContainer').append(packageHtml);
                    packageIndex++;
                    $('#packageModal').modal('hide');
                    resetPackageForm();
                });
                $(document).on('click', '.removePackage', function() {
                    $(this)
                        .closest('.package-card')
                        .remove();
                    if ($('#packageContainer .package-card').length === 0) {
                        $('#packageContainer').html(`
                            <div id="noPackageMessage"
                                class="text-center border rounded p-4">
                                <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-2">
                                    No packages added yet.
                                </p>
                                <button type="button"
                                        class="btn btn-outline-primary btn-sm"
                                        id="addFirstPackageBtn">
                                    <i class="fas fa-plus"></i>
                                    Add First Package
                                </button>
                            </div>
                        `);
                    }
                });

            });
            $(document).ready(function() {
                var $description = $('#puja_description');
                if ($description.length && typeof $.fn.summernote === 'function') {
                    $description.summernote({
                        height: 200,
                        callbacks: {
                            // When Summernote loads
                            onInit: function() {
                                $description.val(
                                    $(this).summernote('code')
                                );
                            },
                            onChange: function(contents) {
                                $description.val(contents);
                            }
                        }
                    });
                }

                function syncDescription() {
                    if (
                        $description.length &&
                        typeof $.fn.summernote === 'function'
                    ) {
                        var content = $description.summernote('code');
                        $description.val(content);
                        console.log(
                            'DESCRIPTION:',
                            $description.val()
                        );
                    }
                }
                $('form').on('submit', function() {
                    syncDescription();
                });
                $('#wizard_horizontal').on(
                    'click',
                    '.actions a',
                    function() {
                        syncDescription();
                    }
                );
                $('#wizard_horizontal').on(
                    'click',
                    '.actions a[href="#finish"]',
                    function() {
                        syncDescription();
                    }
                );
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