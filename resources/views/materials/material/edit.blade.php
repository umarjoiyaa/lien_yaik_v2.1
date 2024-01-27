@extends('layouts.app')
@push('title')
    <title>MATERIAL UPDATE</title>
@endpush
@section('index')
    MATERIAL
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            MATERIAL UPDATE
        </div>
        <div class="card-body">
            <form action="{{ route('material.update', $material->id) }}" method="POST">
                @csrf
                <div class="row">

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input name="name" value="{{ $material->name }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Type</label>
                            <select name="type" class="form-select">
                                <option disabled value="">Select an option</option>
                                <option value="1" @selected($material->type == 1)>In-House</option>
                                <option value="2" @selected($material->type == 2)>Out-Source</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Category</label>
                            <select name="category" class="form-select">
                                <option disabled selected value="">Select an option</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected($material->category_id == $category->id)>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-sm-4">
                        <div class="form-group">
                            @php
                                $item = json_decode($material->uom_ids);
                            @endphp
                            <label for="">Unit of Material</label>
                            <select name="uom[]" class="form-select" multiple>
                                @foreach ($uoms as $uom)
                                    <option value="{{ $uom->id }}"
                                        {{ $item && in_array($uom->id, $item) ? 'selected' : '' }}>
                                        {{ $uom->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            @php
                                $item2 = json_decode($material->supplier_ids);
                            @endphp
                            <label for="">Supplier</label>
                            <select name="supplier[]" class="form-select" multiple>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ $item2 && in_array($supplier->id, $item2) ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
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
