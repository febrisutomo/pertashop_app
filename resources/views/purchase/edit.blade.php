@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Pembelian</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">Pembelian</a></li>
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
                        <h3 class="card-title">Pembelian</h3>
                    </div>

                </div>
                <form id="insertForm" action="{{ route('purchases.update', $purchase->id) }}" method="POST"
                    class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="created_at">Tanggal</label>
                            <input type="date" class="form-control @error('created_at') is-invalid @enderror"
                                id="created_at" name="created_at"
                                value="{{ old('created_at', $purchase->created_at->format('Y-m-d')) }}" required>
                            @error('created_at')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- <div class="form-group">
                            <label for="shop_id">Pertashop</label>
                            <select name="shop_id" id="shop_id"
                                class="form-control @error('shop_id') is-invalid @enderror">
                                <option value="">--Pilih Pertashop--</option>
                                @foreach ($shops as $shop)
                                    <option value="{{ $shop->id }}" @selected($shop->id == old('shop_id'))>
                                        {{ $shop->kode . ' ' . $shop->nama }}</option>
                                @endforeach
                            </select>
                            @error('shop_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div> --}}

                        <div class="form-group">
                            <label for="supplier_id">Supplier</label>
                            <select class="form-control @error('supplier_id') is-invalid @enderror" name="supplier_id"
                                id="supplier_id">
                                <option value="">--Pilih Supplier--</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @selected(old('supplier_id', $purchase->supplier_id) == $supplier->id)>{{ $supplier->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="no_so">No. SO</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('no_so') is-invalid @enderror"
                                    id="no_so" name="no_so" value="{{ old('no_so', $purchase->no_so) }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">&ell;</span>
                                </div>
                            </div>
                            @error('no_so')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="volume">Volume</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('volume') is-invalid @enderror"
                                    id="volume" name="volume" value="{{ old('volume', $purchase->volume) }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">&ell;</span>
                                </div>
                            </div>
                            @error('volume')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="total_bayar">Total Bayar</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('total_bayar') is-invalid @enderror"
                                    id="total_bayar" name="total_bayar"
                                    value="{{ old('total_bayar', $purchase->total_bayar) }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">&ell;</span>
                                </div>
                            </div>
                            @error('total_bayar')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="harga">Harga per Liter</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control" id="harga" name="harga"
                                    value="{{ old('harga', $purchase->harga) }}" readonly>
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
            //calculate harga per liter
            //total bayar / volume
            $('#volume, #total_bayar').on('keyup', function() {
                let total_bayar = $('#total_bayar').val() * 1;
                let volume = $('#volume').val() * 1;
                let harga = total_bayar / volume;
                $('#harga').val(harga);
            });
        });
    </script>
@endpush
