@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Percobaan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('test-pumps.index') }}">Percobaan</a></li>
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
                        <h3 class="card-title">Edit Percobaan</h3>
                    </div>

                </div>
                <form id="insertForm" action="{{ route('test-pumps.update', $testPump->id) }}" method="POST"
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
                                        value="{{ old('date', $testPump->created_at->format('Y-m-d')) }}" required>
                                    @error('date')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="time">Jam</label>
                                    <input type="time" class="form-control @error('time') is-invalid @enderror"
                                        id="time" name="time"
                                        value="{{ old('time', $testPump->created_at->format('H:i')) }}" required>
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
                                        <option value="{{ $shop->id }}" @selected($shop->id == old('shop_id', $testPump->shop_id))>
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
                                    value="{{ old('totalisator_awal', $testPump->totalisator_awal) }}" readonly required>
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
                                    value="{{ old('totalisator_akhir', $testPump->totalisator_akhir) }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">&ell;</span>
                                </div>
                            </div>
                            @error('totalisator_akhir')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="jumlah">Jumlah</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="jumlah" name="jumlah"
                                    value="{{ old('jumlah', $testPump->jumlah) }}" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">&ell;</span>
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
        function getData() {
            var shop_id = $('select[name=shop_id]').val();
            var operator_id = @json(old('operator_id', $testPump->operator_id));

            @if (Auth::user()->role == 'operator')
                shop_id = @json(Auth::user()->operator->shop_id);
            @endif

            var options = `<option value=''>--Pilih Operator--</option>`;
            $('select[name=operator_id]').html(options);

            $('input[name=totalisator_awal]').val(0)
            if (shop_id) {
                $.ajax({
                    url: "{{ route('test-pumps.create') }}",
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

            var jumlah = 0;
            if (totalisator_akhir > totalisator_awal) {
                jumlah = totalisator_akhir - totalisator_awal
            }

            $("#jumlah").val(jumlah.toFixed(3));

        }

        $(document).ready(function() {
            calculateValues();
            $("#totalisator_akhir, #stik_akhir, #shop_id").on("input", calculateValues);
            getData();

            $('select[name=shop_id]').on('change', getData);

        });
    </script>
@endpush
