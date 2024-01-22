@extends('layouts.app')
@push('title')
    <title>PRODUCTION ORDER LIST</title>
@endpush
@section('index')
    PRODUCTION ORDER
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row px-2">
                <div class="mr-auto">
                    <h5>PRODUCTION ORDER LIST</h5>
                </div>
                <div>
                    <a href="{{ route('production.create') }}" class="form-control btn btn-info">create</a>
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
                                Target
                            </th>
                            <th>
                                Reject
                            </th>
                            <th>
                                Order Date
                            </th>
                            <th>
                                Due Date
                            </th>
                            <th class="removeDT">
                                Image
                            </th>
                            <th class="removeDT">
                                Action
                            </th>
                        </tr>

                    </thead>
                    <tbody class="text-center">
                        @foreach ($productions as $production)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $production->batch->batch_no }}</td>
                                <td>{{ $production->target_produce }}</td>
                                <td>{{ $production->reject_qty }}</td>
                                <td>{{ Carbon\Carbon::parse($production->order_date)->format('d-m-Y') }}</td>
                                <td>{{ Carbon\Carbon::parse($production->due_date)->format('d-m-Y') }}</td>
                                <td>
                                    @if ($production->product->file != null)
                                        <a target="_blank"
                                            href="{{ asset('/products/') }}/{{ $production->product->file }}"><iconify-icon
                                                icon="il:image"
                                                style="color: lightgreen; margin-top: 5px; margin-right: 5px;"
                                                width="25" height="25"></iconify-icon></a>
                                    @else
                                        <iconify-icon icon="il:image" title="Not Uploaded"
                                            style="color: lightgreen; margin-top: 5px; margin-right: 5px;" width="25"
                                            height="25"></iconify-icon>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('production.edit', $production->id) }}"><iconify-icon
                                            icon="akar-icons:edit" width="20" height="20"
                                            style="color: steelblue;"></iconify-icon></a>
                                    <a data-delete="{{ route('production.destroy', $production->id) }}"
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
