<div class="wrapper">
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="{{ asset('images/logo-pertashop.png') }}" alt="pertashop-logo" height="80"
            width="80">
    </div>
    <nav class="main-header navbar navbar-expand navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
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
                        @if (Auth::user()->shop)
                            {{ Auth::user()->shop->corporation->nama }}
                        @else
                            Pertashop App
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
                            <i class="nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-dashboard"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M12 13m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                    <path d="M13.45 11.55l2.05 -2.05"></path>
                                    <path d="M6.4 20a9 9 0 1 1 11.2 0z"></path>
                                </svg>
                            </i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>

                    @if (collect(['super-admin', 'admin'])->contains(Auth::user()->role))
                        <li class="nav-item">
                            <a href="{{ route('purchases.index') }}"
                                class="nav-link {{ Request::routeIs('purchases.*') ? 'active' : '' }}">
                                <i class="nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-shopping-cart" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                        <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                        <path d="M17 17h-11v-14h-2"></path>
                                        <path d="M6 5l14 1l-1 7h-13"></path>
                                    </svg>
                                </i>
                                <p>
                                    Pembelian
                                </p>
                            </a>
                        </li>
                    @endif

                    @if (Auth::user()->role != 'investor')
                        <li class="nav-item">
                            <a href="{{ route('daily-reports.index') }}"
                                class="nav-link {{ Request::routeIs('daily-reports.*') ? 'active' : '' }}">
                                <i class="nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-clipboard-text" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path
                                            d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2">
                                        </path>
                                        <path
                                            d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z">
                                        </path>
                                        <path d="M9 12h6"></path>
                                        <path d="M9 16h6"></path>
                                    </svg>
                                </i>
                                <p>
                                    Laporan Harian
                                </p>
                            </a>
                        </li>




                        {{-- <li class="nav-item">
                            <a href="{{ route('incomings.index') }}"
                                class="nav-link {{ Request::routeIs('incomings.*') ? 'active' : '' }}">
                                <i class="nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-download" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path>
                                        <path d="M7 11l5 5l5 -5"></path>
                                        <path d="M12 4l0 12"></path>
                                    </svg>
                                </i>
                                <p>
                                    Penerimaan
                                </p>
                            </a>
                        </li> --}}

                        {{-- <li class="nav-item">
                            <a href="{{ route('test-pumps.index') }}"
                                class="nav-link {{ Request::routeIs('test-pumps.*') ? 'active' : '' }}">
                                <i class="nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-test-pipe-2" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M15 3v15a3 3 0 0 1 -6 0v-15"></path>
                                        <path d="M9 12h6"></path>
                                        <path d="M8 3h8"></path>
                                    </svg>
                                </i>
                                <p>
                                    Test Pump
                                </p>
                            </a>
                        </li> --}}

                        {{-- <li class="nav-item">
                            <a href="{{ route('spendings.index') }}"
                                class="nav-link {{ Request::routeIs('spendings.*') ? 'active' : '' }}">
                                <i class="nav-icon fas">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-receipt-2" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path
                                            d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2">
                                        </path>
                                        <path
                                            d="M14 8h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5m2 0v1.5m0 -9v1.5">
                                        </path>
                                    </svg>
                                </i>
                                <p>
                                    Pengeluaran
                                </p>
                            </a>
                        </li> --}}
                    @endif


                    @if (collect(['super-admin', 'admin', 'investor'])->contains(Auth::user()->role))
                        <li class="nav-item">
                            <a href="{{ route('laba-kotor.index') }}"
                                class="nav-link {{ Request::routeIs('laba-kotor.*') ? 'active' : '' }}">
                                <i class="nav-icon fas">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-file-diff" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                        <path
                                            d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z">
                                        </path>
                                        <path d="M12 10l0 4"></path>
                                        <path d="M10 12l4 0"></path>
                                        <path d="M10 17l4 0"></path>
                                    </svg>
                                </i>
                                <p>
                                    Laba Kotor
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('laba-bersih.index') }}"
                                class="nav-link {{ Request::routeIs('laba-bersih.*') ? 'active' : '' }}">
                                <i class="nav-icon fas">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-file-dollar" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                        <path
                                            d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z">
                                        </path>
                                        <path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5"></path>
                                        <path d="M12 17v1m0 -8v1"></path>
                                    </svg>
                                </i>
                                <p>
                                    Laba Bersih
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('rekap-modal.index') }}"
                                class="nav-link {{ Request::routeIs('modal.*') ? 'active' : '' }}">
                                <i class="nav-icon fas">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-file-star" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                        <path
                                            d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z">
                                        </path>
                                        <path
                                            d="M11.8 16.817l-2.172 1.138a.392 .392 0 0 1 -.568 -.41l.415 -2.411l-1.757 -1.707a.389 .389 0 0 1 .217 -.665l2.428 -.352l1.086 -2.193a.392 .392 0 0 1 .702 0l1.086 2.193l2.428 .352a.39 .39 0 0 1 .217 .665l-1.757 1.707l.414 2.41a.39 .39 0 0 1 -.567 .411l-2.172 -1.138z">
                                        </path>
                                    </svg>
                                </i>
                                <p>
                                    Modal Kerja
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('profit-sharing.index') }}"
                                class="nav-link {{ Request::routeIs('profit-sharing.*') ? 'active' : '' }}">
                                <i class="nav-icon fas">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-file-percent" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M10 17l4 -4"></path>
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                        <path
                                            d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z">
                                        </path>
                                        <path d="M10 13h.01"></path>
                                        <path d="M14 17h.01"></path>
                                    </svg>
                                </i>
                                <p>
                                    Profit Sharing
                                </p>
                            </a>
                        </li>
                        {{-- <li class="nav-item {{ Request::routeIs('reports.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::routeIs('reports.*') ? 'active' : '' }}">
                                <i class="nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-report" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697"></path>
                                        <path d="M18 14v4h4"></path>
                                        <path d="M18 11v-4a2 2 0 0 0 -2 -2h-2"></path>
                                        <path
                                            d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z">
                                        </path>
                                        <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                                        <path d="M8 11h4"></path>
                                        <path d="M8 15h3"></path>
                                    </svg>
                                </i>
                                <p>
                                    Laporan Bulanan
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('reports.index') }}"
                                        class="nav-link {{ Request::routeIs('reports.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Laba Kotor</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('reports.index') }}"
                                        class="nav-link {{ Request::routeIs('reports.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Laba Bersih</p>
                                    </a>
                                </li>
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

                        </li> --}}
                    @endif

                    @if (collect(['super-admin'])->contains(Auth::user()->role))
                        <li class="nav-item {{ Request::is('data/*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('data/*') ? 'active' : '' }}">
                                <i class="nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-database" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M12 6m-8 0a8 3 0 1 0 16 0a8 3 0 1 0 -16 0"></path>
                                        <path d="M4 6v6a8 3 0 0 0 16 0v-6"></path>
                                        <path d="M4 12v6a8 3 0 0 0 16 0v-6"></path>
                                    </svg>
                                </i>
                                <p>
                                    Data Master
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="{{ route('corporations.index') }}"
                                        class="nav-link {{ Request::routeIs('corporations.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Badan Usaha</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('shops.index') }}"
                                        class="nav-link {{ Request::routeIs('shops.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pertashop</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}"
                                        class="nav-link {{ Request::routeIs('users.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>User</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('prices.index') }}"
                                        class="nav-link {{ Request::routeIs('prices.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Harga</p>
                                    </a>
                                </li>

                            </ul>

                        </li>
                    @endif
                    <li class="nav-item pt-2 border-top">
                        <a class="nav-link" href="{{ route('logout') }}" onclick="logout(event)">
                            <i class="nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-logout"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2">
                                    </path>
                                    <path d="M9 12h12l-3 -3"></path>
                                    <path d="M18 15l3 -3"></path>
                                </svg>
                            </i>
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
        <strong>&copy; 2023 <a href="#">Pertashop App</a>. </strong> Developed By <a
            href="https://github.com/febrisutomo">Febri S</a>.
    </footer>
</div>
