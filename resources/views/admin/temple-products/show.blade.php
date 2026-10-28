
@extends('admin.layouts.app')

@section('content')
    @can('product-temple.show')
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Temple Product Details</h4>
                                <div class="card-header-action">
                                    @can('product-temple.edit')
                                        <a href="{{ route('admin.temple-products.edit', $product->id) }}" class="btn btn-primary">
                                            <i class="fas fa-edit"></i> Edit Product
                                        </a>
                                    @endcan

                                    <a href="{{ route('admin.temple-products.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back to Products
                                    </a>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th width="25%">Product Name</th>
                                                <td>{{ $product->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>SKU</th>
                                                <td>{{ $product->sku ?: '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Price</th>
                                                <td>{{ number_format((float) $product->price, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Description</th>
                                                <td>
                                                    {!! nl2br(e($product->description ?: '-')) !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    @if($product->status)
                                                        <span class="badge badge-success badge-shadow">Active</span>
                                                    @else
                                                        <span class="badge badge-danger badge-shadow">Inactive</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Created At</th>
                                                <td>{{ $product->created_at?->format('d-m-Y h:i A') ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Last Updated</th>
                                                <td>{{ $product->updated_at?->format('d-m-Y h:i A') ?? '-' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <a href="{{ route('admin.temple-products.index') }}" class="btn btn-secondary">
                                    Back
                                </a>

                                @can('product-temple.edit')
                                    <a href="{{ route('admin.temple-products.edit', $product->id) }}" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> Edit Product
                                    </a>
                                @endcan
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
