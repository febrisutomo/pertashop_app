@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Penerimaan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('incomings.index') }}">Penerimaan</a></li>
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
                        <h3 class="card-title">Penerimaan</h3>
                    </div>

                </div>
                <form id="insertForm" action="{{ route('incomings.update', $incoming->id) }}" method="POST"
                    class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="card-body">

                        <div class="form-group row">
                            <label for="created_at" class="col-sm-4 col-form-label">Tanggal</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control @error('created_at') is-invalid @enderror"
                                    id="created_at" name="created_at"
                                    value="{{ old('created_at', date_format(date_create($incoming->created_at), 'Y-m-d')) }}"
                                    readonly>
                                @error('created_at')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if (Auth::user()->role != 'operator')
                            <div class="form-group row">
                                <label for="operator" class="col-sm-4 col-form-label">Operator</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="operator" name="operator"
                                        value="{{ $incoming->operator->user->name }}" readonly>
                                </div>
                            </div>
                        @endif

                        <div class="form-group row">
                            <label for="no_so" class="col-sm-4 col-form-label">No. SO</label>
                            <div class="col-sm-8">
                                <select name="purchase_id" id="purchase_id"
                                    class="form-control @error('purchase_id') is-invalid @enderror">
                                    <option value="">--Pilih No. SO--</option>
                                    @foreach ($purchases as $purchase)
                                        <option value="{{ $purchase->id }}" @selected($purchase->id == old('purchase_id', $incoming->purchase_id))
                                            data-purchase='@json($purchase)'>
                                            {{ $purchase->no_so }}</option>
                                    @endforeach
                                </select>
                                @error('purchase_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sopir" class="col-sm-4 col-form-label">Sopir</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('sopir') is-invalid @enderror"
                                    id="sopir" name="sopir" value="{{ old('sopir', $incoming->sopir) }}">
                                @error('sopir')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="no_polisi" class="col-sm-4 col-form-label">No. Polisi</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('no_polisi') is-invalid @enderror"
                                    id="no_polisi" name="no_polisi" value="{{ old('no_polisi', $incoming->no_polisi) }}">
                                @error('no_polisi')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="volume" class="col-sm-4 col-form-label">Volume Pemesanan</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="volume" name="volume"
                                        value="{{ old('volume', $incoming->volume) }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="stik_awal" class="col-sm-4 col-form-label">Pengukuran Awal</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="input-group">
                                            <input type="number"
                                                class="form-control @error('stik_awal') is-invalid @enderror" id="stik_awal"
                                                name="stik_awal" value="{{ old('stik_awal', $incoming->stik_awal) }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">cm</span>
                                            </div>
                                        </div>
                                        @error('stik_awal')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="stok_awal" name="stok_awal"
                                                value="{{ old('stok_awal', $incoming->stik_awal * $shop->skala) }}"
                                                readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text">&ell;</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="stik_akhir" class="col-sm-4 col-form-label">Pengukuran Akhir</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="input-group">
                                            <input type="number"
                                                class="form-control @error('stik_akhir') is-invalid @enderror"
                                                id="stik_akhir" name="stik_akhir"
                                                value="{{ old('stik_akhir', $incoming->stik_akhir) }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">cm</span>
                                            </div>
                                        </div>
                                        @error('stik_akhir')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="stok_akhir" name="stok_akhir"
                                                value="{{ old('stok_akhir', $incoming->stik_akhir * $shop->skala) }}"
                                                readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text">&ell;</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="volume_aktual" class="col-sm-4 col-form-label">Volume Aktual</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number"
                                        class="form-control @error('volume_aktual') is-invalid @enderror"
                                        id="volume_aktual" name="volume_aktual"
                                        value="{{ old('volume_aktual', $incoming->volume_aktual) }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
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
            $('#purchase_id').on('change', function() {
                let volume = 0;
                let purchase = $(this).find(':selected').data('purchase');
                if (purchase) {
                    volume = purchase.volume * 1;
                }
                $('#volume').val(volume.toFixed(2));
            });

            //calculate volume aktual (stok akhir - stok awal)
            $('#stik_akhir, #stik_awal').on('input', function() {
                let stik_akhir = $('#stik_akhir').val();
                let stik_awal = $('#stik_awal').val();
                let skala = {{ $shop->skala }};
                let stok_akhir = stik_akhir * skala;
                let stok_awal = stik_awal * skala;
                let volume_aktual = stok_akhir - stok_awal;
                $('#stok_awal').val(stok_awal.toFixed(2))
                $('#stok_akhir').val(stok_akhir.toFixed(2));
                $('#volume_aktual').val(volume_aktual.toFixed(2));
            });

        })
    </script>
@endpush
