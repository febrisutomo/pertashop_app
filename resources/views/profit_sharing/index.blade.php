@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Profit Sharing</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Profit Sharing</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">

                    <div class="row justify-content-between align-items-center">
                        <div
                            class="{{ Auth::user()->role != 'admin' ? 'col-lg-6' : 'col-6 col-lg-3' }} d-flex justify-content-between align-items-center">
                            @if (Auth::user()->role != 'admin')
                                <select id="shop_id" name="shop_id" class="form-control mr-2">
                                    <option value="" disabled>--Pilih Pertashop--</option>
                                    @foreach ($shops as $s)
                                        <option value="{{ $s->id }}" @selected(Request::query('shop_id') == $s->id)>
                                            {{ $s->kode . ' ' . $s->nama }}</option>
                                    @endforeach
                                </select>
                            @endif
                            <select id="year" name="year" class="form-control">
                                <option value="" disabled>--Pilih Tahun--</option>
                                @for ($year = date('Y'); $year >= 2021; $year--)
                                    <option value="{{ $year }}" @selected(Request::query('year', date('Y')) == $year)>
                                        {{ $year }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-3 d-flex justify-content-end order-first order-md-last mb-2 mb-md-0">
                            <button class="btn btn-warning" onclick="window.print()">
                                <i class="fas fa-print mr-2 "></i>
                                <span>Cetak</span>
                            </button>
                        </div>
                    </div>

                </div>
                <div class="card-body">

                    <h6 class="text-center text-uppercase font-weight-bold">RINCIAN PEMBAGIAN PROFIT SHARING PS
                        {{ $shop->kode . ' ' . $shop->nama }} TAHUN {{ Request::query('year', date('Y')) }}</h6>

                    <div class="table-responsive">
                        <div class="print-section">

                            <h5 class="text-center text-uppercase font-weight-bold header mb-3 d-none">RINCIAN PEMBAGIAN
                                PROFIT
                                SHARING PS
                                {{ $shop->kode . ' ' . $shop->nama }} TAHUN {{ Request::query('year', date('Y')) }}</h5>

                            <table id="table" class="table table-bordered">
                                <thead class="table-warning">
                                    <tr>
                                        <th colspan="100%">Nilai Investasi: <span
                                                class="currency">{{ $shop->nilai_investasi }}</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="align-middle text-center">Bulan</th>
                                        <th class="align-middle text-center">Nilai Profit Sharing</th>
                                        <th class="align-middle text-center">Alokasi Modal</th>
                                        <th class="align-middle text-center">Sisa Profit yang Dibagi</th>
                                        @foreach ($shop->investors as $investor)
                                            <th
                                                class="align-middle text-center {{ Auth::user()->id == $investor->id ? 'bg-warning' : '' }}">
                                                {{ $investor->nama }}
                                                <div>(<span
                                                        class="number-float">{{ $investor->pivot->persentase }}</span>%)
                                                </div>
                                            </th>
                                        @endforeach
                                        <th class="align-middle text-center">Return of Investment to Go</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($profitSharings as $profit)
                                        <tr>
                                            <td class="text-nowrap">
                                                {{ $profit->bulan }}
                                            </td>
                                            <td
                                                class="text-right text-nowrap currency @if ($profit->nilai_profit_sharing < 0) text-danger @endif">
                                                {{ $profit->nilai_profit_sharing }}
                                            </td>
                                            <td class="text-right text-nowrap currency">
                                                {{ $profit->alokasi_modal ?? 0 }}
                                            </td>
                                            <td
                                                class="text-right text-nowrap currency @if ($profit->nilai_profit_sharing < 0) text-danger @endif"">
                                                {{ $profit->sisa_profit_dibagi }}
                                            </td>
                                            @php
                                                $investor_profits = $profit->investorProfits;
                                                $profits = $shop->investors->map(function ($inv) use ($investor_profits) {
                                                    return $investor_profits->where('investor_shop_id', $inv->pivot->id)->first()?->nilai_profit ?? 0;
                                                });
                                            @endphp
                                            {{-- @dump($investor_profits->where('investor_shop_id', 1)->first()?->nilai_profit) --}}
                                            @foreach ($profits as $p)
                                                <td
                                                    class="text-right text-nowrap currency @if ($profit->nilai_profit_sharing < 0) text-danger @endif"">
                                                    {{ $p }}
                                                </td>
                                            @endforeach
                                            <td class="text-right text-nowrap currency">
                                                {{ $profit->roi }}
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                                <tfoot class="table-warning">
                                    <tr>
                                        <th class="text-nowrap">Total Profit</th>
                                        <th class="text-right text-nowrap currency">
                                            {{ $shop->profitSharings->filter(function ($val) {
                                                    return $val->created_at->format('Y') <= Request::query('year', date('Y'));
                                                })->sum('nilai_profit_sharing') ?? 0 }}
                                        </th>
                                        <th></th>
                                        <th></th>
                                        @foreach ($shop->investors as $inv)
                                            <th
                                                class="text-right text-nowrap currency {{ Auth::user()->id == $inv->id ? 'bg-warning' : '' }}">
                                                {{ $inv->pivot->profits->filter(function ($val) {
                                                        return $val->profitSharing->created_at->format('Y') <= Request::query('year', date('Y'));
                                                    })->sum('nilai_profit') ?? 0 }}
                                            </th>
                                        @endforeach
                                        <th class="text-right text-nowrap currency">{{ $profitSharings->last()?->roi }}
                                        </th>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        @if (Auth::user()->role == 'admin')
            $('#year').on('change', function() {
                const year = $('#year').val();
                window.location.replace(
                    `{{ route('profit-sharing.index') }}?year=${year}`
                );
            });
        @else
            $('#shop_id, #year').on('change', function() {
                const shop_id = $('#shop_id').val();
                const year = $('#year').val();
                window.location.replace(
                    `{{ route('profit-sharing.index') }}?shop_id=${shop_id}&year=${year}`
                );
            });
        @endif
    </script>
@endpush

@push('style')
    <style>
        @media print {
            body {
                visibility: hidden;
                -webkit-print-color-adjust: exact;
            }

            .ttd,
            .header,
            .aksi {
                display: block !important;
            }

            .print-section {
                visibility: visible;
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>
@endpush
