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
                        <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Penjualan</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
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
                <form id="insertForm" action="{{ route('sales.store') }}" method="POST" class="needs-validation"
                    novalidate>
                    @csrf
                    <div class="card-body">

                        <div class="form-group row">
                            <label for="date" class="col-sm-4 col-form-label">Tanggal</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                    id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                @error('tanggal')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="totalisator_awal" class="col-sm-4 col-form-label">Totalisator Awal</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="totalisator_awal" name="totalisator_awal"
                                        value="{{ $totalisator_awal }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="form-group row">
                            <label for="totalisator_akhir" class="col-sm-4 col-form-label">Totalisator Akhir</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number"
                                        class="form-control @error('totalisator_akhir') is-invalid @enderror"
                                        id="totalisator_akhir" name="totalisator_akhir"
                                        value="{{ old('totalisator_akhir') }}" required>
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
                            <label for="test-pump" class="col-sm-4 col-form-label">Test Pump</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="test-pump" name="test-pump"
                                        value="{{ old('test-pump') }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
                            </div>

                        </div>



                        <div class="form-group row">
                            <label for="volume" class="col-sm-4 col-form-label">Volume Penjualan</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="volume" name="volume"
                                        value="{{ old('volume') }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="harga" class="col-sm-4 col-form-label">Harga per Liter</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control" id="harga" value="{{ $harga }}"
                                        readonly>
                                </div>
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="omset" class="col-sm-4 col-form-label">Rupiah Penjualan</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control" id="omset"
                                        value="{{ old('omset') }}" readonly>
                                </div>
                            </div>

                        </div>


                        <div class="form-group row">
                            <label for="stik_awal" class="col-sm-4 col-form-label">Stik Awal</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" class="form-control @error('stik_awal') is-invalid @enderror"
                                        id="stik_awal" name="stik_awal" value="{{ old('stik_awal') }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                                @error('stik_awal')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="form-group row">
                            <label for="stok_awal" class="col-sm-4 col-form-label">Stok Awal</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" class="form-control @error('stok_awal') is-invalid @enderror"
                                        id="stok_awal" name="stok_awal" value="{{ old('stok_awal') }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
                                @error('stok_awal')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="form-group row">
                            <label for="penerimaan" class="col-sm-4 col-form-label">Penerimaan</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" class="form-control @error('penerimaan') is-invalid @enderror"
                                        id="penerimaan" name="penerimaan" value="{{ old('penerimaan') }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
                                @error('penerimaan')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="form-group row">
                            <label for="stok_akhir_teoritis" class="col-sm-4 col-form-label">Stok Akhir Toritis</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number"
                                        class="form-control @error('stok_akhir_teoritis') is-invalid @enderror"
                                        id="stok_akhir_teoritis" name="stok_akhir_teoritis"
                                        value="{{ old('stok_akhir_teoritis') }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
                                @error('stok_akhir_teoritis')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="stik_akhir" class="col-sm-4 col-form-label">Stik Akhir</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" class="form-control @error('stik_akhir') is-invalid @enderror"
                                        id="stik_akhir" name="stik_akhir" value="{{ old('stik_akhir') }}">
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
                            <label for="stok_akhir" class="col-sm-4 col-form-label">Stok Akhir</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" class="form-control @error('stok_akhir') is-invalid @enderror"
                                        id="stok_akhir" name="stok_akhir" value="{{ old('stok_akhir') }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
                                @error('stok_akhir')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="form-group row">
                            <label for="losses_gain" class="col-sm-4 col-form-label">Gain/Losses</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" class="form-control @error('losses_gain') is-invalid @enderror"
                                        id="losses_gain" name="losses_gain" value="{{ old('losses_gain') }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
                                @error('losses_gain')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>


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
