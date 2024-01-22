@extends('layouts.app')
@push('title')
    <title>FINAL CHECKING UPDATE</title>
@endpush
@section('index')
    FINAL CHECKING
@endsection
@section('content')
    <form action="{{ route('final-checking.update', $final_checking->id) }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                FINAL CHECKING UPDATE
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
                                    <option value="{{ $batch->id }}" @selected($final_checking->batch_id == $batch->id)>{{ $batch->batch_no }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Remarks</label>
                            <textarea name="remarks" class="form-control" id="" cols="30" rows="1">{{ $final_checking->remarks }}</textarea>
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
                            <input name="date" type="date" value="{{ $final_checking->date }}" class="form-control">
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <button type="button" style="background:#7AA1B8; border:1px solid #7AA1B8;"
                                class="btn btn-primary add_item" data-toggle="modal" data-target="#exampleModal">
                                SB PELLETES
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
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
                                @foreach ($shotblasts as $key => $shotblast)
                                    <tr>
                                        <td><input type="hidden" value="{{ $shotblast->pellete_id }}"
                                                name="shotblast[{{ $key }}][id]">{{ $shotblast->pellete->pellete_no }}
                                        </td>
                                        <td><input readonly type="number" name="shotblast[{{ $key }}][weight]"
                                                value="{{ $shotblast->weight }}" class="form-control"></td>
                                        <td><input readonly type="number" name="shotblast[{{ $key }}][pcs]"
                                                value="{{ $shotblast->pcs }}" class="form-control"></td>
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
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <button type="button" style="background:#7AA1B8; border:1px solid #7AA1B8;"
                                class="btn btn-primary add_item1" data-toggle="modal" data-target="#exampleModal1">
                                GOOD PALLETES
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 my-5">
                        <table class="table tabe-striped" id="myTable4">
                            <thead>
                                <tr>
                                    <th>PALLETE No</th>
                                    <th>WEIGHT</th>
                                    <th>PCS</th>
                                    <th>REMOVE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($goods as $key => $good)
                                    <tr>
                                        <td><input type="hidden" value="{{ $good->pellete_id }}"
                                                name="good[{{ $key }}][id]">{{ $good->pellete->pellete_no }}
                                        </td>
                                        <td><input type="number" name="good[{{ $key }}][weight]"
                                                value="{{ $good->weight }}" class="form-control weight"></td>
                                        <td><input type="number" name="good[{{ $key }}][pcs]"
                                                value="{{ $good->pcs }}" class="form-control pcs"></td>
                                        <td><a class="delete_row1"><iconify-icon icon="fluent:delete-dismiss-24-filled"
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
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <button type="button" style="background:#7AA1B8; border:1px solid #7AA1B8;"
                                class="btn btn-primary add_item2" data-toggle="modal" data-target="#exampleModal2">
                                REJECT PALLETES
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 my-5">
                        <table class="table tabe-striped" id="myTable6">
                            <thead>
                                <tr>
                                    <th>PALLETE No</th>
                                    <th>WEIGHT</th>
                                    <th>PCS</th>
                                    <th>REMOVE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rejects as $key => $reject)
                                    <tr>
                                        <td><input type="hidden" value="{{ $reject->pellete_id }}"
                                                name="reject[{{ $key }}][id]">{{ $reject->pellete->pellete_no }}
                                        </td>
                                        <td><input type="number" name="reject[{{ $key }}][weight]"
                                                value="{{ $reject->weight }}" class="form-control weight"></td>
                                        <td><input type="number" name="reject[{{ $key }}][pcs]"
                                                value="{{ $reject->pcs }}" class="form-control pcs"></td>
                                        <td><a class="delete_row2"><iconify-icon icon="fluent:delete-dismiss-24-filled"
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

            <div class="mr-auto mt-2"><a href="{{ route('final-checking.index') }}">Go Back</a></div>

            <div><button type="submit" class="btn btn-info">save</button></div>

        </div>
    </form>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ADD PELLETES</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="myTable1">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Pallete</th>
                                    <th>Weight</th>
                                    <th>PCS</th>
                                </tr>
                            </thead>
                            <tbody>

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


    <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ADD PELLETES</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="myTable3">
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
                    <button type="button" class="btn btn-primary btn-info" id="addrows1"
                        data-dismiss="modal">add</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ADD PELLETES</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="myTable5">
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
                    <button type="button" class="btn btn-primary btn-info" id="addrows2"
                        data-dismiss="modal">add</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script>
        var route = "{{ route('pelletes.final-checking.get') }}";
    </script>
    <script src="{{ asset('assets/js/custom/productions/in-progress/final-checking/edit.js') }}"></script>
@endpush
