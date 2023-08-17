@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Laporan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="/laporan">Laporan</a></li>
                        <li class="breadcrumb-item active">Laba Kotor</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class=" d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <h3 class="card-title mr-2">Laporan Laba Kotor</h3>
                        </div>

                        {{-- <a href="{{ route('sales.create') }}" class="btn btn-primary"><i class="fa fa-plus mr-2"></i>Tambah
                            Penjualan</a> --}}
                    </div>

                </div>
                <div class="card-body">
                    <p class="font-italic mb-0">PERTAMAX:</p>
                    @foreach ($reports as $i => $r)
                        <div class="row">
                            <div class="col-lg-3">Harga Beli {{ $i + 1 }} : Rp <span
                                    class="number">{{ $r['harga_beli'] }}</span></div>
                            <div class="col-lg-3">Harga Jual {{ $i + 1 }}: Rp <span
                                    class="number">{{ $r['harga_jual'] }}</span></div>
                            @if ($loop->last)
                                <div class="col-lg-6 text-lg-right">Rata-rata omset Harian (&ell;) = <span class="number">{{ collect($reports)->sum('rata_rata_omset_harian') / collect($reports)->count() }}</span></div>
                            @endif
                        </div>
                    @endforeach
                    <hr>

                    <div class="table-responsive-lg">

                        @foreach ($reports as $i => $r)
                            <table style="width: 100%">
                                <tr class="font-weight-bold">
                                    <td>I.</td>
                                    <td>PEMBELIAN {{ $i + 1 }}</td>
                                    <td colspan="100%"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td width="300" class="pl-3">Stok Awal</td>
                                    <td class="px-2">=</td>
                                    <td class="text-right"><span class="number">{{ $r['stok_awal'] }}</span><span
                                            class="ml-2">&ell;</span></td>
                                    <td class="px-3">x</td>
                                    <td class="d-flex justify-content-between">
                                        <span>Rp</span><span class="number">{{ $r['stok_awal_harga_beli'] }}</span>
                                    </td>
                                    <td class="px-5">&#10132;</td>
                                    <td class="d-flex justify-content-between">
                                        <span>Rp</span><span
                                            class="number-int">{{ $r['stok_awal'] * $r['stok_awal_harga_beli'] }}</span>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="d-flex justify-content-between pl-3">
                                        <span class="mr-2">Gmlr Datang</span>
                                        <span>
                                            <span>{{ $r['count_datang'] }}</span>
                                            <span class="px-3">x</span>
                                            <span class="number-int">2000</span>
                                            <span class="ml-2">&ell;</span>
                                        </span>
                                    </td>
                                    <td class="px-2">=</td>
                                    <td class="text-right line-bottom"><span class="number">{{ $r['datang'] }}</span><span
                                            class="ml-2">&ell;</span></td>
                                    <td class="px-3">x</td>
                                    <td class="d-flex justify-content-between">
                                        <span>Rp</span>
                                        <span class="number">{{ $r['harga_beli'] }}</span>
                                    </td>
                                    <td class="px-5">&#10132;</td>
                                    <td class="d-flex justify-content-between line-bottom"><span>Rp</span><span
                                            class="number-int">{{ $r['datang'] * $r['harga_beli'] }}</span>
                                    </td>
                                    <td class="px-3">+</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="font-weight-bold font-italic">A. Jumlah Pembelian 1
                                    </td>
                                    <td class="px-2"></td>
                                    <td class="text-right"><span class="number">{{ $r['jumlah_pembelian'] }}</span><span
                                            class="ml-2">&ell;</span></td>
                                    <td></td>
                                    <td></td>
                                    <td class="px-5"></td>
                                    <td class="d-flex justify-content-between font-weight-bold">
                                        <span>Rp</span><span class="number-int">{{ $r['jumlah_pembelian_rp'] }}</span>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td>II.</td>
                                    <td>PENJUALAN {{ $i + 1 }}</td>
                                    <td colspan="100%"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="pl-3">a. Totalisator Akhir {{ $i + 1 }}</td>
                                    <td class="px-2">=</td>
                                    <td class="text-right"><span class="number">{{ $r['totalisator_akhir'] }}</span><span
                                            class="ml-2">&ell;</span></td>
                                    <td colspan="100%"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="pl-3">b. Totalisator Awal {{ $i + 1 }}</td>
                                    <td class="px-2">=</td>
                                    <td class="text-right line-bottom"><span
                                            class="number">{{ $r['totalisator_awal'] }}</span><span
                                            class="ml-2">&ell;</span></td>
                                    <td class="px-3">-</td>
                                    <td colspan="100%"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="font-weight-bold pl-3">c. Total Penjualan (a-b)</td>
                                    <td class="px-2">=</td>
                                    <td class="text-right font-weight-bold"><span
                                            class="number">{{ $r['total_penjualan'] }}</span><span
                                            class="ml-2">&ell;</span></td>
                                    <td colspan="100%"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="pl-3">d. Percobaan 1 (Test Pump)</td>
                                    <td class="px-2">=</td>
                                    <td class="text-right line-bottom"><span class="number">{{ 0 }}</span><span
                                            class="ml-2">&ell;</span></td>
                                    <td class="px-3">-</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="font-weight-bold font-italic">B. Jumlah Penjualan {{ $i + 1 }} (c-d)
                                    </td>
                                    <td class="px-2">=</td>
                                    <td class="text-right"><span class="number">{{ $r['jumlah_penjualan'] }}</span><span
                                            class="ml-2">&ell;</span></td>
                                    <td class="px-3">x</td>
                                    <td class="d-flex justify-content-between"><span>Rp</span><span
                                            class="number">{{ $r['harga_jual'] }}</span>
                                    </td>
                                    <td class="px-5">&#10132;</td>
                                    <td class="d-flex justify-content-between">
                                        <span>Rp</span><span
                                            class="number-int">{{ $r['jumlah_penjualan'] * $r['harga_jual'] }}</span>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="font-weight-bold font-italic pl-3">Sisa Stock {{ $i + 1 }} (A-B)
                                    </td>
                                    <td class="px-2">=</td>
                                    <td class="text-right"><span class="number">{{ $r['sisa_stok'] }}</span><span
                                            class="ml-2">&ell;</span></td>
                                    <td class="px-3">x</td>
                                    <td class="d-flex justify-content-between"><span>Rp</span><span
                                            class="number">{{ $r['harga_beli'] }}</span>
                                    </td>
                                    <td class="px-5">&#10132;</td>
                                    <td class="d-flex justify-content-between line-bottom">
                                        <span>Rp</span><span
                                            class="number-int">{{ $r['sisa_stok'] * $r['harga_beli'] }}</span>
                                    </td>
                                    <td class="px-3">+</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="pl-3">Jumlah</td>
                                    <td colspan="5"></td>
                                    <td class="d-flex justify-content-between">
                                        <span>Rp</span><span
                                            class="number-int">{{ $r['jumlah_penjualan'] * $r['harga_jual'] + $r['sisa_stok'] * $r['harga_beli'] }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="d-flex justify-content-between pl-3">
                                        <span>Losses/Gain</span>
                                        <span class="px-3">&#10132;</span>
                                        <span class="text-danger mr-2">Losses</span>
                                        <span class="text-danger">(<span
                                                class="number">{{ $r['persen_losses_gain'] }}</span>) %</span>
                                    </td>
                                    <td class="px-2">=</td>
                                    <td class="text-right text-danger">(<span
                                            class="number">{{ $r['losses_gain'] }}</span>)<span
                                            class="ml-2">&ell;</span></td>
                                    <td class="px-3">x</td>
                                    <td class="d-flex justify-content-between"><span>Rp</span><span
                                            class="number">{{ $r['harga_beli'] }}</span>
                                    </td>
                                    <td class="px-5">&#10132;</td>
                                    <td class="d-flex justify-content-between text-danger line-bottom">
                                        <span>Rp</span><span
                                            class="number-int">{{ $r['losses_gain'] * $r['harga_beli'] }}</span>
                                    </td>
                                    <td class="px-3">+</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="font-weight-bold font-italic">C. Jumlah Penjualan Bersih
                                        {{ $i + 1 }}
                                    </td>
                                    <td colspan="5"></td>
                                    <td class="d-flex justify-content-between font-weight-bold">
                                        <span>Rp</span><span
                                            class="number-int">{{ $r['jumlah_penjualan_bersih_rp'] }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">III.</td>
                                    <td class="d-flex justify-content-between"><span class="font-weight-bold ">SISA STOK
                                            AKHIR
                                            {{ $i + 1 }}</span> <span><span
                                                class="number">{{ $r['stik_akhir'] }}</span><span
                                                class="ml-2">cm</span></span>
                                    </td>
                                    <td class="px-2">=</td>
                                    <td class="text-right"><span class="number">{{ $r['sisa_stok_akhir'] }}</span><span
                                            class="ml-2">&ell;</span></td>
                                    <td class="px-3">x</td>
                                    <td class="d-flex justify-content-between"><span>Rp</span><span
                                            class="number">{{ $r['harga_beli'] }}</span>
                                    </td>
                                    <td class="px-5">&#10132;</td>
                                    <td class="d-flex justify-content-between"><span>Rp</span><span
                                            class="number-int">{{ $r['sisa_stok_akhir'] * $r['harga_beli'] }}</span>
                                    </td>
                                </tr>
                            </table>
                            <hr>
                        @endforeach


                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        .line-bottom {
            border-bottom: 2px solid #000;
        }
    </style>
@endpush
