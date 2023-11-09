@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Laba Bersih {{ $report->created_at->monthName . ' ' . $report->created_at->year }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        {{-- <li class="breadcrumb-item"><a href="/laporan">Laporan</a></li> --}}
                        <li class="breadcrumb-item"><a href="{{ route('laba-bersih.index') }}">Laba Bersih</a></li>
                        <li class="breadcrumb-item active">Detail</li>
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
                        <h3 class="card-title">{{ $shop->kode . ' ' . $shop->nama }}</h3>
                    </div>

                </div>
                <div class="card-body">

                    <div class="laba-kotor container-fluid text-sm" id="section-to-print">
                        <div class="pb-2 mb-2 d-flex justify-content-center" style="border-bottom: 4px solid #000">
                            <h3 class="card-title font-weight-bold text-uppercase text-center">
                                Perhitungan Laba Bersih
                                {{ $report->created_at->startOfMonth()->format('d') }} s/d
                                {{ $report->created_at->endOfMonth()->format('d') . ' ' . $report->created_at->monthName . ' ' . $report->created_at->year }}
                                <br>PERTASHOP {{ $shop->kode }} {{ $shop->alamat }} <br> {{ $shop->corporation->nama }}
                            </h3>
                        </div>


                        <div class="table-responsive">

                            <table style="width: 100%">
                                <tr>
                                    <td class="font-weight-bold">PENDAPATAN</td>
                                    <td width="50"></td>
                                    <td width="150"></td>
                                    <td width="20"></td>
                                    <td width="150"></td>
                                    <td width="20"></td>
                                </tr>
                                <tr>
                                    <td><span style="width: 20px; display: inline-block">1.</span>
                                        <span>LABA KOTOR</span>
                                    </td>
                                    <td>=</td>
                                    <td class="line-bottom d-flex justify-content-between">
                                        <span>Rp</span>
                                        <span class="number">{{ $report->laba_kotor }}</span>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="d-flex justify-content-between font-weight-bold">
                                        <span>Rp</span>
                                        <span class="number">{{ $report->laba_kotor }}</span>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">PENGELURARAN</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                @foreach ($spendingByAdmin as $spending)
                                    <tr>
                                        <td><span style="width: 20px; display: inline-block">{{ $loop->iteration }}.</span>
                                            <span class="text-uppercase">{{ $spending->keterangan }}</span>
                                        </td>
                                        <td>=</td>
                                        <td class="d-flex justify-content-between currency">
                                            {{ $spending->jumlah }}
                                        </td>
                                        <td class="px-2"></td>
                                        <td class=" d-flex align-items-center">
                                            <button class="btn btn-link px-1" data-toggle="modal"
                                                data-target="#spendingEdit{{ $spending->id }}"><i
                                                    class="fas fa-edit"></i></button>
                                            <button class="btn btn-link px-1 text-danger spending-delete"
                                                data-id="{{ $spending->id }}"><i class="fas fa-trash"></i></button>

                                            <!-- Modal Pengeluaran -->
                                            <div class="modal fade" id="spendingEdit{{ $spending->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
                                                data-backdrop="static">
                                                <div class="modal-dialog modal-md" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Pengeluaran</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('spendings.update', $spending->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">

                                                                <div class="form-group">
                                                                    <label for="keterangan">Keterangan</label>
                                                                    <input type="text"
                                                                        class="form-control @error('keterangan') is-invalid @enderror"
                                                                        id="keterangan" name="keterangan"
                                                                        value="{{ old('keterangan', $spending->keterangan) }}"
                                                                        required>
                                                                    @error('keterangan')
                                                                        <div class="invalid-feedback d-block">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror

                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="jumlah">Jumlah</label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">Rp</span>
                                                                        </div>
                                                                        <input type="number"
                                                                            class="form-control @error('jumlah') is-invalid @enderror"
                                                                            id="jumlah" name="jumlah"
                                                                            value="{{ old('jumlah', $spending->jumlah) }}"
                                                                            required>
                                                                    </div>
                                                                    @error('jumlah')
                                                                        <div class="invalid-feedback d-block">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror

                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Cancel</button>
                                                                <button type="submit"
                                                                    class="btn btn-primary btn-save-pengeluaran">Update</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @php
                                    $no = $spendingByAdmin->count() + 1;
                                @endphp
                                @foreach ($spendingByOperator as $spending)
                                    <tr>
                                        <td><span style="width: 20px; display: inline-block">{{ $no++ }}.</span>
                                            <span class="text-uppercase">{{ $spending['pengeluaran'] }}</span>
                                        </td>
                                        <td>=</td>
                                        <td
                                            class="d-flex justify-content-between currency @if ($loop->last) line-bottom @endif">
                                            {{ $spending['jumlah'] }}
                                        </td>
                                        <td class="px-2">
                                            @if ($loop->last)
                                                +
                                            @endif
                                        </td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>
                                        <button class="btn btn-link px-0" data-toggle="modal" data-target="#spendingAdd"><i
                                                class="fas fa-plus mr-2"></i>Tambah</button>
                                    </td>
                                    <td></td>
                                    <td class="d-flex justify-content-between">
                                        <span>Rp</span>
                                        <span class="number">{{ $report->total_biaya }}</span>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                            </table>
                            <br>
                            <table style="width: 100%">
                                <tr class="font-weight-bold">
                                    <td class="text-right">Total Laba Kotor</td>
                                    <td width="20" class="px-2">=</td>
                                    <td width="150">
                                        <div class="d-flex justify-content-between">
                                            <span>Rp</span>
                                            <span class="number">{{ $report['laba_kotor'] }}</span>
                                        </div>

                                    </td>
                                    <td width="20"></td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td class="text-right">Total Biaya</td>
                                    <td width="20" class="px-2">=</td>
                                    <td width="150">
                                        <div class="line-bottom d-flex justify-content-between">
                                            <span>Rp</span>
                                            <span class="number">{{ $report->total_biaya }}</span>
                                        </div>

                                    </td>
                                    <td width="20" class="px-2">-</td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td class="text-right">Laba Bersih</td>
                                    <td width="20" class="px-2">=</td>
                                    <td width="150">
                                        <div class="d-flex justify-content-between">
                                            <span>Rp</span>
                                            <span class="number">{{ $report['laba_bersih'] }}</span>
                                        </div>

                                    </td>
                                    <td width="20"></td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td class="text-right">
                                        <button class="btn btn-link px-1" data-toggle="modal"
                                            data-target="#persenAlokasiEdit"><i class="fas fa-edit"></i></button>
                                        Alokasi Modal Dasar dari
                                        <span class="number-float">{{ $report['persentase_alokasi_modal'] }}</span>%
                                        Profit
                                    </td>
                                    <td width="20" class="px-2">=</td>
                                    <td width="150">
                                        <div class="line-bottom d-flex justify-content-between">
                                            <span>Rp</span>
                                            <span class="number">{{ $report['alokasi_modal'] }}</span>
                                        </div>

                                    </td>
                                    <td width="20" class="px-2">+</td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td class="text-right">Saldo Laba Bersih Dibagi</td>
                                    <td width="20" class="px-2">=</td>
                                    <td width="150">
                                        <div class="d-flex justify-content-between">
                                            <span>Rp</span>
                                            <span class="number">{{ $report['laba_bersih_dibagi'] }}</span>
                                        </div>

                                    </td>
                                    <td width="20"></td>
                                </tr>
                            </table>
                            <br>
                            <table style="min-width: 50%">
                                <tr class="font-weight-bold">
                                    <td>Pembagian Laba Bersih</td>
                                </tr>
                                @foreach ($shop->investors->sortByDesc('pivot.persentase') as $investor)
                                    <tr>
                                        <td class="d-flex justify-content-between">
                                            <span>
                                                <span
                                                    style="width: 20px; display: inline-block">{{ $loop->iteration }}.</span>
                                                <span>{{ $investor->name }}</span>
                                            </span>
                                            <span class="ml-1">
                                                <span class="number">{{ $investor->pivot->persentase }}</span>%
                                            </span>
                                        </td>

                                        <td width="20" class="px-2">=</td>
                                        <td width="150">
                                            <div
                                                class="@if ($loop->last) line-bottom @endif d-flex justify-content-between">
                                                <span>Rp</span>
                                                <span
                                                    class="number">{{ ($report['laba_bersih_dibagi'] * $investor->pivot->persentase) / 100 }}</span>
                                            </div>

                                        </td>
                                        <td width="20" class="px-2">
                                            @if ($loop->last)
                                                +
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right"></td>
                                    <td width="20" class="px-2"></td>
                                    <td width="150">
                                        <div class="d-flex justify-content-between">
                                            <span>Rp</span>
                                            <span class="number">{{ $report['laba_bersih_dibagi'] }}</span>
                                        </div>

                                    </td>
                                </tr>
                            </table>
                            <br>
                            <table style="width: 100%">
                                <tr class="font-weight-bold">
                                    <td>Catatan</td>
                                </tr>
                                <tr>Bila disetujui maka laba akan segera ditransferkan ke rekening</tr>
                                @foreach ($shop->investors->sortByDesc('pivot.persentase') as $investor)
                                    <tr>
                                        <td class="d-flex justify-content-between">
                                            <span>
                                                <span
                                                    style="width: 20px; display: inline-block">{{ $loop->iteration }}.</span>
                                                <span>{{ $investor->pivot->nama_bank }}
                                                    {{ $investor->pivot->no_rekening }} a/n
                                                    {{ $investor->pivot->pemilik_rekening }}</span>
                                            </span>
                                        </td>

                                        <td width="150">
                                            <div class="d-flex justify-content-between">
                                                <span>Rp</span>
                                                <span
                                                    class="number">{{ ($report['laba_bersih_dibagi'] * $investor->pivot->persentase) / 100 }}</span>
                                            </div>

                                        </td>

                                    </tr>
                                @endforeach
                            </table>

                        </div>
                        <div class="ttd pt-4" style="display: none">
                            <p class="text-right px-3">Banyumas,
                                {{ $report->created_at->endOfMonth()->format('d') . ' ' . $report->created_at->monthName . ' ' . $report->created_at->year }}
                            <p>
                            <div class="row">
                                <div class="col-sm-9">
                                    <div class="text-center">Disetujui Oleh,</div>
                                    <div class="row">
                                        @foreach ($shop->investors->sortByDesc('pivot.persentase') as $investor)
                                            <div class="col-sm mb-2 px-1">
                                                <div class="text-center">
                                                    <p style="margin-top: 3cm">{{ $investor->name }}</p>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                                <div class="col-sm-3 mb-2 px-1">
                                    <div class="text-center ">
                                        <div>Dibuat Oleh,</div>
                                        <p style="margin-top: 3cm">{{ Auth::user()->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-footer">
                    <div class="text-right">
                        @if (Auth::user()->role != 'investor')
                            <button class="btn btn-danger mr-2 btn-delete">
                                <i class="fas fa-trash mr-2 "></i>
                                <span>Hapus</span>
                            </button>
                        @endif

                        <button class="btn btn-warning" onclick="window.print()">
                            <i class="fas fa-print mr-2 "></i>
                            <span>Cetak</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>


    </section>


    <!-- Modal Pengeluaran -->
    <div class="modal fade" id="spendingAdd" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Pengeluaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('spendings.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">

                        <input type="hidden" name="shop_id" value="{{ $report->shop_id }}">
                        <input type="hidden" name="category_id" value="99">
                        <input type="hidden" name="created_at" value="{{ $report->created_at }}">


                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control @error('keterangan') is-invalid @enderror"
                                id="keterangan" name="keterangan" value="{{ old('keterangan') }}" required>
                            @error('keterangan')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control @error('jumlah') is-invalid @enderror"
                                    id="jumlah" name="jumlah" value="{{ old('jumlah') }}" required>
                            </div>
                            @error('jumlah')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-save-pengeluaran">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="persenAlokasiEdit" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Persentase Alokasi Modal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('laba-bersih.update', $report->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="persentase_alokasi_modal">Persen Alokasi
                                Modal</label>
                            <div class="input-group">
                                <input type="number" step="any"
                                    class="form-control @error('persentase_alokasi_modal') is-invalid @enderror"
                                    id="persentase_alokasi_modal" name="persentase_alokasi_modal"
                                    value="{{ old('persentase_alokasi_modal', $report->persentase_alokasi_modal) }}"
                                    required>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            @error('persentase_alokasi_modal')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection


@push('style')
    <style>
        .dot-fill {
            width: 200,
                overflow: hidden;
            white-space: nowrap;
        }

        .dot-fill:after {
            content: "......................................................................................................................................................................................................"
        }

        .line-bottom {
            border-bottom: 2px solid #000;
        }

        @media print {
            body {
                visibility: hidden;
            }

            .btn-link {
                display: none
            }

            .ttd {
                display: block !important;
            }

            #section-to-print {
                visibility: visible;
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            $('.btn-alokasi').on('click', function() {
                $('#modal_laba_kotor').modal('show');
            });

            $('.btn-delete').on('click', function() {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Laporan Laba Bersih akan dihapus secara permanen!",
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
                            url: "{{ route('laba-bersih.destroy', $report->id) }}",
                            success: function(response) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then((result) => {
                                    window.location.replace(
                                        "{{ route('laba-bersih.index', ['shop_id' => $report->shop_id]) }}"
                                    );
                                });
                            }
                        });
                    }
                });
            });

            $('.spending-delete').on('click', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Pengeluaran akan dihapus!",
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
                            url: "{{ route('spendings.index') }}" + "/" + id,
                            success: function(response) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then((result) => {
                                    window.location.reload();
                                });
                            }
                        });
                    }
                });
            });
        })
    </script>
@endpush
