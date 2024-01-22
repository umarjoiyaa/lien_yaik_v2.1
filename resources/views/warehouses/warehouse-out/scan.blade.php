@extends('layouts.app')
@push('title')
    <title>WAREHOUSE OUT QR</title>
@endpush
@section('index')
    WAREHOUSE OUT
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            WAREHOUSE OUT QR
        </div>
        <div class="card-body">
            @foreach ($scan as $scanner)
                <div class="col-sm-3 mt-4">
                    <div class="card">
                        <div class="card-header qrbox" data-id="{{ $scanner->pellete }}"
                            style="background: #FAE275; text-align: center;">
                            {{ $scanner->pellete_no }}<br>
                        </div>
                        <div class="card-body">
                            <div class="qr text-center" id="qrcode{{ $scanner->pellete }}" style=""
                                data-id="{{ $scanner->pellete }}" data-index="{{ $loop->index }}">
                                {{ $scanner->pellete_no }}
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            @endforeach
        </div>
    </div>
    <br>
    <div class="row px-3">
        <div class="mr-auto mt-2"><a href="{{ route('warehouse-in.index') }}">Go Back</a></div>
        <div><button type="button" class="btn btn-info submit">save</button></div>
    </div>
@endsection
@push('custom-scripts')
    <script src="{{ asset('assets/js/qrcode.js') }}"></script>
    <script src="{{ asset('assets/js/html5-qrcode.min.js') }}"></script>
    <script>
        function test(element) {
            var x = element.innerHTML;
            element.innerHTML = "";
            generateQRCode(x, element);
        }

        function generateQRCode(text, element) {
            var qr = qrcode(0, "L");
            qr.addData(text);
            qr.make();
            var qrImage = qr.createImgTag(4);
            element.innerHTML = qrImage;
        }

        $(document).ready(function() {
            var qrElements = document.getElementsByClassName('qr');
            Array.from(qrElements).forEach(test);
        });

        function printDiv(print) {
            $('img').attr('width', '191px');
            var printContents = document.getElementById("print").innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endpush
