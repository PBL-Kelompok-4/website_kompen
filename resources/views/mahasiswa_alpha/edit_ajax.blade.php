@empty($mahasiswa_alpha)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" arialabel="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/mahasiswa)alpha') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/mahasiswa_alpha/' . $mahasiswa_alpha->id_mahasiswa . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Mahasiswa Alpha</h5>
                    <button type="button" class="close" data-dismiss="modal" arialabel="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input value="{{ $mahasiswa_alpha->id_level }}" type="number" name="id_level" id="id_level" class="form-control" hidden>
                    <div class="form-group">
                        <label>NIM</label>
                        <input value="{{ $mahasiswa_alpha->nomor_induk }}" type="number" name="nomor_induk" id="nomor_induk" class="form-control" disabled>
                        <small id="error-nomor_induk" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input value="{{ $mahasiswa_alpha->username }}" type="text" name="username" id="username" class="form-control" disabled>
                        <small id="error-username" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <input value="{{ $mahasiswa_alpha->nama }}" type="text" name="nama" id="nama" class="form-control" disabled>
                        <small id="error-nama" class="error-text form-text text-danger"></small>
                    </div>
                    
                    <div class="form-group">
                        <label>Prodi Mahasiswa</label>
                        <input type="text" value="{{ $mahasiswa_alpha->prodi->id_prodi }}" name="id_prodi" id="id_prodi" class="form-control" hidden>
                        <input type="text" value="{{ $mahasiswa_alpha->prodi->nama_prodi }}" name="id_prodi_tampil" id="id_prodi_tampil" class="form-control" disabled>
                        <small id="error-id_prodi" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Semester</label>
                        <input value="{{ $mahasiswa_alpha->semester }}" type="number" name="semester" id="semester" class="form-control" disabled>
                        <small id="error-semester" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Jam Alpha</label>
                        <input value="{{ $mahasiswa_alpha->jam_alpha }}" type="number" name="jam_alpha" id="jam_alpha" class="form-control" required>
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
    <script>
        $(document).ready(function() {
            $("#form-edit").validate({
                rules: {
                    id_prodi: { required: true, number: true },
                    nomor_induk: { required: true, number: true },
                    nama: { required: true, minlength: 3, maxlength: 150 },
                    semester: { required: true, number: true },
                    jam_alpha: { required: true, number: true },
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
@endempty
