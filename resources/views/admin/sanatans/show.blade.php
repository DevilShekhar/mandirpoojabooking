@extends('admin.layouts.app')

@section('title', 'Sanatan Event Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Sanatan Event Details</h4>
            <p class="text-muted mb-0">View complete religious event information.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.sanatan-events.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to List
            </a>
            <a href="{{ route('admin.sanatan-events.edit', $sanatan->id) }}" class="btn btn-warning">
                <i class="fa fa-edit"></i> Edit Event
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Event Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Event Name</div>
                        <div class="col-md-8">{{ $sanatan->name }}</div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Slug</div>
                        <div class="col-md-8">{{ $sanatan->slug }}</div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Program Type</div>
                        <div class="col-md-8">
                            <span class="badge badge-info">{{ $sanatan->program_type }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Languages</div>
                        <div class="col-md-8">
                            @php
                                $eventLanguages = $sanatan->language;

                                if (is_string($eventLanguages)) {
                                    $decodedLanguages = json_decode($eventLanguages, true);
                                    $eventLanguages = is_array($decodedLanguages)
                                        ? $decodedLanguages
                                        : (strlen($eventLanguages) ? [$eventLanguages] : []);
                                }

                                if (!is_array($eventLanguages)) {
                                    $eventLanguages = [];
                                }
                            @endphp

                            @forelse($eventLanguages as $language)
                                <span class="badge badge-primary mr-1 mb-1">{{ $language }}</span>
                            @empty
                                <span class="text-muted">N/A</span>
                            @endforelse
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Short Description</div>
                        <div class="col-md-8">{{ $sanatan->short_description ?: 'N/A' }}</div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Full Description</div>
                        <div class="col-md-8 description-content">
                            @if(!empty($sanatan->description))
                                {!! $sanatan->description !!}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Duration</div>
                        <div class="col-md-8">{{ $sanatan->duration ?: 'N/A' }}</div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Base Price</div>
                        <div class="col-md-8 fw-semibold text-success">
                            ₹{{ number_format((float) $sanatan->price, 2) }}
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Online Booking</div>
                        <div class="col-md-8">
                            <span class="badge {{ $sanatan->is_online ? 'badge-success' : 'badge-secondary' }}">
                                {{ $sanatan->is_online ? 'Available' : 'Not Available' }}
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Offline Booking</div>
                        <div class="col-md-8">
                            <span class="badge {{ $sanatan->is_offline ? 'badge-success' : 'badge-secondary' }}">
                                {{ $sanatan->is_offline ? 'Available' : 'Not Available' }}
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 fw-semibold">Status</div>
                        <div class="col-md-8">
                            <span class="badge {{ $sanatan->status ? 'badge-success' : 'badge-danger' }}">
                                {{ $sanatan->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Record Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Created By</div>
                        <div class="col-md-8">{{ $sanatan->created_by ?: 'N/A' }}</div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Updated By</div>
                        <div class="col-md-8">{{ $sanatan->updated_by ?: 'N/A' }}</div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Created At</div>
                        <div class="col-md-8">
                            {{ $sanatan->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 fw-semibold">Last Updated</div>
                        <div class="col-md-8">
                            {{ $sanatan->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Event Image</h5>
                </div>
                <div class="card-body text-center">
                    @if($sanatan->image)
                        <img src="{{ asset('storage/' . $sanatan->image) }}" alt="{{ $sanatan->name }}" class="img-fluid rounded border" style="max-height:300px;object-fit:contain;">
                    @else
                        <div class="py-4 text-muted">
                            <i class="fas fa-image fa-3x mb-2"></i>
                            <p class="mb-0">No event image uploaded.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Banner Image</h5>
                </div>
                <div class="card-body text-center">
                    @if($sanatan->banner_image)
                        <img src="{{ asset('storage/' . $sanatan->banner_image) }}" alt="{{ $sanatan->name }} banner" class="img-fluid rounded border" style="max-height:300px;object-fit:contain;">
                    @else
                        <div class="py-4 text-muted">
                            <i class="fas fa-image fa-3x mb-2"></i>
                            <p class="mb-0">No banner image uploaded.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <a href="{{ route('admin.sanatan-events.edit', $sanatan->id) }}" class="btn btn-warning btn-block mb-2">
                        <i class="fa fa-edit"></i> Edit Event
                    </a>
                    <form action="{{ route('admin.sanatan-events.destroy', $sanatan->id) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="fa fa-trash"></i> Delete Event
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function () {
    $('.delete-form').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to delete this Sanatan event?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
@push('styles')
<style>
.description-content {
    overflow-wrap: anywhere;
    line-height: 1.7;
}
.description-content img {
    max-width: 100%;
    height: auto;
}
.description-content table {
    max-width: 100%;
    width: 100%;
}
.description-content iframe,
.description-content video {
    max-width: 100%;
}
</style>
@endpush