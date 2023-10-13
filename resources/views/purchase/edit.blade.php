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
                        <div class="form-group row @if (Auth::user()->role != 'super-admin') d-none @endif">
                            <label for="shop_id" class="col-4 col-form-label">Pertashop</label>
                            <div class="col-8">
                                <select name="shop_id" id="shop_id"
                                    class="form-control @error('shop_id') is-invalid @enderror" disabled>
                                    <option value="" disabled>--Pilih Pertashop--</option>
                                    @foreach ($shops as $shop)
                                        <option value="{{ $shop->id }}" @selected($shop->id == old('shop_id', $purchase->shop_id))>
                                            {{ $shop->kode }} {{ $shop->nama }}</option>
                                    @endforeach
                                </select>
                                @error('shop_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="created_at" class="col-4 col-form-label">Tanggal</label>
                            <div class="col-8">
                                <input type="date" class="form-control @error('created_at') is-invalid @enderror"
                                    id="created_at" name="created_at" value="{{ old('created_at', $purchase->created_at->format('Y-m-d')) }}"
                                    @readonly(Auth::user()->role != 'super-admin')>
                                @error('created_at')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="vendor_id" class="col-4 col-form-label">Vendor</label>
                            <div class="col-8">
                                <select class="form-control @error('vendor_id') is-invalid @enderror" name="vendor_id"
                                    id="vendor_id">
                                    <option value="">--Pilih Vendor--</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" @selected(old('vendor_id', $purchase->vendor_id) == $vendor->id)>
                                            {{ $vendor->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vendor_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="no_so" class="col-4 col-form-label">No. SO</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="text" class="form-control @error('no_so') is-invalid @enderror"
                                        id="no_so" name="no_so" value="{{ old('no_so', $purchase->no_so) }}"
                                        required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
                                @error('no_so')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="volume" class="col-4 col-form-label">Volume</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="number" class="form-control @error('volume') is-invalid @enderror"
                                        id="volume" name="volume" value="{{ old('volume', $purchase->volume) }}"
                                        required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">&ell;</span>
                                    </div>
                                </div>
                                @error('volume')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="total_bayar" class="col-4 col-form-label">Total Bayar</label>
                            <div class="col-8">
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
                        </div>

                        <div class="form-group row">
                            <label for="harga_per_liter" class="col-4 col-form-label">Harga per Liter</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control" id="harga_per_liter" name="harga_per_liter"
                                        value="{{ old('harga_per_liter', $purchase->harga_per_liter) }}" readonly>
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
            //calculate harga_per_liter per liter
            //total bayar / volume
            $('#volume, #total_bayar').on('input', function() {
                let total_bayar = $('#total_bayar').val() * 1;
                let volume = $('#volume').val() * 1;
                let harga_per_liter = total_bayar / volume;
                $('#harga_per_liter').val(harga_per_liter);
            });
        });
    </script>
@endpush
