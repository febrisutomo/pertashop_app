@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Barang Masuk</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Barang Masuk</li>
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
                        <h3 class="card-title">Data Barang Masuk</h3>
                        <a href="{{ route('purchases.create') }}" class="btn btn-primary"><i
                                class="fa fa-plus mr-2"></i>Tambah
                            Barang Masuk</a>
                    </div>

                </div>
                <div class="card-body">

                    <div class="table-responsive-lg">
                        <table id="purchase-table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="align-middle">Tanggal</th>
                                    <th class="align-middle">ID Pembelian</th>
                                    <th class="align-middle">Supplier</th>
                                    <th class="align-middle">Jumlah (&#8467;)</th>
                                    <th class="align-middle">Operator</th>
                                    <th class="align-middle">Aksi</th>
                                </tr>
                            </thead>
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
            var dataTable = $('#purchase-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "/incomings",
                columns: [
                    // {
                    //     data: 'DT_RowIndex',
                    //     name: 'DT_RowIndex'
                    // }, 
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                    },
                    {
                        data: 'supplier.nama',
                        name: 'supplier.nama',
                    },

                    {
                        data: 'jumlah',
                        name: 'jumlah',
                        className: 'text-right',
                        render: function(data, type) {
                            if (type === 'display') {
                                return formatNumber(data)
                            }
                            return data;
                        }
                    },

                    {
                        data: 'harga',
                        name: 'harga',
                        className: 'text-right',
                        render: function(data, type) {
                            if (type === 'display') {
                                return formatNumber(data);
                            }
                            return data;
                        }
                    },
                    {
                        data: 'total_harga',
                        name: 'total_harga',
                        className: 'text-right',
                        render: function(data, type) {
                            if (type === 'display') {
                                return formatNumber(data);
                            }
                            return data;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [0, 'desc']
                ],
                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    },
                    {
                        responsivePriority: 2,
                        targets: -1
                    }
                ],
                responsive: {
                    details: {
                        display: DataTable.Responsive.display.modal({
                            header: function(row) {
                                var data = row.data();
                                return 'Detail Barang Masuk';
                            }
                        }),
                        renderer: DataTable.Responsive.renderer.tableAll({
                            tableClass: 'table'
                        })
                    }
                }
            });


            $('#purchase-table').on('click', '.btn-delete', function() {
                var saleId = $(this).data('id');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data barang Masuk akan dihapus secara permanen!",
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
                            url: "/purchases/" + saleId,
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                dataTable.ajax.reload();
                                Swal.fire(
                                    'Terhapus!',
                                    'Data barang Masuk telah dihapus.',
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
