@extends('layouts.app')
@push('title')
    <title>MACHINE LIMIT</title>
@endpush
@section('index')
    MACHINE
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            MACHINE LIMIT
        </div>
        <div class="card-body">
            <form action="{{ route('machine.limit_set', $machine->id) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Machine Name</label>
                            <input type="text" class="form-control" value="{{ $machine->name }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Machine Code</label>
                            <input type="text" class="form-control" value="{{ $machine->code }}" readonly>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Temperature</label>
                            <span>&#176;</span>
                            <input name="temp_low" type="number" id="temp_low" class="form-control" value="{{(isset($machine->temperature)) ? $machine->temperature->temperature_low : ''}}"
                                 placeholder="Low">
                            <input name="temp_high" type="number" id="temp_high" class="form-control" value="{{(isset($machine->temperature)) ? $machine->temperature->temperature_high : ''}}"
                                 placeholder="High">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Moisture</label>
                            <span>&#37;</span>
                            <input name="moisture_low" type="number" id="moisture_low" class="form-control" value="{{(isset($machine->temperature)) ? $machine->temperature->moisture_low : ''}}"
                                 placeholder="Low">
                            <input name="moisture_high" type="number" id="moisture_high" class="form-control"
                                value="{{(isset($machine->temperature)) ? $machine->temperature->moisture_high : ''}}"  placeholder="High">
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-md-2 back"><a href="{{ route('machine.index') }}">Go Back</a></div>

                        <button type="submit" class="col-md-1 offset-md-9 btn btn-info">set</button>

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
