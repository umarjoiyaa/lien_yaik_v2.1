@extends('layouts.app')
@push('title')
    <title>WAREHOUSE IN LIST</title>
@endpush
@section('index')
    WAREHOUSE IN
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row px-2">
                <div class="mr-auto">
                    <h5>WAREHOUSE IN LIST</h5>
                </div>
                <div>
                    <a href="{{ route('warehouse-in.create') }}" class="form-control btn btn-info">create</a>
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
                        @foreach ($warehouse_ins as $warehouse_in)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $warehouse_in->batch->batch_no }}</td>
                                <td>{{ $warehouse_in->remarks }}</td>
                                <td>{{ Carbon\Carbon::parse($warehouse_in->date)->format('Y-m-d') }}</td>
                                <td>{{ $warehouse_in->user->name }}</td>
                                <td>
                                    <a href="{{ route('warehouse-in.scan', $warehouse_in->id) }}"><iconify-icon
                                        icon="arcticons:lexmark-print" width="20" height="20"
                                        style="color: black;"></iconify-icon></a> 

                                    <a href="{{ route('warehouse-in.edit', $warehouse_in->id) }}"><iconify-icon
                                            icon="akar-icons:edit" width="20" height="20"
                                            style="color: steelblue;"></iconify-icon></a>
                                    <a data-delete="{{ route('warehouse-in.destroy', $warehouse_in->id) }}"
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
