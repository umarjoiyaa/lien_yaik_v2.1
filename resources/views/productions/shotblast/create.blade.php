@extends('layouts.app')
@push('title')
    <title>SHOTBLAST CREATE</title>
@endpush
@section('index')
    SHOTBLAST
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            SHOTBLAST CREATE
        </div>
        <div class="card-body">
            <form action="{{ route('shotblast.store') }}" method="POST">
                @csrf
                <div class="row">

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Batch No</label>
                            <input type="hidden" id="weight_unit" name="weight_unit" value="{{ old('weight_unit') }}">
                            <select name="batch_no" id="batch_id" class="form-select">
                                <option value="" selected disabled>Select an option</option>
                                @foreach ($batches as $batch)
                                    <option value="{{ $batch->id }}" @selected(old('batch_no') == $batch->id)>{{ $batch->batch_no }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Tulang(Kg)</label>
                            <input type="number" name="tulang" value="{{ old('tulang') }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Reject</label>
                            <input name="reject" type="number" value="{{ old('reject') }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Date</label>
                            <input name="date" type="date" value="{{ old('date') }}" class="form-control">
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
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <button type="button" style="background:#7AA1B8; border:1px solid #7AA1B8;"
                                class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
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
                                    <th>PALLETE No</th>
                                    <th>WEIGHT</th>
                                    <th>PCS</th>
                                    <th>REMOVE</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row px-3">

                    <div class="mr-auto mt-2"><a href="{{ route('shotblast.index') }}">Go Back</a></div>

                    <div><button type="button" class="btn btn-info submit">save</button></div>

                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ADD PELLETES</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped firsttable" id="myTable1">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Pallete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pelletes as $pellete)
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td><input type="hidden" value="{{ $pellete->id }}">{{ $pellete->pellete_no }}
                                        </td>
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
@endsection
@push('custom-scripts')
    <script>
        var route = "{{ route('pelletes.grinding.get') }}";
    </script>
    <script src="{{ asset('assets/js/custom/productions/shotblast/create.js') }}"></script>
@endpush
