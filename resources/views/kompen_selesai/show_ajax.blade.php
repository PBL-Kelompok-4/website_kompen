@empty($kompen_selesai)
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
                <a href="{{ url('/kompen_selesai') }}" class="btn btn-warning">Kembali</a>
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
                        <td class="col-9">{{ $kompen_selesai->nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Deskripsi : </th>
                        <td class="col-9">{{ $kompen_selesai->deskripsi }} </td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Pemberi Tugas : </th>
                        <td class="col-9">{{ $kompen_selesai->personilAkademik->nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jenis Kompen : </th>
                        <td class="col-9">{{ $kompen_selesai->jenisKompen->nama_jenis }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kuota Maksimal : </th>
                        <td class="col-9">{{ $kompen_selesai->kuota }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jam Konversi : </th>
                        <td class="col-9">{{ $kompen_selesai->jam_kompen }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Mulai : </th>
                        <td class="col-9">{{ $kompen_selesai->tanggal_mulai }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Selesai : </th>
                        <td class="col-9">{{ $kompen_selesai->tanggal_selesai }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
            </div>
        </div>
    </div>
@endempty