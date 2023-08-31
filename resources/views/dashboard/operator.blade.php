@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-9">
                    <h1>{{ Auth::user()->operator->shop->kode . ' ' . Auth::user()->operator->shop->nama }}</h1>
                </div>
                {{-- <div class="col-3">
                    <h3 class="text-right text-md font-weight-bold">
                        {{ Auth::user()->operator->shop->kode . ' ' . Auth::user()->operator->shop->nama }}
                    </h3>
                </div> --}}
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-6 col-md-3">
                    <div class="card bg-success">
                        <div class="card-body">
                            <h6>Stok BBM</h6>
                            <h6 class="font-weight-bold mb-0"><span>{{ $stok_akhir }}</span> &ell;</h6>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card bg-purple">
                        <div class="card-body">
                            <h6>Volume Penjualan</h6>
                            <h6 class="font-weight-bold mb-0"><span class="number">{{ $volume_penjualan }}</span> &ell;</h6>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card bg-info">
                        <div class="card-body">
                            <h6>Rupiah Penjualan</h6>
                            <h6 class="font-weight-bold currency mb-0">{{ $rupiah_penjualan }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card bg-fuchsia">
                        <div class="card-body">
                            <h6>Belum Disetorkan</h6>
                            <h6 class="font-weight-bold currency mb-0">{{ $belum_disetorkan }}</h6>
                        </div>
                    </div>
                </div>


            </div>
            <div class="row">

                <div class="col-lg-3 col-6">

                    <div class="card">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <div class="text-primary p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-download"
                                    width="42" height="42" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path>
                                    <path d="M7 11l5 5l5 -5"></path>
                                    <path d="M12 4l0 12"></path>
                                </svg>
                            </div>
                            <h6 class=" font-weight-bold">Penerimaan</h6>
                            <a href="{{ route('incomings.index') }}" class="stretched-link"></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">

                    <div class="card">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <div class="text-primary p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-test-pipe-2"
                                    width="42" height="42" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M15 3v15a3 3 0 0 1 -6 0v-15"></path>
                                    <path d="M9 12h6"></path>
                                    <path d="M8 3h8"></path>
                                </svg>

                            </div>
                            <h6 class=" font-weight-bold">Test Pump</h6>
                            <a href="{{ route('test-pumps.index') }}" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">

                    <div class="card">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <div class="text-primary p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-receipt-2"
                                    width="42" height="42" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2">
                                    </path>
                                    <path d="M14 8h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5m2 0v1.5m0 -9v1.5">
                                    </path>
                                </svg>
                            </div>
                            <h6 class=" font-weight-bold">Pengeluran</h6>
                            <a href="{{ route('spendings.index') }}" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">

                    <div class="card">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <div class="text-primary p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-report"
                                    width="42" height="42" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                            </div>
                            <h6 class=" font-weight-bold">Laporan Harian</h6>
                            <a href="{{ route('daily-reports.index') }}" class="stretched-link"></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
