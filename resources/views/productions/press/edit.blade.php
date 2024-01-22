@extends('layouts.app')
@push('title')
    <title>PRESS UPDATE</title>
@endpush
@section('index')
    PRESS
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            PRESS UPDATE
        </div>
        <div class="card-body">
            <form action="{{ route('press.update', $press->id) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-sm-4">
                        <label for="" class="form-label">PIC</label>
                        <select name="pic" class="form-select">
                            <option value="" selected disabled>Select an option</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected($press->pic == $user->id)>{{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label for="" class="form-label">Batch</label>
                        @if ($already > 0)
                            <input type="hidden" name="batch" id="batch_id" value="{{ $press->batch_id }}">
                            <input type="hidden" name="machine" id="machine_id" value="{{ $press->machine_id }}">
                        @endif
                        <select name="batch" class="form-select"
                            @if ($already > 0) disabled @else id="batch_id" @endif onchange="checkMachine()">
                            <option value="" selected disabled>Select an option</option>
                            @foreach ($batches as $batch)
                                <option value="{{ $batch->id }}" @selected($press->batch_id == $batch->id)>{{ $batch->batch_no }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label for="" class="form-label">Machine</label>
                        <select name="machine" class="form-select"
                            @if ($already > 0) disabled @else id="machine_id" @endif
                            onchange="checkMachine()">
                            <option value="" selected disabled>Select an option</option>
                            @foreach ($machines as $machine)
                                <option value="{{ $machine->id }}" @selected($press->machine_id == $machine->id)>{{ $machine->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <script>
                        function checkMachine() {
                            const batch_id = $('#batch_id').val();
                            const machine_id = $('#machine_id').val();
                            history.pushState(null, null, window.location.pathname + "?batch_id=" + batch_id + "&machine_id=" + machine_id);

                            @php
                                $press_id = $press->id;
                                $batch_id = request('batch_id');
                                $machine_id = request('machine_id');
                                $check_machine = App\Models\PressDetail::where('batch_id', '=', $batch_id)
                                    ->where('machine_id', '=', $machine_id)
                                    ->where('press_id', '=', $press_id)
                                    ->orderBy('id', 'DESC')
                                    ->first();
                            @endphp

                            check_machines(@json($check_machine));
                            $('#msg').html('');
                        }
                    </script>
                    <div class="col-sm-4 mt-5">
                        <label for="" class="form-label">Date</label>
                        <input type="date" name="date" value="{{ $press->date }}" class="form-control">
                    </div>
                </div>

                <div class="row mt-5 d-flex justify-content-center text-center">
                    <div id="play" class="col-sm-2" onclick="machineStarter(1, {{ $press->id }})">
                        <iconify-icon icon="codicon:debug-start"
                            style="color: green; font-size:100px; cursor:pointer;"></iconify-icon>
                    </div>
                    <div id="pause" class="col-sm-2" onclick="machineStarter(2, {{ $press->id }})">
                        <iconify-icon icon="carbon:pause-future"
                            style="color: rgb(79, 209, 217); font-size:100px; cursor:pointer;"></iconify-icon>
                    </div>
                    <div id="stop" class="col-sm-2" onclick="machineStarter(3, {{ $press->id }})">
                        <iconify-icon icon="bx:stop-circle"
                            style="color: red; font-size:100px; cursor:pointer;"></iconify-icon>
                    </div>
                </div>
                <div class="row mt-2">
                    <div id="msg" class="col-12 text-center"></div>
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
@push('custom-scripts')
    <script>
        var data = "{{ route('press.start') }}";
        var check_machine = @json($check_machines);
    </script>
    <script src="{{ asset('assets/js/custom/productions/press/edit.js') }}"></script>
@endpush
