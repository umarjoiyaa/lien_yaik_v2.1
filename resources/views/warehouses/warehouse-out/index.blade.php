@extends('layouts.app')
@push('title')
    <title>WAREHOUSE OUT LIST</title>
@endpush
@section('index')
    WAREHOUSE OUT
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row px-2">
                <div class="mr-auto">
                    <h5>WAREHOUSE OUT LIST</h5>
                </div>
                <div>
                    <a href="{{ route('warehouse-out.create') }}" class="form-control btn btn-info">create</a>
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
                        @foreach ($warehouse_outs as $warehouse_out)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $warehouse_out->batch->batch_no }}</td>
                                <td>{{ $warehouse_out->remarks }}</td>
                                <td>{{ Carbon\Carbon::parse($warehouse_out->date)->format('Y-m-d') }}</td>
                                <td>{{ $warehouse_out->user->name }}</td>
                                <td>
                                    <a href="{{ route('warehouse-out.edit', $warehouse_out->id) }}"><iconify-icon
                                            icon="akar-icons:edit" width="20" height="20"
                                            style="color: steelblue;"></iconify-icon></a>
                                    <a data-delete="{{ route('warehouse-out.destroy', $warehouse_out->id) }}"
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
