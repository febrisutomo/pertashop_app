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
                        <table id="sales-table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-center align-middle">No.</th>
                                    <th rowspan="2" class="text-center align-middle">Tanggal</th>
                                    <th rowspan="2" class="text-center align-middle">Totalisator Awal</th>
                                    <th rowspan="2" class="text-center align-middle">Totalisator Akhir</th>
                                    <th colspan="2" class="text-center">Teoritis Penjualan</th>
                                    <th rowspan="2" class="text-center align-middle">Operator</th>
                                    <th rowspan="2" class="text-center align-middle">Aksi</th>
                                </tr>
                                <tr>
                                    <th class="text-center align-middle">Volume (&ell;)</th>
                                    <th class="text-center align-middle">Rupiah</th>
                                </tr>
                            </thead>

                        </table>
                        {{-- <table id="sales-table" class="table table-bordered"></table> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('script')
    <script>
        function formatDate(inputDate) {
            const date = new Date(inputDate);
            const options = {
                year: 'numeric',
                month: 'long',
                day: '2-digit',
                weekday: 'long',
            };
            return date.toLocaleDateString('id-ID', options);
        }
        $(document).ready(function() {

            var dataTable = $('#sales-table').DataTable({
                processing: true,
                serverSide: true,
                // paging: false,
                // searching: false,
                ajax: "{{ route('sales.index') }}",
                columns: [{
                        title: 'No.',
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                    },
                    {
                        title: 'Tanggal',
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data, type) {
                            if (type === 'display') {
                                return formatDate(data)
                            }
                            return data;
                        }
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
                        title: 'Totalisator Awal',
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
                        title: 'Totalisator Akhir',
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
                        title: 'Volume (&ell;)',
                        data: 'volume',
                        name: 'volume',
                        className: 'text-right',
                        render: function(data, type) {
                            if (type === 'display') {
                                return formatNumber(data, 2)
                            }
                            return data;
                        }
                    },
                    // {
                    //     title: 'Harga per Liter (Rp)',
                    //     data: 'price.harga_jual',
                    //     name: 'harga_jual',
                    //     className: 'text-right',
                    //     render: function(data, type) {
                    //         if (type === 'display') {
                    //             return formatNumber(data, 0);
                    //         }
                    //         return data;
                    //     }
                    // },
                    {
                        title: 'Rupiah',
                        data: 'rupiah',
                        name: 'rupiah',
                        className: 'text-right',
                        render: function(data, type) {
                            if (type === 'display') {
                                return formatNumber(data, 0);
                            }
                            return data;
                        }
                    },
                    {
                        title: 'Operator',
                        data: 'operator.user.short_name',
                        name: 'operator'
                    },
                    {
                        title: 'Aksi',
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                // order: [
                //     [0, 'desc']
                // ],
                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    },
                    {
                        responsivePriority: 2,
                        targets: -1
                    }
                ],
                // responsive: {
                //     details: {
                //         display: DataTable.Responsive.display.modal({
                //             header: function(row) {
                //                 var data = row.data();
                //                 return 'Detail Penjualan';
                //             }
                //         }),
                //         renderer: DataTable.Responsive.renderer.tableAll({
                //             tableClass: 'table'
                //         })
                //     }
                // }
            });

            $('.dataTables_length').html(
                `<label>Group by: <select name="group_by" class="form-control form-control-sm" >
                    <option value="shift">Shift</option>
                    <option value="date">Tanggal</option>
                </select></label>`)

            $('select[name=shop_id]').on('change', function() {
                let group_by = $('select[name=group_by]').val();
                dataTable.ajax.url(`?shop_id=${this.value}&group_by=${group_by}`).load();
            });

            $('select[name=group_by]').on('change', function() {
                let shop_id = $('select[name=shop_id]').val();
                dataTable.ajax.url(`?shop_id=${shop_id}&group_by=${this.value}`).load();
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
