@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Laporan Laba Bersih</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Laba Bersih</li>
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
                        <div class="col-6 col-lg-3 d-flex justify-content-between align-items-center">
                            @if (Auth::user()->role != 'admin')
                                <select id="shop_id" name="shop_id" class="form-control mr-2">
                                    <option value="" disabled>--Pilih Pertashop--</option>
                                    @foreach ($shops as $s)
                                        <option value="{{ $s->id }}" @selected(Request::query('shop_id') == $s->id)>
                                            {{ $s->kode . ' ' . $s->nama }}</option>
                                    @endforeach
                                </select>
                            @endif

                        </div>

                        @if (Auth::user()->role == 'admin' || Auth::user()->role == 'super-admin')
                            <div class="col-md-3 d-flex justify-content-end order-first order-md-last mb-2 mb-md-0">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAdd"><i
                                        class="fa fa-plus mr-2"></i>Tambah</button>
                            </div>
                        @endif
                    </div>


                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">Bulan</th>
                                    <th class="align-middle text-center">Laba Kotor</th>
                                    <th class="align-middle text-center">Total Biaya</th>
                                    <th class="align-middle text-center">Alokasi Modal</th>
                                    <th class="align-middle text-center">Laba Bersih Dibagi</th>
                                    <th class="align-middle text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($labaBersih as $laba)
                                    <tr>
                                        <td class="align-middle">{{ $laba->bulan }}</td>
                                        <td class="align-middle">
                                            <div class="d-flex justify-content-between currency">
                                                {{ $laba->laba_kotor }}
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex justify-content-between currency">
                                                {{ $laba->total_biaya }}
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex justify-content-between currency">
                                                {{ $laba->alokasi_modal }}
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex justify-content-between currency">
                                                {{ $laba->laba_bersih_dibagi }}
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <a class="btn btn-sm btn-link"
                                                href="{{ route('laba-bersih.edit', ['shop_id' => $laba->shop_id, 'year_month' => $laba->created_at->format('Y-m')]) }}">
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

    <!-- Modal -->
    <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buat Laporan Laba Bersih</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('laba-bersih.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="year_month" class="col-4 col-form-label">Bulan</label>
                            <div class="col-8">
                                <input type="month" class="form-control @error('year_month') is-invalid @enderror"
                                    id="year_month" name="year_month" value="{{ old('year_month', date('Y-m')) }}">
                                @error('year_month')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        $(document).ready(function() {
            $('#shop_id').on('change', function() {
                const shop_id = $('#shop_id').val();
                window.location.replace(
                    `{{ route('laba-bersih.index') }}?shop_id=${shop_id}`
                );
            });

            //data table
            $('#table').DataTable();
        });
    </script>
@endpush
