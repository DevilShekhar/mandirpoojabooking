@extends('admin.layouts.app')

@section('title', 'Edit Sanatan Event')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Sanatan Event</h4>
                    </div>
                    <form action="{{ route('sanatan-events.update', $sanatan->id) }}" method="POST" enctype="multipart/form-data">
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
                            @php
                                $languages = ['Marathi', 'Hindi', 'Sanskrit', 'English', 'Gujarati', 'Tamil', 'Telugu', 'Kannada', 'Malayalam', 'Bengali', 'Punjabi', 'Odia', 'Urdu', 'Other'];
                                $savedLanguages = $sanatan->language;
                                if (is_string($savedLanguages)) {
                                    $decodedLanguages = json_decode($savedLanguages, true);
                                    $savedLanguages = is_array($decodedLanguages) ? $decodedLanguages : (strlen($savedLanguages) ? [$savedLanguages] : []);
                                }
                                $selectedLanguages = old('language', $savedLanguages ?? []);
                                if (!is_array($selectedLanguages)) {
                                    $selectedLanguages = [];
                                }
                            @endphp
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Event Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', $sanatan->name) }}" class="form-control @error('name') is-invalid @enderror" placeholder="Enter event name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Program Type <span class="text-danger">*</span></label>
                                    <select name="program_type" class="form-control @error('program_type') is-invalid @enderror" required>
                                        <option value="">Select Program Type</option>
                                        @foreach(['Kirtan', 'Satsang', 'Parayan', 'Saptah', 'Bhagwat Katha', 'Ram Katha', 'Bhajan Sandhya', 'Other'] as $type)
                                            <option value="{{ $type }}" {{ old('program_type', $sanatan->program_type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                    @error('program_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Languages</label>
                                    <div class="language-options border rounded p-3">
                                        <div class="row">
                                            @foreach($languages as $language)
                                                <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="language[]" value="{{ $language }}" id="language_{{ Str::slug($language) }}" class="custom-control-input" {{ in_array($language, $selectedLanguages) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="language_{{ Str::slug($language) }}">{{ $language }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @error('language')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    @error('language.*')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Select one or more languages for this event.</small>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Duration</label>
                                    <input type="text" name="duration" value="{{ old('duration', $sanatan->duration) }}" class="form-control @error('duration') is-invalid @enderror" placeholder="Example: 2 Hours or 7 Days">
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Base Price <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₹</span>
                                        </div>
                                        <input type="number" name="price" value="{{ old('price', $sanatan->price) }}" min="0" step="0.01" class="form-control @error('price') is-invalid @enderror" placeholder="Enter event price" required>
                                    </div>
                                    @error('price')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Short Description</label>
                                    <textarea name="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="3" maxlength="500" placeholder="Enter short description">{{ old('short_description', $sanatan->short_description) }}</textarea>
                                    @error('short_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Full Description</label>
                                    <textarea id="sanatan_description" name="description" class="form-control @error('description') is-invalid @enderror" rows="6" placeholder="Enter complete event details">{{ old('description', $sanatan->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Event Image</label>
                                    <input type="file" name="image" id="event_image" class="form-control @error('image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                                    <small class="text-muted">Allowed formats: JPG, JPEG, PNG, WEBP. Maximum size: 2 MB.</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-3">
                                        <img id="event_image_preview" src="{{ $sanatan->image ? asset('storage/' . $sanatan->image) : '' }}" alt="Event image preview" class="img-fluid rounded border {{ $sanatan->image ? '' : 'd-none' }}" style="max-height:220px;object-fit:contain;">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Banner Image</label>
                                    <input type="file" name="banner_image" id="event_banner_image" class="form-control @error('banner_image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                                    <small class="text-muted">Allowed formats: JPG, JPEG, PNG, WEBP. Maximum size: 2 MB.</small>
                                    @error('banner_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-3">
                                        <img id="event_banner_preview" src="{{ $sanatan->banner_image ? asset('storage/' . $sanatan->banner_image) : '' }}" alt="Banner image preview" class="img-fluid rounded border {{ $sanatan->banner_image ? '' : 'd-none' }}" style="max-height:220px;object-fit:contain;">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Online Booking <span class="text-danger">*</span></label>
                                    <select name="is_online" class="form-control @error('is_online') is-invalid @enderror" required>
                                        <option value="1" {{ old('is_online', $sanatan->is_online) == 1 ? 'selected' : '' }}>Available</option>
                                        <option value="0" {{ old('is_online', $sanatan->is_online) == 0 ? 'selected' : '' }}>Not Available</option>
                                    </select>
                                    @error('is_online')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Offline Booking <span class="text-danger">*</span></label>
                                    <select name="is_offline" class="form-control @error('is_offline') is-invalid @enderror" required>
                                        <option value="1" {{ old('is_offline', $sanatan->is_offline) == 1 ? 'selected' : '' }}>Available</option>
                                        <option value="0" {{ old('is_offline', $sanatan->is_offline) == 0 ? 'selected' : '' }}>Not Available</option>
                                    </select>
                                    @error('is_offline')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="1" {{ old('status', $sanatan->status) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $sanatan->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('sanatan-events.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Event
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
    const $description = $('#sanatan_description');

    if ($description.length && typeof $.fn.summernote === 'function') {
        $description.summernote({
            height: 200,
            callbacks: {
                onChange: function (contents) {
                    $description.val(contents);
                }
            }
        });
    }

    function syncDescription() {
        if ($description.length && typeof $.fn.summernote === 'function' && $description.next('.note-editor').length) {
            $description.val($description.summernote('code'));
        }
    }

    function previewImage(input, previewSelector) {
        const file = input.files && input.files[0];
        const $preview = $(previewSelector);

        if (!file) {
            return;
        }

        if (!file.type.startsWith('image/')) {
            input.value = '';
            if (typeof Swal !== 'undefined') {
                Swal.fire('Invalid File', 'Please select a valid image file.', 'error');
            } else {
                alert('Please select a valid image file.');
            }
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            input.value = '';
            if (typeof Swal !== 'undefined') {
                Swal.fire('File Too Large', 'Please select an image smaller than or equal to 2 MB.', 'error');
            } else {
                alert('Please select an image smaller than or equal to 2 MB.');
            }
            return;
        }

        const reader = new FileReader();

        reader.onload = function (event) {
            $preview.attr('src', event.target.result).removeClass('d-none');
        };

        reader.readAsDataURL(file);
    }

    $('#event_image').on('change', function () {
        previewImage(this, '#event_image_preview');
    });

    $('#event_banner_image').on('change', function () {
        previewImage(this, '#event_banner_preview');
    });

    $('form').on('submit', function () {
        syncDescription();
    });
});
</script>
<style>
.language-options {
    background: #fff;
}
.language-options .custom-control-label {
    cursor: pointer;
}
#event_image_preview,
#event_banner_preview {
    max-width: 100%;
    background: #fff;
    padding: 5px;
}
#sanatan_description + .note-editor {
    margin-bottom: 10px;
}
</style>
@endpush