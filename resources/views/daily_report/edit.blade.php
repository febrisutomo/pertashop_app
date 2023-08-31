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

                        <div class="form-group row">
                            <label for="tanggal" class="col-sm-4 col-form-label">Tanggal</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                    id="tanggal" name="tanggal"
                                    value="{{ old('tanggal', $dailyReport->created_at->format('Y-m-d')) }}" readonly>
                                @error('tanggal')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if (Auth::user()->role != 'operator')
                            <div class="form-group row">
                                <label for="operator" class="col-sm-4 col-form-label">Operator</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="operator" name="operator"
                                        value="{{ $dailyReport->operator->user->name }}" readonly>
                                </div>
                            </div>
                        @endif

                        <div class="form-group row">
                            <label for="totalisator_awal" class="col-sm-4 col-form-label">Totalisator Awal</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="totalisator_awal" name="totalisator_awal"
                                        value="{{ $dailyReport->totalisator_awal }}" readonly>
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
                            <label for="test_pump" class="col-sm-4 col-form-label">Test Pump</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="test_pump" name="test_pump"
                                        value="{{ old('test_pump', $dailyReport->test_pump) }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="volume_penjualan" class="col-sm-4 col-form-label">Volume Penjualan</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="volume_penjualan" name="volume_penjualan"
                                        value="{{ old('volume_penjualan', $dailyReport->volume_penjualan) }}" readonly>
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
                                    <input type="text" class="form-control" id="harga"
                                        value="{{ $dailyReport->price->harga_jual }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="rupiah_penjualan" class="col-sm-4 col-form-label">Rupiah Penjualan</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control" id="rupiah_penjualan"
                                        value="{{ old('rupiah_penjualan', $dailyReport->rupiah_penjualan) }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="stik_awal" class="col-sm-4 col-form-label">Stik Awal</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="stik_awal" name="stik_awal"
                                        value="{{ $dailyReport->stik_awal }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="stok_awal" class="col-sm-4 col-form-label">Stok Awal</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="stok_awal" name="stok_awal"
                                        value="{{ $dailyReport->stok_awal }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="penerimaan" class="col-sm-4 col-form-label">Penerimaan</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="penerimaan" name="penerimaan"
                                        value="{{ old('penerimaan', $dailyReport->penerimaan) }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="stok_akhir_teoritis" class="col-sm-4 col-form-label">Stok Akhir Teoritis</label>
                            <div class="col-sm-8">
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
                            <label for="stik_akhir" class="col-sm-4 col-form-label">Stik Akhir</label>
                            <div class="col-sm-8">
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
                            <label for="stok_akhir_aktual" class="col-sm-4 col-form-label">Stok Akhir Aktual</label>
                            <div class="col-sm-8">
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
                            <label for="losses_gain" class="col-sm-4 col-form-label">Losses / Gain</label>
                            <div class="col-sm-8">
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

                        <div class="form-group row">
                            <label for="pengeluaran" class="col-sm-4 col-form-label">Pengeluaran</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" class="form-control" id="pengeluaran" name="pengeluaran"
                                        value="{{ old('pengeluaran', $dailyReport->pengeluaran) }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pendapatan" class="col-sm-4 col-form-label">Total Pendapatan</label>
                            <div class="col-sm-8">
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
                            <label for="disetorkan" class="col-sm-4 col-form-label">Disetorkan</label>
                            <div class="col-sm-8">
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
                            <label for="selisih_setoran" class="col-sm-4 col-form-label">Selisih Setoran</label>
                            <div class="col-sm-8">
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

                        <div class="form-group row">
                            <label for="belum_disetorkan" class="col-sm-4 col-form-label">Belum Disetorkan</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" class="form-control" id="belum_disetorkan"
                                        name="belum_disetorkan"
                                        value="{{ old('belum_disetorkan', $dailyReport->belum_disetorkan) }}" readonly>
                                </div>
                            </div>
                        </div>

                        @if (Auth::user()->role == 'admin')
                            <div class="form-group row">
                                <label for="diverifikasi" class="col-sm-4 col-form-label">Diverifikasi</label>
                                <div class="col-sm-8">
                                    <select name="diverifikasi" id="diverifikasi"
                                        class="form-control @error('diverifikasi') is-invalid @enderror">
                                        <option value="0" @selected(0 == old('diverifikasi', $dailyReport->diverifikasi))>Belum</option>
                                        <option value="1" @selected(1 == old('diverifikasi', $dailyReport->diverifikasi))>Sudah</option>
                                    </select>
                                    @error('diverifikasi')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endif

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
            //get total spendings from ajax
            $.ajax({
                url: "{{ route('daily-reports.edit', $dailyReport->id) }}",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    $('#pengeluaran').val(response.total_spendings);
                    $('#penerimaan').val(response.total_incomings);
                    $('#test_pump').val(response.total_test_pumps);
                }
            });

            $('#totalisator_akhir, #stik_akhir, #disetorkan').on('input', function() {
                const totalisator_awal = $('#totalisator_awal').val() * 1;
                const totalisator_akhir = $('#totalisator_akhir').val() * 1;
                const test_pump = $('#test_pump').val() * 1;
                const volume_penjualan = totalisator_akhir - totalisator_awal - test_pump;
                const harga = $('#harga').val() * 1;
                const rupiah_penjualan = volume_penjualan * harga;
                $('#volume_penjualan').val(volume_penjualan);
                $('#rupiah_penjualan').val(rupiah_penjualan.toFixed(2));

                //hitung stok akhir teoritis
                const stok_awal = $('#stok_awal').val() * 1;
                const penerimaan = $('#penerimaan').val() * 1;
                const stok_akhir_teoritis = stok_awal + penerimaan - volume_penjualan;
                $('#stok_akhir_teoritis').val(stok_akhir_teoritis.toFixed(2));

                const stik_akhir = $('#stik_akhir').val() * 1;
                const skala = {{ $dailyReport->shop->skala }};
                const stok_akhir_aktual = stik_akhir * skala;
                $('#stok_akhir_aktual').val(stok_akhir_aktual);

                // losses gain = stok akhir - stok akhir teoritis
                const losses_gain = stok_akhir_aktual - stok_akhir_teoritis;
                $('#losses_gain').val(losses_gain.toFixed(2));

                const pengeluaran = $('#pengeluaran').val() * 1;
                const pendapatan = rupiah_penjualan - pengeluaran;
                $('#pendapatan').val(pendapatan);

                //selisih setoran
                const disetorkan_awal = {{ $dailyReport->disetorkan }};
                const disetorkan = $('#disetorkan').val() * 1;
                const s = disetorkan_awal - disetorkan;
                const selisih = disetorkan - pendapatan;
                $('#selisih_setoran').val(selisih);

                const belum_disetorkan = {{ $dailyReport->belum_disetorkan }};
                $('#belum_disetorkan').val(belum_disetorkan - s);
            });
        })
    </script>
@endpush
