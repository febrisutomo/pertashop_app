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
                                <tr>
                                    <td><span style="width: 20px; display: inline-block">1.</span>
                                        <span class="text-uppercase">Gaji Operator</span>
                                    </td>
                                    <td>=</td>
                                    <td class="d-flex justify-content-between">
                                        <span>Rp</span>
                                        <span class="number">{{ $report->gaji_operator }}</span>
                                    </td>
                                    <td class="px-2"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><span style="width: 20px; display: inline-block">2.</span>
                                        <span class="text-uppercase">Gaji Admin</span>
                                    </td>
                                    <td>=</td>
                                    <td class="d-flex justify-content-between">
                                        <span>Rp</span>
                                        <span class="number">{{ $report->gaji_admin }}</span>
                                    </td>
                                    <td class="px-2"></td>
                                    <td></td>
                                </tr>
                                @foreach ($spendings as $spending)
                                    <tr>
                                        <td><span
                                                style="width: 20px; display: inline-block">{{ $loop->iteration + 2 }}.</span>
                                            <span class="text-uppercase">{{ $spending['pengeluaran'] }}</span>
                                        </td>
                                        <td>=</td>
                                        <td
                                            class="d-flex justify-content-between @if ($loop->last) line-bottom @endif">
                                            <span>Rp</span>
                                            <span class="number">{{ $spending['jumlah'] }}</span>
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
                                    <td></td>
                                    <td></td>
                                    <td class="d-flex justify-content-between">
                                        <span>Rp</span>
                                        <span class="number">{{ $spendings->sum('jumlah') }}</span>
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
                                            <span class="number">{{ $report['total_biaya'] }}</span>
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
                                    <td class="text-right">Alokasi Modal Dasar dari
                                        <span class="number-float">{{ $report['persentase_alokasi_modal'] }}</span>% Profit
                                    </td>
                                    <td width="20" class="px-2">=</td>
                                    <td width="150">
                                        <div class="line-bottom d-flex justify-content-between">
                                            <span>Rp</span>
                                            <span class="number-float">{{ $report['alokasi_modal'] }}</span>
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
                                @foreach ($shop->investors as $investor)
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
                                @foreach ($shop->investors as $investor)
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
                                        @foreach ($shop->investors as $investor)
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
                            <button class="btn btn-primary mr-2" data-toggle="modal" data-target="#modalEdit">
                                <i class="fas fa-edit mr-2"></i>
                                <span>Edit</span>
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

    <!-- Modal -->
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Laporan Laba Bersih</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('laba-bersih.update', $report->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="gaji_operator" class="col-4 col-form-label">Gaji Operator</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number"
                                        class="form-control @error('gaji_operator') is-invalid @enderror"
                                        id="gaji_operator" name="gaji_operator"
                                        value="{{ old('gaji_operator', $report->gaji_operator) }}" required>

                                </div>
                                @error('gaji_operator')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="gaji_admin" class="col-4 col-form-label">Gaji Admin</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" class="form-control @error('gaji_admin') is-invalid @enderror"
                                        id="gaji_admin" name="gaji_admin"
                                        value="{{ old('gaji_admin', $report->gaji_admin) }}" required>

                                </div>
                                @error('gaji_admin')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="persentase_alokasi_modal" class="col-4 col-form-label">Persen Alokasi
                                Modal</label>
                            <div class="col-8">
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

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                visibility: hidden;
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
        })
    </script>
@endpush
