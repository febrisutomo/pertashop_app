@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-6 col-lg-9">
                    <h1>Dashboard</h1>
                </div>
                <div class="col-6 col-lg-3">
                    @if (Auth::user()->shop)
                        <h3 class="text-right text-md font-weight-bold">
                            {{ Auth::user()->shop->kode . ' ' . Auth::user()->shop->nama }}</h3>
                    @else
                        <select id="shop_id" name="shop_id" class="form-control">
                            <option value="" disabled>-- Pilih Pertashop --</option>
                            @foreach ($shops as $s)
                                <option value="{{ $s->id }}" @selected(Request::query('shop_id') == $s->id)>
                                    {{ $s->kode . ' ' . $s->nama }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">


                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-download"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pembelian</span>
                            <span class="info-box-number currency">
                                {{ $summary['jumlah_pembelian_rp'] }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-upload"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Penjualan</span>
                            <span class="info-box-number currency">
                                {{ $summary['jumlah_penjualan_bersih_rp'] }}
                            </span>
                        </div>
                    </div>
                </div>


                <div class="clearfix hidden-md-up"></div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-wallet"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Laba Kotor</span>
                            <span class="info-box-number currency">
                                {{ $summary['laba_kotor'] }}
                            </span>
                        </div>

                    </div>

                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-chart-bar"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Omset Harian</span>
                            <span class="info-box-number liter">
                                {{ $summary['rata_rata_omset_harian'] }}
                        </div>

                    </div>

                </div>

            </div>
            <div class="row">
                <div class="col-12 col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title mr-2"><i class="fas fa-chart-line mr-1"></i>Grafik Penjualan</h3>
                                <div style="width: 200px">
                                    <select id="year_month" name="year_month" class="form-control">
                                        <option value="" disabled>--Pilih Bulan--</option>
                                        @php
                                            $currentYear = date('Y');
                                            $currentMonth = date('n');
                                        @endphp
                                        @for ($tahun = $currentYear; $tahun >= 2021; $tahun--)
                                            @php
                                                $lastMonth = $tahun == $currentYear ? $currentMonth : 12;
                                            @endphp
                                            @for ($bulan = $lastMonth; $bulan >= 1; $bulan--)
                                                @php
                                                    $date = Carbon\Carbon::create($tahun, $bulan, 1);
                                                    $value = $date->format('Y-m');
                                                    $label = $date->monthName . ' ' . $date->year;
                                                @endphp
                                                <option value="{{ $value }}" @selected(Request::query('year_month') == $value)>
                                                    {{ $label }}</option>
                                            @endfor
                                        @endfor
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                            <canvas id="salesChart"
                                style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <div class=" d-flex justify-content-between align-items-center">
                                <h3 class="card-title"><i class="fas fa-gas-pump mr-1"></i>Stok BBM (&ell;)</h3>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-column justify-content-center">
                                <canvas id="stockChart"
                                    style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@push('script')
    <script>
        $(document).ready(function() {

            $('#shop_id, #year_month').on('change', function() {
                const shop_id = $('#shop_id').val();
                const year_month = $('#year_month').val();
                window.location.replace(
                    `{{ route('dashboard') }}?shop_id=${shop_id}&year_month=${year_month}`
                );
            });

            const sales = @json($sales);
            const ctxSalesChart = document.getElementById('salesChart').getContext('2d');
            let salesChart = new Chart(ctxSalesChart, {
                type: 'line',
                data: {
                    labels: sales.labels,
                    datasets: [{
                        label: 'Penjualan',
                        data: sales.data,
                        backgroundColor: 'rgba(0, 123, 255, 0.5)',
                        borderColor: 'rgba(0, 123, 255, 1)',
                        borderWidth: 1,
                        // tension: 0.3,
                        // fill: true,
                    }]
                },
                options: {
                    scales: {
                        y: {
                            min: 0,
                        }
                    },
                    plugins: {
                        legend: {
                            display: false,
                        }
                    }
                },

            });

            const stocks = @json($stocks);
            const ctxStockChart = document.getElementById('stockChart').getContext('2d');
            let stockChart = new Chart(ctxStockChart, {
                type: 'bar',
                data: {
                    labels: stocks.labels,
                    datasets: stocks.datasets
                },
                options: {
                    scales: {
                        x: {
                            // display: false, // Sembunyikan sumbu X
                            stacked: true,
                        },
                        y: {
                            min: 0,
                            max: 3500, // totalStock harus diisi dengan total stok produk
                            stacked: true,
                        }
                    },
                    plugins: {
                        legend: {
                            display: false, // Sembunyikan legenda
                        }
                    }
                }
            });
        });
    </script>
@endpush
