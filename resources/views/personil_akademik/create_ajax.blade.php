<form action="{{ url('/personil_akademik/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Personil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Level Personil</label>
                    <select name="id_level" id="id_level" class="form-control" required>
                        <option value="">- Pilih Level -</option>
                        @foreach ($level as $l)
                            <option value="{{ $l->id_level }}">{{ $l->nama_level }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_level" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nomor Induk</label>
                    <input value="" type="number" name="nomor_induk" id="nomor_induk" class="form-control" required>
                    <small id="error-nomor_induk" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input value="" type="text" name="username" id="username" class="form-control" required>
                    <small id="error-username" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama</label>
                    <input value="" type="text" name="nama" id="nama" class="form-control" required>
                    <small id="error-nama" class="error-text form-text text-danger"></small>
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
                    <label>Nomor Telfon</label>
                    <input value="" type="number" name="nomor_telp" id="nomor_telp" class="form-control" required>
                    <small id="error-nomor_telp" class="error-text form-text text-danger"></small>
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
                id_level: { required:true, number: true },
                nomor_induk: { required: true, maxlength: 18 },
                username: { required: true, minlength: 3, maxlength: 20 },
                nama: { required: true, minlength: 3, maxlength: 150 },
                password: { required:true, minlength: 6, maxlength: 20 },
                nomor_telp: { maxlength: 15, number: true }
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
                            dataPersonil.ajax.reload();
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