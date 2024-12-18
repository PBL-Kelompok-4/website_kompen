@empty($kompen_dibuka)
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
                <a href="{{ url('/kompen_dibuka') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Data Kompen</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-info">
                <h5><i class="icon fas fa-info"></i> Data Kompen </h5>
                Berikut adalah detail dari data kompen
            </div>
            <table class="table table-sm table-bordered table-stripped">
                <tr>
                    <th class="text-right col-3">Nama Kompen : </th>
                    <td class="col-9">{{ $kompen_dibuka->nama }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Deskripsi : </th>
                    <td class="col-9">{{ $kompen_dibuka->deskripsi }} </td>
                </tr>
                <tr>
                    <th class="text-right col-3">Pemberi Tugas : </th>
                    <td class="col-9">{{ $kompen_dibuka->personilAkademik->nama }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Jenis Kompen : </th>
                    <td class="col-9">{{ $kompen_dibuka->jenisKompen->nama_jenis }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Kuota Maksimal : </th>
                    <td class="col-9">{{ $kompen_dibuka->kuota }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Jam Konversi : </th>
                    <td class="col-9">{{ $kompen_dibuka->jam_kompen }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Tanggal Mulai : </th>
                    <td class="col-9">{{ $kompen_dibuka->tanggal_mulai }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Tanggal Selesai : </th>
                    <td class="col-9">{{ $kompen_dibuka->tanggal_selesai }}</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            @if (auth()->user()->level->kode_level == "MHS")
            <form action="{{ url('/kompen_dibuka/ajukan_kompen') }}" method="POST" id="form-pengajuan">
                <input type="number" value="{{ $kompen_dibuka->id_kompen }}" name="id_kompen" id="id_kompen" hidden>
                @csrf
                <button type="submit" class="btn btn-primary">Ajukan Kompen</button>
            </form>
            @elseif (auth()->user()->level->kode_level == "ADM")
            <form action="{{ url('/kompen_dibuka/update_status_kompen') }}" method="POST" id="form-update-status">
                <input type="number" value="{{ $kompen_dibuka->id_kompen }}" name="id_kompen" id="id_kompen" hidden>
                @csrf
                <button type="submit" class="btn btn-primary">Ubah Status Ke Progress</button>
            </form>
            @endif
            <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#form-pengajuan").validate({
            rules: {
                id_kompen: { required: true }
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
                                title: 'Pengajuan Berhasil',
                                text: response.message
                            });
                            dataKompenDibuka.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Pengajuan Gagal',
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
        $("#form-update-status").validate({
            rules: {
                id_kompen: { required: true }
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
                                title: 'Update Status Kompen Berhasil',
                                text: response.message
                            });
                            dataKompenDibuka.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Update Status Kompen Gagal',
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