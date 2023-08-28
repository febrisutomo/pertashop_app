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

                        @if (Auth::user()->role != 'operator')
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="date">Tanggal</label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror"
                                        id="date" name="date"
                                        value="{{ old('date', $sale->created_at->format('Y-m-d')) }}" required>
                                    @error('date')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="time">Jam</label>
                                    <input type="time" class="form-control @error('time') is-invalid @enderror"
                                        id="time" name="time"
                                        value="{{ old('time', $sale->created_at->format('H:i')) }}" required>
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
                                        <option value="{{ $shop->id }}" @selected($shop->id == old('shop_id', $sale->shop_id))>
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
                            <label for="volume">Volume</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="volume" name="volume" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">&ell;</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="harga">Harga per Liter</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control" id="harga"
                                    value="{{ $sale->price->harga_jual }}" readonly>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="omset">Omset</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control" id="omset" readonly>
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
        function getOperators() {
            var shop_id = $('select[name=shop_id]').val();
            var operator_id = @json(old('operator_id', $sale->operator_id));

            @if (Auth::user()->role == 'operator')
                shop_id = @json(Auth::user()->operator->shop_id);
            @endif

            var options = `<option value=''>--Pilih Operator--</option>`;
            $('select[name=operator_id]').html(options);

            $('input[name=totalisator_awal]').val(0)
            if (shop_id) {
                $.ajax({
                    url: "{{ route('sales.edit', $sale) }}",
                    method: 'GET',
                    data: {
                        shop_id
                    },
                    success: function(data) {
                        var options = `<option value=''>--Pilih Operator--</option>`;
                        data.operators.forEach(operator => {
                            options +=
                                `<option value='${operator.id}' ${operator.id == operator_id ? 'selected' : ''}>${operator.user.name}</option>`
                        });

                        $('select[name=operator_id]').html(options);

                        $('input[name=totalisator_awal]').val(data.totalisator_awal)

                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        }

        function calculateValues() {
            var totalisator_awal = parseFloat($("#totalisator_awal").val()) || 0;
            var totalisator_akhir = parseFloat($("#totalisator_akhir").val()) || 0;

            var harga = @json($harga);

            var volume = 0;
            if (totalisator_akhir > totalisator_awal) {
                volume = totalisator_akhir - totalisator_awal
            }

            $("#volume").val(volume.toFixed(3));
            $("#omset").val(formatNumber(volume * harga))

        }

        $(document).ready(function() {
            calculateValues();
            $("#totalisator_akhir, #stik_akhir, #shop_id").on("input", calculateValues);

            getOperators()
            $('select[name=shop_id]').on('change', getOperators)

            var harga = $('#harga').val()
            $('#harga').val(formatNumber(harga))

        });
    </script>
@endpush
