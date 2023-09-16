@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <h4>Pertashop {{ Auth::user()->shop->kode . ' ' . Auth::user()->shop->nama }}</h4>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <div class="alert alert-primary" role="alert">
                {{ $sapaan }} <strong>{{ Auth::user()->name }}</strong> !
            </div>

            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-dashboard"
                                width="42" height="42" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 13m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                <path d="M13.45 11.55l2.05 -2.05"></path>
                                <path d="M6.4 20a9 9 0 1 1 11.2 0z"></path>
                            </svg>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Stok BBM</span>
                            <span class="info-box-number">
                                <span class="number-float">{{ $stok_akhir }}</span> &ell;
                            </span>
                        </div>

                    </div>

                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-gas-station"
                                width="42" height="42" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M14 11h1a2 2 0 0 1 2 2v3a1.5 1.5 0 0 0 3 0v-7l-3 -3"></path>
                                <path d="M4 20v-14a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v14"></path>
                                <path d="M3 20l12 0"></path>
                                <path d="M18 7v1a1 1 0 0 0 1 1h1"></path>
                                <path d="M4 11l10 0"></path>
                            </svg>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Volume Penjualan</span>
                            <span class="info-box-number"><span class="number-float">{{ $volume_penjualan }}</span>
                                &ell;</span>
                        </div>

                    </div>

                </div>


                <div class="clearfix hidden-md-up"></div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-coin" width="42"
                                height="42" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                                <path d="M14.8 9a2 2 0 0 0 -1.8 -1h-2a2 2 0 1 0 0 4h2a2 2 0 1 1 0 4h-2a2 2 0 0 1 -1.8 -1">
                                </path>
                                <path d="M12 7v10"></path>
                            </svg>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Rupiah Penjualan</span>
                            <span class="info-box-number currency">{{ $rupiah_penjualan }}</span>
                        </div>

                    </div>

                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-wallet"
                                width="42" height="42" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path
                                    d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12">
                                </path>
                                <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"></path>
                            </svg>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tabungan</span>
                            <span
                                class="info-box-number currency {{ $tabungan < 0 ? 'text-danger' : '' }}">{{ $tabungan }}</span>
                        </div>

                    </div>

                </div>

            </div>


        </div>
    </section>
@endsection
