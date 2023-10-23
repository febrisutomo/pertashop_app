@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Detail Laporan Harian</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('daily-reports.index') }}">Laporan Harian</a></li>
                        <li class="breadcrumb-item
                                active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h6 class="card-title">{{ $reports->first()?->tanggal_panjang }}</h6>
                </div>
                <div class="card-body">
                    @foreach ($reports as $report)
                        <div class="row justify-content-between">
                            <div class="col-10">
                                @if ($reports->count() > 1)
                                    <h6 class="text-primary mb-0 font-weight-bold">Shift {{ $loop->iteration }}</h6>
                                @endif
                                <div class="row">
                                    <div class="col-6 font-weight-bold">
                                        Jam Penutupan
                                    </div>
                                    <div class="col-6">
                                        {{ $report->created_at->format('H:i') }}
                                    </div>
                                </div>
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
                                        Totalisator Awal
                                    </div>
                                    <div class="col-6 number-float">
                                        {{ $report->totalisator_awal }}
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
                                <div class="row">
                                    <div class="col-6 font-weight-bold">
                                        Test Pump
                                    </div>
                                    <div class="col-6">
                                        <span class="number-float">
                                            {{ $report->percobaan ?? '-' }}
                                        </span>
                                        @if ($report->percobaan > 0)
                                            <button class="btn btn-sm btn-link btn-percobaan"
                                                data-id="{{ $report->id }}"><i class="fa fa-info-circle"
                                                    aria-hidden="true"></i></button>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 font-weight-bold">
                                        Penerimaan
                                    </div>
                                    <div class="col-6">
                                        <span class="number-float">
                                            {{ $report->penerimaan ?? '-' }}
                                        </span>
                                        @if ($report->penerimaan > 0)
                                            <button class="btn btn-sm btn-link btn-penerimaan"
                                                data-id="{{ $report->id }}"><i class="fa fa-info-circle"
                                                    aria-hidden="true"></i></button>
                                        @endif
                                    </div>
                                </div>
                                @if ($reports->count() > 1)
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">
                                            Vol. Penjualan
                                        </div>
                                        <div class="col-6 number-float">
                                            {{ $report->volume_penjualan }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">
                                            Rp. Penjualan
                                        </div>
                                        <div class="col-6 currency">
                                            {{ $report->rupiah_penjualan }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">
                                            Pengeluaran
                                        </div>
                                        <div class="col-6">
                                            <span class="currency">
                                                {{ $report->pengeluaran }}
                                            </span>
                                            @if ($report->pengeluaran > 0)
                                                <button class="btn btn-sm btn-link btn-pengeluaran"
                                                    data-id="{{ $report->id }}><i
                                                        class="fa
                                                    fa-info-circle" aria-hidden="true"></i></button>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">
                                            Pendapatan
                                        </div>
                                        <div class="col-6 currency">
                                            {{ $report->pendapatan }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">
                                            Setor Tunai
                                        </div>
                                        <div class="col-6 currency">
                                            {{ $report->setor_tunai }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">
                                            Setor QRIS
                                        </div>
                                        <div class="col-6 currency">
                                            {{ $report->setor_qris }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">
                                            Setor Transfer
                                        </div>
                                        <div class="col-6 currency">
                                            {{ $report->setor_transfer }}
                                        </div>
                                    </div>
                                @endif

                            </div>
                            @php
                                // diff day from now
                                $diff = $report->created_at->diffInDays(now());
                            @endphp

                            @if (Auth::user()->role == 'operator' && $diff > 1)
                            @else
                                <div class="col-2 text-right">
                                    <a href="{{ route('daily-reports.edit', $report->id) }}"
                                        class="btn btn-sm btn-primary mb-2"><i class="fas fa-edit"></i></a>
                                    @if (Auth::user()->role == 'operator' && $report->operator_id != Auth::user()->id)
                                    @else
                                        <button class="btn btn-sm btn-danger btn-delete mb-2"
                                            data-id="{{ $report->id }}"><i class="fas fa-trash"></i></button>
                                    @endif

                                </div>
                            @endif

                        </div>

                        @if ($reports->count() > 1)
                            <hr>
                        @endif
                        @if ($loop->last)
                            <div class="row">
                                <div class="col-10">
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">
                                            @if ($reports->count() > 1)
                                                Total
                                            @endif
                                            Vol. Penjualan
                                        </div>
                                        <div class="col-6 number-float">
                                            {{ $reports->sum('volume_penjualan') }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">
                                            @if ($reports->count() > 1)
                                                Total
                                            @endif
                                            Rp. Penjualan
                                        </div>
                                        <div class="col-6 currency">
                                            {{ $reports->sum('rupiah_penjualan') }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">
                                            Stik Akhir
                                        </div>
                                        <div class="col-6 number-float">
                                            {{ $reports->last()->stik_akhir ?? '-' }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">
                                            Stok Aktual
                                        </div>
                                        <div class="col-6 number-float">
                                            {{ $reports->last()->stok_akhir_aktual ?? '-' }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">
                                            Gain / Loss
                                        </div>
                                        <div class="col-6 number-float">
                                            {{ $reports->last()->losses_gain ?? '-' }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">
                                            @if ($reports->count() > 1)
                                                Total
                                            @endif
                                            Pengeluaran
                                        </div>
                                        <div class="col-6">
                                            <span class="currency">{{ $reports->sum('pengeluaran') }}</span>
                                            @if ($reports->count() == 1 && $report->pengeluaran > 0)
                                                <button class="btn btn-sm btn-link btn-pengeluaran"
                                                    data-id="{{ $report->id }}"><i
                                                        class="fa
                                                    fa-info-circle"
                                                        aria-hidden="true"></i></button>
                                            @endif

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">
                                            @if ($reports->count() > 1)
                                                Total
                                            @endif
                                            Pendapatan
                                        </div>
                                        <div class="col-6 currency">
                                            {{ $reports->sum('pendapatan') }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">
                                            @if ($reports->count() > 1)
                                                Total
                                            @endif
                                            Setor Tunai
                                        </div>
                                        <div class="col-6 currency">
                                            {{ $reports->sum('setor_tunai') }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">
                                            @if ($reports->count() > 1)
                                                Total
                                            @endif
                                            Setor QRIS
                                        </div>
                                        <div class="col-6 currency">
                                            {{ $reports->sum('setor_qris') }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">
                                            @if ($reports->count() > 1)
                                                Total
                                            @endif
                                            Setor Transfer
                                        </div>
                                        <div class="col-6 currency">
                                            {{ $reports->sum('setor_transfer') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($report->percobaan > 0)
                            {{-- modal testPump --}}
                            <div class="modal fade" id="modalTestPump{{ $report->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="modelTitleId" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Test Pump</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table">
                                                <tr>
                                                    <td>Volume Test</td>
                                                    <td>
                                                        <div class="float-right">
                                                            <span
                                                                class="number-float">{{ $report->testPump->volume_test }}</span>
                                                            &ell;
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Volume Aktual</td>
                                                    <td>
                                                        <div class="float-right">
                                                            <span
                                                                class="number-float">{{ $report->testPump->volume_aktual }}</span>
                                                            &ell;
                                                        </div>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Selisih</td>
                                                    <td>
                                                        <div class="float-right">
                                                            <span
                                                                class="number-float">{{ $report->testPump->selisih }}</span>
                                                            &ell;
                                                        </div>

                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif


                        @if ($report->penerimaan > 0)
                            {{-- modal penerimaan --}}
                            <div class="modal fade" id="modalPenerimaan{{ $report->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Penerimaan</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table">
                                                <tr>
                                                    <td>No. SO</td>
                                                    <td>{{ $report->incoming->purchase->no_so }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Vendor Order</td>
                                                    <td>{{ $report->incoming->purchase->vendor->nama }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Vendor Pengirim</td>
                                                    <td>{{ $report->incoming->vendor->nama }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Sopir</td>
                                                    <td>{{ $report->incoming->sopir }}</td>
                                                </tr>
                                                <tr>
                                                    <td>No. Polisi</td>
                                                    <td>{{ $report->incoming->no_polisi }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Volume Order</td>
                                                    <td><span
                                                            class="number-float">{{ $report->incoming->purchase->volume }}</span>
                                                        &ell;
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Stik Sebelum Curah</td>
                                                    <td><span
                                                            class="number-float">{{ $report->incoming->stik_sebelum_curah }}</span>
                                                        cm (<span
                                                            class="number-float">{{ $report->incoming->stok_sebelum_curah }}</span>
                                                        &ell;
                                                        )</td>
                                                </tr>
                                                <tr>
                                                    <td>Stik Setelah Curah</td>
                                                    <td><span
                                                            class="number-float">{{ $report->incoming->stik_setelah_curah }}</span>
                                                        cm (<span
                                                            class="number-float">{{ $report->incoming->stok_setelah_curah }}</span>
                                                        &ell;
                                                        )</td>
                                                </tr>
                                                <tr>
                                                    <td>Penerimaan Real</td>
                                                    <td><span
                                                            class="number-float">{{ $report->incoming->penerimaan_real }}</span>
                                                        &ell;
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif


                        @if ($report->pengeluaran > 0)
                            {{-- modal pengeluaran --}}
                            <div class="modal fade" id="modalPengeluaran{{ $report->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Pengeluaran</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <ol class="pl-3">
                                                @foreach ($report->spendings as $spending)
                                                    <li>{{ $spending->category_id == 99 ? $spending->keterangan : $spending->category->nama }}
                                                        <span class="float-right currency">{{ $spending->jumlah }}</span>
                                                    </li>
                                                @endforeach
                                            </ol>
                                            <div class="font-weight-bold"><span>Total Pengeluaran</span><span
                                                    class="float-right currency">{{ $report->pengeluaran }}</span></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

    </section>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('.btn-delete').on('click', function() {
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

            $('.btn-percobaan').on('click', function() {
                //open clossest modalPercobaan
                let id = $(this).data('id');
                $('#modalTestPump' + id).modal('show');
            });

            $('.btn-pengeluaran').on('click', function() {
                let id = $(this).data('id');
                $('#modalPengeluaran' + id).modal('show');
            });

            $('.btn-penerimaan').on('click', function() {
                let id = $(this).data('id');
                $('#modalPenerimaan' + id).modal('show');
            });
        });
    </script>
@endpush
