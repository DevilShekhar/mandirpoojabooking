@can('darshan-verification-officers.show')
@extends('admin.layouts.app')
@section('title', 'View Darshan Verification Officer')
@section('content')
@php
    $profileImage = !empty($officer->profile)
        ? asset('storage/' . $officer->profile)
        : asset('images/default-user.png');
@endphp
<div class="section">
    <div class="section-header">
        <h1>
            <i class="fas fa-user"></i>
            Darshan Verification Officer Details
        </h1>     
    </div>
    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-4 col-md-5">
                <div class="card card-primary">
                    <div class="card-body text-center">
                        <img
                            src="{{ $profileImage }}"
                            class="rounded-circle shadow mb-3"
                            style="width:140px;height:140px;object-fit:cover;border:4px solid #fff;"
                            onerror="this.src='{{ asset('images/default-user.png') }}'"
                            alt="Officer Profile"
                        >
                        <h4 class="mb-1">{{ $officer->name }}</h4>
                        <p class="text-muted mb-2">
                            {{ $officer->email }}
                        </p>
                        <span class="badge badge-primary">
                            Darshan Verification Officer
                        </span>
                        <div class="mt-3">
                            @if($officer->status)
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle"></i> Active
                                </span>
                            @else
                                <span class="badge badge-danger">
                                    <i class="fas fa-times-circle"></i> Inactive
                                </span>
                            @endif
                        </div>
                        <hr>
                        <div class="text-left">
                            <p>
                                <strong><i class="fas fa-phone "></i> Mobile:</strong>
                                <span class="float-right">
                                    {{ $officer->phone ?: 'Not Available' }}
                                </span>
                            </p>
                            <p>
                                <strong><i class="fas fa-venus-mars "></i> Gender:</strong>
                                <span class="float-right">
                                    {{ $officer->gender ? ucfirst($officer->gender) : 'Not Available' }}
                                </span>
                            </p>
                            <p class="mb-0">
                                <strong><i class="fas fa-calendar "></i> Date of Birth:</strong>
                                <span class="float-right">
                                    {{ $officer->birth_date ? \Carbon\Carbon::parse($officer->birth_date)->format('d M Y') : 'Not Available' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fas fa-place-of-worship"></i>
                            Assigned Temple
                        </h4>
                    </div>
                    <div class="card-body">
                        <h6 class="">
                            {{ $officer->temple->name ?? 'Not Assigned' }}
                        </h6>
                        <small class="text-muted">
                            Temple assigned to this verification officer.
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-7">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>
                            <i class="fas fa-info-circle"></i>
                            Officer Information
                        </h4>
                        <div class="card-header-action">
                            @can('darshan-verification-officers.edit')
                                <a href="{{ route('admin.darshan-verification-officers.edit', $officer->id) }}"
                                    class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit Officer
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class=" border-bottom pb-2 mb-3">
                            <i class="fas fa-user"></i>
                            Personal Details
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Full Name</small>
                                <h6>{{ $officer->name }}</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Email Address</small>
                                <h6>{{ $officer->email }}</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Mobile Number</small>
                                <h6>{{ $officer->phone ?: 'Not Available' }}</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Gender</small>
                                <h6>
                                    {{ $officer->gender ? ucfirst($officer->gender) : 'Not Available' }}
                                </h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Date of Birth</small>
                                <h6>
                                    {{ $officer->birth_date ? \Carbon\Carbon::parse($officer->birth_date)->format('d M Y') : 'Not Available' }}
                                </h6>
                            </div>
                        </div>
                        <hr>
                        <h6 class=" border-bottom pb-2 mb-3">
                            <i class="fas fa-place-of-worship"></i>
                            Temple Details
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Temple Name</small>
                                <h6>{{ $officer->temple->name ?? 'Not Assigned' }}</h6>
                            </div>                          
                        </div>
                        <hr>
                        <h6 class=" border-bottom pb-2 mb-3">
                            <i class="fas fa-user-shield"></i>
                            Account Details
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Assigned Role</small>
                                <h6>Darshan Verification Officer</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Account Status</small>
                                <h6>
                                    @if($officer->status)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Created At</small>
                                <h6>
                                    {{ $officer->created_at ? $officer->created_at->format('d M Y, h:i A') : 'Not Available' }}
                                </h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Last Updated</small>
                                <h6>
                                    {{ $officer->updated_at ? $officer->updated_at->format('d M Y, h:i A') : 'Not Available' }}
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('admin.darshan-verification-officers.index') }}"  class="btn btn-light">
                            <i class="fas fa-arrow-left"></i>
                            Back to List
                        </a>
                        @can('darshan-verification-officers.edit')
                            <a href="{{ route('admin.darshan-verification-officers.edit', $officer->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i>
                                Edit Officer
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
    .card {
        border-radius: 8px;
    }
    .card-header h4 {
        font-weight: 600;
    }
    .card-body h6 {
        font-weight: 600;
        color: #34395e;
        margin-top: 5px;
    }
    .card-body small {
        font-size: 12px;
    }
    .badge {
        padding: 6px 10px;
        border-radius: 5px;
    }
</style>
@endpush
@endcan