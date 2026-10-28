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
                                            <label for="qty" class="form-label">Quantity</label>
                                            <input type="number" name="qty" id="qty" class="form-control" min="0" step="1"
                                                value="{{ old('qty', $product->qty ?? 0) }}" required >
                                            @error('qty')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="unit" class="form-label">Product Unit</label>

                                            <select name="unit" id="unit"  class="form-control" required>
                                                <option value="piece" {{ old('unit', $product->unit ?? 'piece') == 'piece' ? 'selected' : '' }}>Piece</option>
                                                <option value="packet" {{ old('unit', $product->unit ?? '') == 'packet' ? 'selected' : '' }}>Packet</option>
                                                <option value="box" {{ old('unit', $product->unit ?? '') == 'box' ? 'selected' : '' }}>Box</option>
                                                <option value="ml" {{ old('unit', $product->unit ?? '') == 'ml' ? 'selected' : '' }}>Millilitre (ml)</option>
                                                <option value="litre" {{ old('unit', $product->unit ?? '') == 'litre' ? 'selected' : '' }}>Litre</option>
                                                <option value="gram" {{ old('unit', $product->unit ?? '') == 'gram' ? 'selected' : '' }}>Gram (g)</option>
                                                <option value="kg" {{ old('unit', $product->unit ?? '') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                                <option value="roll" {{ old('unit', $product->unit ?? '') == 'roll' ? 'selected' : '' }}>Roll</option>
                                                <option value="kit" {{ old('unit', $product->unit ?? '') == 'kit' ? 'selected' : '' }}>Kit</option>
                                                <option value="dozen" {{ old('unit', $product->unit ?? '') == 'dozen' ? 'selected' : '' }}>Dozen</option>
                                            </select>

                                            @error('unit')
                                                <div class="text-danger">{{ $message }}</div>
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