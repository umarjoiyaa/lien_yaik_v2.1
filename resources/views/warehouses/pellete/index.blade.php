@extends('layouts.app')
@push('title')
    <title>PELLETE LIST</title>
@endpush
@section('index')
    PELLETE
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row px-2">
                <div class="mr-auto">
                    <h5>PELLETE LIST</h5>
                </div>
                <div>
                    <a href="{{ route('pellete.create') }}" class="form-control btn btn-info">create</a>
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
                                Pellete No
                            </th>
                            <th>
                                Status
                            </th>
                            <th class="removeDT">
                                Qr Code
                            </th>
                            <th class="removeDT">
                                Action
                            </th>
                        </tr>

                    </thead>
                    <tbody class="text-center">
                        @foreach ($pelletes as $pellete)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $pellete->pellete_no }}</td>
                                <td><span class="badge badge-success">{{ $pellete->status }}</span></td>
                                <td>
                                    <a target="_blank" href="{{ $pellete->qr_code }}" download="{{ $pellete->qr_code }}"
                                        title="Download Qr Code"><iconify-icon icon="il:image"
                                            style="color: lightgreen; margin-top: 5px; margin-right: 5px;" width="25"
                                            height="25"></iconify-icon></a>
                                </td>
                                <td>
                                    <a href="{{ route('pellete.edit', $pellete->id) }}"><iconify-icon icon="akar-icons:edit"
                                            width="20" height="20" style="color: steelblue;"></iconify-icon></a>
                                    <a data-delete="{{ route('pellete.destroy', $pellete->id) }}"
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
