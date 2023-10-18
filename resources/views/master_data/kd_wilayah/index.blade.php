@extends('layouts.template')
@section('title', 'Kode Wilayah | ?')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="font-size: 18px; color: black">
                List Kode Wilayah
                <a href="javascript:void(0);" onclick="functionCreateData()" class="btn btn-primary width-md rounded-pill waves-effect waves-light" style="float: right;">+ Tambah</a>
            </div>
            <div class="alert alert-success d-none"></div>
            <div class="card-body">
                <table id="kdWilayahTable" class="table table-bordered dt-responsive table-responsive nowrap">
                    <thead>
                        <tr>
                            <th style="width: 5px">No</th>
                            <th style="width: 10px">Aksi</th>
                            <th>Kode Wilayah</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Standard modal content -->
<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-none"></div>
                <div class="mb-3">
                    <label for="kode" class="form-label">Kode Wilayah</label>
                    <input type="number" class="form-control" id="kode" name="kode" pattern="/^-?\d+\.?\d*$/"  
                    onKeyPress="if( this.value.length == 5 ) return false;" placeholder="Silahkan isi dengan Kode Wilayah">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Tutup</button>
                <button type="button" id="simpan-data" class="btn btn-primary rounded-pill"></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@section('js')
    @include('master_data.kd_wilayah.js.index')
@endsection
