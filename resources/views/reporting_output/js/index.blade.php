<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    $('#bpok').on('click', function() {
        $('#standard-modal').modal('show');
        $('#modal-title').html('Tambah Data');
    });
   
</script>
