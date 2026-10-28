@extends('admin.layouts.app')

@section('content')
    @can('product-temple.edit')
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Edit Temple Product</h4>
                            </div>

                            <form action="{{ route('admin.temple-products.update', $product->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-lg-6">
                                            <label for="name">Product Name <span class="text-danger">*</span></label>
                                            <input
                                                type="text"
                                                name="name"
                                                id="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name', $product->name) }}"
                                                placeholder="Enter product name"
                                                required
                                            >
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-lg-6">
                                            <label for="sku">SKU</label>
                                            <input
                                                type="text"
                                                name="sku"
                                                id="sku"
                                                class="form-control @error('sku') is-invalid @enderror"
                                                value="{{ old('sku', $product->sku) }}"
                                                placeholder="Enter product SKU"
                                            >
                                            @error('sku')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-lg-6">
                                            <label for="description">Description</label>
                                            <textarea
                                                name="description"
                                                id="description"
                                                class="form-control @error('description') is-invalid @enderror"
                                                rows="4"
                                                placeholder="Enter product description"
                                            >{{ old('description', $product->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-lg-6">
                                            <label for="price">Price <span class="text-danger">*</span></label>
                                            <input
                                                type="number"
                                                name="price"
                                                id="price"
                                                class="form-control @error('price') is-invalid @enderror"
                                                value="{{ old('price', $product->price) }}"
                                                min="0"
                                                max="99999999.99"
                                                step="0.01"
                                                placeholder="Enter price"
                                                required
                                            >
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-lg-6">
                                            <label for="status">Status</label>
                                            <div class="custom-control custom-switch">
                                                <input type="hidden" name="status" value="0">
                                                <input
                                                    type="checkbox"
                                                    name="status"
                                                    id="status"
                                                    value="1"
                                                    class="custom-control-input"
                                                    {{ old('status', $product->status ? '1' : '0') == '1' ? 'checked' : '' }}
                                                >
                                                <label class="custom-control-label" for="status">Active</label>
                                            </div>
                                            @error('status')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer text-right">
                                    <a href="{{ route('admin.temple-products.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Product
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        @php abort(403); @endphp
    @endcan
@endsection