@extends('admin.layouts.app')
@section('title', 'Temple Product Listing')
@section('content')
@can('product-temple.view')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Temple Product Listing</h4>
                        <div class="card-header-action">
                            @can('product-temple.create')
                                <a href="{{ route('admin.temple-products.create') }}"
                                   class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add Product
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Product Name</th>
                                        <th>SKU</th>
                                        <th>Price (₹)</th>
                                        <th>Qty</th>
                                        <th>Unit</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $product)
                                        <tr>
                                            <td class="text-center">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                <strong>
                                                    {{ $product->name }}
                                                </strong>
                                            </td>
                                            <td>
                                                {{ $product->sku ?: '-' }}
                                            </td>
                                            <td>
                                                ₹{{ number_format((float) $product->price, 2) }}
                                            </td>
                                            <td>
                                                {{ $product->qty ?? 0 }}
                                            </td>
                                            <td>
                                                {{ ucfirst($product->unit ?? 'piece') }}
                                            </td>
                                            <td>
                                                @if($product->status)
                                                    <span class="badge badge-success badge-shadow">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger badge-shadow">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @can('product-temple.show')
                                                        <a href="{{ route('admin.temple-products.show', $product->id) }}"
                                                           class="btn btn-info btn-sm mr-1"
                                                           title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endcan
                                                    @can('product-temple.edit')
                                                        <a href="{{ route('admin.temple-products.edit', $product->id) }}"
                                                           class="btn btn-primary btn-sm mr-1"
                                                           title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                    @can('product-temple.delete')
                                                        <form action="{{ route('admin.temple-products.destroy', $product->id) }}"
                                                            method="POST" class="d-inline delete-product-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button  type="submit" class="btn btn-danger btn-sm"
                                                                title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                No temple products found.
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
@else
    @php abort(403); @endphp
@endcan
@endsection
@push('scripts')
<script>
$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#table-1')) {
        $('#table-1').DataTable().destroy();
    }
    $('#table-1').DataTable({
        ordering: true,
        order: [],
        searching: true,
        paging: true,
        info: true,
        pageLength: 10,
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, 'All']
        ],
        columnDefs: [
            {
                orderable: false,
                targets: [0, 7]
            }
        ],
        language: {
            emptyTable: 'No temple products found.',
            zeroRecords: 'No matching products found.',
            search: 'Search:',
            lengthMenu: 'Show _MENU_ entries',
            info: 'Showing _START_ to _END_ of _TOTAL_ products',
            infoEmpty: 'Showing 0 to 0 of 0 products'
        }
    });
    $(document).on('submit', '.delete-product-form', function (e) {
        e.preventDefault();
        let form = this;
        Swal.fire({
            title: 'Are you sure?',
            text: 'This product will be deleted permanently.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#fc544b',
            cancelButtonColor: '#6777ef',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel'
        }).then(function (result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
@if(session('success'))
    Swal.fire({
        title: 'Success!',
        text: @json(session('success')),
        icon: 'success',
        confirmButtonColor: '#6777ef',
        confirmButtonText: 'OK'
    });
@endif
@if(session('error'))
    Swal.fire({
        title: 'Error!',
        text: @json(session('error')),
        icon: 'error',
        confirmButtonColor: '#fc544b',
        confirmButtonText: 'OK'
    });
@endif
</script>
@endpush