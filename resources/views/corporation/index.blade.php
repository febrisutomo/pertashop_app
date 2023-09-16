@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Badan Usaha</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Badan Usaha</li>
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
                        <div class="card-title">
                            Badan Usaha
                        </div>
                        <a href="{{ route('corporations.create') }}" class="btn btn-primary"><i
                                class="fa fa-plus mr-2"></i>Tambah</a>
                    </div>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="table" class="table table-bordered">
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
            var dataTable = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('corporations.index') }}",
                columns: [{
                        title: '#',
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        width: '20',
                    },
                    {
                        title: 'Nama',
                        data: 'nama',
                        name: 'nama',
                        className: 'text-nowrap',
                    },
                    {
                        title: 'No Telepon',
                        data: 'no_hp',
                        name: 'no_hp',
                        className: 'text-nowrap',
                    },
                    {
                        title: 'Alamat',
                        data: 'alamat',
                        name: 'alamat',
                    },
                    {
                        title: 'Izin Berakhir',
                        data: 'izin_berakhir',
                        name: 'izin_berakhir',
                        className: 'text-nowrap',
                        render: function(data, type, row) {
                            if (data) {
                                return formatDate(data);
                            }
                            return null
                        }
                    },

                    {
                        title: 'Aksi',
                        data: 'action',
                        name: 'action',
                        className: 'text-center',
                        width: 100,
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [0, 'desc']
                ],
            });


            $('#table').on('click', '.btn-delete', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data badan usaha akan dihapus secara permanen!",
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
                            url: "{{ route('corporations.index') }}" + "/" +
                                id,
                            success: function(response) {
                                dataTable.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500 // milliseconds
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
