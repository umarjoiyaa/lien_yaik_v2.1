@extends('layouts.app')
@push('title')
    <title>CHECK PELLETE</title>
@endpush
@section('index')
    CHECK PELLETE
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row px-2">
                <div class="mr-auto">
                    <h5>CHECK PELLETE</h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mt-2" id="pallet_error">
                <div class="col-sm-3">
                    <input type="text" id="searchPellete" placeholder="Check Pellete" class="form-control">
                </div>
                <div>
                    <input type="button" id="search" name="search" class="form-control btn btn-success" value="Check">
                </div>
                <div>
                    <input type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-success ml-3"
                        value="Scan">
                    <div class="container mt-5">
                        <div class="modal fade" data-keyboard="false" data-backdrop="static" id="exampleModal"
                            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">QR Scanner</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col">
                                                <div style="width: 100%;" id="reader"></div>
                                            </div>
                                        </div>
                                        <div id="result"></div>
                                    </div>
                                    <div class="modal-footer"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row-check">

            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script>
        var route = "{{ route('check-pellete.get') }}";
    </script>
    <script src="{{ asset('assets/js/html5-qrcode.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/warehouses/check-pellete/index.js') }}"></script>
@endpush
