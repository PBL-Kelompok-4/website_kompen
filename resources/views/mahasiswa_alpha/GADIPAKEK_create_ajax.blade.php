<form action="{{ url('/mahasiswa_alpha/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Mahasiswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input value="5" type="number" name="id_level" id="id_level" class="form-control" hidden>
                    <div class="form-group">
                        <label>Mahasiswa</label>
                        <select name="id_mahasiswa" id="id_mahasiswa" class="form-control select2" style="width: 100%;">
                            <option value="">- Pilih Mahasiswa -</option>
                            @foreach ( $mahasiswa as $m)
                                <option value="{{ $m->id_mahasiswa }}"> {{$m->nomor_induk}} - {{$m->nama}} </option>
                            @endforeach
                            <small id="error-id_mahasiswa" class="error-text form-text text-danger"></small>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Jam Alpha</label>
                        <input value="" type="number" name="jam_alpha" id="jam_alpha" class="form-control" required>
                        <small id="error-jam_alpha" class="error-text form-text text-danger"></small>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Inisialisasi Select2
    $('#id_mahasiswa').select2();
    $('.select2').select2();

    $("#form-tambah").validate({
        rules: {
            id_mahasiswa: { required: true, number: true },
            jam_alpha: { required: true, number: true },
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        dataMahasiswaAlpha.ajax.reload();
                    } else {
                        $('.error-text').text('');
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                }
            });
            return false;
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>