@extends('layouts.app')
@push('title')
    <title>PURCHASE ORDER CREATE</title>
@endpush
@section('index')
    PURCHASE ORDER
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            PURCHASE ORDER CREATE
        </div>
        <div class="card-body">
            <form action="{{ route('purchase.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" name="customer_name"
                                value="{{ old('customer_name') }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="form-label">Part Name</label>
                            <select name="product" class="form-select">
                                <option value="" selected disabled>Select an option</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" @selected($product->id == old('product'))>{{ $product->name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label for="" class="form-label">Material</label>
                        <select name="material[]" multiple class="form-select">
                            @foreach ($materials as $material)
                                <option value="{{ $material->id }}"
                                    {{ old('material') && in_array($material->id, old('material')) ? 'selected' : '' }}
                                    @selected($material->id == old('material'))>{{ $material->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Purchase Order No</label>
                            <input type="text" name="order_no" class="form-control" value="{{ old('order_no') }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Order date</label>
                            <input type="date" name="order_date" class="form-control" value="{{ old('order_date') }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="form-label">Customer Request Date</label>
                            <input type="date" name="request_date" class="form-control"
                                value="{{ old('request_date') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="form-label">Ordered Unit</label>
                            <input type="number" name="order_unit" class="form-control" value="{{ old('order_unit') }}">
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-sm-3 mt-5">
                        <h6>CAVITIES WEIGHT @ PER MOLD :</h6>
                    </div>
                    <div class="col-sm-3">
                        <label for="" class="form-abel">Cavities</label>
                        <input type="number" name="cavities" class="form-control" id="input1"
                            value="{{ old('cavities') }}">
                    </div>
                    <div class="col-sm-3">
                        <label for="" class="form-label">Unit KG</label>
                        <input type="number" name="unit_kg" id="input2" class="form-control"
                            value="{{ old('unit_kg') }}">
                    </div>
                    <div class="col-sm-3">
                        <label for="" class="form-label">Weight</label>
                        <input type="text" name="weight" id="result" class="form-control" readonly>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-sm-3 mt-2">
                        <h6>PURCHASE ORDER ISSUED BY :</h6>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" readonly name="issued_by"
                            value="{{ Auth::user()->name }}" />
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-sm-3 mt-2">
                        <h6>PURCHASE ORDER APPROVED BY :</h6>
                    </div>
                    <div class="col-sm-3">
                        <select name="approved_by[]" multiple class="form-select">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('approved_by') && in_array($user->id, old('approved_by')) ? 'selected' : '' }}>
                                    {{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <br>
                <div class="row px-3">

                    <div class="mr-auto mt-2"><a href="{{ route('purchase.index') }}">Go Back</a></div>

                    <div><button type="submit" class="btn btn-info">save</button></div>

                </div>
            </form>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script src="{{ asset('assets/js/custom/productions/purchase-order/create.js') }}"></script>
@endpush
