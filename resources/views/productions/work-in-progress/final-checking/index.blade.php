@extends('layouts.app')
@push('title')
    <title>FINAL CHECKING LIST</title>
@endpush
@section('index')
    FINAL CHECKING
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row px-2">
                <div class="mr-auto">
                    <h5>FINAL CHECKING LIST</h5>
                </div>
                <div>
                    <a href="{{ route('final-checking.create') }}" class="form-control btn btn-info">create</a>
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
                        @foreach ($final_checkings as $final_checking)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $final_checking->batch->batch_no }}</td>
                                <td>{{ $final_checking->remarks }}</td>
                                <td>{{ Carbon\Carbon::parse($final_checking->date)->format('d-m-Y') }}</td>
                                <td>{{ $final_checking->user->name }}</td>
                                <td>
                                    <a href="{{ route('final-checking.edit', $final_checking->id) }}"><iconify-icon
                                            icon="akar-icons:edit" width="20" height="20"
                                            style="color: steelblue;"></iconify-icon></a>
                                    <a data-delete="{{ route('final-checking.destroy', $final_checking->id) }}"
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
