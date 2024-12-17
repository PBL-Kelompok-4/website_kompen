<form action="{{ url('/kompen_diajukan/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pengajuan Tugas Kompen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <div class="form-group">
                        <label>Jenis Kompen</label>
                        <select name="id_jenis_kompen" id="id_jenis_kompen" class="form-control" required>
                            <option value="">- Pilih Jenis Kompen -</option>
                            @foreach ($jenis_kompen as $j)
                                <option value="{{ $j->id_jenis_kompen }}">{{ $j->nama_jenis }}</option>
                            @endforeach
                        </select>
                        <small id="error-id_jenis_kompen" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Nama Kompen</label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                        <small id="error-nama" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" cols="30" rows="5"></textarea>
                        <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Kuota</label>
                        <input value="" type="number" name="kuota" id="kuota" class="form-control" required>
                        <small id="error-kuota" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Jam Konversi</label>
                        <input value="" type="number" name="jam_kompen" id="jam_kompen" class="form-control" required>
                        <small id="error-jam_kompen" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input value="" type="datetime-local" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required>
                        <small id="error-tanggal_mulai" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Selesai</label>
                        <input value="" type="datetime-local" name="tanggal_selesai" id="tanggal_selesai" class="form-control" required>
                        <small id="error-tanggal_selesai" class="error-text form-text text-danger"></small>
                    </div>
                    <input type="number" value="{{ auth()->user()->id_personil }}" name="id_personil" id="id_personil" hidden>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#form-tambah").validate({
            rules: {
                id_personil: { required: true },
                nama: { required: true },
                deskripsi: { required: true },
                id_jenis_kompen: { required: true, number: true },
                kuota: { required: true, number: true, min: 1 },
                jam_kompen: { required: true, number: true, min: 1 },
                tanggal_mulai: { required: true },
                tanggal_selesai: { required: true }
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
                            dataKompenDiajukan.ajax.reload();
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