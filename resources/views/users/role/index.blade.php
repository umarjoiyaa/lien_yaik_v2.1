@extends('layouts.app')
@push('title')
    <title>ROLE LIST</title>
@endpush
@section('index')
    ROLE
@endsection
@section('content')
    <div class="container-fluid mt-4">
        <div class="card">
            <div class="card-header">
                <div class="row px-2">
                    <div class="mr-auto">
                        <h5>ROLE LIST</h5>
                    </div>
                    <div>
                        <a href="{{ route('role.create') }}" class="form-control btn btn-info">create</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped firsttable" id="myTable">

                        <thead class="" style="background:#FBEA9D !important;">
                            <tr>
                                <th>
                                    Serial No
                                </th>
                                <th>
                                    Name
                                </th>
                                <th class="removeDT">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        <a href="{{ route('role.edit', $role->id) }}"><iconify-icon icon="akar-icons:edit"
                                                width="20" height="20" style="color: steelblue;"></iconify-icon></a>
                                        <a data-delete="{{ route('role.destroy', $role->id) }}"
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
    </div>
@endsection
