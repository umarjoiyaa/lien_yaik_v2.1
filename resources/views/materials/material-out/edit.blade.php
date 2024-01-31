@extends('layouts.app')
@push('title')
    <title>MATERIAL OUT UPDATE</title>
@endpush
@section('index')
    MATERIAL OUT
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            MATERIAL OUT UPDATE
        </div>
        <div class="card-body">
            <form action="{{ route('material-out.update', $material_out->id) }}" method="POST">
                @csrf
                <div class="row">

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Date</label>
                            <input name="date" type="date" value="{{ $material_out->date }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">PIC</label>
                            <input readonly name="pic" value="{{ Auth::user()->name }}" class="form-control">
                        </div>
                    </div>

                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <button type="button" style="background:#7AA1B8; border:1px solid #7AA1B8;"
                                class="btn btn-primary add_item" data-toggle="modal" data-target="#exampleModal">
                                Add Item
                            </button>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12 my-5">
                        <table class="table tabe-striped" id="myTable2">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Uom</th>
                                    <th>Supplier</th>
                                    <th>Quantity</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($details as $key => $detail)
                                    <tr>
                                        <td><input type="hidden" value="{{ $detail->item_id }}"
                                                name="items[{{ $key }}][id]">{{ $detail->item->name }}
                                        </td>
                                        <td>{{ $detail->item->category->name }}</td>
                                        @foreach ($materials as $material)
                                            @if ($material->id == $detail->item_id)
                                                <td>{{ $material->uoms }}</td>
                                                <td>{{ $material->suppliers }}</td>
                                            @endif
                                        @endforeach
                                        <td><input type="number" name="items[{{ $key }}][qty]"
                                                value="{{ $detail->qty }}" class="form-control"></td>
                                        <td><a class="delete_row1"><iconify-icon icon="fluent:delete-dismiss-24-filled"
                                                    width="20" height="20" style="color: red;"></iconify-icon><a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row px-3">

                    <div class="mr-auto mt-2"><a href="{{ route('material-out.index') }}">Go Back</a></div>

                    <div><button type="submit" class="btn btn-info">save</button></div>

                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ADD MATERIALS</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped firsttable" id="myTable1">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Uom</th>
                                    <th>Supplier</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($materials as $material)
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td><input type="hidden" value="{{ $material->id }}">{{ $material->name }}</td>
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
                    <button type="button" class="btn btn-primary btn-info" id="addrows" data-dismiss="modal">add</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script src="{{ asset('assets/js/custom/materials/material-in/edit.js') }}"></script>
@endpush
