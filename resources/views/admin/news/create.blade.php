@can('news.create')

@extends('admin.layouts.app')

@section('title', 'Create News')

@section('content')
<section class="section">
    <div class="section-header">
        <h1><i class="fas fa-newspaper mr-2"></i>Create News</h1>
    </div>

    <div class="section-body">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="mr-3 d-flex align-items-center justify-content-center" style="width:60px;height:60px;background:#eef2ff;border-radius:12px;">
                            <i class="fas fa-newspaper fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">Mandir News</h4>
                            <p class="text-muted mb-0">Create and publish temple news and articles.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <strong><i class="fas fa-exclamation-triangle mr-1"></i>Please correct the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4><i class="fas fa-info-circle mr-2 "></i>News Information</h4>
                        <div class="card-header-action">
                            <span class="badge badge-primary"><i class="fas fa-newspaper mr-1"></i>News</span>
                        </div>
                    </div>

                    <form action="{{ route('my-news.store') }}" method="POST" enctype="multipart/form-data" id="newsForm">
                        @csrf

                        <div class="card-body">
                            <h6 class=" mb-3"><i class="fas fa-place-of-worship mr-2"></i>Temple Information</h6>

                            @if(auth()->user()->hasAnyRole(['Admin', 'Super Admin']))
                                <div class="form-group">
                                    <label for="temple_id">Select Temple <span class="text-danger">*</span></label>
                                    <select name="temple_id" id="temple_id" class="form-control @error('temple_id') is-invalid @enderror" required>
                                        <option value="">-- Select Temple --</option>
                                        @foreach($temples as $temple)
                                            <option value="{{ $temple->id }}" {{ (string) old('temple_id') === (string) $temple->id ? 'selected' : '' }}>
                                                {{ $temple->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('temple_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @elseif(auth()->user()->hasRole('Temple Manager') && $temples->count() === 1)
                                <div class="form-group">
                                    <label>Assigned Temple</label>
                                    <input type="hidden" name="temple_id" value="{{ $selectedTempleId }}">
                                    <input type="text" class="form-control" value="{{ $temples->first()->name }}" readonly>
                                    <small class="form-text text-success">
                                        <i class="fas fa-check-circle mr-1"></i>Your approved temple has been automatically assigned.
                                    </small>
                                </div>
                            @elseif(auth()->user()->hasRole('Temple Manager'))
                                <div class="form-group">
                                    <label for="temple_id">Select Assigned Temple <span class="text-danger">*</span></label>
                                    <select name="temple_id" id="temple_id" class="form-control @error('temple_id') is-invalid @enderror" required>
                                        <option value="">-- Select Temple --</option>
                                        @foreach($temples as $temple)
                                            <option value="{{ $temple->id }}" {{ (string) old('temple_id') === (string) $temple->id ? 'selected' : '' }}>
                                                {{ $temple->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('temple_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    <i class="fas fa-ban mr-2"></i>You are not authorized to create news.
                                </div>
                            @endif

                            <hr>

                            <h6 class=" mb-3"><i class="fas fa-heading mr-2"></i>News Details</h6>

                            <div class="form-group">
                                <label for="title">News Title <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-newspaper"></i></span>
                                    </div>
                                    <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="Enter news title" value="{{ old('title') }}" maxlength="255" required>
                                </div>
                                @error('title')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="short_description">Short Description</label>
                                <textarea id="short_description" name="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="3" placeholder="Enter short news description">{{ old('short_description') }}</textarea>
                                @error('short_description')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Full Description</label>
                                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="7" placeholder="Enter complete news description">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>

                            <h6 class=" mb-3"><i class="fas fa-images mr-2"></i>News Images</h6>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="image">News Image</label>
                                    <div class="d-flex align-items-center mb-3">
                                        <img id="newsPreview" src="{{ asset('assets/img/news-placeholder.png') }}" alt="News preview" class="rounded mr-3" style="width:110px;height:90px;object-fit:cover;border:2px solid #e9ecef;" onerror="this.style.display='none'">
                                        <div>
                                            <strong>Upload News Image</strong>
                                            <p class="text-muted mb-0">JPG, JPEG, PNG or WEBP. Maximum 5 MB.</p>
                                        </div>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" id="image" name="image" class="custom-file-input @error('image') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                                        <label class="custom-file-label" for="image">Choose news image</label>
                                    </div>
                                    @error('image')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="artical_img">Article Image</label>
                                    <div class="d-flex align-items-center mb-3">
                                        <img id="articlePreview" src="{{ asset('assets/img/news-placeholder.png') }}" alt="Article preview" class="rounded mr-3" style="width:110px;height:90px;object-fit:cover;border:2px solid #e9ecef;" onerror="this.style.display='none'">
                                        <div>
                                            <strong>Upload Article Image</strong>
                                            <p class="text-muted mb-0">JPG, JPEG, PNG or WEBP. Maximum 5 MB.</p>
                                        </div>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" id="artical_img" name="artical_img" class="custom-file-input @error('artical_img') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                                        <label class="custom-file-label" for="artical_img">Choose article image</label>
                                    </div>
                                    @error('artical_img')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <hr>

                            <h6 class=" mb-3"><i class="fas fa-toggle-on mr-2"></i>News Status</h6>

                            <div class="form-group mb-0">
                                <div class="custom-control custom-checkbox">
                                    <input type="hidden" name="status" value="0">
                                    <input type="checkbox" id="status" name="status" value="1" class="custom-control-input" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="status">Active News</label>
                                </div>
                                <small class="form-text text-muted">Active news can be displayed on the Sri Mandir website.</small>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <a href="{{ route('my-news.index') }}" class="btn btn-light mr-2">
                                <i class="fas fa-arrow-left mr-1"></i>Cancel
                            </a>
                            <button type="reset" class="btn btn-outline-secondary mr-2" id="resetForm">
                                <i class="fas fa-undo mr-1"></i>Reset
                            </button>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save mr-1"></i>Create News
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 10px;
    }

    .card-header h4 {
        font-weight: 600;
    }

    .form-group label {
        font-weight: 600;
        color: #34395e;
    }

    .form-control,
    .input-group-text,
    .custom-file-label {
        border-radius: 5px;
    }

    .input-group-text {
        min-width: 42px;
        justify-content: center;
        background: #f8f9fa;
    }

    .custom-file-label {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .custom-control-label {
        cursor: pointer;
    }

    .card-footer {
        background: #fff;
        border-top: 1px solid #e9ecef;
    }

    #description + .note-editor {
        margin-bottom: 10px;
    }

    @media (max-width: 576px) {
        .card-footer .btn {
            margin: 4px 0;
        }
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function () {
    const $description = $('#description');

    if ($description.length && typeof $.fn.summernote === 'function') {
        $description.summernote({
            height: 250,
            placeholder: 'Enter complete news description',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview']]
            ],
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

    function previewUpload(input, previewId, defaultName) {
        const file = input.files && input.files[0];
        const preview = document.getElementById(previewId);

        $(input).next('.custom-file-label').text(file ? file.name : defaultName);

        if (!file) {
            return;
        }

        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

        if (!allowedTypes.includes(file.type)) {
            alert('Please select a JPG, JPEG, PNG or WEBP image.');
            input.value = '';
            $(input).next('.custom-file-label').text(defaultName);
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            alert('Image must not exceed 5 MB.');
            input.value = '';
            $(input).next('.custom-file-label').text(defaultName);
            return;
        }

        const reader = new FileReader();

        reader.onload = function (event) {
            preview.src = event.target.result;
            preview.style.display = 'block';
        };

        reader.readAsDataURL(file);
    }

    $('#image').on('change', function () {
        previewUpload(this, 'newsPreview', 'Choose news image');
    });

    $('#artical_img').on('change', function () {
        previewUpload(this, 'articlePreview', 'Choose article image');
    });

    $('#resetForm').on('click', function () {
        setTimeout(function () {
            $('#image').next('.custom-file-label').text('Choose news image');
            $('#artical_img').next('.custom-file-label').text('Choose article image');
            $('#newsPreview, #articlePreview').attr('src', '').hide();

            if ($description.next('.note-editor').length) {
                $description.summernote('reset');
            }
        }, 0);
    });

    $('#newsForm').on('submit', function () {
        syncDescription();

        const button = $('#submitBtn');

        if (this.checkValidity()) {
            button.prop('disabled', true);
            button.html('<i class="fas fa-spinner fa-spin mr-1"></i> Creating...');
        }
    });
});
</script>
@endpush
@else
    @php
        abort(403);
    @endphp
@endcan