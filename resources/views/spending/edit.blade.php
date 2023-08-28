@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pengeluaran {{ $periode->monthName . ' ' . $periode->year }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('spendings.index') }}">Pengeluaran</a></li>
                        <li class="breadcrumb-item active">{{ $periode->format('Y-m') }}</li>
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
                        <h3 class="card-title text-uppercase">Pertashop {{ $shop->kode . ' ' . $shop->nama }}</h3>
                        <a href="{{ route('spendings.create') }}" class="btn btn-primary"><i
                                class="fa fa-plus mr-2"></i>Tambah
                            Pengeluaran</a>
                    </div>

                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Total Pengeluaran</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                {{-- <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        .dataTables_info {
            display: none;
        }
    </style>
@endpush

@push('script')
    <script>
        function formatDate(inputDate) {
            const date = new Date(inputDate);
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                weekday: 'long',
            };
            return date.toLocaleDateString('id-ID', options);
        }

        $(document).ready(function() {
            var dataTable = $('.table').DataTable({
                processing: true,
                serverSide: true,
                paging: false,
                searching: false,
                ajax: "{{ route('spendings.edit', ['shop_id' => $shop->id, 'year_month' => $year_month]) }}",
                columns: [{
                        title: 'No',
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        width: 10,
                    },
                    {
                        title: 'Tanggal',
                        data: 'created_at',
                        name: 'created_at',
                        width: 180,
                        render: function(data, type) {
                            if (type === 'display') {
                                return formatDate(data);
                            }
                            return data;
                        }
                    },
                    {
                        title: 'Keterangan',
                        data: 'keterangan',
                        name: 'keterangan',
                    },

                    {
                        title: 'Jumlah',
                        data: 'jumlah',
                        name: 'jumlah',
                        width: 180,
                        render: function(data, type) {
                            if (type === 'display') {
                                // return formatNumber(data, 0);
                                return `<div class="d-flex justify-content-between"><span>Rp</span><span>${formatNumber(data, 0)}</span></div>`
                            }
                            return data;
                        }
                    },
                    {
                        title: 'Aksi',
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: 80
                    },
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    var total = api
                        .column('jumlah:name', {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            // Assuming 'b' is the formatted number string, remove commas and convert to number
                            var num = parseFloat(b.replace(',', ''));
                            return a + (isNaN(num) ? 0 : num);
                        }, 0);

                    // Update the footer cell with the total value
                    $(api.column('jumlah:name').footer()).html(
                        `<div class="d-flex justify-content-between"><span>Rp</span><span>${formatNumber(total, 0)}</span></div>`
                    );
                },
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
                                return 'Detail Pengeluaran';
                            }
                        }),
                        renderer: DataTable.Responsive.renderer.tableAll({
                            tableClass: 'table'
                        })
                    }
                },

            });


            $('.table').on('click', '.btn-delete', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data pengeluaran akan dihapus secara permanen!",
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
                            url: "{{ url('') }}" + "/spendings/" + id,
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
