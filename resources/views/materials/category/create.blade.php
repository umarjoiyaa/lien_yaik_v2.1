@extends('layouts.app')
@push('title')
    <title>CATEGORY CREATE</title>
@endpush
@section('index')
    CATEGORY
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            CATEGORY CREATE
        </div>
        <div class="card-body">
            <form action="{{ route('category.store') }}" method="POST">
                @csrf
                <div class="row">

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input name="name" value="{{ old('name') }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Code</label>
                            <input name="code" value="{{ old('code') }}" class="form-control">
                        </div>
                    </div>

                </div>
                <br>
                <div class="row px-3">

                    <div class="mr-auto mt-2"><a href="{{ route('category.index') }}">Go Back</a></div>

                    <div><button type="submit" class="btn btn-info">save</button></div>

                </div>
            </form>
        </div>
    </div>
@endsection
