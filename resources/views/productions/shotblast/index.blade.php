@extends('layouts.app')
@push('title')
    <title>SHOTBLAST LIST</title>
@endpush
@section('index')
    SHOTBLAST
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row px-2">
                <div class="mr-auto">
                    <h5>SHOTBLAST LIST</h5>
                </div>
                <div>
                    <a href="{{ route('shotblast.create') }}" class="form-control btn btn-info">create</a>
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
                                Tulang
                            </th>
                            <th>
                                Reject
                            </th>
                            <th>
                                Date
                            </th>
                            <th>
                                Operator Name
                            </th>
                            <th class="removeDT">
                                Action
                            </th>
                        </tr>

                    </thead>
                    <tbody class="text-center">
                        @foreach ($shotblasts as $shotblast)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $shotblast->batch->batch_no }}</td>
                                <td>{{ $shotblast->waste }}</td>
                                <td>{{ $shotblast->defect }}</td>
                                <td>{{ Carbon\Carbon::parse($shotblast->date)->format('d-m-Y') }}</td>
                                <td>{{ $shotblast->user->name }}</td>
                                <td>
                                    <a href="{{ route('shotblast.edit', $shotblast->id) }}"><iconify-icon
                                            icon="akar-icons:edit" width="20" height="20"
                                            style="color: steelblue;"></iconify-icon></a>
                                    <a data-delete="{{ route('shotblast.destroy', $shotblast->id) }}"
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
