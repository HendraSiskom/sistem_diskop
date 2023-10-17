@extends('layouts.template')
@section('title', 'Profil | ?')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                {{ session('status') }}
            </div>
            @endif
            <div class="card-header" style="font-size: 18px; color: black">
                Form Profil
            </div>
            <div class="card-body">
                <form action="{{ route('profil.update', $data_pengguna->id) }}" method="post" enctype="multipart/form-data">
                    @method('PUT')
                    {{ csrf_field() }}
                    <div class="row mb-2">
                        <label for="username" class="col-3 col-xl-3 col-form-label">Username</label>
                        <div class="col-9 col-xl-9">
                            <input type="text" class="form-control" value="{{ $data_pengguna->username }}" readonly>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="nama" class="col-3 col-xl-3 col-form-label">Nama</label>
                        <div class="col-9 col-xl-9">
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ $data_pengguna->nama }}" readonly>
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
                        <label for="kd_wilayah" class="col-3 col-xl-3 col-form-label">Kode Wilayah</label>
                        <div class="col-9 col-xl-9">
                            <input type="text" class="form-control" id="kd_wilayah" name="kd_wilayah" value="{{ $daftar_kd_wil->kode }}" readonly>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="wilayah" class="col-3 col-xl-3 col-form-label">Wilayah</label>
                        <div class="col-9 col-xl-9">
                            <input type="text" class="form-control" id="wilayah" name="wilayah" value="{{ $daftar_kd_wil->nm_wilayah }}" readonly>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="nm_role" class="col-3 col-xl-3 col-form-label">Jabatan</label>
                        <div class="col-9 col-xl-9">
                            <input type="text" class="form-control" id="nm_role" name="nm_role" value="{{ $data_jabatan->nm_role }}" readonly>
                        </div>
                    </div>
                    <div class="justify-content-end row" style="float: right;"> 
                        <div class="col-12 col-xl-12">
                            <button class="btn btn-primary waves-effect rounded-pill waves-light">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection