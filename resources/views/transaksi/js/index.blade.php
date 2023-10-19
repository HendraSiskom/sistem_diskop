<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        table = $("#tblbarang").DataTable({
            scrollX: true,
            lengthMenu: [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, "All"]
            ],
            ajax: {
                url: "{{ route('transaksi.listbarang') }}",
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
                    data: "kode_barang",
                    name: "kode_barang",
                    visible: false,
                },
                {
                    data: "nama_barang",
                    name: "nama_barang"
                },
                {
                    data: "tanggal_barang",
                    name: "tanggal_barang"
                },
                {
                    data: "harga",
                    name: "harga",
                    render: function(data, type, row, meta) {
                        return Intl.NumberFormat('id-ID').format(data)
                    }
                },
                {
                    title: 'Aksi',
                    data: '',
                    orderable: false,
                    render: (data, type, row) => {
                        let statuss =
                            `<button type="button" style="margin-left:1px;margin-right:2px;" class="btn btn-warning btn-xs" id="editdata" onClick="editdata('${row.id}');" data-toggle="modal" title="Edit Data"/><i class="fas fa-pen-alt"></i></button>` +
                            `<button type="button" class="btn btn-danger btn-xs" id="hapusdata" onClick="hapusdata('${row.id}','${row.nama_barang}','${row.kode_barang}');" data-toggle="modal" title="Hapus Data"/><i class="fas fa-trash-alt"></i></button>`;
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

        $('.tmbh').on('click', function() {
            kosong();
            $('.alert-danger').addClass('d-none');
            $('.alert-danger').html('');
            $('#modaltambah').modal('show');
        });
        // inputan rupiah
        $("input[data-type='rupiah']").on('keyup', function() {
            this.value = formatRupiah(this.value);
        });
        // Modal select2
        $('.tmbhvariandata').select2({
            dropdownParent: $('#modaltambah'),
            theme: "bootstrap-5",
            placeholder: 'Pilih satuan',
            allowClear: true
        });

        $('.tmbhvariandata').on('change', function(event) {
            event.preventDefault();
            let value = this.value;
            let id = $('.tmbhvariandata option:selected').data('id');
            let satuan = $('.tmbhvariandata option:selected').data('satuan');
            let jnsbarang = $('.tmbhvariandata option:selected').data('jnsbarang');
            let stdbarang = $('.tmbhvariandata option:selected').data('stdbarang');
            $('#tmbh_satuan').val(satuan);
            $('#tmbh_jnsbarang').val(jnsbarang);
            $('#tmbh_stdbarang').val(stdbarang);
        });

        // Simpandata
        $('.simpan-data').on('click', function() {
            let nilai = rupiah($('#tmbh_harga').val());
            let id_barang = $('.tmbhvariandata option:selected').data('id');
            let id_wilayah = $('#tmbh_wil').data('id');

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
                url: "{{ route('transaksi.simpanbarang') }}",
                type: 'POST',
                data: {
                    id_barang: id_barang,
                    wilayah: id_wilayah,
                    varian_data: $('#tmbhvariandata').val(),
                    tglbarang: $('#tmbh_tgl').val(),
                    harga: nilai,
                    deskripsi: $('#tmbh_deskripsi').val()

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
                error: function(xhr, status, error) {
                    alert(status.errors);
                },
                complete: function(xhr, status) {
                    $('.simpan-data').prop('disabled', false);
                }
            });
        });

        $('.close').on('click', function() {

        });

        $('.editvariandata').select2({
            dropdownParent: $('#modaledit'),
            theme: "bootstrap-5",
            placeholder: 'Pilih satuan',
            allowClear: true
        });
        $('.editvariandata').on('change', function(event) {
            event.preventDefault();
            let value = this.value;
            let id = $('.editvariandata option:selected').data('id');
            let satuan = $('.editvariandata option:selected').data('satuan');
            let jnsbarang = $('.editvariandata option:selected').data('jnsbarang');
            let stdbarang = $('.editvariandata option:selected').data('stdbarang');
            $('#edit_satuan').val(satuan);
            $('#edit_jnsbarang').val(jnsbarang);
            $('#edit_stdbarang').val(stdbarang);
        });

        $('.update-data').on('click', function() {
            let nilai = rupiah($('#edit_harga').val());
            let id_barang = $('.editvariandata option:selected').data('id');
            let id_wilayah = $('#edit_wil').data('id');
            let id = $('#edit_id').val();

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
                url: "{{ route('transaksi.updatebarang') }}",
                type: 'POST',
                data: {
                    id_barang: id_barang,
                    id: id,
                    id_barangold: $('#kode_barangold').val(),
                    wilayah: id_wilayah,
                    varian_data: $('#editvariandata').val(),
                    tglbarang: $('#edit_tgl').val(),
                    harga: nilai,
                    deskripsi: $('#edit_deskripsi').val()

                },
                beforeSend: function() {
                    $('.update-data').prop('disabled', true);
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
                error: function(xhr, status, error) {
                    alert(status.errors);
                },
                complete: function(xhr, status) {
                    $('.update-data').prop('disabled', false);
                }
            });
        });

    });

    function formatRupiah(angka) {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        hasil = split[1] != undefined ? rupiah + ',' + split[1] :
            rupiah;
        return hasil;
    }

    function rupiah(angka) {
        let hasil = angka.split('.').join('');
        return hasil;
    }

    function editdata(id) {
        kosongedit();
        $.ajax({
            url: "{{ route('transaksi.wherelistbarang') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function(response) {
                $('#edit_id').val(response.id);
                $('#kode_barangold').val(response.kode_barang);
                $('#editvariandata').val(response.nama_barang).trigger('change');
                $('#edit_satuan').val(response.nama_satuan);
                $('#edit_jnsbarang').val(response.nama_jnsbarang);
                $('#edit_stdbarang').val(response.nama_standarbarang);
                $('#edit_tgl').val(response.tanggal_barang);
                $('#edit_harga').val(formatRupiah(response.harga));
                $('#edit_deskripsi').val(response.deskripsi);
                $('#modaledit').modal('show');
            }
        });
    }

    function hapusdata(id, nama_barang, kode_barang) {
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
        let confirme = confirm('Yakin hapus data barang ' + nama_barang + ' ?');
        if (confirme == true) {
            $.ajax({
                url: "{{ route('transaksi.hapusbarang') }}",
                type: 'post',
                data: {
                    id_barang: id,
                    kode_barang: kode_barang
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
    }

    function kosong() {
        $('.tmbhvariandata').val(null).trigger('change');
        $('#tmbh_satuan').val('');
        $('#tmbh_jnsbarang').val('');
        $('#tmbh_stdbarang').val('');
        $('#tmbh_tgl').val('');
        $('#tmbh_harga').val('');
        $('#tmbh_deskripsi').val('');
    }

    function kosongedit() {
        $('.editvariandata').val(null).trigger('change');
        $('#edit_satuan').val('');
        $('#edit_jnsbarang').val('');
        $('#edit_stdbarang').val('');
        $('#edit_tgl').val('');
        $('#edit_harga').val('');
        $('#edit_deskripsi').val('');
    }
</script>
