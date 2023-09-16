@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Rekap Modal</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Rekap Modal</li>
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

                        <div class="col-md-3 d-flex justify-content-end order-first order-md-last mb-2 mb-md-0">
                            <button class="btn btn-warning mr-2" onclick="window.print()">
                                <i class="fas fa-print mr-2 "></i>
                                <span>Cetak</span>
                            </button>
                            @if (Auth::user()->role == 'admin' || Auth::user()->role == 'super-admin')
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAdd"><i
                                        class="fa fa-plus mr-2"></i>Tambah</button>
                            @endif
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="print-section">
                        <h6 class="text-center text-uppercase font-weight-bold">REKAPITULASI NILAI MODAL PS
                            {{ $shop->kode . ' ' . $shop->nama }}</h6>

                        <div class="table-responsive">
                            <table id="table" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="align-middle text-center table-warning">No</th>
                                        <th class="align-middle text-center table-warning">Bulan</th>
                                        <th class="align-middle text-center table-warning">Nilai Modal Awal</th>
                                        <th class="align-middle text-center table-danger">Penyusutan Karena Rugi</th>
                                        <th class="align-middle text-center table-danger">Penyusutan Karena Pajak & Biaya
                                            Bank
                                        </th>
                                        <th class="align-middle text-center table-success">Penambahan dari Alokasi
                                            Keuntungan
                                        </th>
                                        <th class="align-middle text-center table-success">Penambahan dari Bunga Bank</th>
                                        <th class="align-middle text-center table-warning">Posisi Akhir Modal</th>
                                        <th class="align-middle text-center table-warning aksi">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-info">
                                        <th colspan="7" class="align-middle text-right">Saldo Awal Modal</th>
                                        <th class="align-middle text-right currency">
                                            {{ $shop->modal_awal }}</th>
                                        <th class="aksi"></th>
                                    </tr>
                                    @foreach ($modals as $modal)
                                        <tr>
                                            <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                            <td class="align-middle text-nowrap">{{ $modal['bulan'] }}</td>
                                            <td class="align-middle text-right currency">{{ $modal->modal_awal }}
                                            </td>
                                            <td class="align-middle text-right text-danger currency">{{ $modal->rugi }}
                                            </td>
                                            <td class="align-middle text-right text-danger currency">
                                                {{ $modal->pajak_bank }}
                                            </td>
                                            <td class="align-middle text-right currency">
                                                {{ $modal->alokasi_keuntungan }}
                                            </td>
                                            <td class="align-middle text-right currency">{{ $modal->bunga_bank }}
                                            </td>
                                            <td class="align-middle text-right currency">{{ $modal->modal_akhir }}
                                            </td>
                                            <td class="align-middle text-center aksi">
                                                <a class="btn btn-sm btn-link"
                                                    href="{{ route('rekap-modal.edit', ['shop_id' => $modal->shop_id, 'year_month' => $modal->created_at->format('Y-m')]) }}">
                                                    <i class="fas fa-list"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="table-info">
                                        <th colspan="7" class="align-middle text-right">Saldo Akhir Modal</th>
                                        <th class="align-middle text-right currency">
                                            {{ $modals->last()?->modal_akhir }}
                                        </th>
                                        <th class="aksi"></th>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        @if ($modals->count() > 0)
                            <div class="ttd pt-3" style="display: none">
                                <div class="d-flex justify-content-end">
                                    <div class="text-center px-4">
                                        <div>Banyumas,
                                            {{ $modals->last()?->created_at->endOfMonth()->format('d') .' ' .$modals->last()->created_at->monthName .' ' .$modals->last()->created_at->year }}
                                        </div>
                                        <div style="margin-bottom: 3cm">Dibuat Oleh,</div>
                                        <div>{{ Auth::user()->name }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

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
                    <h5 class="modal-title">Buat Rekap Modal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('rekap-modal.store') }}" method="POST">
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
        $('#shop_id').on('change', function() {
            const shop_id = $('#shop_id').val();
            window.location.replace(
                `{{ route('rekap-modal.index') }}?shop_id=${shop_id}`
            );
        });
    </script>
@endpush

@push('style')
    <style>
        @media print {
            body {
                visibility: hidden;
            }

            .ttd {
                display: block !important;
            }

            .aksi {
                display: none !important;
            }

            .print-section {
                visibility: visible;
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>
@endpush
