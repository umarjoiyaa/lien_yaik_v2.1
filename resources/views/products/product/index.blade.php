@extends('layouts.app')
@push('title')
    <title>PRODUCT LIST</title>
@endpush
@section('index')
    PRODUCT
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row px-2">
                <div class="mr-auto">
                    <h5>PRODUCT LIST</h5>
                </div>
                <div>
                    <a href="{{ route('product.create') }}" class="form-control btn btn-info">create</a>
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
                                Part Name
                            </th>
                            <th>
                                Part Code
                            </th>
                            <th>
                                Company
                            </th>
                            <th>
                                Dimension
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
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->code }}</td>
                                <td>{{ $product->company }}</td>
                                <td>{{ $product->dimension }}</td>
                                <td>
                                    @if ($product->file != null)
                                        <a target="_blank"
                                            href="{{ asset('/products/') }}/{{ $product->file }}"><iconify-icon
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
                                    <a href="{{ route('product.edit', $product->id) }}"><iconify-icon icon="akar-icons:edit"
                                            width="20" height="20" style="color: steelblue;"></iconify-icon></a>
                                    <a data-delete="{{ route('product.destroy', $product->id) }}"
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
