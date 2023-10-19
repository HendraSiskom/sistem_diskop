@extends('layouts.template')
@section('title', 'Transaksi Barang | ?')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="font-size: 18px; color: black">
                    <button class="btn btn-primary width-md rounded-pill waves-effect waves-light tmbh"
                        style="width:90px;float: right;" type="button">+ Tambah</i></button>
                </div>
                <div class="alert alert-success d-none"></div>
                <div class="card-body">
                    <table id="tblbarang" class="table table-bordered nowrap w-100">
                        <thead>
                            <tr>
                                <th style="width:10px">No</th>
                                <th hidden>Id</th>
                                <th hidden>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th style="width:50px">Tanggal Barang</th>
                                <th>Harga</th>
                                <th style="width: 10px;text-align:center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modaltambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Tambah
                        Data</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-control">
                        <div class="alert alert-danger d-none"></div>
                        <div class="row">
                            <div class="col-3 mb-2">
                                <label class="form-label">Wilayah</label>
                            </div>
                            <div class="col-9 mb-2">
                                <input type="text" class="form-control" id="tmbh_wil" name="tmbh_wil"
                                    placeholder="Wilayah" data-id="{{ $wilayah->wilayah }}"
                                    value="{{ ucwords($wilayah->nm_wilayah) }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 mb-2">
                                <label class="form-label">Varian Data</label>
                            </div>
                            <div class="col-9 mb-2">
                                <select class="form-control tmbhvariandata" id="tmbhvariandata" name="tmbhvariandata"
                                    style="width:100%;">
                                    <option value="">-- Pilih Varian Barang --</option>
                                    @if (!empty($master_barang))
                                        @foreach ($master_barang as $master_barangs)
                                            <option data-id="{{ $master_barangs->id }}"
                                                data-satuan="{{ $master_barangs->nama_satuan }}"
                                                data-jnsbarang="{{ $master_barangs->nama_jnsbarang }}"
                                                data-stdbarang="{{ $master_barangs->nama_standarbarang }}"
                                                value="{{ $master_barangs->nama_barang }}">
                                                {{ $master_barangs->nama_barang }} | Kuantitas :
                                                {{ $master_barangs->kuantitas }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 mb-2">
                                <label class="form-label">Satuan</label>
                            </div>
                            <div class="col-9 mb-2">
                                <input type="text" class="form-control" id="tmbh_satuan" name="tmbh_satuan"
                                    placeholder="Satuan" style="background-color: rgb(226, 226, 224)" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 mb-2">
                                <label class="form-label">Jenis Barang</label>
                            </div>
                            <div class="col-9 mb-2">
                                <input type="text" class="form-control" id="tmbh_jnsbarang" name="tmbh_jnsbarang"
                                    placeholder="Jenis Barang" style="background-color: rgb(226, 226, 224)" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 mb-2">
                                <label class="form-label">Standar Barang</label>
                            </div>
                            <div class="col-9 mb-2">
                                <input type="text" class="form-control" id="tmbh_stdbarang" name="tmbh_stdbarang"
                                    placeholder="Standar Barang" style="background-color: rgb(226, 226, 224)" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 mb-2">
                                <label class="form-label">Tanggal Barang</label>
                            </div>
                            <div class="col-6 mb-2">
                                <input type="date" class="form-control" id="tmbh_tgl" name="tmbh_tgl">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 mb-2">
                                <label class="form-label">Harga</label>
                            </div>
                            <div class="col-9 mb-2">
                                <input type="text" class="form-control" id="tmbh_harga" name="tmbh_harga"s
                                    data-type="rupiah" placeholder="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 mb-2">
                                <label class="form-label">Deskripsi</label>
                            </div>
                            <div class="col-9 mb-2">
                                <textarea class="form-control" id="tmbh_deskripsi" rows="3"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill close"
                        data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary rounded-pill simpan-data">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit-->
    <div class="modal fade" id="modaledit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit
                        Data</h5>
                    <button type="button" class="btn-close update-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form class="form-control">
                        <div class="alert alert-danger d-none"></div>
                        <div class="row">
                            <div class="col-3 mb-2">
                                <label class="form-label">Wilayah</label>
                            </div>
                            <div class="col-9 mb-2">
                                <input type="text" class="form-control" id="edit_wil" name="edit_wil"
                                    placeholder="Wilayah" data-id="{{ $wilayah->wilayah }}"
                                    value="{{ ucwords($wilayah->nm_wilayah) }}" readonly>
                            </div>
                        </div>
                        <div class="row" hidden>
                            <div class="col-3 mb-2">
                                <label class="form-label">Id</label>
                            </div>
                            <div class="col-9 mb-2">
                                <input type="text" class="form-control" id="edit_id" name="edit_id" />
                            </div>
                        </div>
                        <div class="row" hidden>
                            <div class="col-3 mb-2">
                                <label class="form-label">Kode Barang</label>
                            </div>
                            <div class="col-9 mb-2">
                                <input type="text" class="form-control" id="kode_barangold" name="kode_barangold" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 mb-2">
                                <label class="form-label">Varian Data</label>
                            </div>
                            <div class="col-9 mb-2">
                                <select class="form-control editvariandata" id="editvariandata" name="editvariandata"
                                    style="width:100%;">
                                    <option value="">-- Pilih Varian Barang --</option>
                                    @if (!empty($master_barang))
                                        @foreach ($master_barang as $master_barangs)
                                            <option data-id="{{ $master_barangs->id }}"
                                                data-satuan="{{ $master_barangs->nama_satuan }}"
                                                data-jnsbarang="{{ $master_barangs->nama_jnsbarang }}"
                                                data-stdbarang="{{ $master_barangs->nama_standarbarang }}"
                                                value="{{ $master_barangs->nama_barang }}">
                                                {{ $master_barangs->nama_barang }} | Kuantitas :
                                                {{ $master_barangs->kuantitas }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <label class="form-label">Satuan</label>
                            </div>
                            <div class="col-9 mb-2">
                                <input type="text" class="form-control" id="edit_satuan" name="edit_satuan"
                                    placeholder="Satuan" style="background-color: rgb(226, 226, 224)" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 mb-2">
                                <label class="form-label">Jenis Barang</label>
                            </div>
                            <div class="col-9 mb-2">
                                <input type="text" class="form-control" id="edit_jnsbarang" name="edit_jnsbarang"
                                    placeholder="Jenis Barang" style="background-color: rgb(226, 226, 224)" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 mb-2">
                                <label class="form-label">Standar Barang</label>
                            </div>
                            <div class="col-9 mb-2">
                                <input type="text" class="form-control" id="edit_stdbarang" name="edit_stdbarang"
                                    placeholder="Standar Barang" style="background-color: rgb(226, 226, 224)" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 mb-2">
                                <label class="form-label">Tanggal Barang</label>
                            </div>
                            <div class="col-6 mb-2">
                                <input type="date" class="form-control" id="edit_tgl" name="edit_tgl">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 mb-2">
                                <label class="form-label">Harga</label>
                            </div>
                            <div class="col-9 mb-2">
                                <input type="text" class="form-control" id="edit_harga" name="edit_harga"s
                                    data-type="rupiah" placeholder="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 mb-2">
                                <label class="form-label">Deskripsi</label>
                            </div>
                            <div class="col-9 mb-2">
                                <textarea class="form-control" id="edit_deskripsi" rows="3"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill update-close"
                        data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-warning rounded-pill update-data">Update</button>
                </div>
            </div>
        </div>
    </div>
@section('js')
    @include('transaksi.js.index');
@endsection
@endsection
