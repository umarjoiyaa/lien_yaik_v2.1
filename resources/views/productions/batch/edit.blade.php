@extends('layouts.app')
@push('title')
    <title>BATCH UPDATE</title>
@endpush
@section('index')
    BATCH
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            BATCH UPDATE
        </div>
        <div class="card-body">
            <form action="{{ route('batch.update', $batch->id) }}" method="POST">
                @csrf
                <div class="row">

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Batch No</label>
                            <input name="batch_no" value="{{ $batch->batch_no }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Planned Start</label>
                            <input type="datetime-local" name="planned_start" value="{{ $batch->planned_start }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Planned End</label>
                            <input type="datetime-local" name="planned_end" value="{{ $batch->planned_end }}" class="form-control">
                        </div>
                    </div>

                </div>
                <br>
                <div class="row px-3">

                    <div class="mr-auto mt-2"><a href="{{ route('batch.index') }}">Go Back</a></div>

                    <div><button type="submit" class="btn btn-info">save</button></div>

                </div>
            </form>
        </div>
    </div>
@endsection
