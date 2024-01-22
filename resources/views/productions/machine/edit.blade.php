@extends('layouts.app')
@push('title')
    <title>MACHINE UPDATE</title>
@endpush
@section('index')
    MACHINE
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            MACHINE UPDATE
        </div>
        <div class="card-body">
            <form action="{{ route('machine.update', $machine->id) }}" method="POST">
                @csrf
                <div class="row">

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input name="name" value="{{ $machine->name }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Code</label>
                            <input name="code" value="{{ $machine->code }}" class="form-control">
                        </div>
                    </div>

                </div>
                <br>
                <div class="row px-3">

                    <div class="mr-auto mt-2"><a href="{{ route('machine.index') }}">Go Back</a></div>

                    <div><button type="submit" class="btn btn-info">save</button></div>

                </div>
            </form>
        </div>
    </div>
@endsection
