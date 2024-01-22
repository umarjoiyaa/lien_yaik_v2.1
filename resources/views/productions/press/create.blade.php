@extends('layouts.app')
@push('title')
    <title>PRESS CREATE</title>
@endpush
@section('index')
    PRESS
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            PRESS CREATE
        </div>
        <div class="card-body">
            <form action="{{ route('press.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-sm-4">
                        <label for="" class="form-label">PIC</label>
                        <select name="pic" id="" class="form-select">
                            <option value="" selected disabled>Select an option</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected(old('pic') == $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label for="" class="form-label">Batch</label>
                        <select name="batch" id="" class="form-select">
                            <option value="" selected disabled>Select an option</option>
                            @foreach ($batches as $batch)
                                <option value="{{ $batch->id }}" @selected(old('batch') == $batch->id)>{{ $batch->batch_no }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label for="" class="form-label">Machine</label>
                        <select name="machine" id="" class="form-select">
                            <option value="" selected disabled>Select an option</option>
                            @foreach ($machines as $machine)
                                <option value="{{ $machine->id }}" @selected(old('machine') == $machine->id)>{{ $machine->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4 mt-5">
                        <label for="" class="form-label">Date</label>
                        <input type="date" name="date" value="{{ old('date') }}" class="form-control">
                    </div>
                </div>
                <br>
                <div class="row px-3">

                    <div class="mr-auto mt-2"><a href="{{ route('press.index') }}">Go Back</a></div>

                    <div><button type="submit" class="btn btn-info">save</button></div>

                </div>
            </form>
        </div>
    </div>
@endsection
