@extends('layouts.app')
@push('title')
    <title>PRODUCT CREATE</title>
@endpush
@section('index')
    PRODUCT
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            PRODUCT CREATE
        </div>
        <div class="card-body">
            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Part Name</label>
                            <input name="name" value="{{ old('name') }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Part Code</label>
                            <input name="code" value="{{ old('code') }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Company</label>
                            <input type="text" class="form-control" value="{{ old('company') }}" name="company">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Dimension</label>
                            <input type="text" class="form-control" name="dimension" value="{{ old('dimension') }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Description</label>
                            <textarea name="description" class="form-control" id="" rows="1">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="">Image</label>
                            <input type="file" class="form-control myfile" name="file">
                        </div>
                    </div>
                    <div class="col-sm-1 mt-4" style="align-item: left;">

                        <img src="" id="blah" style="width: 60px; height: 60px;" class="Front_img" />
                    </div>
                </div>
                <br>
                <div class="row px-3">

                    <div class="mr-auto mt-2"><a href="{{ route('product.index') }}">Go Back</a></div>

                    <div><button type="submit" class="btn btn-info">save</button></div>

                </div>
            </form>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script src="{{ asset('assets/js/custom/products/product/create.js') }}"></script>
@endpush
