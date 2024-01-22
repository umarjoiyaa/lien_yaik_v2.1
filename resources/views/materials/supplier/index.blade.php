@extends('layouts.app')
@push('title')
    <title>SUPPLIER LIST</title>
@endpush
@section('index')
    SUPPLIER
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row px-2">
                <div class="mr-auto">
                    <h5>SUPPLIER LIST</h5>
                </div>
                <div>
                    <a href="{{ route('supplier.create') }}" class="form-control btn btn-info">create</a>
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
                                Code
                            </th>
                            <th class="removeDT">
                                Action
                            </th>
                        </tr>

                    </thead>
                    <tbody class="text-center">
                        @foreach ($suppliers as $supplier)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->code }}</td>
                                <td>
                                    <a href="{{ route('supplier.edit', $supplier->id) }}"><iconify-icon
                                            icon="akar-icons:edit" width="20" height="20"
                                            style="color: steelblue;"></iconify-icon></a>
                                    <a data-delete="{{ route('supplier.destroy', $supplier->id) }}" class="delete_row"><iconify-icon
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
