@extends('layouts.app')
@push('title')
    <title>MACHINE DASHBOARD</title>
@endpush
@section('index')
    MACHINE DASHBOARD
@endsection
@section('content')
    <style>
        .container-body {
            background-image: url('../assets/images/machine.jpg');
            background-repeat: no-repeat;
            width: auto;
            height: 900px;
            background-size: 100%;
            margin: 10px;
        }

        @media screen and (max-width: 500px) {
            .machines {
                margin-top: 100px !important;
            }

            .m3 {
                margin-top: 0px !important;
            }

            .machines h5 {
                font-size: 10px;
            }

            #status {
                font-size: 8px;
            }

            .machines span {
                font-size: 8px !important;
            }

            #ps_m1,
            #ps_m2 {
                font-size: 5px !important;
            }

            .col-3,
            .col-2 {
                padding: 3px !important;
            }
        }

        .text-danger {
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0% {
                opacity: 1;
            }

            30% {
                opacity: 0.1;
            }

            100% {
                opacity: 1;
            }
        }
    </style>
    <div class="container-fluid mt-4 container-body">
        <div class="row machines m3">
            <div class="col-3 offset-2 p-3 mt-5" style="background: #FAE275 ; border-radius: 7px;">
                <h3 class="text-center"><span style="font-size: 20px;"> </span> <span style=" color: red;">Sand Mixer</span>
                </h3>
                <h5>Temperature : <span id="t_m3" style="font-size: 15px"> </span><span><svg
                            xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                            <path fill="currentColor"
                                d="M13.19 9.21c0-.5.18-.93.53-1.28c.36-.36.78-.53 1.28-.53c.49 0 .92.18 1.27.53c.35.36.53.78.53 1.28s-.18.93-.53 1.29c-.35.36-.78.54-1.27.54s-.92-.18-1.28-.54s-.53-.79-.53-1.29zm.88 0c0 .26.09.48.27.67c.19.19.41.28.67.28c.26 0 .48-.09.67-.28s.28-.41.28-.67a.87.87 0 0 0-.28-.66a.947.947 0 0 0-.67-.28c-.26 0-.48.09-.67.27c-.18.18-.27.4-.27.67z" />
                        </svg></span> </h5>
                <span class="text-danger" id="warning_high"></span>
                <h5>Moisture : <span id="m_m3" style="font-size: 15px"> </span> <span>%</span></h5>
                <span class="text-danger" id="warning_moisture"></span>
            </div>
        </div>

        <div class="row machines" style="margin-top: 300px">
            <div class="col-3 p-3" style="background: #FAE275 ; border-radius: 7px;">
                <h5 id="status">Production Status : <span id="ps_m1" class="badge" style="font-size: 11px"></span>
                </h5>
                <h5>Temperature : <span id="t_m1" style="font-size: 15px"> </span> <span><svg
                            xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                            <path fill="currentColor"
                                d="M13.19 9.21c0-.5.18-.93.53-1.28c.36-.36.78-.53 1.28-.53c.49 0 .92.18 1.27.53c.35.36.53.78.53 1.28s-.18.93-.53 1.29c-.35.36-.78.54-1.27.54s-.92-.18-1.28-.54s-.53-.79-.53-1.29zm.88 0c0 .26.09.48.27.67c.19.19.41.28.67.28c.26 0 .48-.09.67-.28s.28-.41.28-.67a.87.87 0 0 0-.28-.66a.947.947 0 0 0-.67-.28c-.26 0-.48.09-.67.27c-.18.18-.27.4-.27.67z" />
                        </svg></span></h5>
                <h5>Batch No : <span id="b_m1" style="font-size: 15px"> </span> </h5>
                <h5>Planned Press : <span id="ct_m1" style="font-size: 15px"> </span> </h5>
                <h5>Mold Press : <span id="tt_m1" style="font-size: 15px"> </span> </h5>
                <span id="s_m1" class=""></span>
                <span id="w_t_m1" class="text-danger"></span>
            </div>
            <div class="col-3 offset-6 p-3" style="background: #FAE275 ; border-radius: 7px;">
                <h5 id="status">Production Status : <span id="ps_m2" class="badge" style="font-size: 11px"></span>
                </h5>
                <h5>Temperature : <span id="t_m2" style="font-size: 15px"> </span> <span><svg
                            xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                            <path fill="currentColor"
                                d="M13.19 9.21c0-.5.18-.93.53-1.28c.36-.36.78-.53 1.28-.53c.49 0 .92.18 1.27.53c.35.36.53.78.53 1.28s-.18.93-.53 1.29c-.35.36-.78.54-1.27.54s-.92-.18-1.28-.54s-.53-.79-.53-1.29zm.88 0c0 .26.09.48.27.67c.19.19.41.28.67.28c.26 0 .48-.09.67-.28s.28-.41.28-.67a.87.87 0 0 0-.28-.66a.947.947 0 0 0-.67-.28c-.26 0-.48.09-.67.27c-.18.18-.27.4-.27.67z" />
                        </svg></span></h5>
                <h5>Batch No : <span id="b_m2" style="font-size: 15px"> </span> </h5>
                <h5>Planned Press : <span id="ct_m2" style="font-size: 15px"> </span> </h5>
                <h5>Mold Press : <span id="tt_m2" style="font-size: 15px"> </span> </h5>
                <span id="s_m2" class=""></span>
                <span id="w_t_m2" class="text-danger"></span>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script>
        function getData(startDate, endDate) {
            $.ajax({
                url: "{{ route('machine_dashboard.get') }}",
                success: function(data) {
                    if (data.length > 0) {
                        $('#ps_m1').text(data["M1"].status);
                        $('#t_m1').text(data["M1"].temp);
                        $('#b_m1').text(data["M1"].batch);
                        $('#ct_m1').text(data["M1"].planned);
                        $('#tt_m1').text(data["M1"].mold.toFixed(0));
                        $('#w_t_m1').text(data["M1"].check_temp);

                        if (data["M1"].mold >= data["M1"].planned) {
                            if (data["M1"].mold != 0) {
                                $('#s_m1').html("<b>production complete</b>");
                                $('#s_m1').css('color', '#0acb8e');
                                $('#s_m1').css('font-size', '20px');
                            } else {
                                $('#s_m1').html("");
                            }
                        } else {
                            $('#s_m1').html("");
                        }

                        $('#ps_m2').text(data["M2"].status);
                        $('#t_m2').text(data["M2"].temp);
                        $('#b_m2').text(data["M2"].batch);
                        $('#ct_m2').text(data["M2"].planned);
                        $('#tt_m2').text(data["M2"].mold.toFixed(0));
                        $('#w_t_m2').text(data["M2"].check_temp);

                        if (data["M2"].mold >= data["M2"].planned) {
                            if (data["M2"].mold != 0) {
                                $('#s_m2').html("<b>production complete</b>");
                                $('#s_m2').css('color', '#0acb8e');
                                $('#s_m2').css('font-size', '20px');
                            } else {
                                $('#s_m2').html("");
                            }
                        } else {
                            $('#s_m2').html("");
                        }

                        $('#t_m3').text(data["M3"].temp);
                        $('#m_m3').text(data["M3"].moisture);
                        $('#warning_temp').text(data["M3"].check_temp);
                        $('#warning_moisture').text(data["M3"].check_moisture);

                        if (data["M1"].status == "Stopped") {
                            $('#ps_m1').removeClass('badge-success');
                            $('#ps_m1').addClass('badge-danger');
                        } else {
                            $('#ps_m1').removeClass('badge-danger');
                            $('#ps_m1').addClass('badge-success');
                        }

                        if (data["M2"].status == "Stopped") {
                            $('#ps_m2').removeClass('badge-success');
                            $('#ps_m2').addClass('badge-danger');
                        } else {
                            $('#ps_m2').removeClass('badge-danger');
                            $('#ps_m2').addClass('badge-success');
                        }

                    }
                }
            });
        }

        setInterval(getData, 1000);
    </script>
@endpush
