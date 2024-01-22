@extends('layouts.app')
@push('title')
    <title>PELLETE CREATE</title>
@endpush
@section('index')
    PELLETE
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            PELLETE CREATE
        </div>
        <div class="card-body">
            <form action="{{ route('pellete.store') }}" method="POST">
                @csrf
                <div class="row">

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Pellete No</label>
                            <input name="pellete_no" oninput="generate($(this).val())" class="pellete form-control" value="{{ old('pellete_no') }}"
                                class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Qr Code</label>
                            <input type="hidden" name="qr_code" class="hidden">
                            <div id="qrcode"></div>
                        </div>
                    </div>

                </div>
                <br>
                <div class="row px-3">

                    <div class="mr-auto mt-2"><a href="{{ route('pellete.index') }}">Go Back</a></div>

                    <div><button type="button" class="btn btn-info submit">save</button></div>

                </div>
            </form>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script src="{{ asset('assets/js/qrcode.js') }}"></script>
    <script src="{{ asset('assets/js/custom/warehouses/pellete/create.js') }}"></script>
@endpush
