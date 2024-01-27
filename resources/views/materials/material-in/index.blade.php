@extends('layouts.app')
@push('title')
    <title>MATERIAL IN LIST</title>
@endpush
@section('index')
    MATERIAL IN
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row px-2">
                <div class="mr-auto">
                    <h5>MATERIAL IN LIST</h5>
                </div>
                <div>
                    <a href="{{ route('material-in.create') }}" class="form-control btn btn-info">create</a>
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
                        @foreach ($material_ins as $material_in)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ Carbon\Carbon::parse($material_in->date)->format('d-m-Y') }}</td>
                                <td>{{ $material_in->user->name }}</td>
                                <td>
                                    <a href="{{ route('material-in.edit', $material_in->id) }}"><iconify-icon
                                            icon="akar-icons:edit" width="20" height="20"
                                            style="color: steelblue;"></iconify-icon></a>
                                    <a data-delete="{{ route('material-in.destroy', $material_in->id) }}"
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
