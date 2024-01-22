@extends('layouts.app')
@push('title')
    <title>GRINDING LIST</title>
@endpush
@section('index')
    GRINDING
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row px-2">
                <div class="mr-auto">
                    <h5>GRINDING LIST</h5>
                </div>
                <div>
                    <a href="{{ route('grinding.create') }}" class="form-control btn btn-info">create</a>
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
                        @foreach ($grindings as $grinding)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $grinding->batch->batch_no }}</td>
                                <td>{{ $grinding->remarks }}</td>
                                <td>{{ Carbon\Carbon::parse($grinding->date)->format('Y-m-d') }}</td>
                                <td>{{ $grinding->user->name }}</td>
                                <td>
                                    <a href="{{ route('grinding.edit', $grinding->id) }}"><iconify-icon
                                            icon="akar-icons:edit" width="20" height="20"
                                            style="color: steelblue;"></iconify-icon></a>
                                    <a data-delete="{{ route('grinding.destroy', $grinding->id) }}"
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
