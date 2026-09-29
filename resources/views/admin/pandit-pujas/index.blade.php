@can('pandit-pujas.index')
@extends('admin.layouts.app')

@section('title', 'Pandit Puja Management')

@section('content')

<section class="section">
    <div class="section-body">

        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">

                        <h4>Pandit Puja Management</h4>

                        <div class="card-header-action">
                            <a href="{{ route('pandit-pujas.create') }}"
                               class="btn btn-primary">
                                <i class="fas fa-plus"></i> Assign Puja
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
                                        <th class="text-center">#</th>
                                        <th>Pandit</th>
                                        <th>Temple</th>
                                        <th>Puja</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($panditPujas as $panditPuja)

                                    <tr>

                                        <td class="text-center">
                                            {{ $panditPujas->firstItem() + $loop->index }}
                                        </td>

                                        <td>
                                            {{ $panditPuja->pandit->name ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $panditPuja->pandit->temple->name ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $panditPuja->puja->name ?? '-' }}
                                        </td>

                                        <td>

                                            <div class="d-flex align-items-center justify-content-center">

                                                <a href="{{ route('pandit-pujas.edit', $panditPuja->id) }}"
                                                   class="btn btn-primary btn-sm mr-1">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('pandit-pujas.destroy', $panditPuja->id) }}"
                                                      method="POST"
                                                      class="d-inline delete-pandit-puja-form">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>

                                    @empty

                                    <tr>
                                        <td colspan="5" class="text-center">
                                            No pandit puja mappings found.
                                        </td>
                                    </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                        <div class="mt-3">
                            {{ $panditPujas->links() }}
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
    $('.delete-pandit-puja-form').on('submit', function(e) {
        e.preventDefault();

        let form = this;

        Swal.fire({
            title: 'Are you sure?',
            text: 'This puja assignment will be removed.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#fc544b',
            cancelButtonColor: '#6777ef',
            confirmButtonText: 'Yes, remove',
            cancelButtonText: 'Cancel'
        }).then(function(result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>

@endpush
@else
    @php
        abort(403);
    @endphp
@endcan
