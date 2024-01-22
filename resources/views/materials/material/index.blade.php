@extends('layouts.app')
@push('title')
    <title>MATERIAL LIST</title>
@endpush
@section('index')
    MATERIAL
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row px-2">
                <div class="mr-auto">
                    <h5>MATERIAL LIST</h5>
                </div>
                <div>
                    <a href="{{ route('material.create') }}" class="form-control btn btn-info">create</a>
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
                                Category
                            </th>
                            <th>
                                Type
                            </th>
                            <th class="removeDT">
                                Action
                            </th>
                        </tr>

                    </thead>
                    <tbody class="text-center">
                        @foreach ($materials as $material)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $material->name }}</td>
                                <td>{{ $material->category->name }}</td>
                                <td>{{ $material->type == 1 ? 'In-House' : 'Out-Source' }}</td>
                                <td>
                                    <a href="{{ route('material.edit', $material->id) }}"><iconify-icon
                                            icon="akar-icons:edit" width="20" height="20"
                                            style="color: steelblue;"></iconify-icon></a>
                                    <a data-delete="{{ route('material.destroy', $material->id) }}" class="delete_row"><iconify-icon
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
