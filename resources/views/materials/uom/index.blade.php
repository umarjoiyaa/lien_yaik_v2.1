@extends('layouts.app')
@push('title')
    <title>UOM LIST</title>
@endpush
@section('index')
    UOM
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row px-2">
                <div class="mr-auto">
                    <h5>UOM LIST</h5>
                </div>
                <div>
                    <a href="{{ route('uom.create') }}" class="form-control btn btn-info">create</a>
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
                                Name
                            </th>
                            <th>
                                Symbol
                            </th>
                            <th class="removeDT">
                                Action
                            </th>
                        </tr>

                    </thead>
                    <tbody class="text-center">
                        @foreach ($uoms as $uom)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $uom->name }}</td>
                                <td>{{ $uom->symbol }}</td>
                                <td>
                                    <a href="{{ route('uom.edit', $uom->id) }}"><iconify-icon icon="akar-icons:edit"
                                            width="20" height="20" style="color: steelblue;"></iconify-icon></a>
                                    <a data-delete="{{ route('uom.destroy', $uom->id) }}" class="delete_row"><iconify-icon
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
