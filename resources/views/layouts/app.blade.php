<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    @stack('title')
    <link href="{{ asset('assets/images/lien_orig.png') }}" rel="shortcut icon" />
    <link href="{{ asset('assets/plugins/material/css/materialdesignicons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/simplebar/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
</head>

<body class="navbar-fixed sidebar-fixed" id="body">
    <!-- ====================================
    ——— WRAPPER
    ===================================== -->
    <div class="wrapper">
        <!-- ====================================
          ——— LEFT SIDEBAR WITH OUT FOOTER
        ===================================== -->
        <aside class="left-sidebar sidebar-dark" id="left-sidebar">
            <div id="sidebar" class="sidebar sidebar-with-footer">
                <!-- Aplication Brand -->
                <div class="app-brand">
                    <a href="{{ route('dashboard') }}" class="p-0 justify-content-left">
                        <img style="width: 100px; margin-left: -10px;" src="{{ url('assets/images/lien_orig.png') }}"
                            alt="">
                        <h3><span class="text-white">LIEN YAIK</span></h3>
                    </a>
                </div>
                <!-- begin sidebar scrollbar -->
                <div class="sidebar-left" data-simplebar style="height: 100%;">
                    <!-- sidebar menu -->
                    <ul class="nav sidebar-inner" id="sidebar-menu">
                        <li class="has-sub">
                            <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                                data-target="#dashboards" aria-expanded="false" aria-controls="dashboards">
                                <iconify-icon icon="carbon:dashboard" style="color: white;" width="20"
                                    height="20"></iconify-icon>
                                <span class="nav-text ml-2">DASHBOARDS</span> <b class="caret"></b>
                            </a>
                            <ul class="collapse" data-parent="#sidebar-menu" id="dashboards">
                                <div class="sub-menu">
                                    <li>
                                        <a href="{{ route('dashboard') }}">Dashboard</a>
                                    </li>

                                    <li>
                                        <a href="{{ route('machine_dashboard') }}">Machine Dashboard</a>
                                    </li>

                                    <li>
                                        <a href="{{ route('production_dashboard') }}">Production Dashboard</a>
                                    </li>
                                </div>
                            </ul>
                        </li>
                        <li id="material-stocks" class="has-sub">
                            <a class="sidenav-item-link" href="{{ route('material-stock') }}">
                                <iconify-icon inline icon="carbon:heat-map-stocks" style="color: white;" width="20"
                                    height="20"></iconify-icon>
                                <span class="nav-text ml-2">MATERIAL STOCK</span>
                            </a>
                        </li>
                        <li id="reports" class="has-sub">
                            <a class="sidenav-item-link" href="{{ route('report') }}">
                                <iconify-icon icon="iconoir:stats-report" style="color: white;" width="20"
                                    height="20"></iconify-icon>
                                <span class="nav-text ml-2">REPORT</span>
                            </a>
                        </li>
                        <li id="stocks" class="has-sub">
                            <a class="sidenav-item-link" href="{{ route('stock-report') }}">
                                <iconify-icon inline icon="carbon:heat-map-stocks" style="color: white;" width="20"
                                    height="20"></iconify-icon>
                                <span class="nav-text ml-2">STOCK</span>
                            </a>
                        </li>

                        <li class="section-title">
                            DEPARTMENTS
                        </li>

                        <li class="has-sub">
                            <a class="sidenav-item-link trigger_user" href="javascript:void(0)" data-toggle="collapse"
                                data-target="#users" aria-expanded="false" aria-controls="users">
                                <iconify-icon icon="carbon:user-avatar-filled-alt" style="color: white;" width="20"
                                    height="20"></iconify-icon>
                                <span class="nav-text ml-2">USER</span> <b class="caret"></b>
                            </a>
                            <ul class="collapse" id="users" data-parent="#sidebar-menu">
                                <div class="sub-menu">
                                    <li>
                                        <a class="sidenav-item-link" href="{{ route('role.index') }}">
                                            <span class="nav-text">Role</span>

                                        </a>
                                    </li>
                                    <li>
                                        <a class="sidenav-item-link" href=" {{ route('user.index') }} ">
                                            <span class="nav-text">User</span>

                                        </a>
                                    </li>

                                </div>
                            </ul>
                        </li>

                        <li class="has-sub">
                            <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                                data-target="#products" aria-expanded="false" aria-controls="products">
                                <iconify-icon icon="material-symbols:inventory-2-outline-sharp" style="color: white;"
                                    width="20" height="20"></iconify-icon>
                                <span class="nav-text ml-2">PRODUCT</span> <b class="caret"></b>
                            </a>
                            <ul class="collapse" id="products" data-parent="#sidebar-menu">
                                <div class="sub-menu">

                                    <li>
                                        <a href="{{ route('product.index') }}">Product</a>
                                    </li>

                                </div>
                            </ul>
                        </li>
                        <li class="has-sub">
                            <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                                data-target="#materials" aria-expanded="false" aria-controls="materials">
                                <iconify-icon icon="arcticons:materialos" style="color: white;" width="20"
                                    height="20"></iconify-icon>
                                <span class="nav-text ml-2">MATERIALS</span> <b class="caret"></b>
                            </a>
                            <ul class="collapse" id="materials" data-parent="#sidebar-menu">
                                <div class="sub-menu">

                                    <li>
                                        <a href="{{ route('category.index') }}">Category</a>
                                    </li>

                                    <li>
                                        <a href="{{ route('uom.index') }}">UOM</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('supplier.index') }}">Supplier</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('material.index') }}">Material</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('material-in.index') }}">Material In</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('material-out.index') }}">Material Out</a>
                                    </li>
                                </div>
                            </ul>
                        </li>

                        <li class="has-sub">
                            <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                                data-target="#productions" aria-expanded="false" aria-controls="productions">
                                <iconify-icon icon="fa-solid:pallet" style="color: white;" width="20"
                                    height="20"></iconify-icon>
                                <span class="nav-text ml-2">PRODUCTION</span> <b class="caret"></b>
                            </a>
                            <ul class="collapse" id="productions" data-parent="#sidebar-menu">
                                <div class="sub-menu">
                                    <li>
                                        <a class="sidenav-item-link" href="{{ route('batch.index') }}">
                                            <span class="nav-text">Batch</span>

                                        </a>
                                    </li>
                                    {{-- <li>
                                        <a class="sidenav-item-link" href="{{ route('machine.index') }}">
                                            <span class="nav-text">Machine</span>

                                        </a>
                                    </li> --}}
                                    <li>
                                        <a class="sidenav-item-link" href="{{ route('purchase.index') }}">
                                            <span class="nav-text">Purchase Order</span>

                                        </a>
                                    </li>
                                    <li>
                                        <a class="sidenav-item-link" href="{{ route('production.index') }}">
                                            <span class="nav-text">Production Order</span>

                                        </a>
                                    </li>
                                    <li>
                                        <a class="sidenav-item-link" href="{{ route('press.index') }}">
                                            <span class="nav-text">Press</span>

                                        </a>
                                    </li>
                                    <li>
                                        <a class="sidenav-item-link" href="{{ route('shotblast.index') }}">
                                            <span class="nav-text">Shotblast</span>

                                        </a>
                                    </li>
                                    <li class="has-sub">
                                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                                            data-target="#inprogresses" aria-expanded="false"
                                            aria-controls="inprogresses">
                                            <span class="nav-text" style="font-size: 14px !important;">WORK IN
                                                PROGRESS</span>&nbsp;&nbsp;<b class="caret"></b>
                                        </a>
                                        <ul class="collapse" id="inprogresses" data-parent="#productions">
                                            <div class="sub-menu">

                                                <li>
                                                    <a href="{{ route('grinding.index') }}"><span
                                                            class="nav-text">Grinding</span></a>
                                                </li>

                                                <li>
                                                    <a href="{{ route('drilling.index') }}"><span
                                                            class="nav-text">Drilling</span></a>
                                                </li>

                                                <li>
                                                    <a href="{{ route('final-checking.index') }}"><span
                                                            class="nav-text">Final
                                                            Checking</span></a>
                                                </li>

                                            </div>
                                        </ul>
                                    </li>

                                </div>
                            </ul>
                        </li>
                        <li class="has-sub">
                            <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                                data-target="#warehouses" aria-expanded="false" aria-controls="warehouses">
                                <iconify-icon icon="ic:twotone-warehouse" style="color: white;" width="20"
                                    height="20"></iconify-icon>
                                <span class="nav-text ml-2">WAREHOUSE</span> <b class="caret"></b>
                            </a>
                            <ul class="collapse" id="warehouses" data-parent="#sidebar-menu">
                                <div class="sub-menu">
                                    <li>
                                        <a href="{{ route('pellete.index') }}">Pellete</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('warehouse-in.index') }}">Warehouse In</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('warehouse-out.index') }}">Warehouse Out</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('check-pellete.index') }}">Check Pellete</a>
                                    </li>
                                </div>
                            </ul>
                        </li>

                    </ul>
                </div>
            </div>
        </aside>
        <!-- ====================================
      ——— PAGE WRAPPER
      ===================================== -->
        <div class="page-wrapper">
            <!-- Header -->
            <header class="main-header" id="header">
                <nav class="navbar navbar-expand-lg navbar-light" id="navbar">
                    <!-- Sidebar toggle button -->
                    <button id="sidebar-toggler" class="sidebar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                    </button>
                    <span class="page-title">@yield('index')</span>
                    <div class="navbar-right ">
                        <ul class="nav navbar-nav">
                            <!-- Offcanvas -->
                            <li class="custom-dropdown">
                                <button class="notify-toggler custom-dropdown-toggler">
                                    <i class="mdi mdi-bell-outline icon"></i>
                                    <span class="badge badge-xs rounded-circle order-length"></span>
                                </button>
                                <div class="dropdown-notify">
                                    <header>
                                        <div class="nav nav-underline" id="nav-tab" role="tablist">
                                            <a class="nav-item nav-link active" id="all-tabs" data-toggle="tab"
                                                href="#all" role="tab" aria-controls="nav-home"
                                                aria-selected="true">All (Notifications)</a>
                                        </div>
                                    </header>
                                    <div data-simplebar style="height: 325px;">
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active order-notification" id="all"
                                                role="tabpanel" aria-labelledby="all-tabs">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- User Account -->
                            <li class="dropdown user-menu">
                                <button class="dropdown-toggle nav-link" data-toggle="dropdown">
                                    @if (Auth::user()->profile != null)
                                        <img src="{{ asset('/profile/') }}/{{ Auth::user()->profile }}"
                                            alt="Profile" class="user-image rounded-circle" />
                                    @else
                                        <img src="{{ asset('assets/images/man.png') }}" alt="Profile"
                                            class="user-image rounded-circle" />
                                    @endif
                                    <span class="d-none d-lg-inline-block">{{ Auth::user()->name }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a class="dropdown-link-item" href="{{ route('user.profile') }}">
                                            <i class="mdi mdi-account-outline"></i>
                                            <span class="nav-text">My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-link-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="mdi mdi-logout"></i>
                                            <span class="nav-text">Log Out</span>
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </div>
                </nav>
            </header>
            <div class="content-body container-fluid mt-3">
                @include('includes.errors')
                @include('includes.success')
                @yield('content')
            </div>
            <!-- Footer  -->
            <footer class="footer mt-auto text-center">
                <div class="copyright bg-white">
                    <p>
                        &copy; <span id="copy-year">{{ date('Y') }}</span> Copyright <a class="text-primary"
                            href="http://iotsata.com/" target="_blank">IOT SATA SDN BHD.</a>All rights reserved.
                    </p>
                </div>
            </footer>
            <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
            <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
            <script src="{{ asset('assets/js/side-bar.js') }}"></script>
            <script src="{{ asset('assets/plugins/simplebar/simplebar.min.js') }}"></script>
            <script src="{{ asset('assets/js/iconify-icon.min.js') }}"></script>
            <script src="{{ asset('assets/js/sweetalert.js') }}"></script>
            <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
            <script src="{{ asset('assets/js/deletion.js') }}"></script>
            <script src="{{ asset('assets/js/mono.js') }}"></script>
            <script src="{{ asset('assets/js/select2.min.js') }}"></script>
            <script>
                function GetOrder() {
                    $.ajax({
                        url: "{{ route('notification.index') }}",
                        success: function(data) {
                            if (data != null) {
                                $('.order-notification').html('');
                                $('.order-length').text(data.length);
                                $('.order-notification').append(data.output);
                            }
                        }
                    });
                }
                setInterval(GetOrder, 5000);
            </script>
            @stack('custom-scripts')
</body>

</html>
