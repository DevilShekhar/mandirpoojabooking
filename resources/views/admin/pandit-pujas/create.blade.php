@can('pandit-pujas.view')
@extends('admin.layouts.app')

@section('title', 'Assign Puja')

@section('content')

<section class="section">
    <div class="section-body">

        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">

                        <h4>Assign Puja to Pandit</h4>

                        <div class="card-header-action">
                            <a href="{{ route('pandit-pujas.index') }}"
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>

                    </div>

                    <form method="POST"
                          action="{{ route('pandit-pujas.store') }}">

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

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="section-title mt-0">
                                Pandit Puja Information
                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-6">

                                    <label>
                                        Pandit <span class="text-danger">*</span>
                                    </label>

                                    <select name="pandit_id"
                                            class="form-control"
                                            required>

                                        <option value="">
                                            Select Pandit
                                        </option>

                                        @foreach($pandits as $pandit)

                                            <option value="{{ $pandit->id }}"
                                                {{ old('pandit_id') == $pandit->id ? 'selected' : '' }}>

                                                {{ $pandit->name }}

                                                @if($pandit->temple)
                                                    - {{ $pandit->temple->name }}
                                                @endif

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                                <div class="form-group col-md-6">

                                    <label>
                                        Puja <span class="text-danger">*</span>
                                    </label>

                                    <select name="puja_id"
                                            class="form-control"
                                            required>

                                        <option value="">
                                            Select Puja
                                        </option>

                                        @foreach($pujas as $puja)

                                            <option value="{{ $puja->id }}"
                                                {{ old('puja_id') == $puja->id ? 'selected' : '' }}>

                                                {{ $puja->name }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>

                        </div>

                        <div class="card-footer text-right">

                            <a href="{{ route('pandit-pujas.index') }}"
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
                                Assign Puja
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
        abort(403);
    @endphp
@endcan
