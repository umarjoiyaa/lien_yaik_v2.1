@extends('layouts.app')
@push('title')
    <title>MATERIAL OUT LIST</title>
@endpush
@section('index')
    MATERIAL OUT
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row px-2">
                <div class="mr-auto">
                    <h5>MATERIAL OUT LIST</h5>
                </div>
                <div>
                    <a href="{{ route('material-out.create') }}" class="form-control btn btn-info">create</a>
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
                                Date
                            </th>
                            <th>
                                PIC
                            </th>
                            <th class="removeDT">
                                Action
                            </th>
                        </tr>

                    </thead>
                    <tbody class="text-center">
                        @foreach ($material_outs as $material_out)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $material_out->date }}</td>
                                <td>{{ $material_out->user->name }}</td>
                                <td>
                                    <a href="{{ route('material-out.edit', $material_out->id) }}"><iconify-icon
                                            icon="akar-icons:edit" width="20" height="20"
                                            style="color: steelblue;"></iconify-icon></a>
                                    <a data-delete="{{ route('material-out.destroy', $material_out->id) }}" class="delete_row"><iconify-icon
                                            icon="fluent:delete-dismiss-24-filled" width="20" height="20"
                                            style="color: red;"></iconify-icon></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
