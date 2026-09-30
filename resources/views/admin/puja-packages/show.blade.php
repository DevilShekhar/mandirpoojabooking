@can('puja-packages.view')
@extends('admin.layouts.app')

@section('title', 'View Puja Package')

@section('content')

<section class="section">
    <div class="section-body">
    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-header">
                    <h4>View Puja Package</h4>

                    <div class="card-header-action">
                        <a href="{{ route('puja-packages.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>

                        <a href="{{ route('puja-packages.edit', $pujaPackage->id) }}"
                           class="btn btn-primary ml-1">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>

                <div class="card-body">

                    <div class="section-title mt-0">
                        Package Information
                    </div>

                    <div class="row">

                        <div class="form-group col-md-6">
                            <label>PuJa</label>
                            <div class="form-control bg-light">
                                {{ $pujaPackage->puja->name ?? '-' }}
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Package Name</label>
                            <div class="form-control bg-light">
                                {{ $pujaPackage->name }}
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Slug</label>
                            <div class="form-control bg-light">
                                {{ $pujaPackage->slug }}
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Person Count</label>
                            <div class="form-control bg-light">
                                {{ $pujaPackage->person_count }}
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Price</label>
                            <div class="form-control bg-light">
                                ₹{{ number_format($pujaPackage->price, 2) }}
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Discount Price</label>
                            <div class="form-control bg-light">
                                @if($pujaPackage->discount_price)
                                    ₹{{ number_format($pujaPackage->discount_price, 2) }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <label>Short Description</label>
                            <div class="form-control bg-light" style="height:auto; min-height:50px;">
                                {{ $pujaPackage->short_description ?? '-' }}
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <label>Description</label>
                            <div class="form-control bg-light" style="height:auto; min-height:100px;">
                                {!! nl2br(e($pujaPackage->description ?? '-')) !!}
                            </div>
                        </div>

                    </div>

                    <div class="section-title">
                        Package Benefits & Services
                    </div>

                    <div class="row">

                        <div class="form-group col-md-3">
                            <label>Sankalp</label>
                            <div>
                                @if($pujaPackage->includes_sankalp)
                                    <span class="badge badge-success badge-shadow">
                                        Included
                                    </span>
                                @else
                                    <span class="badge badge-danger badge-shadow">
                                        Not Included
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Prasad</label>
                            <div>
                                @if($pujaPackage->includes_prasad)
                                    <span class="badge badge-success badge-shadow">
                                        Included
                                    </span>
                                @else
                                    <span class="badge badge-danger badge-shadow">
                                        Not Included
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Video</label>
                            <div>
                                @if($pujaPackage->includes_video)
                                    <span class="badge badge-success badge-shadow">
                                        Included
                                    </span>
                                @else
                                    <span class="badge badge-danger badge-shadow">
                                        Not Included
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Certificate</label>
                            <div>
                                @if($pujaPackage->includes_certificate)
                                    <span class="badge badge-success badge-shadow">
                                        Included
                                    </span>
                                @else
                                    <span class="badge badge-danger badge-shadow">
                                        Not Included
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="section-title">
                        Display Settings
                    </div>

                    <div class="row">

                        <div class="form-group col-md-4">
                            <label>Featured</label>
                            <div>
                                @if($pujaPackage->is_featured)
                                    <span class="badge badge-warning badge-shadow">
                                        Featured
                                    </span>
                                @else
                                    <span class="badge badge-secondary badge-shadow">
                                        Normal
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Sort Order</label>
                            <div class="form-control bg-light">
                                {{ $pujaPackage->sort_order ?? 0 }}
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Status</label>
                            <div>
                                @if($pujaPackage->status)
                                    <span class="badge badge-success badge-shadow">
                                        Active
                                    </span>
                                @else
                                    <span class="badge badge-danger badge-shadow">
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>

                    @if($pujaPackage->package_image)

                        <div class="section-title">
                            Package Image
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{ asset('storage/' . $pujaPackage->package_image) }}"
                                     alt="{{ $pujaPackage->name }}"
                                     class="img-fluid rounded"
                                     style="max-height:250px;">
                            </div>
                        </div>

                    @endif

                </div>

                <div class="card-footer text-right">

                    <a href="{{ route('puja-packages.index') }}"
                       class="btn btn-secondary mr-1">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>

                    <a href="{{ route('puja-packages.edit', $pujaPackage->id) }}"
                       class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Package
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

</section>

@endsection
@else
    @php
        abort(403);
    @endphp
@endcan
