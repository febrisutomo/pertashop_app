@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Laporan Harian</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Laporan Harian</li>
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
                            class="{{ Auth::user()->role == 'super-admin' ? 'col-lg-6' : 'col-6 col-lg-3' }} d-flex justify-content-between align-items-center">
                            @if (Auth::user()->role == 'super-admin')
                                <select id="shop_id" name="shop_id" class="form-control mr-2">
                                    <option value="" disabled>--Pilih Pertashop--</option>
                                    @foreach ($shops as $s)
                                        <option value="{{ $s->id }}" @selected(Request::query('shop_id') == $s->id)>
                                            {{ $s->kode . ' ' . $s->nama }}</option>
                                    @endforeach
                                </select>
                            @endif
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

                        @if (Auth::user()->role == 'operator' || Auth::user()->role == 'super-admin')
                            <div class="col-md-3 d-flex justify-content-end order-first order-md-last mb-2 mb-md-0">
                                <a href="{{ route('daily-reports.create', Auth::user()->role == 'super-admin' ? ['shop_id' => Request::query('shop_id', 1)] : null) }}"
                                    class="btn btn-primary"><i class="fa fa-plus mr-2"></i>Tambah</a>
                            </div>
                        @endif
                    </div>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered">
                            <thead class="table-success">
                                <tr>
                                    <th rowspan="2" class="text-center align-middle">Tanggal</th>
                                    <th rowspan="2" class="text-center align-middle">Operator</th>
                                    <th rowspan="2" class="text-center align-middle">Totalisator Akhir</th>
                                    <th colspan="2" class="text-center align-middle">Penjualan</th>
                                    @if ($shop->operators->count() > 1)
                                        <th colspan="2" class="text-center align-middle">Total Penjualan</th>
                                    @endif
                                    <th rowspan="2" class="text-center align-middle">Test Pump (&ell;)</th>
                                    <th rowspan="2" class="text-center align-middle">Curah (&ell;)</th>
                                    <th rowspan="2" class="text-center align-middle">Stik Akhir (cm)</th>
                                    <th rowspan="2" class="text-center align-middle">Stok Aktual (&ell;)</th>
                                    <th rowspan="2" class="text-center align-middle">Gain / Loss (&ell;)</th>
                                    <th rowspan="2" class="text-center align-middle">Pengeluaran</th>
                                    <th rowspan="2" class="text-center align-middle">Pendapatan</th>
                                    @if ($shop->operators->count() > 1)
                                        <th rowspan="2" class="text-center align-middle">Total Pendapatan</th>
                                    @endif
                                    <th rowspan="2" class="text-center align-middle">Disetorkan</th>
                                    <th rowspan="2" class="text-center align-middle">Selisih</th>
                                    <th rowspan="2" class="text-center align-middle">Aksi</th>
                                </tr>
                                <tr>
                                    <th class="text-center align-middle">Volume (&ell;)</th>
                                    <th class="text-center align-middle">Rupiah</th>
                                    @if ($shop->operators->count() > 1)
                                        <th class="text-center align-middle">Volume (&ell;)</th>
                                        <th class="text-center align-middle">Rupiah</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $prevDate = null;
                                    $countDays = $reports->groupBy('tanggal')->count() == 0 ? 1 : $reports->groupBy('tanggal')->count();
                                @endphp
                                @foreach ($reports as $report)
                                    @if ($report->tanggal != $prevDate)
                                        @php
                                            $date = Carbon\Carbon::create($report->created_at);
                                            $now = Carbon\Carbon::now();
                                            $diff = $date->diffInDays($now);
                                        @endphp
                                        <tr>
                                            <td rowspan="{{ $reports->where('tanggal', $report->tanggal)->count() }}"
                                                class="align-middle table-warning text-nowrap">
                                                {{ $report->tanggal_panjang }}
                                            </td>
                                            <td class="align-middle">{{ $report->operator->nama }}</td>
                                            <td class="align-middle text-right number-float">
                                                {{ $report->totalisator_akhir }}
                                            </td>
                                            <td class="align-middle text-right number-float">
                                                {{ $report->volume_penjualan }}
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex justify-content-between">
                                                    <span class="mr-1">Rp</span>
                                                    <span class="number">{{ $report->rupiah_penjualan }}</span>
                                                </div>
                                            </td>
                                            @if ($shop->operators->count() > 1)
                                                <td rowspan="{{ $reports->where('tanggal', $report->tanggal)->count() }}"
                                                    class="align-middle text-right number-float">
                                                    {{ $reports->where('tanggal', $report->tanggal)->sum('volume_penjualan') }}
                                                </td>
                                                <td rowspan="{{ $reports->where('tanggal', $report->tanggal)->count() }}"
                                                    class="align-middle">
                                                    <div class="d-flex justify-content-between">
                                                        <span class="mr-1">Rp</span>
                                                        <span
                                                            class="number">{{ $reports->where('tanggal', $report->tanggal)->sum('rupiah_penjualan') }}</span>
                                                    </div>
                                                </td>
                                            @endif
                                            <td class="align-middle text-right number-float">
                                                {{ $report->percobaan ?? 0 }}
                                            </td>
                                            <td class="align-middle text-right number-float">
                                                {{ $report->penerimaan ?? 0 }}
                                            </td>
                                            @if ($shop->operators->count() > 1)
                                                <td rowspan="{{ $reports->where('tanggal', $report->tanggal)->count() }}"
                                                    class="align-middle text-right number-float">
                                                    {{ $reports->where('tanggal', $report->tanggal)->last()->stik_akhir }}
                                                </td>
                                            @else
                                                <td class="align-middle text-right number-float">
                                                    {{ $report->stik_akhir }}
                                                </td>
                                            @endif
                                            @if ($shop->operators->count() > 1)
                                                <td rowspan="{{ $reports->where('tanggal', $report->tanggal)->count() }}"
                                                    class="align-middle text-right number-float {{ $reports->where('tanggal', $report->tanggal)->last()->stok_akhir_aktual <= 1500 ? 'table-danger' : '' }}">
                                                    {{ $reports->where('tanggal', $report->tanggal)->last()->stok_akhir_aktual }}
                                                </td>
                                            @else
                                                <td
                                                    class="align-middle text-right number-float {{ $report->stok_akhir_aktual <= 1500 ? 'table-danger' : '' }}">
                                                    {{ $report->stok_akhir_aktual }}
                                                </td>
                                            @endif
                                            @if ($shop->operators->count() > 1)
                                                <td rowspan="{{ $reports->where('tanggal', $report->tanggal)->count() }}"
                                                    class="align-middle text-right number-float">
                                                    {{ $reports->where('tanggal', $report->tanggal)->last()->losses_gain }}
                                                </td>
                                            @else
                                                <td class="align-middle text-right number-float">
                                                    {{ $report->losses_gain }}
                                                </td>
                                            @endif
                                            <td class="align-middle">
                                                <div class="d-flex justify-content-between">
                                                    <span class="mr-1">Rp</span>
                                                    <span class="number">{{ $report->pengeluaran }}</span>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex justify-content-between">
                                                    <span class="mr-1">Rp</span>
                                                    <span class="number">{{ $report->pendapatan }}</span>
                                                </div>
                                            </td>
                                            @if ($shop->operators->count() > 1)
                                                <td rowspan="{{ $reports->where('tanggal', $report->tanggal)->count() }}"
                                                    class="align-middle">
                                                    <div class="d-flex justify-content-between">
                                                        <span class="mr-1">Rp</span>
                                                        <span
                                                            class="number">{{ $reports->where('tanggal', $report->tanggal)->sum('pendapatan') }}</span>
                                                    </div>
                                                </td>
                                            @endif
                                            <td class="align-middle">
                                                <div class="d-flex justify-content-between">
                                                    <span class="mr-1">Rp</span>
                                                    <span class="number">{{ $report->disetorkan }}</span>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex justify-content-between">
                                                    <span class="mr-1">Rp</span>
                                                    <span class="number">{{ $report->selisih_setoran }}</span>
                                                </div>
                                            </td>
                                            <td rowspan="{{ $reports->where('tanggal', $report->tanggal)->count() }}"
                                                class="align-middle text-center">
                                                <a class="btn btn-sm btn-link"
                                                    href="{{ route('daily-reports.detail', ['shop_id' => $report->shop_id, 'date' => $report->created_at->format('Y-m-d')]) }}">
                                                    <i class="fas fa-list"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @php
                                            $prevDate = $report->tanggal;
                                        @endphp
                                    @else
                                        <tr>
                                            <td class="align-middle">{{ $report->operator->nama }}</td>
                                            <td class="align-middle text-right number-float">
                                                {{ $report->totalisator_akhir }}
                                            </td>
                                            <td class="align-middle text-right number-float">
                                                {{ $report->volume_penjualan }}
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex justify-content-between">
                                                    <span class="mr-1">Rp</span>
                                                    <span class="number">{{ $report->rupiah_penjualan }}</span>
                                                </div>

                                            </td>
                                            <td class="text-right number-float">
                                                {{ $report->percobaan ?? 0 }}
                                            </td>
                                            <td class="text-right number-float">
                                                {{ $report->penerimaan ?? 0 }}
                                            </td>
                                            @if ($shop->operators->count() == 1)
                                                <td class="align-middle text-right number-float">
                                                    {{ $report->stik_akhir }}
                                                </td>
                                            @endif
                                            @if ($shop->operators->count() == 1)
                                                <td
                                                    class="align-middle text-right number-float  {{ $report->stok_akhir_aktual <= 1500 ? 'table-danger' : '' }}">
                                                    {{ $report->stok_akhir_aktual }}
                                                </td>
                                            @endif
                                            @if ($shop->operators->count() == 1)
                                                <td class="align-middle text-right number-float">
                                                    {{ $report->losses_gain }}
                                                </td>
                                            @endif
                                            <td class="align-middle">
                                                <div class="d-flex justify-content-between">
                                                    <span class="mr-1">Rp</span>
                                                    <span class="number">{{ $report->pengeluaran }}</span>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex justify-content-between">
                                                    <span class="mr-1">Rp</span>
                                                    <span class="number">{{ $report->pendapatan }}</span>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex justify-content-between">
                                                    <span class="mr-1">Rp</span>
                                                    <span class="number">{{ $report->disetorkan }}</span>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex justify-content-between">
                                                    <span class="mr-1">Rp</span>
                                                    <span class="number">{{ $report->selisih_setoran }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot class="table-success">
                                <tr>
                                    <th colspan="3" class="text-right">Jumlah</th>
                                    @if ($shop->operators->count() > 1)
                                        <th></th>
                                        <th></th>
                                        <th class="text-right number-float">{{ $reports->sum('volume_penjualan') }}</th>
                                        <th>
                                            <div class="d-flex justify-content-between">
                                                <span class="mr-1">Rp</span>
                                                <span class="number">{{ $reports->sum('rupiah_penjualan') }}</span>
                                            </div>
                                        </th>
                                    @else
                                        <th class="text-right number-float">{{ $reports->sum('volume_penjualan') }}</th>
                                        <th>
                                            <div class="d-flex justify-content-between">
                                                <span class="mr-1">Rp</span>
                                                <span class="number">{{ $reports->sum('rupiah_penjualan') }}</span>
                                            </div>
                                        </th>
                                    @endif
                                    <th class="text-right number-float">{{ $reports->sum('percobaan') }}</th>
                                    <th class="text-right number-float">{{ $reports->sum('penerimaan') }}</th>
                                    <th class="text-right number-float"></th>
                                    <th class="text-right number-float"></th>
                                    <th class="text-right number-float">{{ $reports->sum('losses_gain') }}</th>
                                    <th>
                                        <div class="d-flex justify-content-between">
                                            <span class="mr-1">Rp</span>
                                            <span class="number">{{ $reports->sum('pengeluaran') }}</span>
                                        </div>
                                    </th>
                                    @if ($shop->operators->count() > 1)
                                        <th></th>
                                        <th>
                                            <div class="d-flex justify-content-between">
                                                <span class="mr-1">Rp</span>
                                                <span class="number">{{ $reports->sum('pendapatan') }}</span>
                                            </div>
                                        </th>
                                    @else
                                        <th>
                                            <div class="d-flex justify-content-between">
                                                <span class="mr-1">Rp</span>
                                                <span class="number">{{ $reports->sum('pendapatan') }}</span>
                                            </div>
                                        </th>
                                    @endif
                                    <th rowspan="2" class="table-primary align-middle">
                                        @foreach ($shop->operators as $operator)
                                            <div class="text-right text-nowrap">Tbgn {{ $operator->nama }}</div>
                                        @endforeach
                                    </th>
                                    <th rowspan="2" class="table-primary align-middle">
                                        @foreach ($shop->operators as $operator)
                                            <div class="d-flex justify-content-between">
                                                <span class="mr-1">Rp</span>
                                                <span
                                                    class="number">{{ $reports->where('operator_id', $operator->id)->sum('selisih_setoran') }}</span>
                                            </div>
                                        @endforeach

                                    </th>
                                    <th rowspan="2" class="table-primary"></th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-right">Rata-rata per hari</th>
                                    @if ($shop->operators->count() > 1)
                                        <th></th>
                                        <th></th>
                                        <th class="text-right number-float">
                                            {{ $reports->sum('volume_penjualan') / $countDays }}</th>
                                        <th>
                                            <div class="d-flex justify-content-between">
                                                <span class="mr-1">Rp</span>
                                                <span
                                                    class="number">{{ $reports->sum('rupiah_penjualan') / $countDays }}</span>
                                            </div>
                                        </th>
                                    @else
                                        <th class="text-right number-float">
                                            {{ $reports->sum('volume_penjualan') / $countDays }}</th>
                                        <th>
                                            <div class="d-flex justify-content-between">
                                                <span class="mr-1">Rp</span>
                                                <span
                                                    class="number">{{ $reports->sum('rupiah_penjualan') / $countDays }}</span>
                                            </div>
                                        </th>
                                    @endif
                                    <th></th>
                                    <th class="text-right number">{{ $reports->sum('penerimaan') / 2000 }}</th>
                                    <th class="text-right number-float"></th>
                                    <th class="text-right number-float"></th>
                                    <th class="text-right number-float">
                                        {{ $reports->sum('losses_gain') / $countDays }}</th>
                                    <th>
                                        <div class="d-flex justify-content-between">
                                            <span class="mr-1">Rp</span>
                                            <span class="number">{{ $reports->sum('pengeluaran') / $countDays }}</span>
                                        </div>
                                    </th>
                                    @if ($shop->operators->count() > 1)
                                        <th></th>
                                        <th>
                                            <div class="d-flex justify-content-between">
                                                <span class="mr-1">Rp</span>
                                                <span class="number">{{ $reports->sum('pendapatan') / $countDays }}</span>
                                            </div>
                                        </th>
                                    @else
                                        <th>
                                            <div class="d-flex justify-content-between">
                                                <span class="mr-1">Rp</span>
                                                <span class="number">{{ $reports->sum('pendapatan') / $countDays }}</span>
                                            </div>
                                        </th>
                                    @endif
                                </tr>
                            </tfoot>
                        </table>
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
                    `{{ route('daily-reports.index') }}?shop_id=${shop_id}&year_month=${year_month}`
                );
            });

            $('#table').on('click', '.btn-delete', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data laporan akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: `{{ route('daily-reports.index') }}/${id}`,
                            success: function(response) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then((result) => {
                                    window.location.reload()
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
