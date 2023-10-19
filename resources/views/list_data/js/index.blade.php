<script type="text/javascript">
    $(document).ready(function() {
        let table;
        let id;
        let id_satuan;
        let id_jnsbarang;
        let id_stdbarang;
        let edit_id_satuan;
        let edit_id_jnsbarang;
        let edit_id_stdbarang;
        // csrf-token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Datatables
        table = $("#tblbarang").DataTable({
            scrollX: true,
            lengthMenu: [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, "All"]
            ],
            ajax: {
                url: "{{ route('listbarang.list_barang') }}",
                type: "POST"
            },
            columns: [{
                    data: null,
                    name: null,
                    targets: 0,
                },
                {
                    data: "id",
                    name: "id",
                    visible: false,
                },
                {
                    data: "nama_barang",
                    name: "nama_barang"
                },
                {
                    data: "kuantitas",
                    name: "kuantitas",
                    visible: false,
                },
                {
                    data: "nama_satuan",
                    name: "nama_satuan"
                },
                {
                    data: "nama_jnsbarang",
                    name: "nama_jnsbarang"
                },
                {
                    data: "nama_standarbarang",
                    name: "nama_standarbarang",
                    visible: false,
                },
                {
                    title: 'Aksi',
                    data: null,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        let statuss;
                        if (data.status == '1') {
                            statuss =
                                '<button type="button" style="margin-left:1px;margin-right:2px;" class="btn btn-warning btn-xs" id="editdata" data-toggle="modal" title="Edit Data" disabled/><i class="fas fa-pen-alt"></i></button>' +
                                '<button type="button" class="btn btn-danger btn-xs" id="hapusdata" data-toggle="modal" title="Hapus Data" disabled/><i class="fas fa-trash-alt"></i></button>';
                        } else {
                            statuss =
                                '<button type="button" style="margin-left:1px;margin-right:2px;" class="btn btn-warning btn-xs" id="editdata" title="Edit Data" data-toggle="modal"/><i class="fas fa-pen-alt"></i></button>' +
                                '<button type="button" class="btn btn-danger btn-xs" id="hapusdata" data-toggle="modal" title="Hapus Data"/><i class="fas fa-trash-alt"></i></button>';
                        }
                        return statuss;

                    }
                    // (data, type, row, meta) =>

                }
            ]
        });
        table.on('order.dt search.dt', function() {
            table.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();


        // Modal show Tambah
        $('.tmbh').on('click', function(event) {
            event.preventDefault();
            $('#modaltambah').modal('show');
        });

        $('.tmbhsatuan').select2({
            dropdownParent: $("#modaltambah"),
            theme: "bootstrap-5",
            placeholder: 'Pilih satuan',
            allowClear: true

        });
        $('.tmbhjnsbarang').select2({
            dropdownParent: $("#modaltambah"),
            theme: "bootstrap-5",
            placeholder: 'Pilih Data',
            allowClear: true
        });
        $('.tmbhstdbarang').select2({
            dropdownParent: $("#modaltambah"),
            theme: "bootstrap-5",
            placeholder: 'Pilih Data',
            allowClear: true
        });

        // Onchange
        $('.tmbhsatuan').on('change', function(e) {
            e.preventDefault();
            id_satuan = $('.tmbhsatuan option:selected').data('id');
        });
        $('.tmbhjnsbarang').on('change', function(e) {
            e.preventDefault();
            id_jnsbarang = $('.tmbhjnsbarang option:selected').data('id');
        });
        $('.tmbhstdbarang').on('change', function(e) {
            e.preventDefault();
            id_stdbarang = $('.tmbhstdbarang option:selected').data('id');
        });

        $('.simpan-data').on('click', function(e) {
            e.preventDefault();
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "100",
                "hideDuration": "500",
                "timeOut": "3000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }

            $.ajax({
                url: "{{ route('listbarang.simpanbarang') }}",
                type: 'post',
                data: {
                    nama_barang: $('#tmbhnama').val(),
                    tmbhkuantitas: $('#tmbhkuantitas').val(),
                    tmbhsatuan: $('.tmbhsatuan').val(),
                    tmbhjnsbarang: $('.tmbhjnsbarang').val(),
                    tmbhstdbarang: $('.tmbhstdbarang').val(),
                    tmbh_satuan: id_satuan,
                    tmbh_jnsbarang: id_jnsbarang,
                    tmbh_stdbarang: id_stdbarang,
                },
                beforeSend: function() {
                    $('.simpan-data').prop('disabled', true);
                },
                success: function(response) {
                    if (response.errors) {
                        $('.alert-danger').addClass('d-none');
                        $('.alert-danger').html('');
                        $('.alert-danger').removeClass('d-none');
                        $('.alert-danger').append("<ul>");
                        $.each(response.errors, function(key, value) {
                            $('.alert-danger').find('ul').append("<li>" + value +
                                "</li>");
                        })
                        $('.alert-danger').append("</ul>");
                    } else {
                        $('#modaltambah').modal('hide');
                        toastr.success(response.success);
                        $('#tblbarang').DataTable().ajax.reload();
                    }

                },
                error: function(xhr, status, error) {},
                complete: function(xhr, status) {
                    $('.simpan-data').prop('disabled', false);
                    kosongtambah();
                }

            });
        });


        // Modal Edit
        $('.editsatuan').select2({
            dropdownParent: $("#modaledit"),
            theme: "bootstrap-5",
            placeholder: 'Pilih satuan',
            allowClear: true

        });
        $('.editjnsbarang').select2({
            dropdownParent: $("#modaledit"),
            theme: "bootstrap-5",
            placeholder: 'Pilih Data',
            allowClear: true
        });
        $('.editstdbarang').select2({
            dropdownParent: $("#modaledit"),
            theme: "bootstrap-5",
            placeholder: 'Pilih Data',
            allowClear: true
        });

        // Button edit
        $('#tblbarang tbody').on('click', '#editdata', function(event) {
            event.preventDefault();
            var data = table.row($(this).parents('tr')).data();
            id = data['id'];
            $('#editnama').val(data['nama_barang']);
            $('#editkuantitas').val(data['kuantitas']);
            $('.editsatuan').val(data['nama_satuan']).trigger('change');
            $('.editjnsbarang').val(data['nama_jnsbarang']).trigger('change');
            $('.editstdbarang').val(data['nama_standarbarang']).trigger('change');
            $('#modaledit').modal('show');
        });




        // Onchange
        $('.editsatuan').on('change', function(e) {
            e.preventDefault();
            edit_id_satuan = $('.editsatuan option:selected').data('id');
        });
        $('.editjnsbarang').on('change', function(e) {
            e.preventDefault();
            edit_id_jnsbarang = $('.editjnsbarang option:selected').data('id');
        });
        $('.editstdbarang').on('change', function(e) {
            e.preventDefault();
            edit_id_stdbarang = $('.editstdbarang option:selected').data('id');
        });
        // Edit
        $('.edit-data').on('click', function(e) {
            e.preventDefault();
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "100",
                "hideDuration": "500",
                "timeOut": "3000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
            $.ajax({
                url: "{{ route('listbarang.editbarang') }}",
                type: 'post',
                data: {
                    id_barang: id,
                    nama_barang: $('#editnama').val(),
                    editkuantitas: $('#editkuantitas').val(),
                    editsatuan: $('.editsatuan').val(),
                    editjnsbarang: $('.editjnsbarang').val(),
                    editstdbarang: $('.editstdbarang').val(),
                    edit_satuan: edit_id_satuan,
                    edit_jnsbarang: edit_id_jnsbarang,
                    edit_stdbarang: edit_id_stdbarang,
                },
                beforeSend: function() {
                    $('.edit-data').prop('disabled', true);
                },
                success: function(response) {
                    if (response.errors) {
                        $('.alert-danger').addClass('d-none');
                        $('.alert-danger').html('');
                        $('.alert-danger').removeClass('d-none');
                        $('.alert-danger').append("<ul>");
                        $.each(response.errors, function(key, value) {
                            $('.alert-danger').find('ul').append("<li>" + value +
                                "</li>");
                        })
                        $('.alert-danger').append("</ul>");
                    } else {
                        $('#modaledit').modal('hide');
                        toastr.success(response.success);
                        $('#tblbarang').DataTable().ajax.reload();
                    }

                },
                error: function(xhr, status, error) {},
                complete: function(xhr, status) {
                    $('.edit-data').prop('disabled', false);
                    kosongtambah();
                }

            });
        });



        // Button hapus data
        $('#tblbarang tbody').on('click', '#hapusdata', function(event) {
            event.preventDefault();
            var data = table.row($(this).parents('tr')).data();
            id = data['id'];
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "100",
                "hideDuration": "500",
                "timeOut": "3000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }

            let confirme = confirm('Yakin hapus data barang ' + data['nama_barang'] + ' ?');
            if (confirme == true) {
                $.ajax({
                    url: "{{ route('listbarang.hapusbarang') }}",
                    type: 'post',
                    data: {
                        id_barang: id,
                    },
                    beforeSend: function() {},
                    success: function(data) {
                        toastr.success(data.pesan);
                    },
                    error: function(xhr, status, error) {},
                    complete: function(xhr, status) {
                        $('#tblbarang').DataTable().ajax.reload();
                    }
                });
            } else {
                $('#tblbarang').DataTable().ajax.reload();
            }
        });


        $('.close').on('click', function(e) {
            $('.alert-danger').html('');
            $('.alert-danger').addClass('d-none');
        });

        $('.edit-close').on('click', function(e) {
            $('.alert-danger').html('');
            $('.alert-danger').addClass('d-none');
        });


    });

    function kosongtambah() {
        $('#tmbhnama').val('');
        $('#tmbhkuantitas').val('');
        $('.tmbhsatuan').val(null).trigger('change');
        $('.tmbhjnsbarang').val(null).trigger('change');
        $('.tmbhstdbarang').val(null).trigger('change');
    }
</script>
