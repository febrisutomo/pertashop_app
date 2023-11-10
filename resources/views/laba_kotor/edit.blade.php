@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Laba Kotor {{ $date->monthName . ' ' . $date->year }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        {{-- <li class="breadcrumb-item"><a href="/laporan">Laporan</a></li> --}}
                        <li class="breadcrumb-item"><a href="{{ route('laba-kotor.index') }}">Laba Kotor</a></li>
                        <li class="breadcrumb-item active">Detail</li>
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
                        <h3 class="card-title">{{ $shop->kode . ' ' . $shop->nama }}</h3>
                    </div>

                </div>

                <div class="card-body">

                    <div class="laba-kotor container-fluid text-sm" id="section-to-print">
                        <div class="pb-2 mb-2 d-flex justify-content-center" style="border-bottom: 4px solid #000">
                            <h3 class="card-title font-weight-bold text-uppercase text-center">Laporan Stock, Penjualan &
                                Laba Kotor
                                {{ $date->startOfMonth()->format('d') }} s/d
                                {{ $date->endOfMonth()->format('d') . ' ' . $date->monthName . ' ' . $date->year }}
                                <br>PERTASHOP {{ $shop->kode }} {{ $shop->alamat }} <br> {{ $shop->corporation->nama }}
                            </h3>
                        </div>
                        <p class="font-italic mb-0">PERTAMAX:</p>
                        @foreach ($reports as $i => $r)
                            <div class="row">
                                <div class="col-md-3">Harga Beli {{ $i + 1 }} : <span
                                        class="currency-decimal">{{ $r['harga_beli'] }}</span></div>
                                <div class="col-md-3">Harga Jual {{ $i + 1 }}: <span
                                        class="currency-decimal">{{ $r['harga_jual'] }}</span></div>
                                @if ($loop->last)
                                    <div class="col-md-6 text-md-right">Rata-rata omset Harian (&ell;) = <span
                                            class="number-float">{{ $reports->sum('rata_rata_omset_harian') / $reports->count() }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        <hr>

                        <div class="table-responsive">

                            @foreach ($reports as $i => $r)
                                <table style="width: 100%">
                                    <tr class="font-weight-bold">
                                        <td width="20">I.</td>
                                        <td>PEMBELIAN {{ $i + 1 }}</td>
                                        <td colspan="100%"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td width="300" class="pl-3">Stok Awal</td>
                                        <td class="px-2">=</td>
                                        <td class="text-right number-float litter">{{ $r['stok_awal'] }}</td>
                                        <td class="px-3">x</td>
                                        <td class="d-flex justify-content-between currency-decimal">
                                            {{ $r['stok_awal_harga_beli'] }}
                                        </td>
                                        <td class="px-5">&#10132;</td>
                                        <td class="d-flex justify-content-between currency-decimal">
                                            {{ $r['stok_awal'] * $r['stok_awal_harga_beli'] }}
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="d-flex justify-content-between pl-3">
                                            <span class="mr-2">Datang</span>
                                            {{-- <span>
                                                <span>{{ $r['count_datang'] }}</span>
                                                <span class="px-3">x</span>
                                                <span class="number litter">1000</span>
                                            </span> --}}
                                        </td>
                                        <td class="px-2">=</td>
                                        <td class="text-right line-bottom"><span
                                                class="number-float litter">{{ $r['datang'] }}</span>
                                        </td>
                                        <td class="px-3">x</td>
                                        <td class="d-flex justify-content-between currency-decimal">
                                            {{ $r['harga_beli'] }}
                                        </td>
                                        <td class="px-5">&#10132;</td>
                                        <td class="d-flex justify-content-between currency-decimal line-bottom">
                                            {{ $r['datang'] * $r['harga_beli'] }}
                                        </td>
                                        <td class="px-3">+</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="font-weight-bold font-italic">A. Jumlah Pembelian 1
                                        </td>
                                        <td class="px-2"></td>
                                        <td class="text-right number-float litter">{{ $r['jumlah_pembelian'] }}</td>
                                        <td></td>
                                        <td></td>
                                        <td class="px-5"></td>
                                        <td class="d-flex justify-content-between font-weight-bold currency-decimal">
                                            {{ $r['jumlah_pembelian_rp'] }}
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr class="font-weight-bold">
                                        <td width="20">II.</td>
                                        <td>PENJUALAN {{ $i + 1 }}</td>
                                        <td colspan="100%"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="pl-3">a. Totalisator Akhir {{ $i + 1 }}</td>
                                        <td class="px-2">=</td>
                                        <td class="text-right number-float litter">{{ $r['totalisator_akhir'] }}</td>
                                        <td colspan="100%"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="pl-3">b. Totalisator Awal {{ $i + 1 }}</td>
                                        <td class="px-2">=</td>
                                        <td class="text-right line-bottom number-float litter">{{ $r['totalisator_awal'] }}
                                        </td>
                                        <td class="px-3">-</td>
                                        <td colspan="100%"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="font-weight-bold pl-3">c. Total Penjualan (a-b)</td>
                                        <td class="px-2">=</td>
                                        <td class="text-right font-weight-bold number-float litter">
                                            {{ $r['total_penjualan'] }}</td>
                                        <td colspan="100%"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="pl-3">d. Percobaan (Test Pump)</td>
                                        <td class="px-2">=</td>
                                        <td class="text-right line-bottom number-float litter">
                                            {{ $r['test_pump'] }}
                                        </td>
                                        <td class="px-3">-</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="font-weight-bold font-italic">B. Jumlah Penjualan {{ $i + 1 }}
                                            (c-d)
                                        </td>
                                        <td class="px-2">=</td>
                                        <td class="text-right number-float litter">{{ $r['jumlah_penjualan'] }}</td>
                                        <td class="px-3">x</td>
                                        <td class="d-flex justify-content-between currency-decimal">
                                            {{ $r['harga_jual'] }}
                                        </td>
                                        <td class="px-5">&#10132;</td>
                                        <td class="d-flex justify-content-between currency-decimal">
                                            {{ $r['jumlah_penjualan_rp'] }}
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="font-weight-bold font-italic pl-3">Sisa Stock {{ $i + 1 }} (A-B)
                                        </td>
                                        <td class="px-2">=</td>
                                        <td class="text-right number-float litter">{{ $r['sisa_stok'] }}</td>
                                        <td class="px-3">x</td>
                                        <td class="d-flex justify-content-between currency-decimal">
                                            {{ $r['harga_beli'] }}
                                        </td>
                                        <td class="px-5">&#10132;</td>
                                        <td class="d-flex justify-content-between line-bottom currency-decimal">
                                            {{ $r['sisa_stok'] * $r['harga_beli'] }}
                                        </td>
                                        <td class="px-3">+</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="pl-3">Jumlah</td>
                                        <td colspan="5"></td>
                                        <td class="d-flex justify-content-between currency-decimal">
                                            {{ $r['jumlah_penjualan'] * $r['harga_jual'] + $r['sisa_stok'] * $r['harga_beli'] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="d-flex justify-content-between pl-3">
                                            <span>Losses/Gain</span>
                                            <span class="px-3">&#10132;</span>
                                            <span class="text-danger mr-2">Losses</span>
                                            <span class="number-float percent">{{ $r['persen_losses_gain'] }}</span>
                                        </td>
                                        <td class="px-2">=</td>
                                        <td class="text-right number-float litter">{{ $r['losses_gain'] }}</td>
                                        <td class="px-3">x</td>
                                        <td class="d-flex justify-content-between currency-decimal">{{ $r['harga_beli'] }}
                                        </td>
                                        <td class="px-5">&#10132;</td>
                                        <td class="d-flex justify-content-between currency-decimal line-bottom">
                                            {{ $r['losses_gain'] * $r['harga_beli'] }}
                                        </td>
                                        <td class="px-3">+</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="font-weight-bold font-italic">C. Jumlah Penjualan Bersih
                                            {{ $i + 1 }}
                                        </td>
                                        <td colspan="5"></td>
                                        <td class="d-flex justify-content-between font-weight-bold currency-decimal">
                                            {{ $r['jumlah_penjualan_bersih_rp'] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold" width="20">III.</td>
                                        <td class="d-flex justify-content-between"><span class="font-weight-bold ">SISA
                                                STOK
                                                AKHIR
                                                {{ $i + 1 }}</span> <span><span
                                                    class="number-float">{{ $r['stik_akhir'] }}</span><span>
                                                    cm</span></span>
                                        </td>
                                        <td class="px-2">=</td>
                                        <td class="text-right number-float litter">{{ $r['sisa_stok_akhir'] }}</td>
                                        <td class="px-3">x</td>
                                        <td class="d-flex justify-content-between currency-decimal">
                                            {{ $r['harga_beli'] }}
                                        </td>
                                        <td class="px-5">&#10132;</td>
                                        <td class="d-flex justify-content-between currency-decimal">
                                            {{ $r['sisa_stok_akhir'] * $r['harga_beli'] }}
                                        </td>
                                    </tr>
                                </table>
                                <hr>
                            @endforeach

                            <div class="d-flex justify-content-end">
                                @if ($reports->count() > 1)
                                    <div class="">
                                        @foreach ($reports as $i => $r)
                                            <table class="mb-2">
                                                <tr>
                                                    <td width="120" class="p-2 text-xl text-center"
                                                        style="border: 2px solid #000">
                                                        {{ $i + 1 }}
                                                    </td>
                                                    <td width="360" class="p-2" style="border: 2px solid #000">
                                                        <table width="100%">
                                                            <tr>
                                                                <td width="180" class="text-right">Jumlah Penjualan
                                                                    Bersih
                                                                    {{ $i + 1 }}
                                                                </td>
                                                                <td class="px-2">=</td>
                                                                <td
                                                                    class="d-flex justify-content-between currency-decimal">
                                                                    {{ $r['jumlah_penjualan_bersih_rp'] }}
                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-right">Jumlah Pembelian
                                                                    {{ $i + 1 }}
                                                                </td>
                                                                <td class="px-2">=</td>
                                                                <td
                                                                    class="d-flex justify-content-between line-bottom currency-decimal">
                                                                    {{ $r['jumlah_pembelian_rp'] }}
                                                                </td>
                                                                <td class="px-1">-</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-right font-weight-bold">Laba Kotor
                                                                    {{ $i + 1 }}
                                                                    (B-A)
                                                                </td>
                                                                <td class="px-2">=</td>
                                                                <td
                                                                    class="d-flex justify-content-between font-weight-bold currency-decimal">
                                                                    {{ $r['laba_kotor'] }}
                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        @endforeach
                                        <table class="mb-2">
                                            <tr>
                                                <td width="120" class="p-2 text-xl text-center"
                                                    style="border: 2px solid #000">
                                                    1 + 2
                                                </td>
                                                <td width="360" class="p-2" style="border: 2px solid #000">
                                                    <table width="100%">
                                                        @foreach ($reports as $i => $r)
                                                            <tr>
                                                                <td width="180" class="text-right">Laba Kotor
                                                                    {{ $i + 1 }} (B-A)
                                                                </td>
                                                                <td class="px-2">=</td>
                                                                <td
                                                                    class="d-flex justify-content-between @if ($loop->last) line-bottom @endif currency-decimal">
                                                                    {{ $r['laba_kotor'] }}
                                                                </td>
                                                                <td class="px-1">
                                                                    @if ($loop->last)
                                                                        -
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                        <tr>
                                                            <td class="text-right font-weight-bold ">Grand Total Laba Kotor
                                                            </td>
                                                            <td class="px-2">=</td>
                                                            <td
                                                                class="d-flex justify-content-between font-weight-bold currency-decimal">
                                                                {{ $reports->sum('laba_kotor') }}
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                @else
                                    <div class="">
                                        <table class="mb-2">
                                            <tr>
                                                <td width="120" class="p-2 text-lg text-center"
                                                    style="border: 2px solid #000">
                                                    Laba Kotor
                                                </td>
                                                <td width="360" class="p-2" style="border: 2px solid #000">
                                                    <table width="100%">
                                                        <tr>
                                                            <td width="180" class="text-right">Jumlah Penjualan Bersih
                                                            </td>
                                                            <td class="px-2">=</td>
                                                            <td class="d-flex justify-content-between currency-decimal">
                                                                {{ count($reports) > 0 ? $reports[0]['jumlah_penjualan_bersih_rp'] : 0 }}
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-right">Jumlah Pembelian
                                                            </td>
                                                            <td class="px-2">=</td>
                                                            <td
                                                                class="d-flex justify-content-between line-bottom currency-decimal">
                                                                {{ count($reports) ? $reports[0]['jumlah_pembelian_rp'] : 0 }}
                                                            </td>
                                                            <td class="px-1">-</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-right font-weight-bold">Total Laba Kotor
                                                            </td>
                                                            <td class="px-2">=</td>
                                                            <td
                                                                class="d-flex justify-content-between font-weight-bold currency-decimal">
                                                                {{ count($reports) ? $reports[0]['laba_kotor'] : 0 }}
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                @endif
                            </div>

                        </div>

                        <div class="row mt-3">
                            <div class="col-sm-4">
                                <table style="width: 100%">
                                    <tr class="font-weight-bold">
                                        <td width="20">IV.</td>
                                        <td>Sisa Stock DO di Maos</td>
                                    </tr>
                                </table>
                                <table style="width: 100%" class="table-border">
                                    <tr>
                                        <td></td>
                                        <td class="text-center">PERTAMAX</td>
                                    </tr>
                                    <tr>
                                        <td>Stok Awal</td>
                                        <td class="text-right">{{ $stok_awal_do / 1000 }} KL</td>
                                    </tr>
                                    <tr>
                                        <td>Setor </td>
                                        <td class="text-right">{{ $setor_do / 1000 }} KL</td>
                                    </tr>
                                    <tr>
                                        <td>Setor Tunai</td>
                                        <td class="text-right">- KL</td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah</td>
                                        <td class="text-right">{{ $jumlah_do / 1000 }} KL</td>
                                    </tr>
                                    <tr>
                                        <td>Datang</td>
                                        <td class="text-right">{{ $datang_do / 1000 }} KL</td>
                                    </tr>
                                    <tr>
                                        <td>Sisa</td>
                                        <td class="text-right"> {{ $sisa_do / 1000 }} KL</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-8">
                                <div class="font-weight-bold font-italic">Ilustrasi Turun / Naik Margin Pertamax92
                                    Pertashop
                                </div>
                                <table class="table-border" width="100%">
                                    @foreach ($prices as $price)
                                        @if ($loop->iteration > 1 && $price->harga_jual != $price->prev_harga_jual)
                                            <tr>
                                                <td>{{ $price->tanggal }}</td>
                                                <td colspan="3">
                                                    <span class="currency">{{ $price->prev_harga_jual }}</span>
                                                    <span class="px-2">&#10132;</span>
                                                    <span class="currency">{{ $price->harga_jual }}</span>
                                                    <span class="ml-2">
                                                        (@if ($price->harga_jual > $price->prev_harga_jual)
                                                            <i class="fas fa-arrow-up text-danger"></i>
                                                        @else
                                                            <i class="fas fa-arrow-down text-success"></i>
                                                        @endif
                                                        <span class="currency">{{ $price->naik_turun_harga_jual }}</span>)
                                                    </span>

                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    <tr style="background-color: yellow" class="font-weight-bold">
                                        <td></td>
                                        <td class="text-center">Harga Beli</td>
                                        <td class="text-center">Harga Jual</td>
                                        <td class="text-center">Margin</td>
                                        <td class="text-center">Naik / Turun</td>
                                    </tr>

                                    @foreach ($prices as $price)
                                        <tr>
                                            <td class="@if ($loop->iteration == 1) bg-gray @endif">
                                                @if ($loop->iteration > 1)
                                                    {{ $price->tanggal }}
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between currency-decimal">
                                                    {{ $price->harga_beli }}</div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between currency-decimal">
                                                    {{ $price->harga_jual }}</div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between currency-decimal">
                                                    {{ $price->margin }}</div>
                                            </td>
                                            <td class="@if ($loop->iteration == 1) bg-gray @endif">
                                                @if ($loop->iteration > 1)
                                                    @if ($price->naik_turun_margin > 0)
                                                        <i class="fas fa-arrow-up text-success"></i>
                                                    @else
                                                        <i class="fas fa-arrow-down text-danger"></i>
                                                    @endif
                                                    <span class="currency-decimal">{{ $price->naik_turun_margin }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="ttd pt-4" style="display: none">
                            <p class="text-right px-3">Banyumas,
                                {{ $date->endOfMonth()->format('d') . ' ' . $date->monthName . ' ' . $date->year }}
                            <p>
                            <div class="row">
                                <div class="col-sm-9">
                                    <div class="text-center">Disetujui Oleh,</div>
                                    <div class="row">
                                        @foreach ($shop->investors as $investor)
                                            <div class="col-sm mb-2 px-1">
                                                <div class="text-center">
                                                    <p style="margin-top: 3cm">{{ $investor->name }}</p>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                                <div class="col-sm-3 mb-2 px-1">
                                    <div class="text-center ">
                                        <div>Dibuat Oleh,</div>
                                        <p style="margin-top: 3cm">{{ Auth::user()->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="card-footer">
                    <div class="text-right">
                        <button class="btn btn-warning" onclick="window.print()">
                            <i class="fas fa-print mr-2 "></i>
                            <span>Cetak</span>
                        </button>
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

        .table-border td {
            border: 1px solid #000;
            padding: 1px 8px !important;
        }

        @media print {
            body {
                visibility: hidden;
                -webkit-print-color-adjust: exact;
            }


            .ttd {
                display: block !important;
            }

            #section-to-print {
                visibility: visible;
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>
@endpush
