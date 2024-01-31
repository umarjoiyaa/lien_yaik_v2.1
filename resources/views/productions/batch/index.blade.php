@extends('layouts.app')
@push('title')
    <title>BATCH LIST</title>
@endpush
@section('index')
    BATCH
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row px-2">
                <div class="mr-auto">
                    <h5>BATCH LIST</h5>
                </div>
                <div>
                    <a href="{{ route('batch.create') }}" class="form-control btn btn-info">create</a>
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
                                Planned Start
                            </th>
                            <th>
                                Planned End
                            </th>
                            <th>
                                Duration
                            </th>
                            <th class="removeDT">
                                Action
                            </th>
                        </tr>

                    </thead>
                    <tbody class="text-center">
                        @foreach ($batches as $batch)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $batch->batch_no }}</td>
                                <td>{{ Carbon\Carbon::parse($batch->planned_start)->format('d-m-Y H:i:s') }}</td>
                                <td>{{ Carbon\Carbon::parse($batch->planned_end)->format('d-m-Y H:i:s') }}</td>
                                <td>{{ $batch->duration }} Hour(s)</td>
                                <td>
                                    <a href="{{ route('batch.edit', $batch->id) }}"><iconify-icon icon="akar-icons:edit"
                                            width="20" height="20" style="color: steelblue;"></iconify-icon></a>
                                    <a data-delete="{{ route('batch.destroy', $batch->id) }}"
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
