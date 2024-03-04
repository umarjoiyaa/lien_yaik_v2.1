@extends('layouts.app')
@push('title')
    <title>PURCHASE ORDER UPDATE</title>
@endpush
@section('index')
    PURCHASE ORDER
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            PURCHASE ORDER UPDATE
        </div>
        <div class="card-body">
            <form action="{{ route('purchase.update', $purchase->id) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" name="customer_name"
                                value="{{ $purchase->customer }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="form-label">Part Name</label>
                            <select name="product" class="form-select">
                                <option value="" selected disabled>Select an option</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" @selected($product->id == $purchase->product_id)>{{ $product->name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label for="" class="form-label">Material</label>
                        <select name="material[]" multiple class="form-select">
                            @php
                                $item = json_decode($purchase->item_id);
                            @endphp
                            @foreach ($materials as $material)
                                <option value="{{ $material->id }}" {{ in_array($material->id, $item) ? 'selected' : '' }}>
                                    {{ $material->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Purchase Order No</label>
                            <input type="text" name="order_no" class="form-control" value="{{ $purchase->order_no }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Order date</label>
                            <input type="date" name="order_date" class="form-control"
                                value="{{ $purchase->order_date }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="form-label">Customer Request Date</label>
                            <input type="date" name="request_date" class="form-control"
                                value="{{ $purchase->req_date }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="form-label">Ordered Unit</label>
                            <input type="number" name="order_unit" class="form-control"
                                value="{{ $purchase->order_unit }}">
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
                            value="{{ $purchase->cavities }}">
                    </div>
                    <div class="col-sm-3">
                        <label for="" class="form-label">Unit KG</label>
                        <input type="number" name="unit_kg" id="input2" class="form-control"
                            value="{{ $purchase->unit_kg }}" step="0.01">
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
                        <input type="text" class="form-control" name="issued_by" readonly
                            value="{{ Auth::user()->name }}" />
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-sm-3 mt-2">
                        <h6>PURCHASE ORDER APPROVED BY :</h6>
                    </div>
                    <div class="col-sm-3">
                        @php
                            $item = json_decode($purchase->approved);
                        @endphp
                        <select name="approved_by[]" multiple class="form-select">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ in_array($user->id, $item) ? 'selected' : '' }}>
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
