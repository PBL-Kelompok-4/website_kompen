@empty($mahasiswa_kompen)
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
                <a href="{{ url('/mahasiswa+kompen') }}" class="btn btn-warning">Kembali</a>
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
                    <h5><i class="icon fas fa-info"></i> Data Mahasiswa </h5>
                    Berikut adalah detail dari data mahasiswa
                </div>
                <table class="table table-sm table-bordered table-stripped">
                    <tr>
                        <th class="text-right col-3">Nama : </th>
                        <td class="col-9">{{ $mahasiswa_kompen->mahasiswa->nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">NIM : </th>
                        <td class="col-9">{{ $mahasiswa_kompen->mahasiswa->nomor_induk }} </td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Prodi Mahasiswa : </th>
                        <td class="col-9">{{ $mahasiswa_kompen->mahasiswa->prodi->nama_prodi }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Periode : </th>
                        <td class="col-9">{{ $mahasiswa_kompen->mahasiswa->periode->periode }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Progres : </th>
                        @if ($mahasiswa_kompen->progres_1 != null && $mahasiswa_kompen->progres_2 != null)
                        <td class="col-9">
                            <span class="badge badge-success">100%</span>
                        </td>
                        @elseif ($mahasiswa_kompen->progres_1 != null)
                        <td class="col-9">
                            <span class="badge badge-success">50%</span>
                        </td>
                        @else
                        <td class="col-9">
                            <span class="badge badge-warning">Belum ada progres</span>
                        </td>
                        @endif
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kompen Yang Dikerjakan : </th>
                        <td class="col-9">{{ $mahasiswa_kompen->kompen->nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Mulai : </th>
                        <td class="col-9">{{ $mahasiswa_kompen->kompen->tanggal_mulai }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Selesai : </th>
                        <td class="col-9">{{ $mahasiswa_kompen->kompen->tanggal_selesai }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
            </div>
        </div>
    </div>
@endempty