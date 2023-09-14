@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-8">
                    <h1>Laporan Harian</h1>
                </div>
                <div class="col-4 text-right">
                    <a href="{{ route('daily-reports.create', Auth::user()->role == 'super-admin' ? ['shop_id' => Request::query('shop_id', 1)] : null) }}"
                        class="btn btn-primary"><i class="fa fa-plus mr-2"></i>Tambah</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @foreach ($reports as $tanggal => $reportGroup)
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">{{ $tanggal }}</h6>
                    </div>
                    <div class="card-body">

                        @foreach ($reportGroup->sortBy('created_at') as $report)
                            <div>
                                {{-- @if ($reportGroup->count() > 1)
                                    <h6 class="text-primary mb-0">Shift {{ $loop->iteration }}</h6>
                                @endif --}}
                                <div class="row">
                                    <div class="col-6 font-weight-bold">
                                        Operator
                                    </div>
                                    <div class="col-6">
                                        {{ $report->operator->nama }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 font-weight-bold">
                                        Totalisator Akhir
                                    </div>
                                    <div class="col-6 number-float">
                                        {{ $report->totalisator_akhir }}
                                    </div>
                                </div>
                                @if ($reportGroup->count() > 1)
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">
                                            Vol. Penjualan
                                        </div>
                                        <div class="col-6">
                                            <span class="number-float">
                                                {{ $report->volume_penjualan }}
                                            </span> &ell;
                                        </div>
                                    </div>
                                @endif

                            </div>

                            @if ($reportGroup->count() > 1)
                                <hr>
                            @endif
                            @if ($loop->last)
                                <div>
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">
                                            @if ($reportGroup->count() > 1)
                                                Total
                                            @endif
                                            Vol. Penjualan
                                        </div>
                                        <div class="col-6">
                                            <span class="number-float">
                                                {{ $reportGroup->sum('volume_penjualan') }}
                                            </span> &ell;
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">
                                            Penyetikan
                                        </div>
                                        <div class="col-6">
                                            <span class="number-float">{{ $reportGroup->first()->stik_akhir }}</span>
                                            cm
                                            (<span
                                                class="number-float">{{ $reportGroup->first()->stok_akhir_aktual }}</span>
                                            &ell;)
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">
                                            @if ($reportGroup->count() > 1)
                                                Total
                                            @endif
                                            Pendapatan
                                        </div>
                                        <div class="col-6 currency">
                                            {{ $reportGroup->sum('pendapatan') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('daily-reports.detail', ['shop_id' => $report->shop_id, 'date' => $report->created_at->format('Y-m-d')]) }}"
                                class="btn btn-info">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="d-flex justify-content-center">
                {{ $reports->links() }}
            </div>
        </div>
    </section>
@endsection
