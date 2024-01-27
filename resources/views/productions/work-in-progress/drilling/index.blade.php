@extends('layouts.app')
@push('title')
    <title>DRILLING LIST</title>
@endpush
@section('index')
    DRILLING
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row px-2">
                <div class="mr-auto">
                    <h5>DRILLING LIST</h5>
                </div>
                <div>
                    <a href="{{ route('drilling.create') }}" class="form-control btn btn-info">create</a>
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
                                Remarks
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
                        @foreach ($drillings as $drilling)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $drilling->batch->batch_no }}</td>
                                <td>{{ $drilling->remarks }}</td>
                                <td>{{ Carbon\Carbon::parse($drilling->date)->format('d-m-Y') }}</td>
                                <td>{{ $drilling->user->name }}</td>
                                <td>
                                    <a href="{{ route('drilling.edit', $drilling->id) }}"><iconify-icon
                                            icon="akar-icons:edit" width="20" height="20"
                                            style="color: steelblue;"></iconify-icon></a>
                                    <a data-delete="{{ route('drilling.destroy', $drilling->id) }}"
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
