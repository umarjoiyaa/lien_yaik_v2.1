@extends('layouts.app')
@push('title')
    <title>ROLE CREATE</title>
@endpush
@section('index')
    ROLE
@endsection
@section('content')
    <style>
        .sub-menu1 {
            display: none;
        }

        .sub-menu1.open {
            display: block;
        }

        .menu-arrow {
            cursor: pointer;
        }

        .card-body li {
            list-style: none;
        }

        .check_name, .check_box {
            margin-left: 30px;
        }
    </style>
    <div class="card">
        <!-- Floating Labels Form -->
        <form action="{{ route('role.store') }}" method="POST">
            @csrf
            <div class="card-header">
                ROLE CREATE
            </div>
            <div class="card-body mt-4">
                <div class="row">
                    <div class="col-sm-4">
                        <label for="name"> Name </label>
                        <input type="text" class="form-control mt-2" id="name" value="{{ old('name') }}"
                            name="name">
                    </div>
                </div>
                <div class="row mt-5 ml-0">
                    <h5>Permissions</h5>
                </div>
                <hr>
                <div class="row mt-5">
                    <div class="col-sm-6">
                        <div class="form-check">
                            <ul class="">
                                <li class="mb-5 parents">
                                    <input type="checkbox"
                                            class="form-check-input dashboards name" /><label>Dashboards</label>
                                    <iconify-icon class="menu-arrow ms-5" icon="solar:alt-arrow-down-outline" width="20"
                                        height="20"></iconify-icon>
                                    <ul class="sub-menu1 check_name">
                                        @foreach ($dashboards as $key => $dashboard)
                                            @php
                                                $let = 0;
                                            @endphp
                                            <li class="parents child">
                                                <input type="checkbox"
                                                        class="form-check-input dashboard check_parent" /><label class="label">{{ $key }}</label>
                                                <iconify-icon class="menu-arrow ms-5" icon="solar:alt-arrow-down-outline"
                                                    width="20" height="20"></iconify-icon>
                                                <ul class="sub-menu1 check_box">
                                                    @foreach ($permissions as $row)
                                                        @if ($let < 1)
                                                            @if (Str::contains($row->name, $key))
                                                                <li>
                                                                    <input type="checkbox"
                                                                            name="permission[]" value="{{ $row->id }}"
                                                                            class="form-check-input names"><label>{{ $dashboard[$let] }}</label>
                                                                </li>
                                                                @php
                                                                    $let++;
                                                                @endphp
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                            <ul class="">
                                <li class="mb-5 parents">
                                    <input type="checkbox" class="form-check-input reports name" /><label>Reports</label>
                                    <iconify-icon class="menu-arrow ms-5" icon="solar:alt-arrow-down-outline" width="20"
                                        height="20"></iconify-icon>
                                    <ul class="sub-menu1 check_name">
                                        @foreach ($reports as $key => $report)
                                            @php
                                                $let = 0;
                                            @endphp
                                            <li class="parents child">
                                                <input type="checkbox"
                                                        class="form-check-input report check_parent" /><label class="label">{{ $key }}</label>
                                                <iconify-icon class="menu-arrow ms-5" icon="solar:alt-arrow-down-outline"
                                                    width="20" height="20"></iconify-icon>
                                                <ul class="sub-menu1 check_box">
                                                    @foreach ($permissions as $row)
                                                        @if ($let < 1)
                                                            @if (Str::contains($row->name, $key))
                                                                <li>
                                                                    <input type="checkbox"
                                                                            name="permission[]" value="{{ $row->id }}"
                                                                            class="form-check-input names"><label>{{ $report[$let] }}</label>
                                                                </li>
                                                                @php
                                                                    $let++;
                                                                @endphp
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                            <ul class="">
                                <li class="mb-5 parents">
                                    <input type="checkbox" class="form-check-input users name" /><label>Users</label>
                                    <iconify-icon class="menu-arrow ms-5" icon="solar:alt-arrow-down-outline" width="20"
                                        height="20"></iconify-icon>
                                    <ul class="sub-menu1 check_name">
                                        @foreach ($users as $key => $user)
                                            @php
                                                $let = 0;
                                            @endphp
                                            <li class="parents child">
                                                <input type="checkbox"
                                                        class="form-check-input user check_parent" /><label class="label">{{ $key }}</label>
                                                <iconify-icon class="menu-arrow ms-5" icon="solar:alt-arrow-down-outline"
                                                    width="20" height="20"></iconify-icon>
                                                <ul class="sub-menu1 check_box">
                                                    @foreach ($permissions as $row)
                                                        @if ($let < 4)
                                                            @if (Str::contains($row->name, $key))
                                                                <li>
                                                                    <input type="checkbox"
                                                                            name="permission[]" value="{{ $row->id }}"
                                                                            class="form-check-input names"><label>{{ $user[$let] }}</label>
                                                                </li>
                                                                @php
                                                                    $let++;
                                                                @endphp
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                            <ul class="">
                                <li class="mb-5 parents">
                                    <input type="checkbox" class="form-check-input products name" /><label>Products</label>
                                    <iconify-icon class="menu-arrow ms-5" icon="solar:alt-arrow-down-outline" width="20"
                                        height="20"></iconify-icon>
                                    <ul class="sub-menu1 check_name">
                                        @foreach ($products as $key => $product)
                                            @php
                                                $let = 0;
                                            @endphp
                                            <li class="parents child">
                                                <input type="checkbox"
                                                        class="form-check-input product check_parent" /><label class="label">{{ $key }}</label>
                                                <iconify-icon class="menu-arrow ms-5" icon="solar:alt-arrow-down-outline"
                                                    width="20" height="20"></iconify-icon>
                                                <ul class="sub-menu1 check_box">
                                                    @foreach ($permissions as $row)
                                                        @if ($let < 4)
                                                            @if (Str::contains($row->name, $key))
                                                                <li>
                                                                    <input type="checkbox"
                                                                            name="permission[]"
                                                                            value="{{ $row->id }}"
                                                                            class="form-check-input names"><label>{{ $product[$let] }}</label>
                                                                </li>
                                                                @php
                                                                    $let++;
                                                                @endphp
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                            <ul class="">
                                <li class="mb-5 parents">
                                    <input type="checkbox"
                                            class="form-check-input materials name" /><label>Materials</label>
                                    <iconify-icon class="menu-arrow ms-5" icon="solar:alt-arrow-down-outline"
                                        width="20" height="20"></iconify-icon>
                                    <ul class="sub-menu1 check_name">
                                        @foreach ($materials as $key => $material)
                                            @php
                                                $let = 0;
                                            @endphp
                                            <li class="parents child">
                                                <input type="checkbox"
                                                        class="form-check-input material check_parent" /><label class="label">{{ $key }}</label>
                                                <iconify-icon class="menu-arrow ms-5" icon="solar:alt-arrow-down-outline"
                                                    width="20" height="20"></iconify-icon>
                                                <ul class="sub-menu1 check_box">
                                                    @foreach ($permissions as $row)
                                                        @if ($let < 4)
                                                            @if (Str::contains($row->name, $key))
                                                                <li>
                                                                    <input type="checkbox"
                                                                            name="permission[]"
                                                                            value="{{ $row->id }}"
                                                                            class="form-check-input names"><label>{{ $material[$let] }}</label>
                                                                </li>
                                                                @php
                                                                    $let++;
                                                                @endphp
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                            <ul class="">
                                <li class="mb-5 parents">
                                    <input type="checkbox"
                                            class="form-check-input productions name" /><label>Productions</label>
                                    <iconify-icon class="menu-arrow ms-5" icon="solar:alt-arrow-down-outline"
                                        width="20" height="20"></iconify-icon>
                                    <ul class="sub-menu1 check_name">
                                        @foreach ($productions as $key => $production)
                                            @php
                                                $let = 0;
                                            @endphp
                                            <li class="parents child">
                                                <input type="checkbox"
                                                        class="form-check-input production check_parent" /><label class="label">{{ $key }}</label>
                                                <iconify-icon class="menu-arrow ms-5" icon="solar:alt-arrow-down-outline"
                                                    width="20" height="20"></iconify-icon>
                                                <ul class="sub-menu1 check_box">
                                                    @foreach ($permissions as $row)
                                                        @if ($let < 4)
                                                            @if (Str::contains($row->name, $key))
                                                                <li>
                                                                    <input type="checkbox"
                                                                            name="permission[]"
                                                                            value="{{ $row->id }}"
                                                                            class="form-check-input names"><label>{{ $production[$let] }}</label>
                                                                </li>
                                                                @php
                                                                    $let++;
                                                                @endphp
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                            <ul class="">
                                <li class="mb-5 parents">
                                    <input type="checkbox"
                                            class="form-check-input inprogresses name" /><label>Work In Progress</label>
                                    <iconify-icon class="menu-arrow ms-5" icon="solar:alt-arrow-down-outline"
                                        width="20" height="20"></iconify-icon>
                                    <ul class="sub-menu1 check_name">
                                        @foreach ($productions as $key => $inprogress)
                                            @php
                                                $let = 0;
                                            @endphp
                                            <li class="parents child">
                                                <input type="checkbox"
                                                        class="form-check-input inprogress check_parent" /><label class="label">{{ $key }}</label>
                                                <iconify-icon class="menu-arrow ms-5" icon="solar:alt-arrow-down-outline"
                                                    width="20" height="20"></iconify-icon>
                                                <ul class="sub-menu1 check_box">
                                                    @foreach ($permissions as $row)
                                                        @if ($let < 4)
                                                            @if (Str::contains($row->name, $key))
                                                                <li>
                                                                    <input type="checkbox"
                                                                            name="permission[]"
                                                                            value="{{ $row->id }}"
                                                                            class="form-check-input names"><label>{{ $inprogress[$let] }}</label>
                                                                </li>
                                                                @php
                                                                    $let++;
                                                                @endphp
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                            <ul class="">
                                <li class="mb-5 parents">
                                    <input type="checkbox"
                                            class="form-check-input warehouses name" /><label>WareHouses</label>
                                    <iconify-icon class="menu-arrow ms-5" icon="solar:alt-arrow-down-outline"
                                        width="20" height="20"></iconify-icon>
                                    <ul class="sub-menu1 check_name">
                                        @foreach ($warehouses as $key => $warehouse)
                                            @php
                                                $let = 0;
                                            @endphp
                                            <li class="parents child">
                                                <input type="checkbox"
                                                        class="form-check-input warehouse check_parent" /><label class="label">{{ $key }}</label>
                                                <iconify-icon class="menu-arrow ms-5" icon="solar:alt-arrow-down-outline"
                                                    width="20" height="20"></iconify-icon>
                                                <ul class="sub-menu1 check_box">
                                                    @foreach ($permissions as $row)
                                                        @if ($let < 4)
                                                            @if (Str::contains($row->name, $key))
                                                                <li>
                                                                    <input type="checkbox"
                                                                            name="permission[]"
                                                                            value="{{ $row->id }}"
                                                                            class="form-check-input names"><label>{{ $warehouse[$let] }}</label>
                                                                </li>
                                                                @php
                                                                    $let++;
                                                                @endphp
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                            <ul class="">
                                <li class="mb-5 parents">
                                    <input type="checkbox" class="form-check-input others name" /><label>Others</label>
                                    <iconify-icon class="menu-arrow ms-5" icon="solar:alt-arrow-down-outline"
                                        width="20" height="20"></iconify-icon>
                                    <ul class="sub-menu1 check_name">
                                        @foreach ($others as $key => $other)
                                            @php
                                                $let = 0;
                                            @endphp
                                            <li class="parents child">
                                                <input type="checkbox"
                                                        class="form-check-input other check_parent" /><label class="label">{{ $key }}</label>
                                                <iconify-icon class="menu-arrow ms-5" icon="solar:alt-arrow-down-outline"
                                                    width="20" height="20"></iconify-icon>
                                                <ul class="sub-menu1 check_box">
                                                    @foreach ($permissions as $row)
                                                        @if ($let < 4)
                                                            @if (Str::contains($row->name, $key))
                                                                <li>
                                                                    <input type="checkbox"
                                                                            name="permission[]"
                                                                            value="{{ $row->id }}"
                                                                            class="form-check-input names"><label>{{ $other[$let] }}</label>
                                                                </li>
                                                                @php
                                                                    $let++;
                                                                @endphp
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row px-3">

                    <div class="mr-auto mt-2"><a href="{{ route('role.index') }}">Go Back</a></div>

                    <div><button type="submit" class="btn btn-info">save</button></div>

                </div>
            </div>
        </form><!-- End floating Labels Form -->
    </div>
@endsection
@push('custom-scripts')
    <script src="{{ asset('assets/js/custom/users/role/create.js') }}"></script>
@endpush
