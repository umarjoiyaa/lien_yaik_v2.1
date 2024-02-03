@extends('layouts.app')
@push('title')
    <title>PRODUCTION ORDER CREATE</title>
@endpush
@section('index')
    PRODUCTION ORDER
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            PRODUCTION ORDER CREATE
        </div>
        <div class="card-body">
            <form action="{{ route('production.store') }}" method="POST">
                @csrf
                <div class="row mt-3">
                    <div class="col-sm-4">
                        <label for="" class="form-label">Purchase Order Number</label>
                        <select name="purchase_order" id="purchase_order" class="form-select">
                            <option value="" selected disabled>Select an option</option>
                            @foreach ($orders as $order)
                                <option value="{{ $order->id }}" @selected(old('purchase_order') == $order->id)>{{ $order->order_no }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label for="" class="form-label">Part Name</label>
                        <input type="hidden" name="product_id" id="part_id" class="form-control">
                        <input type="text" id="part_name" readonly class="form-control">
                    </div>
                    <div class="col-sm-4">
                        <label for="" class="form-label">Batch No</label>
                        <select name="batch" class="form-select">
                            <option value="" selected disabled>Select an option</option>
                            @foreach ($batches as $batch)
                                <option value="{{ $batch->id }}" @selected(old('batch') == $batch->id)>{{ $batch->batch_no }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-4">
                        <label for="" class="form-label">Order Units</label>
                        <input readonly type="text" name="order_unit" id="order_unit" class="form-control">
                    </div>
                    <div class="col-sm-4">
                        <label for="" class="label">No of Cavity</label>
                        <input type="text" readonly name="no_cavity" id="no_cavity" class="form-control">
                    </div>
                    <div class="col-sm-4">
                        <label for="" class="form-label">Reject (%)</label>
                        <input type="number" name="reject" id="reject" value="{{ old('reject') }}" class="form-control">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-4">
                        <label for="" class="form-label">Target Produce</label>
                        <input type="text" readonly name="target_produce" id="target_produce"
                            class="form-control">
                    </div>
                    <div class="col-sm-4">
                        <label for="" class="form-label">Available Quantity</label>
                        <input type="text" readonly name="available_quantity" id="available_quantity" class="form-control">
                    </div>
                    <div class="col-sm-4">
                        <label for="" class="form-label">Used Quantity</label>
                        <input type="number" name="used_quantity" id="used_qty" value="{{ old('used_quantity') }}" class="form-control">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-4">
                        <label for="" class="form-label">Need to Produce</label>
                        <input type="text" readonly name="need_produce" id="need_produce"
                            class="form-control">
                    </div>
                    <div class="col-sm-4">
                        <label for="" class="form-label">Order_Date</label>
                        <input type="text" readonly name="order_date" id="order_date" class="form-control">
                    </div>
                    <div class="col-sm-4">
                        <label for="" class="form-label">Operator Name</label>
                        <input type="text" readonly name="operation_name" value="{{ Auth::user()->name }}"
                            class="form-control">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-4">
                        <label class="form-label">Inhouse Target Date</label>
                        <input type="date" name="target_date" value="{{ old('target_date') }}" class="form-control">
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label">Weight per Unit (kg)</label>
                        <input type="text" readonly name="weight_unit" id="weight_unit" class="form-control">
                    </div>
                    <div class="col-sm-4">
                        <label for="" class="form-label">Machine</label>
                        <select class="form-select" name="machine[]" multiple>
                            @foreach ($machines as $machine)
                                <option value="{{ $machine->id }}" {{ old('machine') && in_array($machine->id, old('machine')) ? 'selected' : '' }}>{{ $machine->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-4">
                        <label for="" class="label">Weight Per Mould</label>
                        <input type="text" readonly name="weight_mold" onchange="calculateRawMaterial()" id="weight-per-mold" class="form-control">
                    </div>
                    <div class="col-sm-4">
                        <label for="" class="form-label">Press</label>
                        <input type="text" readonly name="press" onchange="calculateRawMaterial()" id="press" class="form-control">
                    </div>
                    <div class="col-sm-4">
                        <label for="" class="form-label">Raw Material</label>
                        <input type="text" readonly name="raw_material" id="raw_material"
                            class="form-control">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-4">
                        <label for="" class="form-label">Date of Issued</label>
                        <input type="text" name="issue_date" readonly value="{{ date('m/d/Y') }}"
                            class="form-control">
                    </div>
                    <div class="col-sm-4">
                        <label for="" class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="" selected disabled>Select an option</option>
                            <option value="1" @selected(old('status') == 1)>Active</option>
                            <option value="2" @selected(old('status') == 2)>On-hold</option>
                            <option value="3" @selected(old('status') == 3)>Complete</option>
                        </select>
                    </div>
                    <div class="col-sm-4"></div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <button type="button" style="background:#7AA1B8; border:1px solid #7AA1B8;"
                            class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                            Add Item
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="table-responsive">
                        <table class="table tabe-striped" id="myTable2">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Material</th>
                                    <th>Category</th>
                                    <th>Uom</th>
                                    <th>Supplier</th>
                                    <th>Available Quantity</th>
                                    <th>Required Quantity</th>
                                    <th>Need to Produce/Purchase</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">ADD MATERIALS</h5>
                                <button type="button" class="btn-close" data-dismiss="modal"
                                    aria-label="Close">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-striped firsttable" id="myTable1">
                                        <thead>
                                            <tr>
                                                <th>Select</th>
                                                <th>Material</th>
                                                <th>Type</th>
                                                <th>Category</th>
                                                <th>Uom</th>
                                                <th>Supplier</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($materials as $material)
                                                <tr>
                                                    <td><input type="checkbox"></td>
                                                    <td><input type="hidden"
                                                            value="{{ $material->id }}">{{ $material->name }}</td>
                                                    <td><input type="hidden"
                                                        value="{{ $material->value }}">{{ $material->type == 1 ? 'In-House' : 'Out-Source' }}</td>
                                                    <td>{{ $material->category }}</td>
                                                    <td>{{ $material->uoms }}</td>
                                                    <td>{{ $material->suppliers }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary btn-info" id="addrows"
                                    data-dismiss="modal">add</button>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row px-3">

                    <div class="mr-auto mt-2"><a href="{{ route('production.index') }}">Go Back</a></div>

                    <div><button type="button" class="btn btn-info submit">save</button></div>

                </div>
            </form>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script>
        var data = "{{ route('production.get') }}";
    </script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/productions/production-order/create.js') }}"></script>
@endpush
