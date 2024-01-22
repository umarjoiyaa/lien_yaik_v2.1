@extends('layouts.app')
@push('title')
    <title>PRESS LIST</title>
@endpush
@section('index')
    PRESS
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row px-2">
                <div class="mr-auto">
                    <h5>PRESS LIST</h5>
                </div>
                <div>
                    <a href="{{ route('press.create') }}" class="form-control btn btn-info">create</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped firsttable" id="myTable">

                    <thead class="table-dark" style="color: white;background: #8d9db6;">
                        <tr>
                            <th>
                                Serial No
                            </th>
                            <th>
                                Batch No
                            </th>
                            <th>
                                Machine
                            </th>
                            <th>
                                Date
                            </th>
                            <th>
                                Status
                            </th>
                            <th class="removeDT">
                                Action
                            </th>
                        </tr>

                    </thead>
                    <tbody class="text-center">
                        @foreach ($presses as $press)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $press->batches->batch_no }}</td>
                                <td>{{ $press->machine->name }}</td>
                                <td>{{ Carbon\Carbon::parse($press->date)->format('d-m-Y') }}</td>
                                <td>
                                    @php
                                        $check = App\Models\PressDetail::where('press_id', '=', $press->id)
                                            ->orderBy('id', 'DESC')
                                            ->first();
                                    @endphp
                                    @if (isset($check))
                                        @if ($check->status == null || $check->status == 0)
                                            <span class="badge badge-secondary">not initiated</span>
                                        @elseif($check->status == 1)
                                            <span class="badge badge-success">started</span>
                                        @elseif($check->status == 2)
                                            <span class="badge badge-warning text-white">paused</span>
                                        @elseif($check->status == 3)
                                            <span class="badge badge-danger">stopped</span>
                                        @endif
                                    @else
                                        <span class="badge badge-secondary">not initiated</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('press.edit', $press->id) }}"><iconify-icon icon="akar-icons:edit"
                                            width="20" height="20" style="color: steelblue;"></iconify-icon></a>
                                    <a data-delete="{{ route('press.destroy', $press->id) }}"
                                        class="delete_row"><iconify-icon icon="fluent:delete-dismiss-24-filled"
                                            width="20" height="20" style="color: red;"></iconify-icon></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
