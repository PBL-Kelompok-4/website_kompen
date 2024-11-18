@empty($mahasiswa_alpha)
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
                <a href="{{ url('/mahasiswa_alpha') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Mahasiswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Data Mahasiswa Alpha </h5>
                    Berikut adalah detail dari data mahasiswa alpha
                </div>
                <table class="table table-sm table-bordered table-stripped">
                    <tr>
                        <th class="text-right col-3">Prodi Mahasiswa : </th>
                        <td class="col-9">{{ $mahasiswa_alpha->prodi->nama_prodi }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">NIM : </th>
                        <td class="col-9">{{ $mahasiswa_alpha->nomor_induk }} </td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Username : </th>
                        <td class="col-9">{{ $mahasiswa_alpha->username }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama : </th>
                        <td class="col-9">{{ $mahasiswa_alpha->nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Semester : </th>
                        <td class="col-9">{{ $mahasiswa_alpha->semester }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jumlah Alpha : </th>
                        <td class="col-9">{{ $mahasiswa_alpha->jam_alpha }}</td>
                    </tr>
                    {{-- <tr>
                        <th class="text-right col-3">List Kompetensi</th>
                        <td class="col-9"></td>
                    </tr> --}}
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
            </div>
        </div>
    </div>
@endempty