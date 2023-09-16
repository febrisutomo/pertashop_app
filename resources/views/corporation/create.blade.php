@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tambah Badan Usaha</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('corporations.index') }}">Badan Usaha</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
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
                        <h3 class="card-title">Badan Usaha</h3>
                    </div>

                </div>
                <form id="insertForm" action="{{ route('corporations.store') }}" method="POST" class="needs-validation"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <div class="form-group row">
                            <label for="nama" class="col-sm-4 col-form-label">Nama</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    id="nama" name="nama" value="{{ old('nama') }}">
                                @error('nama')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="no_hp" class="col-sm-4 col-form-label">No Telepon</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                    id="no_hp" name="no_hp" value="{{ old('no_hp') }}">
                                @error('no_hp')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="alamat" class="col-sm-4 col-form-label">Alamat</label>
                            <div class="col-sm-8">
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3"
                                    required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>
                        <div class="form-group row">
                            <label for="izin_dikeluarkan" class="col-sm-4 col-form-label">Izin Dikeluarkan</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control @error('izin_dikeluarkan') is-invalid @enderror"
                                    id="izin_dikeluarkan" name="izin_dikeluarkan" value="{{ old('izin_dikeluarkan') }}">
                                @error('izin_dikeluarkan')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="izin_berakhir" class="col-sm-4 col-form-label">Izin Berakhir</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control @error('izin_berakhir') is-invalid @enderror"
                                    id="izin_berakhir" name="izin_berakhir" value="{{ old('izin_berakhir') }}">
                                @error('izin_berakhir')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="documents" class="col-sm-4 col-form-label">Dokumen</label>
                            <div class="col-sm-8">
                                <div id="dynamic-form">

                                </div>

                                <div class="mb-3">
                                    <button type="button" class="btn btn-info" id="add-row"><i
                                            class="fas fa-plus"></i></button>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label for="nama_bank" class="col-sm-4 col-form-label">Nama Bank</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('nama_bank') is-invalid @enderror"
                                    id="nama_bank" name="nama_bank" value="{{ old('nama_bank') }}">
                                @error('nama_bank')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="no_rekening" class="col-sm-4 col-form-label">No. Rekening</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('no_rekening') is-invalid @enderror"
                                    id="no_rekening" name="no_rekening" value="{{ old('no_rekening') }}">
                                @error('no_rekening')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pemilik_rekening" class="col-sm-4 col-form-label">a/n Rekening</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('pemilik_rekening') is-invalid @enderror"
                                    id="pemilik_rekening" name="pemilik_rekening" value="{{ old('pemilik_rekening') }}">
                                @error('pemilik_rekening')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
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
        //ready function
        $(document).ready(function() {
            //add row
            $('#add-row').click(function() {
                let html = `
                <div class="d-flex justify-content-between form-group group-document">
                    <div class="input-group">
                        <input type="file" class="form-control" name="documents[]" required>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger remove-row"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                </div>
                `;

                $('#dynamic-form').append(html);
            });

            //remove row
            $(document).on('click', '.remove-row', function() {
                $(this).closest('.group-document').remove();
            });
        });
    </script>
@endpush
