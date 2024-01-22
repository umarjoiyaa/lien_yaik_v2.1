@extends('layouts.app')
@push('title')
    <title>PRODUCT UPDATE</title>
@endpush
@section('index')
    PRODUCT
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            PRODUCT UPDATE
        </div>
        <div class="card-body">
            <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Part Name</label>
                            <input name="name" value="{{ $product->name }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Part Code</label>
                            <input name="code" value="{{ $product->code }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Company</label>
                            <input type="text" class="form-control" value="{{ $product->company }}" name="company">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Dimension</label>
                            <input type="text" class="form-control" name="dimension" value="{{ $product->dimension }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Description</label>
                            <textarea name="description" class="form-control" id="" rows="1">{{ $product->description }}</textarea>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="">Image</label>
                            <input type="file" class="form-control myfile" name="file">
                        </div>
                    </div>
                    <div class="col-sm-1 mt-4" style="align-item: left;">

                        <img src="{{ asset('/products/') }}/{{ $product->file }}" id="blah"
                            style="width: 60px; height: 60px;" class="Front_img" />
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
    <script src="{{ asset('assets/js/custom/products/product/edit.js') }}"></script>
@endpush
