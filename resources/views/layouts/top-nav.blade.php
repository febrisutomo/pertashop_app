<div class="wrapper">

    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
        <div class="container-fluid">
            <a href="/" class="navbar-brand">
                <img src="{{ asset('images/logo-pertashop.png') }}" alt="Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Pertashop App</span>
            </a>

            <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">

                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-circle"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                            <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                            <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"></path>
                        </svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-header font-weight-bold">{{ Auth::user()->name }}</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-user-circle mr-2"></i><span>Profil</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item text-danger" href="{{ route('logout') }}"
                            onclick="logout(event)">
                            <i class="nav-icon fas fa-sign-out-alt mr-2"></i>
                            <span>Logout</span>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>


    <div class="content-wrapper pb-3">


        @yield('content')


    </div>


    <aside class="control-sidebar control-sidebar-dark">

    </aside>

    <nav class="navbar navbar-expand navbar-light bg-light fixed-bottom border-top bottom-nav">
        <div class="container-fluid">
            <div class="row w-100 justify-content-around">
                <div class="col text-center">
                    <a class="nav-link p-0 {{ Request::routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
                            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path>
                        </svg>
                        <span class="d-block">Beranda</span>
                    </a>
                </div>
                <div class="col text-center">
                    <a class="nav-link p-0 {{ Request::routeIs('daily-reports.create') ? 'active' : '' }}"
                        href="{{ route('daily-reports.create') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-report"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697"></path>
                            <path d="M18 14v4h4"></path>
                            <path d="M18 11v-4a2 2 0 0 0 -2 -2h-2"></path>
                            <path d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z">
                            </path>
                            <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                            <path d="M8 11h4"></path>
                            <path d="M8 15h3"></path>
                        </svg>
                        <span class="d-block">Laporan</span>
                    </a>
                </div>
                <div class="col text-center">
                    <a class="nav-link p-0 {{ Request::routeIs('daily-reports.index') ? 'active' : '' }}"
                        href="{{ route('daily-reports.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-history"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 8l0 4l2 2"></path>
                            <path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5"></path>
                        </svg>
                        <span class="d-block">Riwayat</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <footer class="main-footer">
        <div class="container-fluid">
            <div class="text-center">
                <strong>&copy; 2023 <a href="#">{{ Auth::user()->shop->corporation->nama }}</a>
            </div>
        </div>

    </footer>

    <style>
        /* Default menu color (grey) */
        .bottom-nav .nav-link {
            color: #343a40 !important;
        }

        /* Primary color when active */
        .bottom-nav .nav-link.active {
            color: #007bff !important;
            /* Primary color */
        }
    </style>
</div>
