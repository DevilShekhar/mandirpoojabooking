@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Set New Password</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            New Password <span class="text-danger">*</span>
                        </label>
                        <input type="password" name="new_password"  class="form-control @error('new_password') is-invalid @enderror" placeholder="Enter new password" autocomplete="new-password" required>
                        @error('new_password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Confirm New Password <span class="text-danger">*</span>
                        </label>
                        <input type="password" name="new_password_confirmation"  class="form-control" placeholder="Confirm new password"  autocomplete="new-password" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('admin.profile.index') }}" class="btn btn-secondary">
                        <i data-feather="arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="save"></i> Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection