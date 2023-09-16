@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>User</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">User</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-6 d-flex justify-content-between">
                            @if (Auth::user()->shop)
                                <h3 class="card-title mr-2">
                                    {{ Auth::user()->shop->kode . ' ' . Auth::user()->shop->nama }}</h3>
                            @else
                                <select id="shop_id" name="shop_id" class="form-control mr-2">
                                    <option value="">Semua Petashop</option>
                                    @foreach ($shops as $s)
                                        <option value="{{ $s->id }}" @selected(Request::query('shop_id') == $s->id)>
                                            {{ $s->kode . ' ' . $s->nama }}</option>
                                    @endforeach
                                </select>
                            @endif
                            <select id="role" name="role" class="form-control">
                                <option value="">Semua Role</option>
                                @foreach (['admin', 'operator', 'investor'] as $role)
                                    <option value="{{ $role }}" @selected(Request::query('role') == $role)>
                                        {{ Str::ucfirst($role) }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-3 d-flex justify-content-end order-first order-md-last mb-2 mb-md-0">
                            <a href="{{ route('users.create') }}" class="btn btn-primary"><i
                                    class="fa fa-plus mr-2"></i>Tambah</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Role</th>
                                    <th class="text-center">Pertashop</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td class="text-center">{{ Str::ucfirst($user->role) }}</td>
                                        <td class="text-center">
                                            @if ($user->role != 'investor')
                                                {{ $user->shop->nama }}
                                            @else
                                                @foreach ($user->investments as $s)
                                                    {{ $s->nama }}{{ $loop->iteration != $loop->count ? ',' : '' }}
                                                @endforeach
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info btn-sm"><i
                                                    class="fa fa-edit"></i></a>
                                            <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $user->id }}">
                                                <i class="fa fa-trash"></i></button>
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
@endsection


@push('script')
    <script>
        $(document).ready(function() {

            $('#shop_id, #role').on('change', function() {
                const shop_id = $('#shop_id').val();
                const role = $('#role').val();
                window.location.replace(
                    `{{ route('users.index') }}?shop_id=${shop_id}&role=${role}`
                );
            });

            //client data table
            $('#table').dataTable();

            $('#table').on('click', '.btn-delete', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data user akan dihapus secara permanen!",
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
                            url: "{{ route('users.index') }}" + "/" + id,
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500 // milliseconds
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
