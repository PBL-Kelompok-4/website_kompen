@empty($jenis_kompen)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i>Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/jenis_kompen') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/jenis_kompen/' . $jenis_kompen->id_jenis_kompen . '/delete_ajax') }}" method="POST" id="form-delete">
        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus Data Jenis Kompen</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-ban"></i> Konfirmasi !!! </h5>
                        Apakah Anda ingin menghapus data seperti dibawah ini?
                    </div>
                    <table class="table table-sm table-bordered table-stripped">
                        <tr>
                            <th class="text-right col-3">Kode : </th>
                            <td class="col-9">{{ $jenis_kompen->kode_jenis }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Nama : </th>
                            <td class="col-9">{{ $jenis_kompen->nama_jenis }}</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function(){
            $("#form-delete").validate({
                rules:{},
                submitHandler: function(form){
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response){
                            if(response.status){
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.massage
                                });
                                dataJenisKompen.ajax.reload();
                            } else{
                                $('.error-text').text('')
                                $.each(response.msgField, function(prefix, val){
                                    $('#error-'+prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.massage
                                });
                            }
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function (error, element){
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass){
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass){
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty