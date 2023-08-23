@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Penjualan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Penjualan</li>
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
                            @if (Auth::user()->role == 'operator')
                                <h3 class="card-title mr-2">
                                    {{ Auth::user()->operator->shop->kode . ' ' . Auth::user()->operator->shop->nama }}</h3>
                            @else
                                <select name="shop_id" class="form-control" style="width: 200px">
                                    @foreach ($shops as $shop)
                                        <option value="{{ $shop->id }}">{{ $shop->kode . ' ' . $shop->nama }}</option>
                                    @endforeach
                                </select>
                            @endif

                        </div>


                        <a href="{{ route('sales.create') }}" class="btn btn-primary"><i class="fa fa-plus mr-2"></i>Tambah
                            Penjualan</a>

                    </div>

                </div>
                <div class="card-body">

                    <div class="table-responsive-lg">
                        {{-- <table id="sales-table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="align-middle">Tanggal</th>
                                    <th rowspan="2" class="align-middle">Stok Awal (&#8467;)</th>
                                    <th rowspan="2" class="align-middle">Penjualan (&#8467;)</th>
                                    <th colspan="2" class="text-center">Stok Akhir (&#8467;)</th>
                                    <th rowspan="2" class="align-middle">Lossess / Gain (&#8467;)</th>
                                    <th rowspan="2" class="align-middle">Harga (Rp)</th>
                                    <th rowspan="2" class="align-middle">Omset (Rp)</th>
                                    <th rowspan="2" class="align-middle">Operator</th>
                                    <th rowspan="2" class="align-middle">Aksi</th>
                                </tr>
                                <tr>
                                    <th class="align-middle">Teoritis</th>
                                    <th class="align-middle">Aktual</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="7" style="text-align:right">Total Omset:</th>
                                    <th></th> <!-- Ini adalah kolom kosong untuk mengatur layout -->
                                    <th></th> <!-- Ini adalah kolom kosong untuk mengatur layout -->
                                    <th></th> <!-- Ini adalah kolom kosong untuk mengatur layout -->
                                </tr>
                            </tfoot>
                        </table> --}}
                        <table id="sales-table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="align-middle">Tanggal</th>
                                    <th class="align-middle">Totalisator Awal (&#8467;)</th>
                                    <th class="align-middle">Totalisator Akhir (&#8467;)</th>
                                    <th class="align-middle">Jumlah (&#8467;)</th>
                                    <th class="align-middle">Harga per Liter (Rp)</th>
                                    <th class="align-middle">Omset (Rp)</th>
                                    <th class="align-middle">Operator</th>
                                    <th class="align-middle">Aksi</th>
                                </tr>
                            </thead>
                            {{-- <tfoot>
                                <tr>
                                    <th colspan="7" style="text-align:right">Total Omset:</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot> --}}
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
            var dataTable = $('#sales-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('sales.index') }}",
                // ajax: {
                //     url: "{{ route('sales.index') }}",
                //     type: "GET",
                //     data: {
                //         shop_id: $('select[name=shop_id]').val()
                //     },
                // },
                columns: [
                    // {
                    //     data: 'DT_RowIndex',
                    //     name: 'DT_RowIndex'
                    // }, 
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                        // render: function(data, type, row, meta) {
                        //     if (type === 'display' && (meta.row === 0 || data !== dataTable.row(meta
                        //             .row - 1).data().tanggal)) {
                        //         return '<span class="group">' + data + '</span>';
                        //     } else {
                        //         return '';
                        //     }
                        // }
                    },
                    {
                        data: 'totalisator_awal',
                        name: 'totalisator_awal',
                        className: 'text-right',
                        render: function(data, type) {
                            if (type === 'display') {
                                return formatNumber(data, 3)
                            }
                            return data;
                        }
                    },

                    {
                        data: 'totalisator_akhir',
                        name: 'totalisator_akhir',
                        className: 'text-right',
                        render: function(data, type) {
                            if (type === 'display') {
                                return formatNumber(data, 3)
                            }
                            return data;
                        }
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah',
                        className: 'text-right',
                        render: function(data, type) {
                            if (type === 'display') {
                                return formatNumber(data, 3)
                            }
                            return data;
                        }
                    },
                    {
                        data: 'price.harga_jual',
                        name: 'price.harga_jual',
                        className: 'text-right',
                        render: function(data, type) {
                            if (type === 'display') {
                                return formatNumber(data, 0);
                            }
                            return data;
                        }
                    },
                    {
                        data: 'omset',
                        name: 'omset',
                        className: 'text-right',
                        render: function(data, type) {
                            if (type === 'display') {
                                // return parseFloat(data).toLocaleString('id-ID', {
                                //     style: 'currency',
                                //     currency: 'IDR',
                                //     minimumFractionDigits: 0
                                // });
                                return formatNumber(data, 0);
                            }
                            return data;
                        }
                    },
                    {
                        data: 'operator.user.name',
                        name: 'operator.user.name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                // footerCallback: function(row, data, start, end, display) {
                //     var api = this.api();

                //     // Menghitung total omset
                //     var totalOmset = api
                //         .column('omset:name', {
                //             page: 'current'
                //         })
                //         .data()
                //         .reduce(function(a, b) {
                //             return a + parseFloat(b);
                //         }, 0);

                //     // Menampilkan total omset pada footer
                //     $(api.column('omset:name').footer()).html(
                //         parseFloat(totalOmset).toLocaleString('id-ID', {
                //             style: 'currency',
                //             currency: 'IDR',
                //             minimumFractionDigits: 0
                //         })
                //     );
                // },
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
                                return 'Detail Penjualan';
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


            $('#sales-table').on('click', '.btn-delete', function() {
                var saleId = $(this).data('id');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data penjualan akan dihapus secara permanen!",
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
                            url: "{{ url('') }}" + "/sales/" + saleId,
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
