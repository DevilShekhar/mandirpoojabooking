@can('online-puja.view')
@extends('admin.layouts.app')

@section('title', 'View Online Puja')

@section('content')

<section class="section">

    {{-- Page Header --}}
    <div class="section-header">
        <h1>View Online Puja</h1>

        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </div>

            <div class="breadcrumb-item">
                <a href="{{ route('online-pujas.index') }}">Online Pujas</a>
            </div>

            <div class="breadcrumb-item active">View Online Puja</div>
        </div>
    </div>

    <div class="section-body">

        {{-- Top Actions --}}
        <div class="row mb-3">
            <div class="col-12 text-right">

                <a href="{{ route('online-pujas.index') }}"
                   class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>

                <a href="{{ route('online-pujas.edit', $online_puja->id) }}"
                   class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Puja
                </a>

            </div>
        </div>

        {{-- Basic Information --}}
        <div class="row">

            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h4>
                            <i class="fas fa-om"></i>
                            Basic Puja Information
                        </h4>

                        <div class="card-header-action">
                            @if($online_puja->status == 1)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-8">

                                <table class="table table-bordered">

                                    <tr>
                                        <th width="30%">Puja Title</th>
                                        <td>{{ $online_puja->title ?? '-' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Slug</th>
                                        <td>{{ $online_puja->slug ?? '-' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Category</th>
                                        <td>{{ $online_puja->category->name ?? '-' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Temple Name</th>
                                        <td>{{ $online_puja->temple_name ?? '-' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Puja Date & Time</th>
                                        <td>
                                            {{ $online_puja->puja_datetime
                                                ? \Carbon\Carbon::parse($online_puja->puja_datetime)->format('d-m-Y h:i A')
                                                : '-' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Total Seats</th>
                                        <td>{{ $online_puja->total_seat ?? 0 }}</td>
                                    </tr>

                                    <tr>
                                        <th>Booked Seats</th>
                                        <td>{{ $online_puja->booked_seat ?? 0 }}</td>
                                    </tr>

                                    <tr>
                                        <th>Available Seats</th>
                                        <td>
                                            {{ max(0, ($online_puja->total_seat ?? 0) - ($online_puja->booked_seat ?? 0)) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Created By</th>
                                        <td>{{ $online_puja->creator->name ?? '-' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Created At</th>
                                        <td>
                                            {{ $online_puja->created_at
                                                ? $online_puja->created_at->format('d-m-Y h:i A')
                                                : '-' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Updated At</th>
                                        <td>
                                            {{ $online_puja->updated_at
                                                ? $online_puja->updated_at->format('d-m-Y h:i A')
                                                : '-' }}
                                        </td>
                                    </tr>

                                </table>

                            </div>

                            {{-- Banner Image --}}
                            <div class="col-md-4">

                                <div class="card border">
                                    <div class="card-header">
                                        <h4>Banner Image</h4>
                                    </div>

                                    <div class="card-body text-center">

                                        @if($online_puja->banner_image)
                                            <img
                                                src="{{ asset('storage/' . $online_puja->banner_image) }}"
                                                alt="Banner Image"
                                                class="img-fluid rounded"
                                                style="max-height: 300px; object-fit: contain;">
                                        @else
                                            <div class="text-muted py-5">
                                                <i class="fas fa-image fa-3x"></i>
                                                <p class="mt-2 mb-0">No Banner Image</p>
                                            </div>
                                        @endif

                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>


        {{-- Puja Content --}}
        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>Puja Content Details</h4>
                    </div>

                    <div class="card-body">

                        {{-- Short Description --}}
                        @if(!empty($online_puja->short_description))
                            <div class="mb-4">
                                <h6 class="text-primary">Short Description</h6>
                                <div class="border rounded p-3">
                                    {!! $online_puja->short_description !!}
                                </div>
                            </div>
                        @endif

                        {{-- Full Description --}}
                        @if(!empty($online_puja->description))
                            <div class="mb-4">
                                <h6 class="text-primary">Description</h6>
                                <div class="border rounded p-3">
                                    {!! $online_puja->description !!}
                                </div>
                            </div>
                        @endif

                        {{-- Puja Significance --}}
                        @if(!empty($online_puja->puja_significance))
                            <div class="mb-4">
                                <h6 class="text-primary">Puja Significance</h6>
                                <div class="border rounded p-3">
                                    {!! $online_puja->puja_significance !!}
                                </div>
                            </div>
                        @endif

                        {{-- Puja Benefits --}}
                        @if(!empty($online_puja->puja_benefits))
                            <div class="mb-4">
                                <h6 class="text-primary">Puja Benefits</h6>
                                <div class="border rounded p-3">
                                    {!! $online_puja->puja_benefits !!}
                                </div>
                            </div>
                        @endif

                        {{-- Puja Procedure --}}
                        @if(!empty($online_puja->puja_procedure))
                            <div class="mb-4">
                                <h6 class="text-primary">Puja Procedure</h6>
                                <div class="border rounded p-3">
                                    {!! $online_puja->puja_procedure !!}
                                </div>
                            </div>
                        @endif

                        {{-- What You Will Receive --}}
                        @if(!empty($online_puja->what_you_will_receive))
                            <div class="mb-4">
                                <h6 class="text-primary">What You Will Receive</h6>
                                <div class="border rounded p-3">
                                    {!! $online_puja->what_you_will_receive !!}
                                </div>
                            </div>
                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Packages --}}
        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>
                            <i class="fas fa-box"></i>
                            Puja Packages
                        </h4>
                    </div>

                    <div class="card-body">

                        @if($online_puja->packages && $online_puja->packages->count() > 0)

                            <div class="table-responsive">

                                <table class="table table-bordered table-striped">

                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th>Package Name</th>
                                            <th>Description</th>
                                            <th>Price</th>
                                            <th>Discount Price</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach($online_puja->packages as $key => $package)

                                            <tr>

                                                <td>{{ $key + 1 }}</td>

                                                <td>
                                                    <strong>{{ $package->name ?? '-' }}</strong>
                                                </td>

                                                <td>
                                                    {{ $package->description ?? '-' }}
                                                </td>

                                                <td>
                                                    {{ isset($package->price) ? number_format($package->price, 2) : '-' }}
                                                </td>

                                                <td>
                                                    {{ isset($package->discount_price) ? number_format($package->discount_price, 2) : '-' }}
                                                </td>

                                                <td>
                                                    @if(isset($package->status) && $package->status == 1)
                                                        <span class="badge badge-success">Active</span>
                                                    @elseif(isset($package->status))
                                                        <span class="badge badge-danger">Inactive</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        @else

                            <div class="text-center text-muted py-4">
                                <i class="fas fa-box-open fa-2x"></i>
                                <p class="mt-2 mb-0">No Packages Available.</p>
                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- FAQs --}}
        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>
                            <i class="fas fa-question-circle"></i>
                            Frequently Asked Questions
                        </h4>
                    </div>

                    <div class="card-body">

                        @if($online_puja->faqs && $online_puja->faqs->count() > 0)

                            <div id="faqAccordion">

                                @foreach($online_puja->faqs as $key => $faq)

                                    <div class="card border mb-2">

                                        <div class="card-header">

                                            <h6 class="mb-0">
                                                <a
                                                    data-toggle="collapse"
                                                    href="#faq{{ $key }}"
                                                    aria-expanded="{{ $key == 0 ? 'true' : 'false' }}">

                                                    <i class="fas fa-question-circle text-primary"></i>
                                                    {{ $faq->question ?? '-' }}

                                                </a>
                                            </h6>

                                        </div>

                                        <div
                                            id="faq{{ $key }}"
                                            class="collapse {{ $key == 0 ? 'show' : '' }}"
                                            data-parent="#faqAccordion">

                                            <div class="card-body">
                                                {!! $faq->answer ?? '-' !!}
                                            </div>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @else

                            <div class="text-center text-muted py-4">
                                <i class="fas fa-question-circle fa-2x"></i>
                                <p class="mt-2 mb-0">No FAQs Available.</p>
                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Gallery Images --}}
        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>
                            <i class="fas fa-images"></i>
                            Puja Gallery
                        </h4>
                    </div>

                    <div class="card-body">

                        @php
                            $galleryImages = $online_puja->gallery_images ?? [];
                            
                            if (is_string($galleryImages)) {
                                $galleryImages = json_decode($galleryImages, true) ?? [];
                            }
                        @endphp

                        @if(is_array($galleryImages) && count($galleryImages) > 0)

                            <div class="row">

                                @foreach($galleryImages as $image)

                                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">

                                        <div class="card border">

                                            <img
                                                src="{{ asset('storage/' . $image) }}"
                                                class="card-img-top"
                                                alt="Puja Gallery"
                                                style="height: 200px; object-fit: cover;">

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @else

                            <div class="text-center text-muted py-4">
                                <i class="fas fa-images fa-2x"></i>
                                <p class="mt-2 mb-0">No Gallery Images Available.</p>
                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Bottom Actions --}}
        <div class="row mb-4">

            <div class="col-12 text-right">

                <a href="{{ route('online-pujas.index') }}"
                   class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>

                <a href="{{ route('online-pujas.edit', $online_puja->id) }}"
                   class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Online Puja
                </a>

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