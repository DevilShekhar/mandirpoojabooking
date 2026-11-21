@extends('admin.layouts.app')
@section('title', 'View Temple Timing')
@section('content')
@can('temple-timings.show')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Temple Timing Details</h4>
                        <div class="card-header-action">
                            <a href="{{ route('temple-timings.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            @can('temple-timings.edit')
                                <a href="{{ route('temple-timings.edit', $templeTiming->id) }}"
                                   class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-place-of-worship"></i>
                            Temple Information
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Temple Name</strong>
                                <p>{{ $templeTiming->temple->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Timing Category</strong>
                                <p>{{ $templeTiming->category->name ?? '-' }}</p>
                            </div>
                        </div>
                        <hr>
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-clock"></i>
                            Timing Information
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Timing Name</strong>
                                <p>{{ $templeTiming->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Start Day</strong>
                                <p>{{ $templeTiming->start_day ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>End Day</strong>
                                <p>{{ $templeTiming->end_day ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Opening Time</strong>
                                <p>
                                    {{ $templeTiming->opening_time
                                        ? \Carbon\Carbon::parse($templeTiming->opening_time)->format('h:i A')
                                        : '-' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Closing Time</strong>
                                <p>
                                    {{ $templeTiming->closing_time
                                        ? \Carbon\Carbon::parse($templeTiming->closing_time)->format('h:i A')
                                        : '-' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Status</strong>
                                <p>
                                    @if($templeTiming->status == 1)
                                        <span class="badge badge-success">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            Inactive
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <hr>
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-info-circle"></i>
                            Record Information
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Created By</strong>
                                <p>{{ $templeTiming->creator->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Updated By</strong>
                                <p>{{ $templeTiming->updater->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Created At</strong>
                                <p>
                                    {{ $templeTiming->created_at
                                        ? $templeTiming->created_at->format('d-m-Y h:i A')
                                        : '-' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Updated At</strong>
                                <p>
                                    {{ $templeTiming->updated_at
                                        ? $templeTiming->updated_at->format('d-m-Y h:i A')
                                        : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('temple-timings.index') }}"
                           class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        @can('temple-timings.edit')
                            <a href="{{ route('temple-timings.edit', $templeTiming->id) }}"
                               class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Timing
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@else
    @php abort(403); @endphp
@endcan
@endsection