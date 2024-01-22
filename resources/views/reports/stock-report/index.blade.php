@extends('layouts.app')
@push('title')
    <title>STOCK REPORT</title>
@endpush
@section('index')
    STOCK REPORT
@endsection
@section('content')
    <div class="container-fluid mt-4">
        <div class="card">
            <div class="card-header text-center">
                <h4>STOCK REPORT</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="">By Pellete</label>
                            <input type='text' id='searchByName' class="form-control" placeholder='Search pellete'>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="">By Part Name</label>
                            <input type='text' id='searchByPartName' class="form-control" placeholder='Search part name'>
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
                                    Pellete
                                </th>
                                <th>
                                    Part Name
                                </th>
                                <th>
                                    Stock(PCS)
                                </th>
                                <th>
                                    Stock(WEIGHT)
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
        var route = "{{ route('stock-report.get') }}";
    </script>
    <script src="{{ asset('assets/js/custom/reports/stock-report/index.js') }}"></script>
@endpush
