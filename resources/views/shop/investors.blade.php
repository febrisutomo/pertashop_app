@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Investor Pertashop</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('shops.index') }}">Pertashop</a></li>
                        <li class="breadcrumb-item active">Investor</li>
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
                            <h3 class="card-title mr-2">{{ $shop->kode }} {{ $shop->nama }}</h3>

                        </div>
                        <button class="btn btn-primary btn-add" data-toggle="modal" data-target="#modalAdd"><i
                                class="fa fa-plus mr-2"></i>Tambah</button>

                    </div>

                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Persentase</th>
                                    <th class="text-center">Rekening Bank</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shop->investors as $investor)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $investor->name }}</td>
                                        <td class="text-right"><span
                                                class="number">{{ $investor->pivot->persentase }}</span> %</td>
                                        <td>{{ $investor->pivot->nama_bank . ' ' . $investor->pivot->no_rekening . ' a/n ' . $investor->pivot->pemilik_rekening }}
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info btn-edit" data-toggle="modal"
                                                data-target="#modalEdit{{ $investor->id }}"><i
                                                    class="fa fa-edit"></i></button>
                                            <button class="btn btn-sm btn-danger btn-delete"
                                                data-id="{{ $investor->id }}"><i class="fa fa-trash"></i></button>

                                            <div class="modal fade" id="modalEdit{{ $investor->id }}">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Tambah Investor</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <form id="form_investor"
                                                            action="{{ route('shops.investors.update', $shop->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="form-group row">
                                                                    <label for="investor_id"
                                                                        class="col-sm-4 col-form-label">Investor</label>
                                                                    <div class="col-sm-8">
                                                                        <select name="investor_id" id="investor_id"
                                                                            class="form-control" required>
                                                                            <option value="">--Pilih Investor--
                                                                            </option>
                                                                            @foreach ($investors as $inv)
                                                                                <option value="{{ $inv->id }}"
                                                                                    @selected($inv->id == old('investor_id', $investor->id))>
                                                                                    {{ $inv->name }}</option>
                                                                            @endforeach
                                                                        </select>

                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="persentase"
                                                                        class="col-sm-4 col-form-label">Persentase</label>
                                                                    <div class="col-sm-8">
                                                                        <div class="input-group">
                                                                            <input type="number" class="form-control"
                                                                                id="persentase" name="persentase"
                                                                                value="{{ old('persentase', $investor->pivot->persentase) }}"
                                                                                required>
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text">%</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label for="nama_bank"
                                                                        class="col-sm-4 col-form-label">Nama Bank</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text"
                                                                            class="form-control @error('nama_bank') is-invalid @enderror"
                                                                            id="nama_bank" name="nama_bank"
                                                                            value="{{ old('nama_bank', $investor->pivot->nama_bank) }}"
                                                                            required>
                                                                        @error('nama_bank')
                                                                            <div class="invalid-feedback d-block">
                                                                                {{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label for="no_rekening"
                                                                        class="col-sm-4 col-form-label">No. Rekening</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text"
                                                                            class="form-control @error('no_rekening') is-invalid @enderror"
                                                                            id="no_rekening" name="no_rekening"
                                                                            value="{{ old('no_rekening', $investor->pivot->no_rekening) }}"
                                                                            required>
                                                                        @error('no_rekening')
                                                                            <div class="invalid-feedback d-block">
                                                                                {{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label for="pemilik_rekening"
                                                                        class="col-sm-4 col-form-label">a/n
                                                                        Rekening</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text"
                                                                            class="form-control @error('pemilik_rekening') is-invalid @enderror"
                                                                            id="pemilik_rekening" name="pemilik_rekening"
                                                                            value="{{ old('pemilik_rekening', $investor->pivot->pemilik_rekening) }}"
                                                                            required>
                                                                        @error('pemilik_rekening')
                                                                            <div class="invalid-feedback d-block">
                                                                                {{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>


                                                            </div>
                                                            <div class="modal-footer justify-content-right">
                                                                <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">Close</button>
                                                                <button type="submit"
                                                                    class="btn btn-primary">Simpan</button>
                                                            </div>
                                                        </form>
                                                    </div>

                                                </div>

                                            </div>
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

    <div class="modal fade" id="modalAdd">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Investor</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="form_investor" action="{{ route('shops.investors.store', $shop->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="investor_id" class="col-sm-4 col-form-label">Investor</label>
                            <div class="col-sm-8">
                                <select name="investor_id" id="investor_id" class="form-control" required>
                                    <option value="">--Pilih Investor--</option>
                                    @foreach ($investors as $investor)
                                        <option value="{{ $investor->id }}">
                                            {{ $investor->name }}</option>
                                    @endforeach
                                </select>

                                <input type="hidden" id="investor_name" class="form-control" readonly>
                                <input type="hidden" id="id" name="id">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="persentase" class="col-sm-4 col-form-label">Persentase</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="persentase" name="persentase"
                                        value="" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nama_bank" class="col-sm-4 col-form-label">Nama Bank</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('nama_bank') is-invalid @enderror"
                                    id="nama_bank" name="nama_bank" value="{{ old('nama_bank') }}" required>
                                @error('nama_bank')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="no_rekening" class="col-sm-4 col-form-label">No. Rekening</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('no_rekening') is-invalid @enderror"
                                    id="no_rekening" name="no_rekening" value="{{ old('no_rekening') }}" required>
                                @error('no_rekening')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pemilik_rekening" class="col-sm-4 col-form-label">a/n Rekening</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('pemilik_rekening') is-invalid @enderror"
                                    id="pemilik_rekening" name="pemilik_rekening" value="{{ old('pemilik_rekening') }}"
                                    required>
                                @error('pemilik_rekening')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer justify-content-right">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>

        </div>

    </div>
@endsection


@push('script')
    <script>
        $(document).ready(function() {


            //delete investor
            $('.table').on('click', '.btn-delete', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data investor akan dihapus!",
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
                            url: "{{ route('shops.investors.destroy', $shop->id) }}",
                            data: {
                                id
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then((result) => {
                                    location.reload();
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
