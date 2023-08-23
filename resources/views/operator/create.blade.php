@extends('layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tambah Operator</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('operators.index') }}">Operator</a></li>
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
                        <h3 class="card-title">Tambah Operator</h3>
                    </div>

                </div>
                <form id="insertForm" action="{{ route('operators.store') }}" method="POST" class="needs-validation"
                    novalidate>
                    @csrf
                    <div class="card-body">

                        <div class="form-group">
                            <label for="shop_id">Pertashop</label>
                            <select name="shop_id" id="shop_id"
                                class="form-control @error('shop_id') is-invalid @enderror">
                                <option value="">--Pilih Pertashop--</option>
                                @foreach ($shops as $shop)
                                    <option value="{{ $shop->id }}" @selected($shop->id == old('shop_id'))>
                                        {{ $shop->kode . ' ' . $shop->nama }}</option>
                                @endforeach
                            </select>
                            @error('shop_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" placeholder="Masukkan Nama Lengkap">
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="no_hp">No Handphone</label>
                            <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp"
                                name="no_hp" value="{{ old('no_hp') }}" placeholder="Masukkan No. Handphone">
                            @error('no_hp')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="no_rekening">No Rekening</label>
                            <input type="text" class="form-control @error('no_rekening') is-invalid @enderror" id="no_rekening"
                                name="no_rekening" value="{{ old('no_rekening') }}" placeholder="Masukkan No. Rekening">
                            @error('no_rekening')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat"
                                value="{{ old('alamat') }}" placeholder="Masukkan Alamat"></textarea>
                            @error('alamat')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email') }}" placeholder="Masukkan Email">
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
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
