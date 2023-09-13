@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Laporan Harian</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('daily-reports.index') }}">Laporan Harian</a></li>
                        <li class="breadcrumb-item
                                active">Edit</li>
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
                        <h3 class="card-title">Laporan Harian</h3>
                    </div>

                </div>
                <form id="insertForm" action="{{ route('daily-reports.update', $dailyReport->id) }}" method="POST"
                    class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group row @if (Auth::user()->role != 'super-admin') d-none @endif">
                            <label for="shop_id" class="col-4 col-form-label">Pertashop</label>
                            <div class="col-8">
                                <select name="shop_id" id="shop_id"
                                    class="form-control @error('shop_id') is-invalid @enderror" disabled>
                                    <option value="" disabled>--Pilih Pertashop--</option>
                                    @foreach ($shops as $shop)
                                        <option value="{{ $shop->id }}" @selected($shop->id == old('shop_id', $dailyReport->shop_id))>
                                            {{ $shop->kode }} {{ $shop->nama }}</option>
                                    @endforeach
                                </select>
                                @error('shop_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="operator_id" class="col-4 col-form-label">Operator</label>
                            <div class="col-8">
                                <select name="operator_id" id="operator_id"
                                    class="form-control @error('operator_id') is-invalid @enderror"
                                    @disabled(Auth::user()->role != 'super-admin')>
                                    <option value="" disabled>--Pilih Operator--</option>
                                    @foreach ($operators as $operator)
                                        <option value="{{ $operator->id }}" @selected($operator->id == old('operator_id', $dailyReport->operator_id))>
                                            {{ $operator->name }}</option>
                                    @endforeach
                                </select>
                                @error('operator_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tanggal" class="col-4 col-form-label">Tanggal</label>
                            <div class="col-8">
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                    id="tanggal" name="tanggal"
                                    value="{{ old('tanggal', $dailyReport->created_at->format('Y-m-d')) }}"
                                    @readonly(Auth::user()->role != 'super-admin')>
                                @error('tanggal')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="jam" class="col-4 col-form-label">Jam</label>
                            <div class="col-8">
                                <input type="time" class="form-control @error('jam') is-invalid @enderror" id="jam"
                                    name="jam" value="{{ old('jam', $dailyReport->created_at->format('H:i')) }}">
                                @error('jam')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="price_id" class="col-4 col-form-label">Harga BBM</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <select name="price_id" id="price_id"
                                        class="form-control @error('price_id') is-invalid @enderror">
                                        <option value="">--Pilih Harga--</option>
                                    </select>
                                </div>
                                @error('price_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>
                        <h6 class="text-primary font-weight-bold">PENJUALAN</h6>
                        <div class="form-group row">
                            <label for="totalisator_awal" class="col-4 col-form-label">Totalisator Awal</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="totalisator_awal" name="totalisator_awal"
                                        value="{{ old('totalisator_awal', $dailyReport->totalisator_awal) }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="totalisator_akhir" class="col-4 col-form-label">Totalisator Akhir</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="number"
                                        class="form-control @error('totalisator_akhir') is-invalid @enderror"
                                        id="totalisator_akhir" name="totalisator_akhir"
                                        value="{{ old('totalisator_akhir', $dailyReport->totalisator_akhir) }}" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
                                @error('totalisator_akhir')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="test_pump" class="col-4 col-form-label">Test Pump</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="test_pump" name="test_pump"
                                        value="{{ old('test_pump', $dailyReport->percobaan) }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                    <div class="input-group-append btn-modal-test-pump">
                                        <button type="button" class="btn btn-info"><i class="fas fa-edit"></i></button>
                                    </div>
                                    <div class="input-group-append btn-delete-test-pump d-none">
                                        <button type="button" class="btn btn-danger"><i
                                                class="fas
                                            fa-trash"></i></button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Modal TestPump -->
                        <div class="modal fade" id="modalTestPump" tabindex="-1" role="dialog"
                            aria-labelledby="modelTitleId" data-backdrop="static" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Test Pump</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label for="volume_test" class="col-4 col-form-label">Volume Test</label>
                                            <div class="col-8">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="volume_test"
                                                        name="volume_test"
                                                        value="{{ old('volume_test', $dailyReport->testPump?->volume_test) }}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">&ell;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="volume_aktual" class="col-4 col-form-label">Volume Aktual</label>
                                            <div class="col-8">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="volume_aktual"
                                                        name="volume_aktual"
                                                        value="{{ old('volume_aktual', $dailyReport->testPump?->volume_aktual) }}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">&ell;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="selisih_test" class="col-4 col-form-label">Selisih Test</label>
                                            <div class="col-8">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="selisih_test"
                                                        name="selisih_test"
                                                        value="{{ old('selisih_test', $dailyReport->testPump?->selisih) }}"
                                                        readonly>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">&ell;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary btn-save-test-pump">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="form-group row">
                            <label for="volume_penjualan" class="col-4 col-form-label">Volume Penjualan</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="volume_penjualan"
                                        name="volume_penjualan"
                                        value="{{ old('volume_penjualan', $dailyReport->volume_penjualan) }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="rupiah_penjualan" class="col-4 col-form-label">Rupiah Penjualan</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" class="form-control" name="rupiah_penjualan"
                                        id="rupiah_penjualan"
                                        value="{{ old('rupiah_penjualan', $dailyReport->rupiah_penjualan) }}" readonly>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h6 class="text-primary font-weight-bold">STOK BBM</h6>
                        <div class="form-group row">
                            <label for="stik_awal" class="col-4 col-form-label">Stik Awal</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="stik_awal" name="stik_awal"
                                        value="{{ old('stik_awal', $dailyReport->stik_awal) }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="stok_awal" class="col-4 col-form-label">Stok Awal</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="stok_awal" name="stok_awal"
                                        value="{{ old('stok_awal', $dailyReport->stok_awal) }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="penerimaan" class="col-4 col-form-label">Penerimaan</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="penerimaan" name="penerimaan"
                                        value="{{ old('penerimaan', $dailyReport->penerimaan) }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                    <div class="input-group-append btn-modal-penerimaan">
                                        <button type="button" class="btn btn-info"><i class="fas fa-edit"></i></button>
                                    </div>
                                    <div class="input-group-append btn-delete-penerimaan d-none">
                                        <button type="button" class="btn btn-danger"><i
                                                class="fas
                                            fa-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Modal Penerimaan -->
                        <div class="modal fade" id="modalPenerimaan" tabindex="-1" role="dialog"
                            aria-labelledby="modelTitleId" aria-hidden="true" data-backdrop="static">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Penerimaan</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label for="purchase_id" class="col-4 col-form-label">No. SO</label>
                                            <div class="col-8">
                                                <select name="purchase_id" id="purchase_id"
                                                    class="form-control @error('purchase_id') is-invalid @enderror">
                                                    <option value="">--No. SO--</option>
                                                    @foreach ($purchases as $purchase)
                                                        <option value="{{ $purchase->id }}"
                                                            data-purchase='@json($purchase)'
                                                            @selected($purchase->id == old('purchase_id', $dailyReport->incoming?->purchase_id))>
                                                            {{ $purchase->no_so }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="volume_order" class="col-4 col-form-label">Volume Order</label>
                                            <div class="col-8">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="volume_order"
                                                        name="volume_order"
                                                        value="{{ old('volume_order', $dailyReport->incoming?->purchase->volume) }}"
                                                        readonly>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">&ell;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="sopir" class="col-4 col-form-label">Sopir</label>
                                            <div class="col-8">
                                                <input type="text"
                                                    class="form-control @error('sopir') is-invalid @enderror"
                                                    id="sopir" name="sopir"
                                                    value="{{ old('sopir', $dailyReport->incoming?->sopir) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="no_polisi" class="col-4 col-form-label">No. Polisi</label>
                                            <div class="col-8">
                                                <input type="text"
                                                    class="form-control @error('no_polisi') is-invalid @enderror"
                                                    id="no_polisi" name="no_polisi"
                                                    value="{{ old('no_polisi', $dailyReport->incoming?->no_polisi) }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="stik_sebelum_curah" class="col-4 col-form-label">Stik Sebelum
                                                Curah</label>
                                            <div class="col-8">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="stik_sebelum_curah"
                                                        name="stik_sebelum_curah"
                                                        value="{{ old('stik_sebelum_curah', $dailyReport->incoming?->stik_sebelum_curah) }}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">cm</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="stok_sebelum_curah" class="col-4 col-form-label">Stok Sebelum
                                                Curah</label>
                                            <div class="col-8">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="stok_sebelum_curah"
                                                        name="stok_sebelum_curah"
                                                        value="{{ old('stok_sebelum_curah', $dailyReport->incoming?->stok_sebelum_curah) }}"
                                                        readonly>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">&ell;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="stik_setelah_curah" class="col-4 col-form-label">Stik Setelah
                                                Curah</label>
                                            <div class="col-8">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="stik_setelah_curah"
                                                        name="stik_setelah_curah"
                                                        value="{{ old('stik_sebelum_curah', $dailyReport->incoming?->stik_setelah_curah) }}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">cm</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="stok_setelah_curah" class="col-4 col-form-label">Stok Setelah
                                                Curah</label>
                                            <div class="col-8">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="stok_setelah_curah"
                                                        name="stok_setelah_curah"
                                                        value="{{ old('stok_sebelum_curah', $dailyReport->incoming?->stok_setelah_curah) }}"
                                                        readonly>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">&ell;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label for="penerimaan_real" class="col-4 col-form-label">Volume
                                                Diterima</label>
                                            <div class="col-8">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="penerimaan_real"
                                                        name="penerimaan_real"
                                                        value="{{ old('penerimaan_real', $dailyReport->incoming?->penerimaan_real) }}"
                                                        readonly>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">&ell;</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary btn-save-penerimaan">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="stok_akhir_teoritis" class="col-4 col-form-label">Stok Akhir Teoritis</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="stok_akhir_teoritis"
                                        name="stok_akhir_teoritis"
                                        value="{{ old('stok_akhir_teoritis', $dailyReport->stok_akhir_teoritis) }}"
                                        readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="stik_akhir" class="col-4 col-form-label">Stik Akhir</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="number" class="form-control @error('stik_akhir') is-invalid @enderror"
                                        id="stik_akhir" name="stik_akhir"
                                        value="{{ old('stik_akhir', $dailyReport->stik_akhir) }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                                @error('stik_akhir')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="stok_akhir_aktual" class="col-4 col-form-label">Stok Akhir Aktual</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="number"
                                        class="form-control @error('stok_akhir_aktual') is-invalid @enderror"
                                        id="stok_akhir_aktual" name="stok_akhir_aktual"
                                        value="{{ old('stok_akhir_aktual', $dailyReport->stok_akhir_aktual) }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
                                @error('stok_akhir_aktual')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="form-group row">
                            <label for="losses_gain" class="col-4 col-form-label">Gain/Losses</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="number" class="form-control @error('losses_gain') is-invalid @enderror"
                                        id="losses_gain" name="losses_gain"
                                        value="{{ old('losses_gain', $dailyReport->losses_gain) }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
                                @error('losses_gain')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <hr>
                        <h6 class="text-primary font-weight-bold">SETORAN</h6>
                        <div class="form-group row">
                            <label for="pengeluaran" class="col-4 col-form-label">Total Pengeluaran</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" class="form-control" id="pengeluaran" name="pengeluaran"
                                        value="{{ old('pengeluaran', $dailyReport->pengeluaran) }}" readonly>
                                    <div class="input-group-append btn-modal-pengeluaran">
                                        <button type="button" class="btn btn-info"><i class="fas fa-edit"></i></button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Modal Pengeluaran -->
                        <div class="modal fade" id="modalPengeluaran" tabindex="-1" role="dialog"
                            aria-labelledby="modelTitleId" aria-hidden="true" data-backdrop="static">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Pengeluaran</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        <div id="dynamic-form">

                                        </div>

                                        <div class="text-right mb-3">
                                            <button type="button" class="btn btn-info" id="add-row"><i
                                                    class="fas fa-plus"></i></button>
                                        </div>

                                        <div class="form-group">
                                            <label for="total_pengeluaran">Total Pengeluaran</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number"
                                                    class="form-control @error('total_pengeluaran') is-invalid @enderror"
                                                    id="total_pengeluaran" name="total_pengeluaran"
                                                    value="{{ old('total_pengeluaran', $dailyReport->pengeluaran) }}"
                                                    readonly>
                                            </div>
                                            @error('total_pengeluaran')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror

                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary btn-save-pengeluaran">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="pendapatan" class="col-4 col-form-label">Total Pendapatan</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control" id="pendapatan" name="pendapatan"
                                        value="{{ old('pendapatan', $dailyReport->pendapatan) }}" readonly>
                                </div>
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="disetorkan" class="col-4 col-form-label">Disetorkan</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" class="form-control @error('disetorkan') is-invalid @enderror"
                                        id="disetorkan" name="disetorkan"
                                        value="{{ old('disetorkan', $dailyReport->disetorkan) }}">
                                </div>
                                @error('disetorkan')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="selisih_setoran" class="col-4 col-form-label">Selisih Setoran</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" class="form-control" id="selisih_setoran"
                                        name="selisih_setoran"
                                        value="{{ old('selisih_setoran', $dailyReport->selisih_setoran) }}" readonly>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="skala" name="skala">

                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>


                </form>
            </div>
        </div>

    </section>
@endsection

@push('script')
    <script>
        $(document).ready(function() {

            $('#shop_id').on('change', function() {
                const shop_id = $(this).val();
                window.location.replace(
                    `${window.location.origin}/daily-reports/create?shop_id=${shop_id}`
                );
            });

            let last_penerimaan_today = 0;
            let last_volume_penjualan_today = 0;
            //ajax get operators by shop_id
            $('#operator_id, #tanggal, #jam').on('change', function() {
                let shop_id = $('#shop_id').val();
                let operator_id = $('#operator_id').val();
                let old_operator_id = "{{ old('operator_id', $dailyReport->operator_id) }}";
                @if (Auth::user()->role == 'operator')
                    old_operator_id = "{{ Auth::user()->id }}";
                @endif
                let tanggal = $('#tanggal').val();
                let jam = $('#jam').val();
                let old_price_id = "{{ old('price_id', $dailyReport->price_id) }}";
                $.ajax({
                    url: "{{ route('daily-reports.shop-data') }}",
                    type: "GET",
                    data: {
                        shop_id,
                        operator_id,
                        tanggal,
                        jam
                    },
                    success: function(response) {

                        $('#price_id').empty();
                        if (response.prices.length == 1) {
                            $('#price_id').attr('readonly', true);
                        } else {
                            $('#price_id').attr('readonly', false);
                            $('#price_id').append(
                                `<option value="" disabled>--Pilih Harga--</option>`);
                        }
                        response.prices.forEach(price => {
                            $('#price_id').append(
                                `<option value="${price.id}" ${old_price_id == price.id || response.prices.length == 1 ? 'selected' : ''}>${parseFloat(price.harga_jual).toFixed()}</option>`
                            );
                        });

                        $('#skala').val(response.skala);

                    }
                });
            });


            $('#operator_id').trigger('change');


            $('#totalisator_akhir, #test_pump, #penerimaan, #pengeluaran, #price_id, #stik_akhir, #disetorkan').on(
                'input',
                function() {
                    const totalisator_awal = $('#totalisator_awal').val() * 1;
                    const totalisator_akhir = $('#totalisator_akhir').val() * 1;
                    const test_pump = $('#test_pump').val() * 1;
                    const volume_penjualan = totalisator_akhir - totalisator_awal - test_pump;
                    const harga = $('#price_id option:selected').text() * 1;
                    const rupiah_penjualan = volume_penjualan * harga;
                    $('#volume_penjualan').val(volume_penjualan.toFixed(2));
                    $('#rupiah_penjualan').val(rupiah_penjualan.toFixed());

                    //hitung stok akhir teoritis
                    const stok_awal = $('#stok_awal').val() * 1;
                    const penerimaan = $('#penerimaan').val() * 1;
                    const stok_akhir_teoritis = stok_awal + penerimaan - volume_penjualan;
                    $('#stok_akhir_teoritis').val(stok_akhir_teoritis.toFixed(2));

                    const stik_akhir = $('#stik_akhir').val() * 1;
                    const skala = $('#skala').val() * 1;
                    if (stik_akhir != '') {
                        const stok_akhir_aktual = stik_akhir * skala;
                        $('#stok_akhir_aktual').val(stok_akhir_aktual.toFixed(2));
                        // losses gain = stok akhir - stok akhir teoritis
                        const losses_gain = stok_akhir_aktual - stok_akhir_teoritis;
                        $('#losses_gain').val(losses_gain.toFixed(3));
                    } else {
                        $('#stok_akhir_aktual').val('');
                        $('#losses_gain').val('');
                    }

                    const pengeluaran = $('#pengeluaran').val() * 1;
                    const pendapatan = rupiah_penjualan - pengeluaran;
                    $('#pendapatan').val(pendapatan.toFixed());

                    //selisih setoran
                    const disetorkan = $('#disetorkan').val() * 1;
                    const selisih = disetorkan - pendapatan;

                    $('#selisih_setoran').val(selisih.toFixed());

                });


            //PENERIMAAN

            //open modal penerimaan
            $('.btn-modal-penerimaan').on('click', function() {
                $('#modalPenerimaan').modal('show');
            })

            //auto fill volume on change purchase_id
            $('#purchase_id').on('change', function() {
                const purchase = $(this).find(':selected').data('purchase');
                $('#volume_order').val(purchase?.volume);

            })

            let last_purchase_id = @json(old('purchase_id', $dailyReport->incoming?->purchase_id));
            let last_sopir = @json(old('sopir', $dailyReport->incoming?->sopir));
            let last_no_polisi = @json(old('no_polisi', $dailyReport->incoming?->no_polisi));
            let last_stik_sebelum_curah = @json(old('stik_sebelum_curah', $dailyReport->incoming?->stik_sebelum_curah));
            let last_stok_sebelum_curah = @json(old('stok_sebelum_curah', $dailyReport->incoming?->stok_sebelum_curah));
            let last_stik_setelah_curah = @json(old('stik_setelah_curah', $dailyReport->incoming?->stik_setelah_curah));
            let last_stok_setelah_curah = @json(old('stok_setelah_curah', $dailyReport->incoming?->stok_setelah_curah));
            let last_penerimaan_real = @json(old('penerimaan_real', $dailyReport->incoming?->penerimaan_real));

            //save penerimaan
            $('.btn-save-penerimaan').on('click', function() {
                //validation all input formPenerimaan
                const purchase_id = $('#purchase_id').val();
                const sopir = $('#sopir').val();
                const no_polisi = $('#no_polisi').val();
                const stik_sebelum_curah = $('#stik_sebelum_curah').val();
                const stik_setelah_curah = $('#stik_setelah_curah').val();
                const penerimaan_real = $('#penerimaan_real').val();
                if (purchase_id == '' || sopir == '' || no_polisi == '' || stik_sebelum_curah == '' ||
                    stik_setelah_curah == '' ||
                    penerimaan_real == '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Data penerimaan tidak lengkap!',
                    })
                    return false;
                }

                //set volume order to penerimaan
                const purchase = $('#purchase_id').find(':selected').data('purchase');
                $('#penerimaan').val(purchase?.volume);
                $('#penerimaan').trigger('input');

                last_purchase_id = purchase_id;
                last_sopir = sopir;
                last_no_polisi = no_polisi;
                last_stik_sebelum_curah = stik_sebelum_curah;
                last_stok_sebelum_curah = stik_sebelum_curah * $('#skala').val();
                last_stik_setelah_curah = stik_setelah_curah;
                last_stok_setelah_curah = stik_setelah_curah * $('#skala').val();
                last_penerimaan_real = penerimaan_real;

                $('#modalPenerimaan').modal('hide');
            })

            $('#purchase_id').trigger('change');

            //reset all input formPenerimaan on close modal
            $('#modalPenerimaan').on('show.bs.modal', function() {
                $('#purchase_id').val(last_purchase_id);
                $('#sopir').val(last_sopir);
                $('#no_polisi').val(last_no_polisi);
                $('#stik_sebelum_curah').val(last_stik_sebelum_curah);
                $('#stok_sebelum_curah').val(last_stok_sebelum_curah);
                $('#stik_setelah_curah').val(last_stik_setelah_curah);
                $('#stok_setelah_curah').val(last_stok_setelah_curah);
                $('#penerimaan_real').val(last_penerimaan_real);
            })

            //show btn-delete-penerimaan if peneriman not empty
            function showDeletePenerimaan() {
                const penerimaan = $('#penerimaan').val() * 1;

                if (penerimaan > 0) {
                    $('.btn-delete-penerimaan').removeClass('d-none');
                } else {
                    $('.btn-delete-penerimaan').addClass('d-none');
                }
            }

            showDeletePenerimaan();

            $('#penerimaan').on('input', function() {
                showDeletePenerimaan();
            })

            //delete penerimaan
            $('.btn-delete-penerimaan').on('click', function() {
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data penerimaan akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#penerimaan').val('');
                        //reset all input formPenerimaan
                        $('#volume_order').val('');
                        $('#purchase_id').val('');
                        $('#sopir').val('');
                        $('#no_polisi').val('');
                        $('#stik_sebelum_curah').val('');
                        $('#stok_sebelum_curah').val('');
                        $('#stik_setelah_curah').val('');
                        $('#stok_setelah_curah').val('');
                        $('#penerimaan_real').val('');
                        $('#penerimaan').trigger('input');

                        //reset last variabel
                        last_purchase_id = '';
                        last_sopir = '';
                        last_no_polisi = '';
                        last_stik_sebelum_curah = '';
                        last_stok_sebelum_curah = '';
                        last_stik_setelah_curah = '';
                        last_stok_setelah_curah = '';
                        last_penerimaan_real = '';
                    }
                })

            })

            //calculate stok sebelum dan setelah curah
            $('#stik_sebelum_curah, #stik_setelah_curah').on('input', function() {
                const stik_sebelum_curah = $('#stik_sebelum_curah').val() * 1;
                const stik_setelah_curah = $('#stik_setelah_curah').val() * 1;
                const skala = $('#skala').val() * 1;
                const stok_sebelum_curah = stik_sebelum_curah * skala;
                const stok_setelah_curah = stik_setelah_curah * skala;
                $('#stok_sebelum_curah').val(stok_sebelum_curah.toFixed(2));
                $('#stok_setelah_curah').val(stok_setelah_curah.toFixed(2));

                const penerimaan_real = stok_setelah_curah - stok_sebelum_curah;
                $('#penerimaan_real').val(penerimaan_real.toFixed(2));
            })

            //TEST PUMP
            //open modal testPump

            let last_volume_test = @json(old('volume_test', $dailyReport->testPump?->volume_test));
            let last_volume_aktual = @json(old('volume_aktual', $dailyReport->testPump?->volume_aktual));

            $('.btn-modal-test-pump').on('click', function() {
                $('#modalTestPump').modal('show');
            })

            //calculate selisih test
            $('#volume_test, #volume_aktual').on('input', function() {
                const volume_test = $('#volume_test').val();
                const volume_aktual = $('#volume_aktual').val();
                const selisih_test = volume_aktual - volume_test;
                $('#selisih_test').val(selisih_test.toFixed(2));
            })

            //validate on save-test-pump
            $('.btn-save-test-pump').on('click', function() {
                const volume_test = $('#volume_test').val();
                const volume_aktual = $('#volume_aktual').val();
                if (volume_test == '' || volume_aktual == '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Data test pump tidak lengkap!',
                    })
                    return false;
                }

                $('#test_pump').val(volume_test);
                $('#test_pump').trigger('input');
                last_volume_test = volume_test;
                last_volume_aktual = volume_aktual;
                $('#modalTestPump').modal('hide');
            })

            //on modal dismiss reset volume_test and volume_aktual
            $('#modalTestPump').on('show.bs.modal', function() {
                $('#volume_test').val(last_volume_test);
                $('#volume_aktual').val(last_volume_aktual);
                $('#selisih_test').val((last_volume_aktual - last_volume_test).toFixed(2));
            })

            function showDeleteTestPump() {
                const test_pump = $('#test_pump').val() * 1;
                if (test_pump > 0) {
                    $('.btn-delete-test-pump').removeClass('d-none');
                } else {
                    $('.btn-delete-test-pump').addClass('d-none');
                }
            }

            showDeleteTestPump();

            //show delete btn if test_pump not empty
            $('#test_pump').on('input', function() {
                showDeleteTestPump();
            })


            //delete test pump
            $('.btn-delete-test-pump').on('click', function() {
                //confirm delete
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data test pump akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#test_pump').val('');
                        $('#volume_test').val('');
                        $('#volume_aktual').val('');
                        $('#selisih_test').val('');
                        $('#test_pump').trigger('input');
                        last_volume_aktual = "";
                        last_volume_test = ""
                    }
                })

            })

            //PENGELUARAN
            //open modal pengeluaran
            $('.btn-modal-pengeluaran').on('click', function() {
                $('#modalPengeluaran').modal('show');
            })

            // Cek jika ada old data dan isi form dengan old data
            let oldCategoryIds = @json(old('category_id', $dailyReport->spendings?->pluck('category_id')));
            let oldJumlah = @json(old('jumlah', $dailyReport->spendings?->pluck('jumlah')));
            let oldKeterangan = @json(old('keterangan', $dailyReport->spendings?->pluck('keterangan')));

            let categories = @json($categories);

            // categories to select category_id
            function categoryOptions(selectedCategoryId) {
                var options = "<option value=''>--Pilih Kategori--</option>";
                categories.forEach(function(category) {
                    options +=
                        `<option value="${category.id}" ${selectedCategoryId == category.id ? 'selected' : ''}>${category.nama}</option>`;
                });
                return options;
            }

            function resetPengeluaran() {
                //reset all input formPengeluaran
                $('#dynamic-form').empty();
                $('#total_pengeluaran').val('');
                //set old data to formPengeluaran
                if (oldCategoryIds.length > 0) {
                    for (let i = 0; i < oldCategoryIds.length; i++) {
                        addRow(oldCategoryIds[i], oldJumlah[i], oldKeterangan[i]);
                    }
                } else {
                    addRow("", "", "");
                }

                $('input[name="jumlah[]"]').trigger('input');
                //trigger change category_id to show keterangan
                $('select[name="category_id[]"]').trigger('change');
            }

            resetPengeluaran();

            //modal on show
            $('#modalPengeluaran').on('show.bs.modal', function() {
                resetPengeluaran()
            })

            // Fungsi untuk menambahkan baris input
            $("#add-row").click(function() {
                addRow("", "", "");
            });



            function addRow(categoryId, jumlah, keterangan) {
                let newRow = `
            <div class="form-row-pengeluaran">
                <div class="row align-items-end">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Pengeluaran</label>
                            <select class="form-control" name="category_id[]">
                                ${categoryOptions(categoryId)}
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-4">
                        <div class="form-group">
                            <label>Jumlah</label>
                            <input type="text" class="form-control" name="jumlah[]" value="${jumlah}">
                        </div>
                    </div>
                
                    <div class="col-2">
                        <div class="form-group text-right">
                        <button type="button" class="btn btn-danger remove-row-pengeluaran"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
                <div class="form-group keterangan d-none">
                    <label>Keterangan</label>
                    <input type="text" class="form-control" name="keterangan[]" value="${keterangan}">
                </div>
                <hr>
            </div>
        `;

                $("#dynamic-form").append(newRow);
            }

            // Fungsi untuk menghapus baris input
            $(document).on("click", ".remove-row-pengeluaran", function() {
                $(this).closest(".form-row-pengeluaran").remove();
                let total_pengeluaran = 0;
                $('input[name="jumlah[]"]').each(function() {
                    total_pengeluaran += $(this).val() * 1;
                })
                $('#total_pengeluaran').val(total_pengeluaran);

                let categoryIds = [];
                let jumlahs = [];
                let keterangans = [];
                $('select[name="category_id[]"]').each(function() {
                    categoryIds.push($(this).val());
                })
                $('input[name="jumlah[]"]').each(function() {
                    jumlahs.push($(this).val());
                })
                $('input[name="keterangan[]"]').each(function() {
                    keterangans.push($(this).val());
                })

                oldCategoryIds = categoryIds;
                oldJumlah = jumlahs;
                oldKeterangan = keterangans;
            });


            //show keterangan if category_id == 99
            $(document).on('change', 'select[name="category_id[]"]', function() {
                const category_id = $(this).val();
                if (category_id == 99) {
                    $(this).closest('.form-row-pengeluaran').find('.keterangan').removeClass('d-none');
                } else {
                    $(this).closest('.form-row-pengeluaran').find('.keterangan').addClass('d-none');
                    //remove value
                    $(this).closest('.form-row-pengeluaran').find('.keterangan input').val('');
                }
            })

            //sum jumlah pengeluaran and set to total_pengeluaran
            $(document).on('input', 'input[name="jumlah[]"]', function() {
                let total_pengeluaran = 0;
                $('input[name="jumlah[]"]').each(function() {
                    total_pengeluaran += $(this).val() * 1;
                })
                $('#total_pengeluaran').val(total_pengeluaran);
            })

            //save pengeluaran
            $('.btn-save-pengeluaran').on('click', function() {
                //validation all input pengeluaran required
                let valid = true;
                $('select[name="category_id[]"]').each(function() {
                    if ($(this).val() == '') {
                        valid = false;
                    }
                })

                //if category_id == 99 keterangan required
                $('select[name="category_id[]"]').each(function() {
                    if ($(this).val() == 99) {
                        if ($(this).closest('.form-row-pengeluaran').find(
                                'input[name="keterangan[]"]').val() ==
                            '') {
                            valid = false;
                        }
                    }
                })

                $('input[name="jumlah[]"]').each(function() {
                    if ($(this).val() == '') {
                        valid = false;
                    }
                })
                if (!valid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Data pengeluaran tidak lengkap!',
                    })
                    return false;
                }

                //total pengeluaran
                let total_pengeluaran = $('#total_pengeluaran').val() * 1;
                $('#pengeluaran').val(total_pengeluaran);
                $('#pengeluaran').trigger('input');

                //get data and save to old data
                let categoryIds = [];
                let jumlahs = [];
                let keterangans = [];
                $('select[name="category_id[]"]').each(function() {
                    categoryIds.push($(this).val());
                })
                $('input[name="jumlah[]"]').each(function() {
                    jumlahs.push($(this).val());
                })
                $('input[name="keterangan[]"]').each(function() {
                    keterangans.push($(this).val());
                })

                oldCategoryIds = categoryIds;
                oldJumlah = jumlahs;
                oldKeterangan = keterangans;

                $('#modalPengeluaran').modal('hide');
            })



        })
    </script>
@endpush
