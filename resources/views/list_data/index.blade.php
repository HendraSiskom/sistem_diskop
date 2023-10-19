@extends('layouts.template')
@section('title', 'Master Barang | ?')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="font-size: 18px; color: black">
                    List Master Barang
                    <button class="btn btn-primary width-md rounded-pill waves-effect waves-light tmbh"
                        style="width:90px;float: right;" type="button">+ Tambah</i></button>
                    {{-- <a href="javascript:void(0);" onclick="functionCreateData()"
                        class="btn btn-primary width-md rounded-pill waves-effect waves-light" style="">+
                        Tambah</a> --}}
                </div>
                <div class="alert alert-success d-none"></div>
                <div class="card-body">
                    <table id="tblbarang" class="table table-bordered nowrap w-100">
                        <thead>
                            <tr>
                                <th style="width: 10px">No</th>
                                <th hidden>Id Barang</th>
                                <th>Nama Barang</th>
                                <th hidden>kuantitas</th>
                                <th>Satuan</th>
                                <th>Jenis Barang</th>
                                <th hidden>Standar Barang</th>
                                <th style="width: 70px;text-align:center">Aksi</th>
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Tambah
                        Data</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger d-none"></div>
                    <form class="form-control">
                        <div class="form-group mb-1">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="tmbhnama" name="tmbhnama"
                                placeholder="Isikan Nama Barang">
                        </div>
                        <div class="form-group mb-1">
                            <label class="form-label">Kuantitas</label>
                            <input type="number" min="0" class="form-control" data-type="onlynumber"
                                id="tmbhkuantitas" name="tmbhkuantitas" placeholder="0">
                        </div>
                        <div class="form-group mb-1">
                            <label class="form-label">Satuan</label>
                            <select class="form-control tmbhsatuan" style="width:100%;">
                                @if (!empty($master_satuan))
                                    {{-- <optgroup label="Master Data"> --}}
                                    <option value=""> -- Pilih data --</option>
                                    @foreach ($master_satuan as $master_satuans)
                                        <option data-id="{{ $master_satuans->id }}">
                                            {{ $master_satuans->nama_satuan }}</option>
                                    @endforeach
                                    {{-- </optgroup> --}}
                                @endif
                            </select>
                        </div>
                        <div class="form-group mb-1">
                            <label class="form-label">Jenis Barang</label>
                            <select class="form-control tmbhjnsbarang" style="width:100%;">
                                @if (!empty($master_jns_barang))
                                    <option value=""> -- Pilih data --</option>
                                    @foreach ($master_jns_barang as $master_jns_barangs)
                                        <option data-id="{{ $master_jns_barangs->id }}">{{ $master_jns_barangs->nama_jns }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group mb-1">
                            <label class="form-label">Standar Barang</label>
                            <select class="form-control tmbhstdbarang" style="width:100%;">
                                @if (!empty($master_standar_barang))
                                    <option value=""> -- Pilih data --</option>
                                    @foreach ($master_standar_barang as $master_standar_barangs)
                                        <option data-id="{{ $master_standar_barangs->id }}">
                                            {{ $master_standar_barangs->nama_standar }}</option>
                                    @endforeach
                                @endif
                            </select>
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

    <!-- Modal Edit -->
    <div class="modal fade" id="modaledit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit
                        Data</h5>
                    <button type="button" class="btn-close edit-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger d-none"></div>
                    <form class="form-control">
                        <div class="form-group mb-1">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="editnama" name="editnama"
                                placeholder="Isikan Nama Barang">
                        </div>
                        <div class="form-group mb-1">
                            <label class="form-label">Kuantitas</label>
                            <input type="number" min="0" class="form-control" data-type="onlynumber"
                                id="editkuantitas" name="editkuantitas" placeholder="0">
                        </div>
                        <div class="form-group mb-1">
                            <label class="form-label">Satuan</label>
                            <select class="form-control editsatuan" style="width:100%;">
                                @if (!empty($master_satuan))
                                    {{-- <optgroup label="Master Data"> --}}
                                    <option value=""> -- Pilih data --</option>
                                    @foreach ($master_satuan as $master_satuans)
                                        <option data-id="{{ $master_satuans->id }}">{{ $master_satuans->nama_satuan }}
                                        </option>
                                    @endforeach
                                    {{-- </optgroup> --}}
                                @endif
                            </select>
                        </div>
                        <div class="form-group mb-1">
                            <label class="form-label">Jenis Barang</label>
                            <select class="form-control editjnsbarang" style="width:100%;">
                                @if (!empty($master_jns_barang))
                                    <option value=""> -- Pilih data --</option>
                                    @foreach ($master_jns_barang as $master_jns_barangs)
                                        <option data-id="{{ $master_jns_barangs->id }}">{{ $master_jns_barangs->nama_jns }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group mb-1">
                            <label class="form-label">Standar Barang</label>
                            <select class="form-control editstdbarang" style="width:100%;">
                                @if (!empty($master_standar_barang))
                                    <option value=""> -- Pilih data --</option>
                                    @foreach ($master_standar_barang as $master_standar_barangs)
                                        <option data-id="{{ $master_standar_barangs->id }}">
                                            {{ $master_standar_barangs->nama_standar }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill edit-close"
                        data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-warning rounded-pill edit-data">Update</button>
                </div>
            </div>
        </div>
    </div>


@section('js')
    @include('list_data.js.index');
@endsection

@endsection
