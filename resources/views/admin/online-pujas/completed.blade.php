@can('online-puja.completed')
@extends('admin.layouts.app')

@section('title', 'Completed Online Pujas')

@section('content')

<section class="section">

    {{-- Page Header --}}
    <div class="section-header">
        <h1>Completed Online Pujas</h1>

        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </div>
            <div class="breadcrumb-item">
                <a href="{{ route('online-pujas.index') }}">Online Pujas</a>
            </div>
            <div class="breadcrumb-item active">Completed Pujas</div>
        </div>
    </div>

    <div class="section-body">

        {{-- Top Actions --}}
        <div class="row mb-3">
            <div class="col-12 text-right">
                <a href="{{ route('online-pujas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to All Pujas
                </a>
            </div>
        </div>

        {{-- Completed Pujas Table Card --}}
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4><i class="fas fa-check-circle text-success mr-2"></i> Completed Pujas List</h4>
                    </div>

                    <div class="card-body">
                        @if($onlinePujas->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover align-middle">
                                    <thead>
                                        <tr class="table-secondary">
                                            <th width="5%">#</th>
                                            <th>Puja Title</th>
                                            <th>Category</th>
                                            <th>Temple Name</th>
                                            <th>Puja Date & Time</th>
                                            <th>Status</th>
                                            <th width="15%" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($onlinePujas as $key => $puja)
                                            <tr>
                                                <td>{{ $onlinePujas->firstItem() + $key }}</td>
                                                <td><strong>{{ $puja->title ?? '-' }}</strong></td>
                                                <td>
                                                    <span class="badge badge-light text-dark border">
                                                        {{ $puja->category->name ?? '-' }}
                                                    </span>
                                                </td>
                                                <td>{{ $puja->temple_name ?? '-' }}</td>
                                                <td>
                                                    <i class="far fa-calendar-alt text-muted mr-1"></i>
                                                    {{ $puja->puja_datetime ? \Carbon\Carbon::parse($puja->puja_datetime)->format('d-m-Y h:i A') : '-' }}
                                                </td>
                                                <td>
                                                    <span class="badge badge-info px-3 py-2">Completed</span>
                                                </td>
                                                <td class="text-center">
                                                   <a href="{{ route('online-pujas.bookings', $puja->id) }}"
                                                       class="btn btn-sm btn-success mr-2"
                                                       title="Customer Bookings">
                                                        <i class="fas fa-calendar-check"></i>
                                                    </a>
                                                    <a href="{{ route('online-pujas.show', $puja->id) }}"
                                                       class="btn btn-sm btn-info mr-2"
                                                       title="View Online Puja">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination Links --}}
                            <div class="card-footer px-0 pb-0">
                                <div class="d-flex justify-content-center">
                                    {{ $onlinePujas->links() }}
                                </div>
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-check-circle fa-3x mb-2 text-black-50"></i>
                                <p class="mb-0">No Completed Online Pujas Found.</p>
                            </div>
                        @endif
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