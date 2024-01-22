@extends('layouts.app')
@push('title')
    <title>MATERIAL CREATE</title>
@endpush
@section('index')
    MATERIAL
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            MATERIAL CREATE
        </div>
        <div class="card-body">
            <form action="{{ route('material.store') }}" method="POST">
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
                            <label for="">Type</label>
                            <select name="type" class="form-select">
                                <option disabled selected value="">Select an option</option>
                                <option value="1" @selected(old('type') == 1)>In-House</option>
                                <option value="2" @selected(old('type') == 2)>Out-Source</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Category</label>
                            <select name="category" class="form-select">
                                <option disabled selected value="">Select an option</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category') == $category->id)>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Unit of Material</label>
                            <select name="uom[]" class="form-select" multiple>
                                @foreach ($uoms as $uom)
                                    <option value="{{ $uom->id }}"
                                        {{ old('uom') && in_array($uom->id, old('uom')) ? 'selected' : '' }}>
                                        {{ $uom->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Supplier</label>
                            <select name="supplier[]" class="form-select" multiple>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ old('supplier') && in_array($supplier->id, old('supplier')) ? 'selected' : '' }}>
                                        {{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                <br>
                <div class="row px-3">

                    <div class="mr-auto mt-2"><a href="{{ route('material.index') }}">Go Back</a></div>

                    <div><button type="submit" class="btn btn-info">save</button></div>

                </div>
            </form>
        </div>
    </div>
@endsection
