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
                <div id="productionGraph" style="height: 50vh;"></div>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/echarts.min.js') }}"></script>
    <script>
        var dates = [];
        var temp = [];
        var moisture = [];

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
                        moisture[i] = value.moisture;
                        i++;
                    });

                    var myChart = echarts.init(document.getElementById('productionGraph'));

                    var chartDom = document.getElementById('productionGraph');
                    var myChart = echarts.init(chartDom, 'dark');
                    var option;

                    option = {
                        tooltip: {
                            trigger: 'axis'
                        },
                        legend: {},
                        toolbox: {
                            show: true,
                            feature: {
                                dataZoom: {
                                    yAxisIndex: 'none'
                                },
                                magicType: {
                                    type: ['line', 'bar']
                                },
                                restore: {},
                                saveAsImage: {}
                            }
                        },
                        xAxis: {
                            type: 'category',
                            boundaryGap: false,
                            data: data.dates
                        },
                        yAxis: {
                            type: 'value',
                            axisLabel: {
                                formatter: '{value}'
                            }
                        },
                        series: [{
                                name: 'Moisture',
                                type: 'line',
                                data: moisture,
                                markPoint: {
                                    data: [{
                                            type: 'max',
                                            name: 'Max'
                                        },
                                        {
                                            type: 'min',
                                            name: 'Min'
                                        }
                                    ]
                                },
                                markLine: {
                                    data: [{
                                        type: 'average',
                                        name: 'Avg'
                                    }]
                                }
                            },
                            {
                                name: 'Temperature',
                                type: 'line',
                                data: temp,
                                markPoint: {
                                    data: [{
                                        name: '周最低',
                                        value: -2,
                                        xAxis: 1,
                                        yAxis: -1.5
                                    }]
                                },
                                markLine: {
                                    data: [{
                                            type: 'average',
                                            name: 'Avg'
                                        },
                                        [{
                                                symbol: 'none',
                                                x: '90%',
                                                yAxis: 'max'
                                            },
                                            {
                                                symbol: 'circle',
                                                label: {
                                                    position: 'start',
                                                    formatter: 'Max'
                                                },
                                                type: 'max',
                                                name: '最高点'
                                            }
                                        ]
                                    ]
                                }
                            }
                        ]
                    };

                    option && myChart.setOption(option);
                }
            });
        }
    </script>
@endpush
