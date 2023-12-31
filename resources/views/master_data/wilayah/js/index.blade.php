<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.select2-modal').select2({
            dropdownParent: $('#standard-modal .modal-content'),
            placeholder: 'Silahkan Pilih',
            width: 'resolve',
            theme: 'bootstrap-5'
        });

        $('#WilayahTable').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [10, 50],
            ajax: {
                "url": "{{ route('wilayah.load_data') }}",
                "type": "POST",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: "text-center",
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    width: 100,
                    className: "text-center",
                },
                {
                    data: 'kode',
                    name: 'kode',
                },
                {
                    data: 'nm_wilayah',
                    name: 'nm_wilayah',
                },
            ],
        });
    });
    
    let id_s = '';

    // membuat data baru
    function functionCreateData() {
        $('#standard-modal').modal('show');
        $('#modal-title').html('Tambah Data');
        $('#simpan-data').html('Simpan');
        $('#id_kd_wil').prop('disabled', false);
    }

    //untuk membersihkan data yang sudah ditutup
    $('#standard-modal').on('hidden.bs.modal', function() {
        $('#nm_wil').val('');
        $('#id_kd_wil').val('').change();
        $('.alert-danger').addClass('d-none');
        $('.alert-danger').html('');
    });

    //melihat edit data
    function functionShowData(id) {
        let url = '{{ route("wilayah.edit", ":id") }}';
        url = url.replace(':id', id);
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                id: id,
            },
            success: function(response) {
                $('#standard-modal').modal('show');
                $('#modal-title').html('Edit Data');
                $('#simpan-data').html('Update');
                $('#id_kd_wil').val(response.result.id_kd_wilayah).change();
                $('#id_kd_wil').prop('disabled', true);
                $('#nm_wil').val(response.result.nm_wilayah);
                id_s = id
            }
        });

    }

    //simpan or update data
    $('#simpan-data').click(function() {
        $('#simpan-data').prop('disabled', true);
        if (id_s == '') {
            var var_url = "{{ route('wilayah.store') }}";
            var var_type = "POST";
        } else {
            let url = '{{ route("wilayah.update", ":id") }}';
            var_url = url.replace(':id', id_s);
            var var_type = "PUT";
        }

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
            url: var_url,
            type: var_type ,
            dataType: 'json',
            data: {
                id_kd_wilayah: $('#id_kd_wil').val(),
                nm_wilayah: $('#nm_wil').val(),
            },
            success: function(response) {
                if (response.errors) {
                    $('.alert-danger').addClass('d-none');
                    $('.alert-danger').html('');
                    $('.alert-danger').removeClass('d-none');
                    $('.alert-danger').append("<ul>");
                    $.each(response.errors, function(key, value) {
                        $('.alert-danger').find('ul').append("<li>" + value + "</li>");
                    })
                    $('.alert-danger').append("</ul>");
                    $('#simpan-data').prop('disabled', false);
                } else {
                    $('#standard-modal').modal('hide');
                    toastr.success(response.success);
                    $('#WilayahTable').DataTable().ajax.reload();
                }
                $('#simpan-data').prop('disabled', false);
            }
        });
    });

    // hapus
    function functionDeleteData(id) {
        let tanya = confirm('Apakah anda yakin untuk menghapus data ini');
        if (tanya == true) {
            let url = '{{ route("wilayah.destroy", ":id") }}';
                url = url.replace(':id', id);
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        id: id,
                    },
                    success: function(data) {
                        if (data.message == '1') {
                            alert('Data berhasil dihapus!');
                            $('#WilayahTable').DataTable().ajax.reload();
                        } else {
                            alert('Data gagal dihapus!');
                        }
                    }
                });
        } else {
            return false;
        }
    }

</script>
