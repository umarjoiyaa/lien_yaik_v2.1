@extends('layouts.app')
@push('title')
    <title>PURCHASE ORDER LIST</title>
@endpush
@section('index')
    PURCHASE ORDER
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row px-2">
                <div class="mr-auto">
                    <h5>PURCHASE ORDER LIST</h5>
                </div>
                <div>
                    <a href="{{ route('purchase.create') }}" class="form-control btn btn-info">create</a>
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
                                Customer Name
                            </th>
                            <th>
                                Part Name
                            </th>
                            <th>
                                Material
                            </th>
                            <th>
                                Purchase No
                            </th>
                            <th>
                                Order Units
                            </th>
                            <th>
                                Cavities
                            </th>
                            <th>
                                Unit KG
                            </th>
                            <th>
                                Per Mold
                            </th>
                            <th>
                                Issued
                            </th>
                            <th>
                                Status
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
                        @foreach ($purchases as $purchase)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $purchase->customer }}</td>
                                <td>{{ $purchase->product->name }}</td>
                                <td>
                                    @foreach (json_decode($purchase->item_id) as $value)
                                        @php
                                            $item = App\Models\Material::find($value);
                                        @endphp
                                        {{ $item->name }}
                                        @unless ($loop->last)
                                            ,
                                        @endunless
                                    @endforeach
                                </td>
                                <td>{{ $purchase->order_no }}</td>
                                <td>{{ $purchase->order_unit }}</td>
                                <td>{{ $purchase->cavities }}</td>
                                <td>{{ $purchase->unit_kg }}</td>
                                <td>{{ $purchase->per_mold }}</td>
                                <td>{{ $purchase->user->name }}</td>
                                <td>
                                    @if ($purchase->status == 0)
                                        <span class="badge badge-warning text-white">pending</span>
                                    @elseif($purchase->status == 1)
                                        <span class="badge badge-success">approved</span>
                                    @elseif ($purchase->status == 2)
                                        <span class="badge badge-danger">rejected</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($purchase->product->file != null)
                                        <a target="_blank"
                                            href="{{ asset('/products/') }}/{{ $purchase->product->file }}"><iconify-icon
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
                                    <a target="_blank" href="{{ route('purchase.pdf', $purchase->id) }}"><iconify-icon
                                            icon="prime:file-pdf" style="color: red;" width="23"
                                            height="23"></iconify-icon></a>
                                    <a href="{{ route('purchase.edit', $purchase->id) }}"><iconify-icon
                                            icon="akar-icons:edit" width="20" height="20"
                                            style="color: steelblue;"></iconify-icon></a>
                                    <a data-delete="{{ route('purchase.destroy', $purchase->id) }}"
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
