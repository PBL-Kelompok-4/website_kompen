@empty($mahasiswa)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i>Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/mahasiswa') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/mahasiswa/' . $mahasiswa->id_mahasiswa . '/delete_ajax') }}" method="POST" id="form-delete">
        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus Data Mahasiswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-ban"></i> Konfirmasi !!! </h5>
                        Apakah Anda ingin menghapus data seeperti dibawah ini?
                    </div>
                    <table class="table table-sm table-bordered table-stripped">
                        <tr>
                            <th class="text-right col-3">Prodi Mahasiswa : </th>
                            <td class="col-9">{{ $mahasiswa->prodi->nama_prodi }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">NIM : </th>
                            <td class="col-9">{{ $mahasiswa->nomor_induk }} </td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Username : </th>
                            <td class="col-9">{{ $mahasiswa->username }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Nama : </th>
                            <td class="col-9">{{ $mahasiswa->nama }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Periode : </th>
                            <td class="col-9">{{ $mahasiswa->periode->periode }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Jumlah Alpha : </th>
                            <td class="col-9">{{ $mahasiswa->jam_alpha }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Jumlah Jam Kompen : </th>
                            <td class="col-9">{{ $mahasiswa->jam_kompen }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Jumlah Jam Kompen Selesai : </th>
                            <td class="col-9">{{ $mahasiswa->jam_kompen_selesai }}</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
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
                                dataMahasiswa.ajax.reload();
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