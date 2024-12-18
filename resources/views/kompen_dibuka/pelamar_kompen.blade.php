@empty($pengajuan_kompen)
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
@csrf
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Daftar Pelamar Kompen</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-success">
                <h5><i class="icon fas fa-info"></i> Data Kompen </h5>
                Berikut adalah data kompen
            </div>
            <table class="table table-sm table-bordered table-stripped">
                <tr>
                    <th class="text-right col-3">Nama Kompen:</th>
                    <td class="col-9">{{ $data_kompen->nama }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Deskripsi:</th>
                    <td class="col-9">{{ $data_kompen->deskripsi }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Kuota:</th>
                    <td class="col-9">{{ $data_kompen->kuota }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Jam Konversi:</th>
                    <td class="col-9">{{ $data_kompen->jam_kompen }}</td>
                </tr>
            </table>
            <div class="alert alert-success">
                <h5><i class="icon fas fa-info"></i> Data Pelamar Kompen </h5>
                Berikut adalah daftar pelamar kompen
            </div>
            <input type="text" name="id_kompen" id="id_kompen" value="{{ $data_kompen->id_kompen }}" hidden>
            <table class="table table-sm table-bordered table-stripped" id="table_pelamar">
                <thead>
                    <tr>
                        <th class="text-center col-3">No</th>
                        <th class="text-center col-3">Nama</th>
                        <th class="text-center col-3">Prodi</th>
                        <th class="text-center col-3">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
        </div>
    </div>
</div>
<script>
    var listPelamar;
    $(document).ready(function(){
        listPelamar = $('#table_pelamar').DataTable({
            processing: true,
            serverSide: true,
            ajax:{
                "url": "{{ url('kompen_dibuka/daftar_pelamar') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    d._token = "{{ csrf_token() }}";
                    d.id_kompen = "{{ $data_kompen->id_kompen }}";
                }
            },
            columns:[
                {
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },{
                    data: "mahasiswa.nama",
                    className: "text-center",
                    orderable: true,
                    searchable: true
                },{
                    data: "mahasiswa.prodi.nama_prodi",
                    className: "text-center",
                    orderable: true,
                    searchable: true
                },{
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }
            ],
            drawCallback: function() {
                // Re-bind click events after table redraw
                bindActionButtons();
            }
        });

        function bindActionButtons() {
            $(document).off('click', '.btn-accept, .btn-reject').on('click', '.btn-accept, .btn-reject', function(e){
                e.preventDefault();
                const button = $(this);
                const row = button.closest('tr');
                const pengajuanId = button.data('id');
                const status = button.hasClass('btn-accept') ? 'acc' : 'reject';
                
                // Get the hidden input values
                const kompenId = $('#id_kompen').val();
                const mahasiswaId = button.data('mahasiswa');

                if (!pengajuanId || !kompenId || !mahasiswaId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Missing required data for processing'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Apakah anda yakin ingin ${status === 'acc' ? 'menerima' : 'menolak'} pengajuan ini?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("update_pengajuan_kompen") }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id_pengajuan: pengajuanId,
                                status: status,
                                id_kompen: kompenId,
                                id_mahasiswa: mahasiswaId
                            },
                            success: function(response) {
                                if(response.status) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: response.message
                                    }).then(() => {
                                        listPelamar.ajax.reload();
                                        dataKompenDibuka.ajax.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: response.message || 'Terjadi kesalahan'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Terjadi kesalahan: ' + (xhr.responseJSON?.message || error)
                                });
                            }
                        });
                    }
                });
            });
        }
        // Initial binding
        bindActionButtons();
    });
</script>
@endempty