@extends('admin.layouts.app')

@section('content')

<section class="section">
    <div class="section-body">

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>User Details</h4>

                        <div class="card-header-action">

                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary mr-1">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>

                        </div>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4">

                                <div class="text-center">

                                    @if($user->profile)

                                        <img
                                            src="{{ asset('storage/' . $user->profile) }}"
                                            alt="{{ $user->name }}"
                                            width="180"
                                            height="180"
                                            class="rounded-circle"
                                            style="object-fit: cover;"
                                        >

                                    @else

                                        <img
                                            src="{{ asset('assets/img/users/user-1.png') }}"
                                            alt="{{ $user->name }}"
                                            width="180"
                                            height="180"
                                            class="rounded-circle"
                                        >

                                    @endif

                                    <h5 class="mt-3 mb-1">
                                        {{ $user->name }}
                                    </h5>

                                    <p class="text-muted">
                                        {{ $user->email }}
                                    </p>

                                    @if($user->status)

                                        <div class="badge badge-success badge-shadow">
                                            Active
                                        </div>

                                    @else

                                        <div class="badge badge-danger badge-shadow">
                                            Inactive
                                        </div>

                                    @endif

                                </div>

                            </div>

                            <div class="col-md-8">

                                <div class="section-title mt-0">
                                    Personal Information
                                </div>

                                <div class="table-responsive">

                                    <table class="table table-bordered">

                                        <tbody>

                                            <tr>
                                                <th width="35%">Name</th>
                                                <td>{{ $user->name }}</td>
                                            </tr>

                                            <tr>
                                                <th>Email</th>
                                                <td>{{ $user->email }}</td>
                                            </tr>

                                            <tr>
                                                <th>Phone Number</th>
                                                <td>{{ $user->phone ?? '-' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Gender</th>
                                                <td>
                                                    {{ $user->gender ? ucfirst($user->gender) : '-' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Birth Date</th>
                                                <td>
                                                    {{ $user->birth_date ? $user->birth_date->format('d-m-Y') : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Role</th>
                                                <td>
                                                    {{ $user->role ? $user->role->name : '-' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Status</th>
                                                <td>

                                                    @if($user->status)

                                                        <div class="badge badge-success badge-shadow">
                                                            Active
                                                        </div>

                                                    @else

                                                        <div class="badge badge-danger badge-shadow">
                                                            Inactive
                                                        </div>

                                                    @endif

                                                </td>
                                            </tr>

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                        <div class="section-title">
                            Account Information
                        </div>

                        <div class="table-responsive">

                            <table class="table table-bordered">

                                <tbody>

                                    <tr>
                                        <th width="35%">
                                            User ID
                                        </th>

                                        <td>
                                            #{{ $user->id }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>
                                            Email Verification
                                        </th>

                                        <td>

                                            @if($user->email_verified_at)

                                                <span class="badge badge-success">
                                                    Verified
                                                </span>

                                                <small class="text-muted ml-2">
                                                    {{ $user->email_verified_at->format('d-m-Y H:i') }}
                                                </small>

                                            @else

                                                <span class="badge badge-warning">
                                                    Not Verified
                                                </span>

                                            @endif

                                        </td>
                                    </tr>

                                    <tr>
                                        <th>
                                            Created At
                                        </th>

                                        <td>
                                            {{ $user->created_at->format('d-m-Y H:i') }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>
                                            Last Updated
                                        </th>

                                        <td>
                                            {{ $user->updated_at->format('d-m-Y H:i') }}
                                        </td>
                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                    <div class="card-footer text-right">

                        <a
                            href="{{ route('admin.users.index') }}"
                            class="btn btn-secondary mr-1"
                        >
                            <i class="fas fa-arrow-left"></i>
                            Back to Users
                        </a>

                        <a
                            href="{{ route('admin.users.edit', $user->id) }}"
                            class="btn btn-primary"
                        >
                            <i class="fas fa-edit"></i>
                            Edit User
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>

@endsection
