@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Profit Sharing</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Profit Sharing</li>
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
                        <div class="d-flex align-items-center">
                            @if (Auth::user()->role == 'admin')
                                <h3 class="card-title mr-2">
                                    {{ Auth::user()->admin->shop->kode . ' ' . Auth::user()->admin->shop->nama }}</h3>
                            @else
                                <select id="shop_id" name="shop_id" class="form-control mr-2">
                                    <option value="" disabled>--Pilih Pertashop--</option>
                                    @foreach ($shops as $s)
                                        <option value="{{ $s->id }}" @selected(Request::query('shop_id') == $s->id)>
                                            {{ $s->kode . ' ' . $s->nama }}</option>
                                    @endforeach
                                </select>
                            @endif

                        </div>
                        {{-- <a href="" class="btn btn-primary"><i class="fa fa-plus mr-2"></i>Tambah
                            Laporan Bulanan</a> --}}
                    </div>

                </div>
                <div class="card-body">

                    <h6 class="text-center text-uppercase font-weight-bold">RINCIAN PEMBAGIAN PROFIT SHARING PS
                        {{ $shop->kode . ' ' . $shop->nama }}</h6>

                    <div class="table-responsive">
                        <table id="table" class="table table-bordered">
                            <thead>
                                <tr class="table-warning">
                                    <th class="align-middle text-center">Bulan</th>
                                    <th class="align-middle text-center">Nilai Profit Sharing</th>
                                    <th class="align-middle text-center">Alokasi Modal</th>
                                    <th class="align-middle text-center">Sisa Keuntungan yang Dibagi</th>
                                    @foreach ($shop->investors as $investor)
                                        <th class="align-middle text-center">{{ $investor->name }}
                                            <div>(<span class="number">{{ $investor->pivot->persentase }}</span>%)</div>
                                        </th>
                                    @endforeach
                                    <th class="align-middle text-center">Return of Investment to Go</th>
                                </tr>
                            </thead>
                            <tbody>


                            </tbody>
                            <tfoot>

                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        $('#shop_id').on('change', function() {
            const shop_id = $('#shop_id').val();
            window.location.replace(
                `{{ route('profit-sharing.index') }}?shop_id=${shop_id}`
            );
        });
    </script>
@endpush
