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
<form action="{{ url('/kompen_dilakukan/' . $kompen_detail->id_kompen_detail . '/store_bukti') }}" method="POST" id="form-update">
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
                                        <input type="file" name="bukti_kompen" id="bukti_kompen" class="custom-file-input">
                                        <label for="bukti_kompen" class="custom-file-label">Pilih File</label>
                                        {{-- <div class="form-text text-muted">Upload file dengan format PDF (max. 2MB)</div> --}}
                                    </div>
                                </div>
                                <small class="form-text text-muted">Upload file dengan format .pdf (max. 2MB)</small>
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
            
        });
    </script>
@endempty
