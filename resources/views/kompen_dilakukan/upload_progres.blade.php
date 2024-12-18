@empty($progres_kompen)
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
                <a href="{{ url('/kompen_dilakukan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/kompen_dilakukan/' . $progres_kompen->id_kompen_detail . '/update_progres') }}" method="POST" id="form-update">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload Progres Pengerjaan Kompen</h5>
                    <button type="button" class="close" data-dismiss="modal" arialabel="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-bordered table-stripped">
                        <tr>
                            <th class="text-right col-3">Nama Kompen : </th>
                            <td class="col-9">{{ $progres_kompen->kompen->nama }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Pemberi Tugas : </th>
                            <td class="col-9">{{ $progres_kompen->kompen->personilAkademik->nama }} </td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Deskripsi : </th>
                            <td class="col-9">{{ $progres_kompen->kompen->deskripsi }} </td>
                        </tr>
                    </table>
                    @if ($progres_kompen->progres_2 == null)
                    <div class="form-group">
                        <label>Progres 1</label>
                        <input value="{{ $progres_kompen->progres_1 }}" type="text" name="progres_1" id="progres_1" class="form-control">
                        <small id="error-progres_1" class="error-text form-text text-danger"></small>
                    </div>
                    @else
                    <div class="form-group">
                        <label>Progres 1</label>
                        <input value="{{ $progres_kompen->progres_1 }}" type="text" name="progres_1" id="progres_1" class="form-control" disabled>
                        <small id="error-progres_1" class="error-text form-text text-danger"></small>
                    </div>
                    @endif
                    
                    @if ($progres_kompen->progres_1 == null)
                    <div class="form-group">
                        <label>Progres 2</label>
                        <input value="" type="text" name="progres_2" id="progres_2" class="form-control" disabled>
                        <small id="error-progres_2" class="error-text form-text text-danger"></small>
                    </div>
                    @else
                    <div class="form-group">
                        <label>Progres 2</label>
                        <input value="{{ $progres_kompen->progres_2 }}" type="text" name="progres_2" id="progres_2" class="form-control">
                        <small id="error-progres_2" class="error-text form-text text-danger"></small>
                    </div>
                    @endif
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
            $("#form-update").validate({
                rules: {
                    progres_1: { maxlength: 255 },
                    progres_2: { maxlength: 255 }
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
                                dataKompenDilakukan.ajax.reload();
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
@endempty
