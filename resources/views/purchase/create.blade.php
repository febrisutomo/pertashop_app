@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tambah Pembelian</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">Pembelian</a></li>
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
                        <h3 class="card-title">Pembelian</h3>
                    </div>

                </div>
                <form id="insertForm" action="{{ route('purchases.store') }}" method="POST" class="needs-validation"
                    novalidate>
                    @csrf
                    <div class="card-body">

                        <div class="form-group row @if (Auth::user()->role != 'super-admin') d-none @endif">
                            <label for="shop_id" class="col-4 col-form-label">Pertashop</label>
                            <div class="col-8">
                                <select name="shop_id" id="shop_id"
                                    class="form-control @error('shop_id') is-invalid @enderror"
                                    @disabled(Auth::user()->role != 'super-admin')>
                                    <option value="" disabled>--Pilih Pertashop--</option>
                                    @foreach ($shops as $shop)
                                        <option value="{{ $shop->id }}" @selected(Auth::user()->role == 'super-admin' ? $shop->id == Request::query('shop_id', '1') : $shop->id == Auth::user()->shop_id)>
                                            {{ $shop->kode }} {{ $shop->nama }}</option>
                                    @endforeach
                                </select>
                                @error('shop_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="created_at" class="col-4 col-form-label">Created_at</label>
                            <div class="col-8">
                                <input type="date" class="form-control @error('created_at') is-invalid @enderror"
                                    id="created_at" name="created_at" value="{{ old('created_at', date('Y-m-d')) }}"
                                    @readonly(Auth::user()->role != 'super-admin')>
                                @error('created_at')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="supplier_id" class="col-4 col-form-label">Supplier</label>
                            <div class="col-8">
                                <select class="form-control @error('supplier_id') is-invalid @enderror" name="supplier_id"
                                    id="supplier_id">
                                    <option value="">--Pilih Supplier--</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>
                                            {{ $supplier->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="no_so" class="col-4 col-form-label">No. SO</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="text" class="form-control @error('no_so') is-invalid @enderror"
                                        id="no_so" name="no_so" value="{{ old('no_so') }}" required>
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
                                        id="volume" name="volume" value="{{ old('volume') }}" required>
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
                                        id="total_bayar" name="total_bayar" value="{{ old('total_bayar') }}" required>
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
                                        value="{{ old('harga_per_liter') }}" readonly>
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
            $('#shop_id').on('change', function() {
                const shop_id = $(this).val();
                window.location.replace(
                    `${window.location.origin}/purchases/create?shop_id=${shop_id}`
                );
            });

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
