@extends('admin.layouts.app')
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
                                        <a href="{{ route('admin.temple-products.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Add Product
                                        </a>
                                    @endcan
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-1">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Product Name</th>
                                                <th>SKU</th>
                                                <th>Price</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($products as $product)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $products->firstItem() + $loop->index }}
                                                    </td>
                                                    <td>
                                                        <strong>{{ $product->name }}</strong>
                                                    </td>
                                                    <td>
                                                        {{ $product->sku ?: '-' }}
                                                    </td>
                                                    <td>
                                                        {{ number_format((float) $product->price, 2) }}
                                                    </td>
                                                    <td>
                                                        @if($product->status)
                                                            <div class="badge badge-success badge-shadow">
                                                                Active
                                                            </div>
                                                        @else
                                                            <div class="badge badge-danger badge-shadow">
                                                                Inactive
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @can('product-temple.show')
                                                                <a
                                                                    href="{{ route('admin.temple-products.show', $product->id) }}"
                                                                    class="btn btn-info btn-sm mr-1"
                                                                    title="View"
                                                                >
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            @endcan

                                                            @can('product-temple.edit')
                                                                <a
                                                                    href="{{ route('admin.temple-products.edit', $product->id) }}"
                                                                    class="btn btn-primary btn-sm mr-1"
                                                                    title="Edit"
                                                                >
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            @endcan

                                                            @can('product-temple.delete')
                                                                <form
                                                                    action="{{ route('admin.temple-products.destroy', $product->id) }}"
                                                                    method="POST"
                                                                    class="delete-product-form"
                                                                >
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button
                                                                        type="submit"
                                                                        class="btn btn-danger btn-sm"
                                                                        title="Delete"
                                                                    >
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">
                                                        No temple products found.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3">
                                    {{ $products->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        @php
            abort(403);
        @endphp
    @endcan
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#table-1').DataTable({
                ordering: true,
                searching: true,
                paging: false,
                info: true,
                columnDefs: [
                    {
                        orderable: false,
                        targets: [0, 5]
                    }
                ]
            });

            $('.delete-product-form').on('submit', function (e) {
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
    </script>

    @if(session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: @json(session('success')),
                icon: 'success',
                confirmButtonColor: '#6777ef',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
@endpush