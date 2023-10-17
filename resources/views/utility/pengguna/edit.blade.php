@extends('layouts.template')
@section('title', 'Ubah Pengguna | SIMAKDA')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            @if (session('status'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                {{ session('status') }}
            </div>
            @endif
            <div class="card-header" style="font-size: 18px; color: black">
                Form Ubah Pengguna
            </div>
            <div class="card-body">
                <form action="{{ route('pengguna.update', $data_pengguna->id) }}" method="post" enctype="multipart/form-data">
                    @method('PUT')
                    {{ csrf_field() }}
                    <div class="row mb-2">
                        <label for="username" class="col-3 col-xl-3 col-form-label">Username</label>
                        <div class="col-9 col-xl-9">
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ $data_pengguna->username }}" placeholder="Silahkan isi dengan Username" required>
                            @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="nama" class="col-3 col-xl-3 col-form-label">Nama</label>
                        <div class="col-9 col-xl-9">
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ $data_pengguna->nama }}" placeholder="Silahkan isi dengan Nama" required>
                            @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label for="password" class="col-3 col-xl-3 col-form-label">Password</label>
                        <div class="col-9 col-xl-9">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Silahkan isi dengan Password" oninvalid="this.setCustomValidity('Password Tidak Boleh Kosong')"  oninput="setCustomValidity('')" required>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="confirmation_password" class="col-3 col-xl-3 col-form-label">Konfirmasi Password</label>
                        <div class="col-9 col-xl-9">
                            <input type="password" class="form-control @error('confirmation_password') is-invalid @enderror" id="confirmation_password" name="confirmation_password" placeholder="Silahkan isi dengan Konfirmasi Password" oninvalid="this.setCustomValidity('Konfirmasi Password Tidak Boleh Kosong')"  oninput="setCustomValidity('')" required>
                            @error('confirmation_password')
                            <div class="invalid-feedback">konfirmasi password dan password tidak sama.</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="wilayah" class="col-3 col-xl-3 col-form-label">Wilayah</label>
                        <div class="col-9 col-xl-9">
                            <select class="form-control select2" id="wilayah" name="wilayah" required>
                                <option value=""></option>
                                @foreach ($daftar_kd_wil as $value)
                                <option value="{{ $value->id }}" {{ old('wilayah') == $value->id ? 'selected' : '' }} {{ $data_pengguna->wilayah == $value->id ? 'selected' : '' }}>
                                    {{ $value->kode }} |
                                    {{ $value->nm_wilayah }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="peran" class="col-3 col-xl-3 col-form-label">Peran</label>
                        <div class="col-9 col-xl-9">
                            <select class="form-control select2" id="peran" name="peran" required>
                                <option value=""></option>
                                @foreach ($daftar_peran as $value)
                                <option value="{{ $value->id }}" {{ old('peran') == $value->id ? 'selected' : '' }} {{ $data_pengguna->role == $value->id ? 'selected' : '' }}>
                                    {{ $value->role }} |
                                    {{ $value->nm_role }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="justify-content-end row" style="float: right;"> 
                        <div class="col-12 col-xl-12">
                            <button class="btn btn-primary waves-effect rounded-pill waves-light">Simpan</button>
                            <a href="{{ route('pengguna.index') }}" class="btn btn-warning rounded-pill btn-md">Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection