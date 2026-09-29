@can('cities.index')
@extends('admin.layouts.app')

@section('title', 'City Management')

@section('content')

    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>City Management</h4>
                            <div class="card-header-action">
                                <a href="{{ route('cities.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add City
                                </a>
                            </div>
                        </div>


                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Country</th>
                                            <th>State</th>
                                            <th>District</th>
                                            <th>City</th>
                                            <th>Pincode</th>
                                            <th>Latitude</th>
                                            <th>Longitude</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($cities as $city)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>

                                                <td>{{ $city->country->name ?? '-' }}</td>

                                                <td>{{ $city->state->name ?? '-' }}</td>

                                                <td>{{ $city->district->name ?? '-' }}</td>

                                                <td>{{ $city->name }}</td>

                                                <td>{{ $city->pincode ?? '-' }}</td>

                                                <td>{{ $city->latitude ?? '-' }}</td>

                                                <td>{{ $city->longitude ?? '-' }}</td>

                                                <td>
                                                    @if($city->status)
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-danger">Inactive</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <div class="d-flex">
                                                        <a href="{{ route('cities.edit', $city->id) }}"
                                                            class="btn btn-sm btn-primary mr-1" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <form action="{{ route('cities.destroy', $city->id) }}" method="POST"
                                                            class="delete-city-form">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
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

@push('scripts')

    <script>
        $(document).ready(function () {
            $('#table-1').DataTable({
                "columnDefs": [
                    {
                        "sortable": false,
                        "targets": [9]
                    }
                ]
            });

            $('.delete-city-form').on('submit', function (e) {
                e.preventDefault();

                let form = this;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}'
            });
        @endif
    </script>

@endpush
@else
    @php
        abort(403);
    @endphp
@endcan
