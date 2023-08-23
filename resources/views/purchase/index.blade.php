@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pembelian</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Pembelian</li>
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
                            {{-- <h3 class="card-title mr-2">Pertashop</h3> --}}
                            <select name="shop_id" class="form-control" style="width: 200px">
                                @foreach ($shops as $shop)
                                    <option value="{{ $shop->id }}">{{ $shop->kode . ' ' . $shop->nama }}</option>
                                @endforeach
                            </select>

                        </div>
                        <a href="{{ route('purchases.create') }}" class="btn btn-primary"><i
                                class="fa fa-plus mr-2"></i>Tambah
                            Pembelian</a>
                    </div>

                </div>
                <div class="card-body">

                    <div class="table-responsive-lg">
                        <table id="purchase-table" class="table table-bordered">
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
                ajax: "/purchases",
                columns: [
                    // {
                    //     data: 'DT_RowIndex',
                    //     name: 'DT_RowIndex'
                    // }, 
                    {
                        title: 'ID Pembelian',
                        data: 'id',
                        name: 'id',
                    },
                    {
                        title: 'Tanggal',
                        data: 'tanggal',
                        name: 'tanggal',
                    },
                    {
                        title: 'Supplier',
                        data: 'supplier.nama',
                        name: 'supplier.nama',
                    },

                    {
                        title: 'Jumlah (&ell;)',
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
                        title: 'Datang (&ell;)',
                        data: 'datang',
                        name: 'datang',
                        className: 'text-right',
                        render: function(data, type) {
                            if (type === 'display') {
                                return formatNumber(data)
                            }
                            return data;
                        }
                    },
                    {
                        title: 'Sisa (&ell;)',
                        data: 'sisa',
                        name: 'sisa',
                        className: 'text-right',
                        render: function(data, type) {
                            if (type === 'display') {
                                return formatNumber(data)
                            }
                            return data;
                        }
                    },

                    {
                        title: 'Harga per Liter (Rp)',
                        data: 'price.harga_beli',
                        name: 'price.harga_beli',
                        className: 'text-right',
                        render: function(data, type) {
                            if (type === 'display') {
                                return formatNumber(data);
                            }
                            return data;
                        }
                    },
                    {
                        title: 'Total Harga (Rp)',
                        data: 'total_harga',
                        name: 'total_harga',
                        className: 'text-right',
                        render: function(data, type) {
                            if (type === 'display') {
                                return formatNumber(data, 0);
                            }
                            return data;
                        }
                    },
                    {
                        title: 'Aksi',
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
                                return 'Detail Pembelian';
                            }
                        }),
                        renderer: DataTable.Responsive.renderer.tableAll({
                            tableClass: 'table'
                        })
                    }
                }
            });

            $('select[name=shop_id]').on('change', function() {
                dataTable.ajax.url(`?shop_id=${this.value}`).load();
            });


            $('#purchase-table').on('click', '.btn-delete', function() {
                var saleId = $(this).data('id');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data pembelian akan dihapus secara permanen!",
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
                            url: "{{ url('') }}" + "/purchases/" + saleId,
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                dataTable.ajax.reload();
                                Swal.fire(
                                    'Terhapus!',
                                    response.message,
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
