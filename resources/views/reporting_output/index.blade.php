@extends('layouts.template')
@section('title', 'Wilayah | ?')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="font-size: 18px; color: black">
                Reporting Output
            </div>
        </div>
    </div>
</div>
<div class="row">
  <div class="col-6">
      <div class="accordion custom-accordion">
          <div class="card mb-0">
              <div class="card-header bg-light" id="bpok">
                  <h5 class="m-0 position-relative">
                      <a class="custom-accordion-title text-reset d-block" href="#">
                          Barang Pokok<i class="mdi mdi-chevron-right accordion-arrow"></i>
                      </a>
                  </h5>
              </div>
          </div>
      </div>
  </div>
  <div class="col-6">
    <div class="accordion custom-accordion">
        <div class="card mb-0">
            <div class="card-header bg-light" id="bpen">
                <h5 class="m-0 position-relative">
                    <a class="custom-accordion-title text-reset d-block" href="#">
                        Barang Penting<i class="mdi mdi-chevron-right accordion-arrow"></i>
                    </a>
                </h5>
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
              <div class="mb-3 ">
                  <label for="id_kd_wil" class="form-label">Pilih Kode Wilayah</label>
                  
              </div>

              <div class="mb-3">
                  <label for="nm_wil" class="form-label">Nama Wilayah</label>
                  <input type="text" class="form-control" id="nm_wil" name="nm_wil" placeholder="Silahkan isi dengan Nama Wilayah">
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Tutup</button>
              <button type="button" id="simpan-data" class="btn btn-primary rounded-pill">Simpan</button>
          </div>
      </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


@endsection

@section('js')
    @include('reporting_output.js.index')
@endsection
