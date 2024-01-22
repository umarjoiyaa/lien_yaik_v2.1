@extends('layouts.app')
@push('title')
    <title>MATERIAL STOCK</title>
@endpush
@section('index')
    MATERIAL STOCK
@endsection
@section('content')
    <div class="container-fluid mt-4">
        <div class="card">
            <div class="card-header text-center">
                <h4>MATERIAL STOCK</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="">By Material</label>
                            <input type='text' id='searchByName' class="form-control" placeholder='Search material'>
                        </div>
                    </div>
                    <div class="col-sm-1 mt-6">
                        <div class="form-group">
                            <select id='searchUsingComparator' class="form-control p-0">
                                <option value='=' selected>=</option>
                                <option value='!='>!=</option>
                                <option value='<'><</option>
                                <option value='<='><=</option>
                                <option value='>'>></option>
                                <option value='>='>>=</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="">By Stock</label>
                            <input type='number' id='searchByValue' class="form-control" placeholder='Stock Value'>
                        </div>
                    </div>
                </div>
                <hr>
                <br>
                <div class="table-responsive">
                    <table class="table table-striped firsttable" id="myTables">

                        <thead class="table-dark" style="color: white; background: #8d9db6;">
                            <tr>
                                <th>
                                    Material
                                </th>
                                <th>
                                    Stock
                                </th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script>
        var route = "{{ route('material-stock.get') }}";
    </script>
    <script src="{{ asset('assets/js/custom/reports/material-stock/index.js') }}"></script>
@endpush
