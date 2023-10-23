@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Posisi Modal Kerja {{ $modal->created_at->monthName . ' ' . $modal->created_at->year }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        {{-- <li class="breadcrumb-item"><a href="/laporan">Laporan</a></li> --}}
                        <li class="breadcrumb-item"><a href="{{ route('rekap-modal.index') }}">Posisi Modal Kerja</a></li>
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
                                Posisi Modal Kerja Periode
                                {{ $modal->created_at->startOfMonth()->format('d') }} s/d
                                {{ $modal->created_at->endOfMonth()->format('d') . ' ' . $modal->created_at->monthName . ' ' . $modal->created_at->year }}
                                <br>PERTASHOP {{ $shop->kode }} {{ $shop->alamat }} <br> {{ $shop->corporation->nama }}
                            </h3>
                        </div>


                        <br>
                        <div class="table-responsive">

                            <h6 class="text-center font-weight-bold">POSISI MODAL KERJA</h6>
                            <table id="table" style="border: 1.5px solid black">
                                <tr style="border: 1.5px solid black">
                                    <th colspan="2" class="text-right font-italic">Saldo Awal Modal Periode Bulan
                                        Sebelumnya</th>
                                    <th width="20">:</th>
                                    <th class="d-flex justify-content-between">
                                        <span>Rp</span><span class="number">{{ $modal->modal_awal }}</span>
                                    </th>
                                    <th width="5"></th>
                                </tr>
                                <tr>
                                    <td width="20">1.</td>
                                    <td class="d-flex justify-content-between">
                                        <span>DO yang masih ada di Pertamina</span>
                                        <span>{{ $sisa_do * 1 }} &ell; <span class="px-2">x</span>Rp <span
                                                class="number">{{ $harga_beli }}</span></span>
                                    </td>
                                    <th width="20">:</th>
                                    <td class="d-flex justify-content-between">
                                        <span>Rp</span><span class="number">{{ $rupiah_sisa_do * -1 }}</span>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td width="20">2.</td>
                                    <td>
                                        Uang Di Bank Periode Bulan ini
                                    </td>
                                    <th width="20">:</th>
                                    <td class="d-flex justify-content-between">
                                        <span>Rp</span><span class="number">{{ $uang_di_bank }}</span>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td width="20">3.</td>
                                    <td>
                                        Kas Kecil di Pertashop (TUNAI)
                                    </td>
                                    <th width="20">:</th>
                                    <td class="d-flex justify-content-between">
                                        <span>Rp</span><span class="number">{{ $modal->kas_kecil * -1 }}</span>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td width="20">4.</td>
                                    <td class="d-flex justify-content-between">
                                        <span>Sisa Stok yang Masih ada Di Pertashop
                                        </span>
                                        <span>{{ $sisa_stok }} &ell; <span class="px-2">x</span>Rp <span
                                                class="number">{{ $harga_beli }}</span></span>
                                    </td>
                                    <th width="20">:</th>
                                    <td class="d-flex justify-content-between">
                                        <span>Rp</span><span class="number">{{ $rupiah_sisa_stok * -1 }}</span>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td width="20">5.</td>
                                    <td>
                                        Hasil Penjualan yang Belum Disetor di Akhir Periode (TUNAI)
                                    </td>
                                    <th width="20">:</th>
                                    <td class="d-flex justify-content-between">
                                        <span>Rp</span><span></span><span class="number">{{ $belum_disetorkan }}</span>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td width="20">6.</td>
                                    <td>
                                        Piutang
                                    </td>
                                    <th width="20">:</th>
                                    <td class="d-flex justify-content-between line-bottom">
                                        <span>Rp</span><span class="number">{{ $modal->piutang * -1 }}</span>
                                    </td>
                                    <td class="px-1">+</td>
                                </tr>
                                <tr style="border-bottom: 1.5px solid black">
                                    <td width="20"></td>
                                    <td class="text-right font-italic">
                                        A. Sub Total Saldo Akhir Modal
                                    </td>
                                    <th width="20">:</th>
                                    <td class="d-flex justify-content-between">
                                        <span>Rp</span><span class="number">{{ $modal->modal_awal }}</span>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td width="20">6.</td>
                                    <td>
                                        Bunga Bank Periode Bulan ini
                                    </td>
                                    <th width="20">:</th>
                                    <td class="d-flex justify-content-between">
                                        <span>Rp</span><span class="number">{{ $modal->bunga_bank }}</span>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td width="20">7.</td>
                                    <td>
                                        Pajak Bank Periode Bulan ini
                                    </td>
                                    <th width="20">:</th>
                                    <td class="d-flex justify-content-between text-danger">
                                        <span>Rp</span><span>(<span class="number">{{ $modal->pajak_bank }}</span>)</span>
                                    </td>
                                    <td></td>
                                </tr>
                                @if ($modal->rugi > 0)
                                    <tr>
                                        <td width="20">8.</td>
                                        <td>
                                            Penyusutan Karena Rugi bulan ini
                                        </td>
                                        <th width="20">:</th>
                                        <td class="d-flex justify-content-between text-danger">
                                            <span>Rp</span><span>(<span class="number">{{ $modal->rugi }}</span>)</span>
                                        </td>
                                        <td></td>
                                    </tr>
                                @endif

                                <tr>
                                    <td width="20">{{ $modal->rugi > 0 ? 9 : 8 }}</td>
                                    <td>
                                        Penambahan Modal dari Keuntungan bulan ini
                                    </td>
                                    <th width="20">:</th>
                                    <td class="d-flex justify-content-between line-bottom">
                                        <span>Rp</span><span class="number">{{ $modal->alokasi_keuntungan }}</span>
                                    </td>
                                    <td class="px-1">+</td>
                                </tr>
                                <tr>
                                    <td width="20"></td>
                                    <td class="text-right font-italic">
                                        B. Sub Total Penambahan Modal Modal
                                    </td>
                                    <th width="20">:</th>
                                    <td class="d-flex justify-content-between">
                                        <span>Rp</span><span class="number">{{ $modal->penambahan_modal }}</span>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="font-weight-bold" style="border-top: 1.5px solid black">
                                    <td width="20"></td>
                                    <td class="text-right">
                                        Total Saldo Akhir Modal (A+B)
                                    </td>
                                    <th width="20">:</th>
                                    <td class="d-flex justify-content-between">
                                        <span>Rp</span><span class="number">{{ $modal->modal_akhir }}</span>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>

                            <div class="ttd pt-3" style="display: none">
                                <div class="d-flex justify-content-end">
                                    <div class="text-center px-4">
                                        <div>Banyumas,
                                            {{ $modal->created_at->endOfMonth()->format('d') . ' ' . $modal->created_at->monthName . ' ' . $modal->created_at->year }}
                                        </div>
                                        <div style="margin-bottom: 3cm">Dibuat Oleh,</div>
                                        <div>{{ Auth::user()->name }}</div>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    @php
                        $labaBersih = App\Models\LabaBersih::where('shop_id', $shop->id)
                            ->whereYear('created_at', $modal->created_at->year)
                            ->whereMonth('created_at', $modal->created_at->month)
                            ->first();
                    @endphp
                    <div class="text-right">
                        @if (Auth::user()->role != 'investor')
                            @if ($labaBersih)
                                <button class="btn btn-danger mr-2 btn-delete">
                                    <i class="fas fa-trash mr-2 "></i>
                                    <span>Hapus</span>
                                </button>
                            @endif
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
                    <h5 class="modal-title">Edit Posisi Modal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('rekap-modal.update', $modal->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="kas_kecil" class="col-4 col-form-label">Kas Kecil</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" class="form-control @error('kas_kecil') is-invalid @enderror"
                                        id="kas_kecil" name="kas_kecil"
                                        value="{{ old('kas_kecil', $modal->kas_kecil) }}" required>

                                </div>
                                @error('kas_kecil')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="piutang" class="col-4 col-form-label">Piutang</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" class="form-control @error('piutang') is-invalid @enderror"
                                        id="piutang" name="piutang" value="{{ old('piutang', $modal->piutang) }}"
                                        required>

                                </div>
                                @error('piutang')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="bunga_bank" class="col-4 col-form-label">Bunga Bank</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" class="form-control @error('bunga_bank') is-invalid @enderror"
                                        id="bunga_bank" name="bunga_bank"
                                        value="{{ old('bunga_bank', $modal->bunga_bank) }}" required>
                                </div>
                                @error('bunga_bank')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pajak_bank" class="col-4 col-form-label">Pajak Bank</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" class="form-control @error('pajak_bank') is-invalid @enderror"
                                        id="pajak_bank" name="pajak_bank"
                                        value="{{ old('pajak_bank', $modal->pajak_bank) }}" required>
                                </div>
                                @error('pajak_bank')
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
        #table {
            width: 100%;
        }

        #table td,
        #table th {
            padding: 5px;
        }

        .line-bottom {
            border-bottom: 2px solid #000;
        }

        @media print {
            body {
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
        $('.btn-delete').on('click', function() {
            var id = "{{ $modal->id }}";

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data rekap modal akan dihapus secara permanen!",
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
                        url: `{{ route('rekap-modal.index') }}/${id}`,
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            }).then((result) => {
                                window.location.replace(
                                    "{{ route('rekap-modal.index') }}");
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush
