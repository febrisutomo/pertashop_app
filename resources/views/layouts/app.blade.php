<!-- Developed By Febri Sutomo -->
<!-- https://github.com/febrisutomo -->
<!-- febrisutomo@gmail.com -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Introduction | AdminLTE v3.2 Documentation</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.3.0/css/OverlayScrollbars.min.css"
        integrity="sha512-ZVVoM7L0mJANR/tsZmE6JqvKq8VG8Ry7YVFc6WioMUNkQiU0tzuuJLTKmKuz7vaDIqiYaeDTvlcrURXmOdnqlg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    @stack('style')

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('images/logo-pertashop.png') }}" alt="pertashop-logo"
                height="80" width="80">
        </div>
        <nav class="main-header navbar navbar-expand navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">

                <li class="nav-item">
                    <div>
                        <p class="mb-0 text-primary font-weight-bold text-uppercase">
                            @if (Auth::user()->role == 'operator')
                                {{ Auth::user()->operator->shop->corporation->nama }}
                            @else
                                Admin Pertashop
                            @endif
                        </p>
                    </div>
                </li>

            </ul>


        </nav>
        <aside class="main-sidebar sidebar-light-primary elevation-4">
            <a href="/" class="brand-link logo-switch">
                <img src="{{ asset('images/logo-pertashop.png') }}" alt="AdminLTE Docs Logo Small"
                    class="brand-image-xl logo-xs">
                <img src="{{ asset('images/logo-pertashop-hz.png') }}" alt="AdminLTE Docs Logo Large"
                    class="brand-image-xs logo-xl" style="left: 12px">
            </a>
            <div class="sidebar">
                <div class="user-panel mb-3 d-flex align-items-center">
                    <div class="image">
                        <img src="https://ui-avatars.com/api/?name={{ str_replace(' ', '+', Auth::user()->name) }}&background=0D8ABC&color=fff"
                            class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                        <span class="badge badge-pill badge-primary">{{ Auth::user()->role }}</span>
                    </div>
                </div>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar nav-child-indent flex-column" data-widget="treeview"
                        role="menu">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('sales.index') }}"
                                class="nav-link {{ Request::routeIs('sales.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-upload"></i>
                                <p>
                                    Penjualan
                                </p>
                            </a>
                        </li>
                        @if (collect(['super-admin', 'admin'])->contains(Auth::user()->role))
                            <li class="nav-item">
                                <a href="{{ route('purchases.index') }}"
                                    class="nav-link {{ Request::routeIs('purchases.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-download"></i>
                                    <p>
                                        Pembelian
                                    </p>
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a href="{{ route('incomings.index') }}"
                                class="nav-link {{ Request::routeIs('incomings.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-truck-moving"></i>
                                <p>
                                    Kedatangan
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('test-pumps.index') }}"
                                class="nav-link {{ Request::routeIs('test-pumps.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-vial"></i>
                                <p>
                                    Percobaan
                                </p>
                            </a>
                        </li>


                        @if (collect(['super-admin', 'admin'])->contains(Auth::user()->role))
                            <li class="nav-item {{ Request::routeIs('reports.*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ Request::routeIs('reports.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-file-alt"></i>
                                    <p>
                                        Laporan
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('reports.index') }}"
                                            class="nav-link {{ Request::routeIs('reports.index') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Laporan Bulanan</p>
                                        </a>
                                    </li>
                                    {{-- <li class="nav-item">
                                    <a href="/docs/3.2/components/main-sidebar.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Laba Bersih</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/docs/3.2/components/control-sidebar.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Modal</p>
                                    </a>
                                </li> --}}
                                    <li class="nav-item">
                                        <a href="/docs/3.2/components/control-sidebar.html" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Rekap Modal</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/docs/3.2/components/control-sidebar.html" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Rekap Profit Sharing</p>
                                        </a>
                                    </li>

                                </ul>

                            </li>
                            <li class="nav-item {{ Request::is('data/*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ Request::is('data/*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-database"></i>
                                    <p>
                                        Data Master
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('operators.index') }}"
                                            class="nav-link {{ Request::routeIs('operators.index') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Operator</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/docs/3.2/components/main-sidebar.html" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Investor</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/docs/3.2/components/main-sidebar.html" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Pertashop</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/docs/3.2/components/control-sidebar.html" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Badan Usaha</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/docs/3.2/components/control-sidebar.html" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Harga</p>
                                        </a>
                                    </li>

                                </ul>

                            </li>
                        @endif
                        <li class="nav-item pt-2 border-top">
                            <a class="nav-link" href="{{ route('logout') }}" onclick="logout(event)">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </li>

                    </ul>
                </nav>

            </div>
        </aside>
        <div class="content-wrapper">
            @yield('content')
        </div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                v1.0
            </div>
            <strong>&copy; 2023 <a href="#">Pertashop App</a>. </strong> Developed By  <a href="https://github.com/febrisutomo">Febri S</a>.
        </footer>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.bundle.min.js"
        integrity="sha512-mULnawDVcCnsk9a4aG1QLZZ6rcce/jSzEGqUkeOLy0b6q0+T6syHrxlsAGH7ZVoqC93Pd0lBqd6WguPWih7VHA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.3.0/js/OverlayScrollbars.min.js"
        integrity="sha512-TuTpz+pcpt4iP0NRpsgIXjew7KvI0u63QbaoAhcpqwlfHkPCDxp/Rar4K9Cd+mvyejGJwwUFtDqX+L1foZPJyQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>

    <script src="{{ asset('js/adminlte.min.js') }}"></script>

    <script>
        function formatNumber(data, fractionDigit = 2) {
            return parseFloat(data).toLocaleString('id-ID', {
                minimumFractionDigits: fractionDigit,
                maximumFractionDigits: fractionDigit
            });
        }

        function formatCurrency(data, fractionDigit = 2) {
            return parseFloat(data).toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: fractionDigit,
                maximumFractionDigits: fractionDigit
            });
        }

        $(document).ready(function() {
            $('.currency').each(function() {
                var value = parseFloat($(this).text());
                if (!isNaN(value)) {
                    var formattedValue = formatCurrency(value, 0);
                    $(this).text(formattedValue);
                }
            });

            $('.number').each(function() {
                var value = parseFloat($(this).text());
                if (!isNaN(value)) {
                    var formattedValue = formatNumber(value);
                    $(this).text(formattedValue);
                }
            });

            $('.number-int').each(function() {
                var value = parseFloat($(this).text());
                if (!isNaN(value)) {
                    var formattedValue = formatNumber(value, 0);
                    $(this).text(formattedValue);
                }
            });
            $('.number-abs').each(function() {
                var value = Math.abs(parseFloat($(this).text()));
                if (!isNaN(value)) {
                    var formattedValue = formatNumber(value);
                    $(this).text(formattedValue);
                }
            });
        });


        function logout(e) {
            e.preventDefault()
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Anda yakin ingin logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#logout-form').submit()
                }
            });
        }


        @if (session('success'))
            var popupId = "{{ uniqid() }}";
            if (!sessionStorage.getItem('shown-' + popupId)) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000 // milliseconds
                });
            }
            sessionStorage.setItem('shown-' + popupId, '1');
        @endif
    </script>

    @stack('script')

</body>

</html>
