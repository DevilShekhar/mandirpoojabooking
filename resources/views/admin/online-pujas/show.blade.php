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
            <div class="col-12 d-flex justify-content-between align-items-center">
                <a href="{{ route('online-pujas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                <div>                    
                    @can('online-puja.edit')
                        @if($online_puja->status == 1)
                            <form id="statusUpdateForm" action="{{ route('online-pujas.update-status', $online_puja->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="button" onclick="confirmStatusUpdate()" class="btn btn-warning mr-2">                                        
                                    <i class="fas fa-sync-alt"></i> Mark Completed
                                </button>
                            </form>
                        @elseif($online_puja->status == 2)
                            <!-- Optional: Show something else or text when status is 2, or leave empty -->
                            <span class="badge badge-success">Completed Puja</span>
                        @endif
                    @endcan
                    <a href="{{ route('online-pujas.edit', $online_puja->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Puja
                    </a>                  
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>
                            <i class="fas fa-om text-primary mr-2"></i>
                            Basic Puja Information
                        </h4>

                        <div class="card-header-action">
                            @if($online_puja->status == 1)
                                <span class="badge badge-success px-3 py-2">Active</span>
                            @elseif($online_puja->status == 2)
                                <span class="badge badge-info px-3 py-2">Completed</span>
                            @else
                                <span class="badge badge-danger px-3 py-2">Inactive</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-8">

                                <table class="table table-bordered table-striped">

                                    <tr>
                                        <th width="30%">Puja Title</th>
                                        <td>{{ $online_puja->title ?? '-' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Slug</th>
                                        <td><code>{{ $online_puja->slug ?? '-' }}</code></td>
                                    </tr>

                                    <tr>
                                        <th>Category</th>
                                        <td>
                                            <span class="badge badge-light text-dark border">
                                                {{ $online_puja->category->name ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Temple Name</th>
                                        <td>{{ $online_puja->temple_name ?? '-' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Puja Date & Time</th>
                                        <td>
                                            <i class="far fa-calendar-alt text-muted mr-1"></i>
                                            {{ $online_puja->puja_datetime ? \Carbon\Carbon::parse($online_puja->puja_datetime)->format('d-m-Y h:i A') : '-' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Total Seats</th>
                                        <td><span class="badge badge-secondary">{{ $online_puja->total_seat ?? 0 }}</span></td>
                                    </tr>

                                    <tr>
                                        <th>Booked Seats</th>
                                        <td><span class="badge badge-warning">{{ $online_puja->booked_seat ?? 0 }}</span></td>
                                    </tr>

                                    <tr>
                                        <th>Available Seats</th>
                                        <td>
                                            <span class="badge badge-success">
                                                {{ max(0, ($online_puja->total_seat ?? 0) - ($online_puja->booked_seat ?? 0)) }}
                                            </span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Created By</th>
                                        <td>{{ $online_puja->creator->name ?? '-' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $online_puja->created_at ? $online_puja->created_at->format('d-m-Y h:i A') : '-' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Updated At</th>
                                        <td>{{ $online_puja->updated_at ? $online_puja->updated_at->format('d-m-Y h:i A') : '-' }}</td>
                                    </tr>

                                </table>

                            </div>

                            {{-- Banner Image --}}
                            <div class="col-md-4">

                                <div class="card border shadow-none h-100 mb-0">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Banner Image</h6>
                                    </div>

                                    <div class="card-body text-center d-flex align-items-center justify-content-center">

                                        @if($online_puja->banner_image)
                                            <img src="{{ asset('storage/' . $online_puja->banner_image) }}"
                                                 alt="Banner Image"
                                                 class="img-fluid rounded shadow-sm"
                                                 style="max-height: 250px; object-fit: contain;">
                                        @else
                                            <div class="text-muted py-5">
                                                <i class="fas fa-image fa-3x mb-2 text-black-50"></i>
                                                <p class="mb-0">No Banner Image</p>
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
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4><i class="fas fa-file-alt text-primary mr-2"></i> Puja Content Details</h4>
                    </div>

                    <div class="card-body">

                        @if(!empty($online_puja->short_description))
                            <div class="mb-4">
                                <h6 class="text-primary font-weight-bold">Short Description</h6>
                                <div class="border rounded p-3 bg-light">
                                    {!! $online_puja->short_description !!}
                                </div>
                            </div>
                        @endif

                        @if(!empty($online_puja->description))
                            <div class="mb-4">
                                <h6 class="text-primary font-weight-bold">Description</h6>
                                <div class="border rounded p-3 bg-light">
                                    {!! $online_puja->description !!}
                                </div>
                            </div>
                        @endif

                        @if(!empty($online_puja->puja_significance))
                            <div class="mb-4">
                                <h6 class="text-primary font-weight-bold">Puja Significance</h6>
                                <div class="border rounded p-3 bg-light">
                                    {!! $online_puja->puja_significance !!}
                                </div>
                            </div>
                        @endif

                        @if(!empty($online_puja->puja_benefits))
                            <div class="mb-4">
                                <h6 class="text-primary font-weight-bold">Puja Benefits</h6>
                                <div class="border rounded p-3 bg-light">
                                    {!! $online_puja->puja_benefits !!}
                                </div>
                            </div>
                        @endif

                        @if(!empty($online_puja->puja_procedure))
                            <div class="mb-4">
                                <h6 class="text-primary font-weight-bold">Puja Procedure</h6>
                                <div class="border rounded p-3 bg-light">
                                    {!! $online_puja->puja_procedure !!}
                                </div>
                            </div>
                        @endif

                        @if(!empty($online_puja->what_you_will_receive))
                            <div class="mb-0">
                                <h6 class="text-primary font-weight-bold">What You Will Receive</h6>
                                <div class="border rounded p-3 bg-light">
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
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4><i class="fas fa-box text-primary mr-2"></i> Puja Packages</h4>
                    </div>

                    <div class="card-body">
                        @if($online_puja->packages && $online_puja->packages->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover align-middle">
                                    <thead>
                                        <tr class="table-secondary">
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
                                                <td><strong>{{ $package->name ?? '-' }}</strong></td>
                                                <td>{{ $package->description ?? '-' }}</td>
                                                <td>{{ isset($package->price) ? '₹ ' . number_format($package->price, 2) : '-' }}</td>
                                                <td>{{ isset($package->discount_price) ? '₹ ' . number_format($package->discount_price, 2) : '-' }}</td>
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
                                <i class="fas fa-box-open fa-3x mb-2 text-black-50"></i>
                                <p class="mb-0">No Packages Available.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- FAQs --}}
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4><i class="fas fa-question-circle text-primary mr-2"></i> Frequently Asked Questions</h4>
                    </div>

                    <div class="card-body">
                        @if($online_puja->faqs && $online_puja->faqs->count() > 0)
                            <div id="faqAccordion">
                                @foreach($online_puja->faqs as $key => $faq)
                                    <div class="card border mb-2 shadow-none">
                                        <div class="card-header bg-light" id="heading{{ $key }}">
                                            <h6 class="mb-0">
                                                <a data-toggle="collapse" href="#faq{{ $key }}" aria-expanded="{{ $key == 0 ? 'true' : 'false' }}" class="text-dark d-block">
                                                    <i class="fas fa-chevron-right text-primary mr-2"></i>
                                                    {{ $faq->question ?? '-' }}
                                                </a>
                                            </h6>
                                        </div>
                                        <div id="faq{{ $key }}" class="collapse {{ $key == 0 ? 'show' : '' }}" data-parent="#faqAccordion">
                                            <div class="card-body">
                                                {!! $faq->answer ?? '-' !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-question-circle fa-3x mb-2 text-black-50"></i>
                                <p class="mb-0">No FAQs Available.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Gallery Images --}}
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4><i class="fas fa-images text-primary mr-2"></i> Puja Gallery</h4>
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
                                        <div class="card border h-100 shadow-sm">
                                            <img src="{{ asset('storage/' . $image) }}"
                                                 class="card-img-top rounded-top"
                                                 alt="Puja Gallery"
                                                 style="height: 180px; object-fit: cover;">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-images fa-3x mb-2 text-black-50"></i>
                                <p class="mb-0">No Gallery Images Available.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Actions --}}
        <div class="row mb-4">
            <div class="col-12 text-right">
                <a href="{{ route('online-pujas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                <a href="{{ route('online-pujas.edit', $online_puja->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Online Puja
                </a>
            </div>
        </div>

    </div>

</section>

@endsection

@push('scripts')
<script>
    function confirmStatusUpdate() {
        Swal.fire({
            title: 'Mark as Completed?',
            text: "Are you sure you want to mark this online puja as completed?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('statusUpdateForm').submit();
            }
        })
    }

    // Optional: Display session success message via SweetAlert if flashed from controller
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
</script>
@endpush

@else
    @php
        abort(403);
    @endphp
@endcan