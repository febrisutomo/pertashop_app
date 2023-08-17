@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Penjualan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Penjualan</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                        <h3 class="card-title">Edit Penjualan</h3>
                    </div>

                </div>
                <form id="insertForm" action="{{ route('sales.update', $sale->id) }}" method="POST"
                    class="needs-validation" novalidate>
                    @csrf
                    @method('PATCH')
                    <div class="card-body">
                        {{-- <div class="form-group">
                            <label for="operator">Operator</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name }}" id="operator"
                                readonly>
                        </div> --}}
                        <div class="form-group">
                            <label for="created_at">Tanggal</label>
                            <input type="date" class="form-control @error('created_at') is-invalid @enderror"
                                id="created_at" name="created_at"
                                value="{{ old('created_at', $sale->created_at->format('Y-m-d')) }}" required>
                            @error('created_at')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="totalisator_awal">Totalisator Awal</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="totalisator_awal" name="totalisator_awal"
                                    value="{{ old('totalisator_awal', $sale->totalisator_awal) }}" readonly required>
                                <div class="input-group-append">
                                    <span class="input-group-text">&ell;</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="totalisator_akhir">Totalisator Akhir</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('totalisator_akhir') is-invalid @enderror"
                                    id="totalisator_akhir" name="totalisator_akhir"
                                    value="{{ old('totalisator_akhir', $sale->totalisator_akhir) }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">&ell;</span>
                                </div>
                            </div>
                            @error('totalisator_akhir')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah Penjualan</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="jumlah" name="jumlah"
                                    value="{{ old('jumlah', $sale->jumlah) }}" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">&ell;</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sisa_stok">Sisa Stok</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="sisa_stok" name="sisa_stok"
                                    value="{{ old('sisa_stok', $sisa_stok) }}" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">&ell;</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="stik_akhir">Deep Stick Akhir</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('stik_akhir') is-invalid @enderror"
                                    id="stik_akhir" name="stik_akhir" value="{{ old('stik_akhir', $sale->stik_akhir) }}"
                                    required>
                                <div class="input-group-append">
                                    <span class="input-group-text">cm</span>
                                </div>
                            </div>
                            @error('stik_akhir')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="sisa_stok_akhir">Sisa Stok Akhir</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="sisa_stok_akhir" name="sisa_stok_akhir"
                                    value="{{ old('sisa_stok_akhir') }}" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">&ell;</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="losses_gain">Lossess/Gain</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="losses_gain" name="losses_gain"
                                    value="{{ old('losses_gain') }}" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="harga">Harga per Liter</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control" id="harga" value="{{ $harga }}"
                                    readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="omset">Omset</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control" id="omset" value="{{ old('omset') }}"
                                    readonly>
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

@push('script')
    <script>
        $(document).ready(function() {
            function calculateValues() {
                var totalisator_awal = parseFloat($("#totalisator_awal").val()) || 0;
                var totalisator_akhir = parseFloat($("#totalisator_akhir").val()) || 0;
                var stik_akhir = parseFloat($("#stik_akhir").val()) || 0;
                var skala = 21;

                var jumlah_penjualan = @json($jumlah_penjualan);
                var sisa_stok = @json($sisa_stok);
                var harga = @json($harga);
                var stik_akhir_lama = @json($stik_akhir);

                var jumlah = 0;

                if (totalisator_akhir > totalisator_awal) {
                    jumlah = totalisator_akhir - totalisator_awal;
                    sisa_stok = sisa_stok - jumlah;
                    jumlah_penjualan = jumlah_penjualan + jumlah;
                }

                var sisa_stok_akhir = stik_akhir_lama * 21;

                if (stik_akhir > 0) {
                    sisa_stok_akhir = stik_akhir * skala;
                }


                var loss_gain = 0;
                if (stik_akhir > 0) {
                    loss_gain = (sisa_stok_akhir - sisa_stok) / jumlah_penjualan * 100;
                }


                $("#jumlah").val(jumlah.toFixed(3));
                $("#sisa_stok").val(sisa_stok.toFixed(3));
                $("#sisa_stok_akhir").val(sisa_stok_akhir.toFixed(3));
                $("#losses_gain").val(loss_gain.toFixed(3));
                $("#omset").val(formatNumber(jumlah * harga))

            }

            $("#totalisator_akhir, #stik_akhir").on("input", calculateValues);

            calculateValues();

            var harga = $('#harga').val()
            $('#harga').val(formatNumber(harga))

            $("#insertForm").submit(function(event) {
                event.preventDefault();
                calculateValues();
                $(this).unbind('submit').submit(); // Continue with the form submission
            });
        });
    </script>
@endpush
