@extends('layouts.template')
@section('title', 'Pengguna | ?')

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
                List Data Pengguna
                <a href="{{ route('pengguna.create') }}" class="btn btn-primary width-md rounded-pill waves-effect waves-light" style="float: right;">+ Tambah</a>
            </div>
            <div class="card-body">
                <table id="penggunaTable" class="table table-bordered dt-responsive table-responsive nowrap">
                    <thead>
                        <tr>
                            <th style="width: 5px">No</th>
                            <th style="width: 10px">Aksi</th>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>Kode Wilayah</th>
                            <th>Wilayah</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    @include('utility.pengguna.js.index')
@endsection
