@can('festival-pujas.createa')
@extends('admin.layouts.app')

@section('title', 'Create Festival Puja')

@section('content')

<section class="section">
    <div class="section-body">

        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>Create Festival Puja</h4>

                        <div class="card-header-action">
                            <a href="{{ route('festival-pujas.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('festival-pujas.store') }}">
                        @csrf

                        <div class="card-body">

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="section-title mt-0">
                                Festival Puja Information
                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-6">

                                    <label for="festival_id">
                                        Festival <span class="text-danger">*</span>
                                    </label>

                                    <select name="festival_id"
                                        id="festival_id"
                                        class="form-control @error('festival_id') is-invalid @enderror"
                                        required>

                                        <option value="">Select Festival</option>

                                        @foreach($festivals as $festival)
                                            <option value="{{ $festival->id }}"
                                                {{ old('festival_id') == $festival->id ? 'selected' : '' }}>
                                                {{ $festival->name }}
                                            </option>
                                        @endforeach

                                    </select>

                                    @error('festival_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="form-group col-md-6">

                                    <label for="puja_id">
                                        Puja <span class="text-danger">*</span>
                                    </label>

                                    <select name="puja_id"
                                        id="puja_id"
                                        class="form-control @error('puja_id') is-invalid @enderror"
                                        required>

                                        <option value="">Select Puja</option>

                                        @foreach($pujas as $puja)
                                            <option value="{{ $puja->id }}"
                                                {{ old('puja_id') == $puja->id ? 'selected' : '' }}>
                                                {{ $puja->name }}
                                            </option>
                                        @endforeach

                                    </select>

                                    @error('puja_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="form-group col-md-6">

                                    <label for="special_price">
                                        Special Price
                                    </label>

                                    <input type="number"
                                        name="special_price"
                                        id="special_price"
                                        step="0.01"
                                        min="0"
                                        class="form-control @error('special_price') is-invalid @enderror"
                                        value="{{ old('special_price') }}"
                                        placeholder="Enter special price">

                                    @error('special_price')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="form-group col-md-6">

                                    <label for="sort_order">
                                        Sort Order
                                    </label>

                                    <input type="number"
                                        name="sort_order"
                                        id="sort_order"
                                        min="0"
                                        class="form-control @error('sort_order') is-invalid @enderror"
                                        value="{{ old('sort_order', 0) }}"
                                        placeholder="Enter sort order">

                                    @error('sort_order')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>

                        </div>

                        <div class="card-footer text-right">

                            <a href="{{ route('festival-pujas.index') }}"
                                class="btn btn-secondary mr-1">
                                Cancel
                            </a>

                            <button type="reset"
                                class="btn btn-light mr-1">
                                Reset
                            </button>

                            <button type="submit"
                                class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Create Festival Puja
                            </button>

                        </div>

                    </form>

                </div>

            </div>
        </div>

    </div>
</section>

@endsection
@else
    @php
        abort(404);
    @endphp
@endcan
