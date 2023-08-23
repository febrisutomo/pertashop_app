@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tambah Kedatangan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('incomings.index') }}">Kedatangan</a></li>
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
                        <h3 class="card-title">Tambah Kedatangan</h3>
                    </div>

                </div>
                <form id="insertForm" action="{{ route('incomings.store') }}" method="POST" class="needs-validation"
                    novalidate>
                    @csrf
                    <div class="card-body">
                        @if (Auth::user()->role != 'operator')
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="date">Tanggal</label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror"
                                        id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                                    @error('date')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="time">Jam</label>
                                    <input type="time" class="form-control @error('time') is-invalid @enderror"
                                        id="time" name="time" value="{{ old('time', date('H:i')) }}" required>
                                    @error('time')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
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
                            </div>

                            <div class="form-group">
                                <label for="operator_id">Operator</label>
                                <select name="operator_id" id="operator_id"
                                    class="form-control @error('operator_id') is-invalid @enderror">
                                    <option value="">--Pilih Operator--</option>
                                </select>
                                @error('operator_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="purchase_id">Pembelian</label>
                            <select name="purchase_id" id="purchase_id"
                                class="form-control @error('purchase_id') is-invalid @enderror">
                                <option value="">--Pilih Pembelian--</option>
                            </select>
                            @error('purchase_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="jumlah">Jumlah</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('jumlah') is-invalid @enderror"
                                    id="jumlah" name="jumlah" value="{{ old('jumlah') }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">&ell;</span>
                                </div>
                            </div>
                            @error('jumlah')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="stik_awal">Stik Awal</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('stik_awal') is-invalid @enderror"
                                    id="stik_awal" name="stik_awal" value="{{ old('stik_awal') }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">cm</span>
                                </div>
                            </div>
                            @error('stik_awal')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="stik_akhir">Stik Akhir</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('stik_akhir') is-invalid @enderror"
                                    id="stik_akhir" name="stik_akhir" value="{{ old('stik_akhir') }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">cm</span>
                                </div>
                            </div>
                            @error('stik_akhir')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
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
        function getData() {
            var shop_id = $('select[name=shop_id]').val();
            
            @if (Auth::user()->role == 'operator')
                shop_id = @json(Auth::user()->operator->shop_id);
            @endif

            var operator_id = @json(old('operator_id'));
            var purchase_id = @json(old('purchase_id'));

            if (shop_id) {
                $.ajax({
                    url: "{{ route('incomings.create') }}",
                    method: 'GET',
                    data: {
                        shop_id
                    },
                    success: function(data) {
                        var operator_options = `<option value=''>--Pilih Operator--</option>`;
                        data.operators.forEach(operator => {
                            operator_options +=
                                `<option value='${operator.id}' ${operator.id == operator_id ? 'selected' : ''}>${operator.user.name}</option>`
                        });

                        $('select[name=operator_id]').html(operator_options);

                        var purchase_options = `<option value=''>--Pilih Pembelian--</option>`;
                        data.purchases.forEach(purchase => {
                            purchase_options +=
                                `<option value='${purchase.id}' ${purchase.id == purchase_id ? 'selected' : ''}>${purchase.id} - ${purchase.supplier.nama} (${parseInt(purchase.jumlah)/1000}KL)</option>`
                        });

                        $('select[name=purchase_id]').html(purchase_options);

                        $('input[name=totalisator_awal]').val(data.totalisator_awal)

                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        }
        $(document).ready(function() {
            getData()
            $('select[name=shop_id]').on('change', getData)
        });
    </script>
@endpush
