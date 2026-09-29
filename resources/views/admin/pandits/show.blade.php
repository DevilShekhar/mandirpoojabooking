@can('pandits.view')
@extends('admin.layouts.app')

@section('title', 'Pandit Details')

@section('content')

<section class="section">
    <div class="section-body">

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>Pandit Details</h4>

                        <div class="card-header-action">
                            <a href="{{ route('pandits.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>

                            <a href="{{ route('pandits.edit', $pandit->id) }}"
                               class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-3 text-center">

                                @if($pandit->profile_image)

                                    <img src="{{ asset('storage/' . $pandit->profile_image) }}"
                                         class="img-fluid"
                                         style="max-height:220px; border-radius:8px;">

                                @else

                                    <div class="text-muted">
                                        No Image
                                    </div>

                                @endif

                            </div>

                            <div class="col-md-9">

                                <table class="table table-bordered">

                                    <tr>
                                        <th width="30%">Name</th>
                                        <td>{{ $pandit->name }}</td>
                                    </tr>

                                    <tr>
                                        <th>Mobile</th>
                                        <td>{{ $pandit->mobile }}</td>
                                    </tr>

                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $pandit->email ?? '-' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Temple</th>
                                        <td>{{ $pandit->temple->name ?? '-' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Experience</th>
                                        <td>{{ $pandit->experience }} Years</td>
                                    </tr>

                                    <tr>
                                        <th>Languages</th>
                                        <td>{{ $pandit->languages ?? '-' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Specialization</th>
                                        <td>{{ $pandit->specialization ?? '-' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Verification</th>
                                        <td>{{ ucfirst($pandit->verification_status) }}</td>
                                    </tr>

                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if($pandit->status)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>

                                </table>

                            </div>

                        </div>

                        <div class="section-title">
                            Assigned Pujas
                        </div>

                        <div class="table-responsive">

                            <table class="table table-striped">

                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Puja</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($pandit->panditPujas as $panditPuja)

                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $panditPuja->puja->name ?? '-' }}</td>
                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="2" class="text-center">
                                                No pujas assigned.
                                            </td>
                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

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
