@can('temple-deities.view')
@extends('admin.layouts.app')

@section('title', 'Temple Deities')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Temple Deities</h4>
                        <div class="card-header-action">
                            <a href="{{ route('temple-deities.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Temple Deity
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Temple</th>
                                        <th>Deity</th>
                                        <th>Primary</th>
                                        <th>Display Order</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($templeDeities as $templeDeity)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>

                                            <td>
                                                {{ $templeDeity->temple->name ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $templeDeity->deity->name ?? '-' }}
                                            </td>

                                            <td>
                                                @if($templeDeity->is_primary)
                                                    <span class="badge badge-success">Yes</span>
                                                @else
                                                    <span class="badge badge-secondary">No</span>
                                                @endif
                                            </td>

                                            <td>
                                                {{ $templeDeity->display_order }}
                                            </td>

                                            <td>
                                                @if($templeDeity->status)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>

                                            <td>
                                                <a href="{{ route('temple-deities.edit', $templeDeity->id) }}"
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('temple-deities.destroy', $templeDeity->id) }}"
                                                      method="POST"
                                                      class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
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
    $('#table-1').DataTable();

    $('.delete-form').on('submit', function (e) {
        e.preventDefault();

        let form = this;

        Swal.fire({
            title: 'Are you sure?',
            text: "This temple deity will be deactivated.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, deactivate it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
@else
    @php
        abort(404);
    @endphp
@endcan
