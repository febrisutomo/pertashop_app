@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Laporan Laba Kotor</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Laba Kotor</li>
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
                            class="{{ Auth::user()->role == 'super-admin' ? 'col-6' : 'col-6 col-lg-3' }} d-flex justify-content-between align-items-center">
                            @if (Auth::user()->role != 'admin' )
                                <select id="shop_id" name="shop_id" class="form-control mr-2">
                                    <option value="" disabled>--Pilih Pertashop--</option>
                                    @foreach ($shops as $s)
                                        <option value="{{ $s->id }}" @selected(Request::query('shop_id') == $s->id)>
                                            {{ $s->kode . ' ' . $s->nama }}</option>
                                    @endforeach
                                </select>
                            @endif

                        </div>

                    </div>

                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">Bulan</th>
                                    <th class="text-center align-middle">Jumlah Pembelian</th>
                                    <th class="text-center align-middle">Jumlah Penjualan Bersih</th>
                                    <th class="text-center align-middle">Rata2 Omset Harian (&ell;)</th>
                                    <th class="text-center align-middle">Sisa Stok Akhir (&ell;)</th>
                                    <th class="text-center align-middle">Laba Kotor</th>
                                    <th class="text-center align-middle">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($labaKotors as $laba)
                                    <tr>
                                        <td class="text-left text-nowrap">{{ $laba['bulan'] }}</td>
                                        <td class="text-right currency">{{ $laba['jumlah_pembelian_rp'] }}</td>
                                        <td class="text-right currency">{{ $laba['jumlah_penjualan_bersih_rp'] }}</td>
                                        <td class="text-right number-float">{{ $laba['rata_rata_omset_harian'] }}</td>
                                        <td class="text-right number-float">{{ $laba['sisa_stok_akhir'] }}</td>
                                        <td class="text-right currency">{{ $laba['laba_kotor'] }}</td>
                                        <td class="align-middle text-center">
                                            <a class="btn btn-sm btn-link"
                                                href="{{ route('laba-kotor.edit', ['shop_id' => $laba['shop_id'], 'year_month' => $laba['bulan']]) }}">
                                                <i class="fas fa-list"></i>
                                            </a>
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

            $('#table').dataTable();
            $('#shop_id').on('change', function() {
                const shop_id = $('#shop_id').val();
                window.location.replace(
                    `{{ route('laba-kotor.index') }}?shop_id=${shop_id}`
                );
            });


            $('#table').on('click', '.btn-delete', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data laporan Laba akan dihapus secara permanen!",
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
                            url: "{{ route('laba-kotor.index') }}" + "/" + id,
                            success: function(response) {
                                dataTable.ajax.reload();
                                Swal.fire(
                                    'Terhapus!',
                                    response.message,
                                    'success'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
