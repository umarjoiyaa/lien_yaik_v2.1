@extends('layouts.app')
@push('title')
    <title>WAREHOUSE IN UPDATE</title>
@endpush
@section('index')
    WAREHOUSE IN
@endsection
@section('content')
    <form action="{{ route('warehouse-in.update', $warehouse_in->id) }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                WAREHOUSE IN UPDATE
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Batch No</label>
                            <input type="hidden" id="weight_unit" name="weight_unit" value="{{ old('weight_unit') }}">
                            <select name="batch_no" id="batch_id" class="form-select">
                                <option value="" selected disabled>Select an option</option>
                                @foreach ($batches as $batch)
                                    <option value="{{ $batch->id }}" @selected($warehouse_in->batch_id == $batch->id)>{{ $batch->batch_no }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Remarks</label>
                            <textarea name="remarks" class="form-control" id="" cols="30" rows="1">{{ $warehouse_in->remarks }}</textarea>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Operator Name</label>
                            <input name="operator_name" type="text" value="{{ Auth::user()->name }}" readonly
                                class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Date</label>
                            <input name="date" type="date" value="{{ $warehouse_in->date }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Part Name</label>
                            <input type="text" id="part_name" readonly class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Customer Name</label>
                            <input type="text" id="customer_name" readonly class="form-control">
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-header">
                <div class="row" id="pallet_error">
                    <div class="col-sm-1 mt-2">
                        <b>Pallet</b>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" id="searchPellete" class="form-control">
                    </div>
                    <div class="">
                        <input type="button" id="search" name="search" class="form-control btn btn-success"
                            value="Create">
                    </div>
                    <div>
                        <input type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-success ml-3"
                            value="Scan">
                        <div class="container mt-5">
                            <div class="modal fade" data-keyboard="false" data-backdrop="static" id="exampleModal"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">QR Scanner</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col">
                                                    <div style="width: 100%;" id="reader"></div>
                                                </div>
                                            </div>
                                            <div id="result"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 my-5">
                        <table class="table tabe-striped" id="myTable1">
                            <thead>
                                <tr>
                                    <th>PALLETE No</th>
                                    <th>WEIGHT</th>
                                    <th>PCS</th>
                                    <th>REMOVE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($details as $key => $detail)
                                    <tr>
                                        <td><input type="hidden" value="{{ $detail->pellete_id }}"
                                                name="pelletes[{{ $key }}][id]">{{ $detail->pellete->pellete_no }}
                                        </td>
                                        <td><input readonly type="number" name="pelletes[{{ $key }}][weight]"
                                                value="{{ $detail->weight }}" class="form-control weight"></td>
                                        <td><input readonly type="number" name="pelletes[{{ $key }}][pcs]"
                                                value="{{ $detail->pcs }}" class="form-control pcs"></td>
                                        <td><a class="delete_row"><iconify-icon icon="fluent:delete-dismiss-24-filled"
                                                    width="20" height="20" style="color: red;"></iconify-icon></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row px-3">

            <div class="mr-auto mt-2"><a href="{{ route('warehouse-in.index') }}">Go Back</a></div>

            <div><button type="submit" class="btn btn-info">save</button></div>

        </div>
    </form>
@endsection
@push('custom-scripts')
    <script>
        var route = "{{ route('batches.warehouse.get') }}";
        var route_pellete = "{{ route('pelletes.warehouse.get') }}";
    </script>
    <script src="{{ asset('assets/js/html5-qrcode.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/warehouses/warehouse-in/edit.js') }}"></script>
@endpush
