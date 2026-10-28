@extends('admin.layouts.app')
@section('content')
    @can('store-managers.show')
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Store Manager Details</h4>

                                <div class="card-header-action">
                                    @can('store-managers.edit')
                                        <a href="{{ route('admin.store-managers.edit', $storeManager->id) }}"
                                           class="btn btn-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    @endcan

                                    @canany(['store-managers.view', 'store-managers.index'])
                                        <a href="{{ route('admin.store-managers.index') }}"
                                           class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Back
                                        </a>
                                    @endcanany
                                </div>
                            </div>

                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-4 text-center mb-4">
                                        @if($storeManager->profile)
                                            <img src="{{ asset('storage/' . $storeManager->profile) }}"
                                                 alt="Profile"
                                                 width="150"
                                                 height="150"
                                                 class="rounded-circle mb-3"
                                                 style="object-fit: cover;">
                                        @else
                                            <img src="{{ asset('assets/img/users/user-1.png') }}"
                                                 alt="Default profile"
                                                 width="150"
                                                 height="150"
                                                 class="rounded-circle mb-3">
                                        @endif

                                        <h5>{{ $storeManager->name }}</h5>

                                        <span class="badge badge-primary">
                                            Store Manager
                                        </span>

                                        <div class="mt-2">
                                            @if($storeManager->status)
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

                                    <div class="col-md-8">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th width="35%">Full Name</th>
                                                        <td>{{ $storeManager->name }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Email Address</th>
                                                        <td>{{ $storeManager->email }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Phone</th>
                                                        <td>{{ $storeManager->phone ?? '-' }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Gender</th>
                                                        <td>
                                                            {{ $storeManager->gender ? ucfirst($storeManager->gender) : '-' }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Birth Date</th>
                                                        <td>
                                                            {{ $storeManager->birth_date ? $storeManager->birth_date->format('d-m-Y') : '-' }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Role</th>
                                                        <td>
                                                            <span class="badge badge-primary">
                                                                Store Manager
                                                            </span>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Account Status</th>
                                                        <td>
                                                            @if($storeManager->status)
                                                                <span class="badge badge-success">Active</span>
                                                            @else
                                                                <span class="badge badge-danger">Inactive</span>
                                                            @endif
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Created By</th>
                                                        <td>{{ optional($storeManager->creator)->name ?? '-' }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Updated By</th>
                                                        <td>{{ optional($storeManager->updater)->name ?? '-' }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Created At</th>
                                                        <td>
                                                            {{ $storeManager->created_at ? $storeManager->created_at->format('d-m-Y h:i A') : '-' }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Last Updated</th>
                                                        <td>
                                                            {{ $storeManager->updated_at ? $storeManager->updated_at->format('d-m-Y h:i A') : '-' }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                @canany(['store-managers.view', 'store-managers.index'])
                                    <a href="{{ route('admin.store-managers.index') }}"
                                       class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back to Listing
                                    </a>
                                @endcanany

                                @can('store-managers.edit')
                                    <a href="{{ route('admin.store-managers.edit', $storeManager->id) }}"
                                       class="btn btn-primary">
                                        <i class="fas fa-edit"></i> Edit Store Manager
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="section">
            <div class="section-body">
                <div class="alert alert-danger">
                    You do not have permission to view Store Manager details.
                </div>
            </div>
        </section>
    @endcan
@endsection