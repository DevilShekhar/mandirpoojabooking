@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Add Temple Product</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Product Details</h4>
                        </div>

                        <form action="{{ route('admin.temple-products.store') }}" method="POST">
                            @csrf

                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Product SKU will be generated automatically using your temple name.
                                </div>

                                <div class="form-group">
                                    <label for="name">Product Name <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}"
                                        placeholder="Enter product name"
                                        maxlength="255"
                                        required
                                    >
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea
                                        name="description"
                                        id="description"
                                        class="form-control @error('description') is-invalid @enderror"
                                        rows="3"
                                        placeholder="Enter product description"
                                    >{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="price">Price (₹) <span class="text-danger">*</span></label>
                                            <input
                                                type="number"
                                                name="price"
                                                id="price"
                                                class="form-control @error('price') is-invalid @enderror"
                                                value="{{ old('price') }}"
                                                placeholder="Enter price"
                                                min="0"
                                                max="99999999.99"
                                                step="0.01"
                                                required
                                            >
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="qty">Quantity <span class="text-danger">*</span></label>
                                            <input
                                                type="number"
                                                name="qty"
                                                id="qty"
                                                class="form-control @error('qty') is-invalid @enderror"
                                                value="{{ old('qty', 0) }}"
                                                placeholder="Enter quantity"
                                                min="0"
                                                step="1"
                                                required
                                            >
                                            @error('qty')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="unit">Unit <span class="text-danger">*</span></label>
                                            <select
                                                name="unit"
                                                id="unit"
                                                class="form-control select2 @error('unit') is-invalid @enderror"
                                                required
                                            >
                                                <option value="">Select Unit</option>
                                                @foreach ([
                                                    'piece' => 'Piece',
                                                    'packet' => 'Packet',
                                                    'pack' => 'Pack',
                                                    'box' => 'Box',
                                                    'ml' => 'Millilitre (ml)',
                                                    'litre' => 'Litre',
                                                    'gram' => 'Gram (g)',
                                                    'kg' => 'Kilogram (kg)',
                                                    'mg' => 'Milligram (mg)',
                                                    'metre' => 'Metre',
                                                    'cm' => 'Centimetre (cm)',
                                                    'roll' => 'Roll',
                                                    'kit' => 'Kit',
                                                    'dozen' => 'Dozen',
                                                    'pair' => 'Pair',
                                                    'set' => 'Set',
                                                    'bottle' => 'Bottle',
                                                    'jar' => 'Jar',
                                                    'bag' => 'Bag',
                                                    'bundle' => 'Bundle',
                                                    'mala' => 'Mala',
                                                    'garland' => 'Garland',
                                                    'offering' => 'Offering',
                                                    'plate' => 'Plate',
                                                    'bowl' => 'Bowl',
                                                    'diya' => 'Diya',
                                                    'stick' => 'Stick',
                                                    'bunch' => 'Bunch',
                                                    'basket' => 'Basket',
                                                    'thali' => 'Thali',
                                                    'pouch' => 'Pouch',
                                                    'container' => 'Container',
                                                    'can' => 'Can',
                                                    'sachet' => 'Sachet',
                                                    'tube' => 'Tube',
                                                    'string' => 'String',
                                                    'leaf' => 'Leaf',
                                                    'flower' => 'Flower',
                                                    'coconut' => 'Coconut',
                                                    'per_person' => 'Per Person',
                                                    'per_day' => 'Per Day',
                                                ] as $value => $label)
                                                    <option value="{{ $value }}" {{ old('unit', 'piece') === $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('unit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Status</label>
                                    <div>
                                        <input type="hidden" name="status" value="0">
                                        <div class="custom-control custom-switch">
                                            <input
                                                type="checkbox"
                                                name="status"
                                                value="1"
                                                id="status"
                                                class="custom-control-input"
                                                {{ old('status', '1') == '1' ? 'checked' : '' }}
                                            >
                                            <label class="custom-control-label" for="status">Active</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <a href="{{ route('admin.temple-products.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Product
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection