@empty($kompen_detail)
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
<form action="{{ url('/kompen_dilakukan/' . $kompen_detail->id_kompen_detail . '/store_bukti') }}" method="POST" id="form-update" enctype="multipart/form-data">
    @csrf
    @method('PUT')    
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload bukti kompen</h5>
                    <button type="button" class="close" data-dismiss="modal" arialabel="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-bordered table-stripped">
                        <tr>
                            <th class="text-right col-3">Nama Kompen : </th>
                            <td class="col-9">{{ $kompen_detail->kompen->nama }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Pemberi Tugas : </th>
                            <td class="col-9">{{ $kompen_detail->kompen->personilAkademik->nama }} </td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Deskripsi : </th>
                            <td class="col-9">{{ $kompen_detail->kompen->deskripsi }} </td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Bukti Kompen</th>
                            <td class="col-9">
                                <a href="{{ url('/kompen_dilakukan/export_bukti_kompen')}}" class="btn btn-warning">
                                    <i class="fa fa-file-pdf"></i> Bukti Kompen
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Upload Bukti Kompen Bertanda Tangan</th>
                            <td class="col-9">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="bukti_kompen" id="bukti_kompen" class="form-control">
                                    </div>
                                </div>
                                <small class="form-text text-muted">Upload file dengan format .pdf (max. 1MB)</small>
                            </td>
                        </tr>
                    </table>
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
                    bukti_kompen: { required: true }
                },
                submitHandler: function(form) {
                    var formData = new FormData(form);
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: formData,
                        processData: false,    // Jangan proses data
                        contentType: false,
                        success: function(response) {
                            if (response.status === true) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {5
                            var errorMessage = xhr.responseJSON 
                                ? (xhr.responseJSON.message || 'Terjadi kesalahan')
                                : 'Terjadi kesalahan saat upload';
                            
                            Swal.fire({
                                icon: 'error',
                                title: 'Kesalahan',
                                text: errorMessage
                            });
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
