@extends('layouts.app')
@push('title')
    <title>USER LIST</title>
@endpush
@section('index')
    USER
@endsection
@section('content')
    <div class="container-fluid mt-4">
        <div class="card">
            <div class="card-header">
                <div class="row px-2">
                    <div class="mr-auto">
                        <h5>USER LIST</h5>
                    </div>
                    <div>
                        <a href="{{ route('user.create') }}" class="form-control btn btn-info">create</a>
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
                                <th>
                                    Email
                                </th>
                                <th class="removeDT">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($users as $user)
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <a href="{{ route('user.edit', $user->id) }}"><iconify-icon icon="akar-icons:edit"
                                            width="20" height="20" style="color: steelblue;"></iconify-icon></a>
                                    <a data-delete="{{ route('user.destroy', $user->id) }}"
                                        class="delete_row"><iconify-icon icon="fluent:delete-dismiss-24-filled"
                                            width="20" height="20" style="color: red;"></iconify-icon></a>
                                </td>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
