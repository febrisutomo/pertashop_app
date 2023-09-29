@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dokumen Pertashop</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('shops.index') }}">Pertashop</a></li>
                        <li class="breadcrumb-item active">Dokumen</li>
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
                        <h3 class="card-title">{{$shop->kode}} {{$shop->nama}}</h3>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#documentAdd"><i
                                class="fas fa-plus mr-2"></i>Tambah</button>
                    </div>

                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table id="table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Dokumen</th>
                                    <th>Izin Dikeluarkan</th>
                                    <th>Izin Berakhir</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    //format tanggal
                                    function formatDate($date)
                                    {
                                        return date('d/m/Y', strtotime($date));
                                    }
                                @endphp
                                @foreach ($shop->documents as $document)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $document->nama_dokumen }}
                                        </td>
                                        <td>
                                            {{ $document->izin_dikeluarkan ? formatDate($document->izin_dikeluarkan) : '-' }}
                                        </td>
                                        <td>
                                            {{ $document->izin_dikeluarkan ? formatDate($document->izin_berakhir) : '-' }}
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-info btn-preview"
                                                data-toggle="modal" data-target="#documentEdit"
                                                data-document='@json($document)'
                                                data-src="{{ asset('documents/' . $document->nama_file) }}">
                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                            </button>
                                            <a href="{{ asset('documents/' . $document->nama_file) }}" target="_blank" class="btn btn-sm btn-success"><i
                                                    class="fas fa-download" aria-hidden="true"></i></a>
                                            <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                data-id="{{ $document->id }}">
                                                <i class="fa fa-trash" aria-hidden="true"></i></button>
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

    <div class="modal fade" id="documentAdd" tabindex="-1" role="dialog" aria-labelledby="documentAddLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentAddLabel">Tambah Dokumen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <input type="hidden" name="shop_id" value="{{ $shop->id }}">

                        <div class="form-group row">
                            <label for="file_dokumen" class="col-sm-4 col-form-label">File Dokumen</label>
                            <div class="col-sm-8">
                                <input type="file" class="form-control" name="file_dokumen" accept=".pdf" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nama_dokumen" class="col-sm-4 col-form-label">Nama Dokumen</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('nama_dokumen') is-invalid @enderror"
                                    id="nama_dokumen" name="nama_dokumen" value="{{ old('nama_dokumen') }}" required>
                                @error('nama_dokumen')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
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

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Modal for Document Preview -->
    <div class="modal fade" id="documentEdit" tabindex="-1" role="dialog" aria-labelledby="documentEditLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentEditLabel">Edit Dokumen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">

                        <div class="form-group row">
                            <label for="nama_dokumen" class="col-sm-4 col-form-label">Nama Dokumen</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('nama_dokumen') is-invalid @enderror"
                                    id="nama_dokumen" name="nama_dokumen" value="{{ old('nama_dokumen') }}" required>
                                @error('nama_dokumen')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
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

                        <iframe id="documentPreview" width="100%" height="500" frameborder="0"
                            scrolling="auto"></iframe>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        //ready function
        $(document).ready(function() {
            $('#table').dataTable();


            $('.btn-delete').on('click', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Dokumen akan dihapus secara permanen!",
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
                            url: `{{ route('documents.index') }}/${id}`,
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

            $('.btn-preview').click(function() {
                let document = $(this).data('document');
                let src = $(this).data('src');
                $('#documentPreview').attr('src', src);
                $('#documentEdit form').attr('action', `{{ route('documents.index') }}/${document.id}`);
                $('#documentEdit form #nama_dokumen').val(document.nama_dokumen);
                $('#documentEdit form #izin_dikeluarkan').val(document.izin_dikeluarkan);
                $('#documentEdit form #izin_berakhir').val(document.izin_berakhir);
            });
        });
    </script>
@endpush
