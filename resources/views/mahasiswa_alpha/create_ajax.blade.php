<form action="{{ url('/mahasiswa/ajax') }}" method="POST" id="form-tambah">
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
                        <label>Prodi Mahasiswa</label>
                        <select name="id_prodi" id="id_prodi" class="form-control" required>
                            <option value="">- Pilih Prodi -</option>
                            @foreach ($prodi as $l)
                                <option value="{{ $l->id_prodi }}">{{ $l->nama_prodi }}</option>
                            @endforeach
                        </select>
                        <small id="error-id_prodi" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>NIM</label>
                        <input value="" type="text" name="nomor_induk" id="nomor_induk" class="form-control" required>
                        <small id="error-nomor_induk" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input value="" type="text" name="username" id="username" class="form-control" required>
                        <small id="error-username" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <div class="input-group">
                            <input value="" type="password" name="password" id="password" class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text" onclick="togglePassword()">
                                    <i class="fa fa-eye" id="toggle-password"></i>
                                </span>
                            </div>
                        </div>
                        <small id="error-password" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <input value="" type="text" name="nama" id="nama" class="form-control" required>
                        <small id="error-nama" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Semester</label>
                        <input value="" type="number" name="semester" id="semester" class="form-control" required>
                        <small id="error-semester" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Jam Alpha</label>
                        <input value="" type="number" name="jam_alpha" id="jam_alpha" class="form-control" required>
                        <small id="error-jam_alpha" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Jam Kompen</label>
                        <input value="" type="number" name="jam_kompen" id="jam_kompen" class="form-control" required>
                        <small id="error-jam_kompen" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Jam Kompen Selesai</label>
                        <input value="" type="number" name="jam_kompen_selesai" id="jam_kompen_selesai" class="form-control" required>
                        <small id="error-jam_kompen_selesai" class="error-text form-text text-danger"></small>
                    </div>
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
                id_prodi: { required: true, number: true },
                nomor_induk: { required: true, maxlength: 10 },
                username: { required: true, minlength: 3, maxlength: 20 },
                nama: { required: true, minlength: 3, maxlength: 150 },
                semester: { required: true, number: true },
                password: {required:true, minlength: 6, maxlength: 20 },
                jam_alpha: { required: true, number: true },
                jam_kompen: { required: true, number: true },
                jam_kompen_selesai: { required: true, number: true },
                id_level: { number: true }
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
                            dataMahasiswa.ajax.reload();
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

        window.togglePassword = function() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggle-password');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.className = 'fa fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'fa fa-eye';
            }
        }
    });
</script>