@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-9">
                    <h1>Dashboard</h1>
                </div>
                <div class="col-sm-3">
                    <select name="shop_id" class="form-control">
                        <option value="">Semua Pertashop</option>
                        @foreach ($shops as $shop)
                            <option value="{{ $shop->id }}">{{ $shop->kode . ' ' . $shop->nama }}</option>
                        @endforeach
                    </select>
                    {{-- <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Home</li>
                    </ol> --}}

                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-upload"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Penjualan Bersih</span>
                            <span class="info-box-number currency">{{ $jumlah_penjualan_bersih_rp }}/span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-download"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pembelian</span>
                            <span class="info-box-number currency">
                                {{ $jumlah_pembelian_rp }}
                                {{-- <small>&ell;</small> --}}
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
                            <span class="info-box-number currency">{{ $laba_kotor }}</span>
                        </div>

                    </div>

                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-chart-bar"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Omset Harian</span>
                            <span class="info-box-number"><span class="number">{{ $rata_rata_omset_harian }}</span>
                                &ell;</span>
                        </div>

                    </div>

                </div>

            </div>
            <div class="row">
                <div class="col-12 col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <div class=" d-flex justify-content-between align-items-center">
                                <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i>Penjualan (&ell;)</h3>
                                <input name="filter" type="text" value="day" hidden>
                                <div class="">
                                    <button class="btn btn-sm btn-primary btn-filter filter-day">Harian</button>
                                    <button class="btn btn-sm btn-link btn-filter filter-week">Mingguan</button>
                                    <button class="btn btn-sm btn-link btn-filter filter-month">Bulanan</button>
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
                                <h3 class="card-title"><i class="fas fa-gas-pump mr-1"></i>Stok (&ell;)</h3>
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
        var ctxSalesChart = document.getElementById('salesChart').getContext('2d');
        var salesChart;

        function showSalesChart(data) {

            var filter = $('input[name=filter]').val()
            // Hapus chart sebelumnya jika ada
            if (salesChart) {
                salesChart.destroy();
            }

            // Buat chart baru
            salesChart = new Chart(ctxSalesChart, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: data.datasets
                },
                options: {
                    scales: {
                        y: {
                            min: 0,
                        }
                    },
                }
            });


            $('.btn-filter').removeClass('btn-primary')
            $('.btn-filter').addClass('btn-link')
            $(`.filter-${filter}`).removeClass('btn-link')
            $(`.filter-${filter}`).addClass('btn-primary')
        }


        var ctxStockChart = document.getElementById('stockChart').getContext('2d');
        var stockChart;

        function showStocks(data) {
            // var bg_color = stok_akhir_aktual <= 1500 ? '#dc3545' :
            //     '#20c997';


            console.log(data)

            if (stockChart) {
                stockChart.destroy(); // Hapus chart yang ada jika sudah ada
            }

            stockChart = new Chart(ctxStockChart, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: data.datasets
                },
                options: {
                    scales: {
                        x: {
                            display: false, // Sembunyikan sumbu X
                            stacked: true,
                        },
                        y: {
                            min: 0,
                            max: 3000, // totalStock harus diisi dengan total stok produk
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
        }

        function getData() {

            var shop_id = $('select[name=shop_id]').val()
            var filter = $('input[name=filter]').val()

            $.ajax({
                url: "{{ route('dashbaord.data') }}",
                method: 'GET',
                data: {
                    filter,
                    shop_id
                },
                success: function(data) {
                    var sales = data.sales;
                    var stocks = data.stocks;
                    showSalesChart(sales)
                    showStocks(stocks)

                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }



        // function showLossesGain() {
        //     var losses_gain = @json($losses_gain)

        //     var formated_losses_gain = formatNumber(Math.abs(losses_gain), 3);

        //     var losses_gain_text = ''
        //     if (parseFloat(losses_gain) < 0) {
        //         losses_gain_text = '<span class="text-danger"><i class="fas fa-arrow-down mr-1"></i>' +
        //             formated_losses_gain +
        //             ' %</span>';
        //     } else if (parseFloat(losses_gain) > 0) {
        //         losses_gain_text = '<span class="text-success"><i class="fas fa-arrow-up mr-1"></i>' +
        //             formated_losses_gain +
        //             ' %</span>';
        //     }
        //     $('#losses-gain').html(losses_gain_text)
        // }

        $(document).ready(function() {
            // $('#time-filter').on('change', function() {
            //     var timeFilter = $(this).val();
            //     getData(timeFilter);
            // });

            $(".filter-week").on('click', function() {
                $('input[name=filter]').val('week').trigger('change');
            })
            $(".filter-day").on('click', function() {
                $('input[name=filter]').val('day').trigger('change');
            })
            $(".filter-month").on('click', function() {
                $('input[name=filter]').val('month').trigger('change');
            })

            $('select[name=shop_id], input[name=filter]').on('change', function() {
                getData()
            })

            getData();
            // showLossesGain()
        });
    </script>
@endpush
