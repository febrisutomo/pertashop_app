@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-6 col-lg-9">
                    <h1>Dashboard</h1>
                </div>
                <div class="col-6 col-lg-3">
                    <select id="shop_id" name="shop_id" class="form-control">
                        <option value="" disabled>-- Pilih Pertashop --</option>
                        @foreach ($shops as $s)
                            <option value="{{ $s->id }}" @selected(Request::query('shop_id') == $s->id)>
                                {{ $s->kode . ' ' . $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="alert alert-success" role="alert">
                {{ $sapaan }} <strong>{{ Auth::user()->name }}</strong> !
            </div>
            <div class="row">


                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-info elevation-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-stack-2"
                                width="42" height="42" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 4l-8 4l8 4l8 -4l-8 -4"></path>
                                <path d="M4 12l8 4l8 -4"></path>
                                <path d="M4 16l8 4l8 -4"></path>
                            </svg>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Posisi Modal</span>
                            <span class="info-box-number currency">
                                {{ $posisi_modal }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger elevation-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-coins"
                                width="42" height="42" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M9 14c0 1.657 2.686 3 6 3s6 -1.343 6 -3s-2.686 -3 -6 -3s-6 1.343 -6 3z"></path>
                                <path d="M9 14v4c0 1.656 2.686 3 6 3s6 -1.344 6 -3v-4"></path>
                                <path
                                    d="M3 6c0 1.072 1.144 2.062 3 2.598s4.144 .536 6 0c1.856 -.536 3 -1.526 3 -2.598c0 -1.072 -1.144 -2.062 -3 -2.598s-4.144 -.536 -6 0c-1.856 .536 -3 1.526 -3 2.598z">
                                </path>
                                <path d="M3 6v10c0 .888 .772 1.45 2 2"></path>
                                <path d="M3 11c0 .888 .772 1.45 2 2"></path>
                            </svg>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Profit PS</span>
                            <span class="info-box-number currency">
                                {{ $total_profit_pertashop }}
                            </span>
                        </div>
                    </div>
                </div>


                <div class="clearfix hidden-md-up"></div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-purple elevation-1">
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
                            <span class="info-box-text">Total Profit Anda</span>
                            <span class="info-box-number currency">
                                {{ $total_profit_anda }}
                            </span>
                        </div>

                    </div>

                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-dollar"
                                width="42" height="42" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M13 21h-7a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v3"></path>
                                <path d="M16 3v4"></path>
                                <path d="M8 3v4"></path>
                                <path d="M4 11h12.5"></path>
                                <path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5"></path>
                                <path d="M19 21v1m0 -8v1"></path>
                            </svg>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Omset Harian</span>
                            <span class="info-box-number currency">
                                {{ $summary['rata_rata_omset_harian_rp'] }}
                        </div>

                    </div>

                </div>

            </div>
            <div class="row">
                <div class="col-12">
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

        });
    </script>
@endpush
