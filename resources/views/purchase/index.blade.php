@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pembelian</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Pembelian</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="row justify-content-between align-items-center">
                        <div
                            class="{{ Auth::user()->role == 'super-admin' ? 'col-lg-6' : 'col-6 col-lg-3' }} d-flex justify-content-between align-items-center">
                            @if (Auth::user()->role == 'super-admin')
                                <select id="shop_id" name="shop_id" class="form-control mr-2">
                                    <option value="" disabled>--Pilih Pertashop--</option>
                                    @foreach ($shops as $s)
                                        <option value="{{ $s->id }}" @selected(Request::query('shop_id') == $s->id)>
                                            {{ $s->kode . ' ' . $s->nama }}</option>
                                    @endforeach
                                </select>
                            @endif
                            <select id="year_month" name="year_month" class="form-control">
                                <option value="" disabled>--Pilih Bulan--</option>
                                @php
                                    $currentYear = date('Y');
                                    $currentMonth = date('n');
                                @endphp
                                @for ($tahun = $currentYear; $tahun >= 2021; $tahun--)
                                    @php
                                        $lastMonth = $tahun == $currentYear ? $currentMonth : 12;
                                    @endphp
                                    @for ($bulan = $lastMonth; $bulan >= 1; $bulan--)
                                        @php
                                            $date = Carbon\Carbon::create($tahun, $bulan, 1);
                                            $value = $date->format('Y-m');
                                            $label = $date->monthName . ' ' . $date->year;
                                        @endphp
                                        <option value="{{ $value }}" @selected(Request::query('year_month') == $value)>
                                            {{ $label }}</option>
                                    @endfor
                                @endfor
                            </select>
                        </div>

                        @if (Auth::user()->role == 'admin' || Auth::user()->role == 'super-admin')
                            <div class="col-md-3 d-flex justify-content-end order-first order-md-last mb-2 mb-md-0">
                                <a href="{{ route('purchases.create', Auth::user()->role == 'super-admin' ? ['shop_id' => Request::query('shop_id', 1)] : null) }}"
                                    class="btn btn-primary"><i class="fa fa-plus mr-2"></i>Tambah</a>
                            </div>
                        @endif
                    </div>

                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th class="text-center">Tanggal Order</th>
                                    <th class="text-center text-nowrap">No. SO</th>
                                    <th class="text-center">Vendor</th>
                                    <th class="text-center">Vol. Order (&ell;)</th>
                                    <th class="text-center">Harga / (&ell;) </th>
                                    <th class="text-center">Total Bayar</th>
                                    <th class="text-center">Vol. Diterima (&ell;)</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchases as $purchase)
                                    <tr>
                                        <td class="text-nowrap">{{ $purchase->tanggal }}</td>
                                        <td>{{ $purchase->no_so }}</td>
                                        <td class="text-nowrap">{{ $purchase->vendor->nama }}</td>
                                        <td class="text-right number">{{ $purchase->volume }}</td>
                                        <td class="text-right currency-decimal">{{ $purchase->harga_per_liter }}</td>
                                        <td class="text-right currency">{{ $purchase->total_bayar }}</td>
                                        <td class="text-right">
                                            @if ($purchase->incomings->sum('volume') > 0)
                                                <button class="btn text-success btn-link" data-toggle="modal"
                                                    data-target="#modalPenerimaan{{ $purchase->id }}">{{ $purchase->incomings->sum('volume') }}</button>
                                                <div class="modal fade" id="modalPenerimaan{{ $purchase->id }}"
                                                    tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Penerimaan</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                @foreach ($purchase->incomings as $incoming)
                                                                    <table class="table">
                                                                        {{-- <tr>
                                                                            <td class="text-left">No. SO</td>
                                                                            <td>{{ $incoming->purchase->no_so }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-left">Vendor Order</td>
                                                                            <td>{{ $incoming->purchase->vendor->nama }}
                                                                            </td>
                                                                        </tr> --}}
                                                                        <tr>
                                                                            <td class="text-left">Tanggal</td>
                                                                            <td>{{ $incoming->report->created_at->format('d-m-Y') }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-left">Vendor Pengirim</td>
                                                                            <td>{{ $incoming->vendor->nama }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-left">Sopir</td>
                                                                            <td>{{ $incoming->sopir }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-left">No. Polisi</td>
                                                                            <td>{{ $incoming->no_polisi }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-left">Volume Diterima</td>
                                                                            <td><span
                                                                                    class="number-float">{{ $incoming->volume }}</span>
                                                                                &ell;
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-left">Stik Sebelum Curah</td>
                                                                            <td><span
                                                                                    class="number-float">{{ $incoming->stik_sebelum_curah }}</span>
                                                                                cm (<span
                                                                                    class="number-float">{{ $incoming->stok_sebelum_curah }}</span>
                                                                                &ell;
                                                                                )</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-left">Stik Setelah Curah</td>
                                                                            <td><span
                                                                                    class="number-float">{{ $incoming->stik_setelah_curah }}</span>
                                                                                cm (<span
                                                                                    class="number-float">{{ $incoming->stok_setelah_curah }}</span>
                                                                                &ell;
                                                                                )</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-left">Penerimaan Real</td>
                                                                            <td><span
                                                                                    class="number-float">{{ $incoming->penerimaan_real }}</span>
                                                                                &ell;
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                @endforeach

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <button class="btn text-warning btn-link">0</button>
                                            @endif
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <a href="{{ route('purchases.edit', $purchase->id) }}"
                                                class="btn btn-sm btn-link"><i class="fas fa-edit"></i></a>
                                            <button class="btn btn-sm text-danger btn-link btn-delete"
                                                data-id="{{ $purchase->id }}"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('script')
    <script>
        $(document).ready(function() {

            $('.data-table').dataTable()

            @if (Auth::user()->role == 'super-admin')
                $('#shop_id, #year_month').on('change', function() {
                    const shop_id = $('#shop_id').val();
                    const year_month = $('#year_month').val();
                    window.location.replace(
                        `{{ route('purchases.index') }}?shop_id=${shop_id}&year_month=${year_month}`
                    );
                });
            @else
                $('#year_month').on('change', function() {
                    const year_month = $('#year_month').val();
                    window.location.replace(
                        `{{ route('purchases.index') }}?year_month=${year_month}`
                    );
                });
            @endif



            $('.btn-delete').on('click', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data pembelian akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: `{{ route('purchases.index') }}/${id}`,
                            success: function(response) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then((result) => {
                                    window.location.reload()
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
