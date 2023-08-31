@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Laba Bersih {{ $date->monthName . ' ' . $date->year }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        {{-- <li class="breadcrumb-item"><a href="/laporan">Laporan</a></li> --}}
                        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Laporan Bulanan</a></li>
                        <li class="breadcrumb-item active">Laba Bersih</li>
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
                        <h3 class="card-title text-uppercase">Pertashop {{ $shop->kode . ' ' . $shop->nama }}</h3>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('reports.laba_kotor', ['shop_id' => $shop->id, 'year_month' => $date->format('Y-m')]) }}"
                                class="btn btn-link mr-2">Laba Kotor</a>
                            <button class="btn btn-primary mr-2">Laba Bersih</button>
                            <button class="btn btn-link">Modal Kerja</button>
                        </div>
                    </div>

                </div>
                <div class="card-body">

                    <div class="laba-kotor container-fluid text-sm" id="section-to-print">
                        <div class="pb-2 mb-2 d-flex justify-content-center" style="border-bottom: 4px solid #000">
                            <h3 class="card-title font-weight-bold text-uppercase text-center">
                                Perhitungan Laba Bersih
                                {{ $date->startOfMonth()->format('d') }} s/d
                                {{ $date->endOfMonth()->format('d') . ' ' . $date->monthName . ' ' . $date->year }}
                                <br>PERTASHOP {{ $shop->kode }} {{ $shop->alamat }} <br> {{ $shop->corporation->nama }}
                            </h3>
                        </div>


                        <div class="table-responsive-lg">

                            <table style="width: 100%">
                                <tr>
                                    <td class="font-weight-bold">PENDAPATAN</td>
                                    <td width="50"></td>
                                    <td width="150"></td>
                                    <td width="20"></td>
                                    <td width="150"></td>
                                    <td width="20"></td>
                                </tr>
                                <tr>
                                    <td><span style="width: 20px; display: inline-block">1</span>
                                        <span>LABA KOTOR</span>
                                    </td>
                                    <td>=</td>
                                    <td class="line-bottom d-flex justify-content-between">
                                        <span>Rp</span>
                                        <span class="number-int">{{ $laba_kotor }}</span>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="d-flex justify-content-between font-weight-bold">
                                        <span>Rp</span>
                                        <span class="number-int">{{ $laba_kotor }}</span>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">PENGELURARAN</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @foreach ($spendings as $spending)
                                    <tr>
                                        <td><span style="width: 20px; display: inline-block">{{ $loop->iteration }}</span>
                                            <span class="text-uppercase">{{ $spending->category->nama }}</span>
                                        </td>
                                        <td>=</td>
                                        <td
                                            class="d-flex justify-content-between @if ($loop->last) line-bottom @endif">
                                            <span>Rp</span>
                                            <span class="number-int">{{ $spending->jumlah }}</span>
                                        </td>
                                        <td class="px-2">
                                            @if ($loop->last)
                                                +
                                            @endif
                                        </td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td class="d-flex justify-content-between">
                                        <span>Rp</span>
                                        <span class="number-int">{{ $spendings->sum('jumlah') }}</span>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                            </table>

                            <table style="width: 100%">
                                <tr class="font-weight-bold">
                                    <td class="text-right">A. Total Laba Kotor</td>
                                    <td width="20" class="px-2">=</td>
                                    <td width="150">
                                        <div class="d-flex justify-content-between">
                                            <span>Rp</span>
                                            <span class="number-int">{{ $laba_kotor }}</span>
                                        </div>

                                    </td>
                                    <td width="20"></td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td class="text-right">B. Total Biaya</td>
                                    <td width="20" class="px-2">=</td>
                                    <td width="150">
                                        <div class="line-bottom d-flex justify-content-between">
                                            <span>Rp</span>
                                            <span class="number-int">{{ $total_biaya }}</span>
                                        </div>

                                    </td>
                                    <td width="20" class="px-2">-</td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td class="text-right">C. Laba Bersih (A-B)</td>
                                    <td width="20" class="px-2">=</td>
                                    <td width="150">
                                        <div class="d-flex justify-content-between">
                                            <span>Rp</span>
                                            <span class="number-int">{{ $laba_bersih }}</span>
                                        </div>

                                    </td>
                                    <td width="20"></td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td class="text-right">D. Alokasi Dana Tak Terduga 10% Laba Bersih</td>
                                    <td width="20" class="px-2">=</td>
                                    <td width="150">
                                        <div class="line-bottom d-flex justify-content-between">
                                            <span>Rp</span>
                                            <span class="number-int">{{ $alokasi_dana_tak_terduga }}</span>
                                        </div>

                                    </td>
                                    <td width="20" class="px-2">+</td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td class="text-right">C. Laba Bersih Financial (C-D)</td>
                                    <td width="20" class="px-2">=</td>
                                    <td width="150">
                                        <div class="d-flex justify-content-between">
                                            <span>Rp</span>
                                            <span class="number-int">{{ $laba_bersih_financial }}</span>
                                        </div>

                                    </td>
                                    <td width="20"></td>
                                </tr>
                            </table>

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
        .dot-fill {
            width: 200,
                overflow: hidden;
            white-space: nowrap;
        }

        .dot-fill:after {
            content: "......................................................................................................................................................................................................"
        }

        .line-bottom {
            border-bottom: 2px solid #000;
        }

        @media print {
            body {
                visibility: hidden;
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
