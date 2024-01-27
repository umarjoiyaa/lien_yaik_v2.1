@extends('layouts.app')
@push('title')
    <title>MOVEMENT REPORT</title>
@endpush
@section('index')
    MOVEMENT REPORT
@endsection
@section('content')
    <link href="{{ asset('assets/css/daterangepicker.min.css') }}" rel="stylesheet" />
    <div class="container-fluid mt-4">
        <div class="card">
            <div class="card-header text-center">
                <h4>MOVEMENT REPORT</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('report.get') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="">SELECT (FROM - TO)</label>
                                <input id='daterangepicker' class="form-control text-center"
                                    value="{{ $startDate }} - {{ $endDate }}">
                            </div>
                            <input type="hidden" id="start" name="start_date" value="{{ $startDate }}">
                            <input type="hidden" id="end" name="end_date" value="{{ $endDate }}">
                        </div>
                        <div class="col-sm-2 mt-6">
                            <button type="submit" class="btn btn btn-info">Generate Report</button>
                        </div>
                    </div>
                </form>
                <hr>
                <br>
                <div class="table-responsive">
                    <table class="table table-striped firsttable" id="myTables">

                        <thead class="table-dark" style="color: white; background: #8d9db6;">
                            <tr>
                                <th style="font-size: 13px;">
                                    Pellete
                                </th>
                                @if (isset($dateIn))
                                    @foreach ($dateIn as $row)
                                        <th>
                                            {{ Carbon\Carbon::parse($row->dateonly)->format('d-m-Y') }}
                                        </th>
                                    @endforeach
                                @endif
                                <th style="background: lightgreen;">
                                    Total In
                                </th>
                                @if (isset($dateOut))
                                    @foreach ($dateOut as $row)
                                        <th>
                                            {{ Carbon\Carbon::parse($row->dateonly)->format('d-m-Y') }}
                                        </th>
                                    @endforeach
                                @endif
                                <th style="background: #FF5C5C;">
                                    Total Out
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($data))
                                @foreach ($data as $row)
                                    <tr>
                                        <td>{{ $row['pellete'] }}</td>
                                        @if (isset($row['in']))
                                            @foreach ($row['in'] as $in)
                                                <td>{{ $in }}</td>
                                            @endforeach
                                        @endif
                                        <td style="color: green;">{{ $row['totalin'] }}</td>
                                        @if (isset($row['out']))
                                            @foreach ($row['out'] as $out)
                                                <td>{{ $out }}</td>
                                            @endforeach
                                        @endif
                                        <td style="color: red;">{{ $row['totalout'] }}</td>
                                    </tr>
                                @endforeach
                            @endif
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
        $("#myTables").DataTable();
        $("#daterangepicker").daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            }
        });

        $('#daterangepicker').on('apply.daterangepicker', function(ev, picker) {
            var startDate = picker.startDate.format('YYYY-MM-DD');
            var endDate = picker.endDate.format('YYYY-MM-DD');
            $("#start").val(startDate);
            $("#end").val(endDate);
        });
    </script>
@endpush
