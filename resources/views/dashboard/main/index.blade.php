@extends('layouts.app')
@push('title')
    <title>MAIN DASHBOARD</title>
@endpush
@section('index')
    DASHBOARD
@endsection
@section('content')
    <link href="{{ asset('assets/css/daterangepicker.min.css') }}" rel="stylesheet" />
    <div class="container-fluid mt-4">
        <div class="card">
            <div class="card-header">
                <div class="row px-2">
                    <div class="mr-auto">
                        <h5>DASHBOARD</h5>
                    </div>
                    <div>
                        <input type="text" id="daterangepicker" class="form-control text-center">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="myTables">
                        <thead class="table-dark" style="color: white; background: #8d9db6;">
                            <tr>
                                <th>DATE</th>
                                <th>BATCH NO</th>
                                <th>CUSTOMER NAME</th>
                                <th>ACTUAL OUTPUT</th>
                                <th>FINAL (KG) </th>
                                <th>FINAL (PCS)</th>
                                <th>WAREHOUSE IN (PCS)</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
    <script>
        $("#daterangepicker").daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
        $("#myTables").dataTable();

        $('#daterangepicker').on('apply.daterangepicker', function(ev, picker) {
            var startDate = picker.startDate.format('YYYY-MM-DD');
            var endDate = picker.endDate.format('YYYY-MM-DD');
            getData(startDate, endDate);
        });
        getData();
        function getData(startDate, endDate) {
            $.ajax({
                url: "{{ route('dashboard.get') }}",
                data: {
                    "startDate": startDate,
                    "endDate": endDate
                },
                success: function(data) {
                    $('#myTables').dataTable().fnDestroy();
                    $('tbody').html(data);
                    $("#myTables").dataTable();
                }
            });
        }

        // setInterval(getData, 10000);
    </script>
@endpush
