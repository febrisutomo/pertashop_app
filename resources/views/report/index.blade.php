@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Laporan Bulanan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Laporan Bulanan</li>
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
                        {{-- <a href="" class="btn btn-primary"><i class="fa fa-plus mr-2"></i>Tambah
                            Laporan Bulanan</a> --}}
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
                ajax: "{{ route('reports.index') }}",
                columns: [{
                        title: '#',
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        width: 16,
                        className: 'text-right'
                    },

                    {
                        title: 'Bulan',
                        data: 'bulan',
                        name: 'bulan',
                    },
                    {
                        title: 'Laba Kotor (Rp)',
                        data: 'laba_kotor',
                        name: 'laba_kotor',
                        className: 'text-right',
                        render: function(data, type) {
                            if (type === 'display') {
                                return formatNumber(data, 0);
                            }
                            return data;
                        }
                    },
                    { 
                        title: 'Laba Bersih (Rp)',
                        data: 'laba_bersih',
                        name: 'laba_bersih',
                        className: 'text-right',
                        render: function(data, type) {
                            if (type === 'display') {
                                return formatNumber(data, 0);
                            }
                            return data;
                        }
                    },

                    {
                        title: 'Posisi Modal Kerja (Rp)',
                        data: 'posisi_modal_kerja',
                        name: 'posisi_modal_kerja',
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
                                return 'Detail Laporan Laba';
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
                    text: "Data laporan Laba akan dihapus secara permanen!",
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
                            url: "{{ url('') }}" + "data/reports/bulanan/" + saleId,
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
