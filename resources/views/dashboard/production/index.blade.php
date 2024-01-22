@extends('layouts.app')
@push('title')
    <title>PRODUCTION DASHBOARD</title>
@endpush
@section('index')
    PRODUCTION DASHBOARD
@endsection
@section('content')
    <link href="{{ asset('assets/css/daterangepicker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/apexcharts.css') }}" rel="stylesheet" />
    <div class="container-fluid mt-4">
        <div class="card">
            <div class="card-header">
                <div class="row px-2">
                    <div class="mr-auto">
                        <h5>PRODUCTION DASHBOARD</h5>
                    </div>
                    <div>
                        <input type="text" id="daterangepicker" class="form-control text-center">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="productionGraph"></div>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/apexcharts.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#daterangepicker").daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
            getData(null, null);
            $('#daterangepicker').on('apply.daterangepicker', function(ev, picker) {
                var startDate = picker.startDate.format('YYYY-MM-DD');
                var endDate = picker.endDate.format('YYYY-MM-DD');
                getData(startDate, endDate);
            });
        });

        function getData(startDate, endDate) {
            $.ajax({
                url: "{{ route('production_dashboard.get') }}",
                data: {
                    "startDate": startDate,
                    "endDate": endDate
                },
                success: function(data) {
                    var i = 0;
                    $.each(data.temp, function(index, value) {
                        dates[i] = value.created_at;
                        temp[i] = value.temperature;
                        mositure[i] = value.moisture;
                        i++;
                    });

                    var options = {
                        chart: {
                            height: 350,
                            type: 'bar',
                        },
                        title: {
                            text: 'Temperature and Moisture Change',
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                        },
                        xaxis: {
                            categories: data.dates,
                        },
                        yaxis: [{
                                title: {
                                    text: 'Moisture',
                                },
                            },
                            {
                                opposite: true,
                                title: {
                                    text: 'Temperature',
                                },
                            },
                        ],
                        legend: {
                            position: 'top',
                        },
                        stroke: {
                            width: [0, 4],
                        },
                        dataLabels: {
                            enabled: true
                        },
                        series: [{
                                name: 'Moisture',
                                type: 'bar',
                                data: data.moisture,
                            },
                            {
                                name: 'Temperature',
                                type: 'bar',
                                data: data.temp,
                            },
                        ],
                    };

                    var chart = new ApexCharts(document.querySelector("#productionGraph"), options);
                    chart.render();
                }
            });
        }
    </script>
@endpush
