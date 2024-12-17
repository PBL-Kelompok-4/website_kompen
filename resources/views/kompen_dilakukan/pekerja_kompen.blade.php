@empty($pekerja_kompen)
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
                <a href="{{ url('/kompen_dilakukan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
@csrf
<div id="modal-master" class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Daftar Pekerja Kompen</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-success">
                <h5><i class="icon fas fa-info"></i> Data Pekerja Kompen </h5>
                Berikut adalah daftar pekerja kompen
            </div>
            <input type="text" name="id_kompen" id="id_kompen" value="{{ $kompen->id_kompen }}" hidden>
            <table class="table table-sm table-bordered table-stripped" id="tabel_pekerja">
                <thead>
                    <tr>
                        <th class="text-center col-3">No</th>
                        <th class="text-center col-3">Nama</th>
                        <th class="text-center col-3">Prodi</th>
                        <th class="text-center col-3">Progres 1</th>
                        <th class="text-center col-3">Progres 2</th>
                        <th class="text-center col-3">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-success btn-selesai" id="button-selesai">Selesaikan Kompen</button>
            <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
        </div>
    </div>
</div>
<script>
    var listPekerja;
    $(document).ready(function(){
        listPekerja = $('#tabel_pekerja').DataTable({
            processing: true,
            serverSide: true,
            ajax:{
                "url": "{{ url('kompen_dilakukan/list_pekerja') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d){
                    d._token = "{{ csrf_token() }}";
                    d.id_kompen = "{{ $kompen->id_kompen }}";
                }
            },
            columns: [
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
                    data: "progres_1",
                    className: "text-center",
                    orderable: true,
                    searchable: true
                },{
                    data: "progres_2",
                    className: "text-center",
                    orderable: true,
                    searchable: true
                },{
                    data: "aksi",
                    className: "text-center",
                    orderable: true,
                    searchable: true
                }
            ],
            drawCallBack: function() {
                bindActionButtons();
            }
        });

        $(document).on('click', '.btn-selesai', function(e){
                const kompenId = $('#id_kompen').val();
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah anda yakin ingin menyelesaikan kompen ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/kompen_dilakukan/selesaikan_kompen',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id_kompen: kompenId
                            },
                            success: function(response) {
                                if(response.status) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: response.massage
                                    }).then(() => {
                                        listPekerja.ajax.reload();
                                        dataKompenDilakukan.ajax.reload();
                                        $('#modal-master').modal('hide');
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: response.massage || 'Terjadi Kesalahan'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Terjadi Kesalahan ' + (xhr.responseJSON?.massage || error)
                                });
                            }
                        });
                    }
                });
            });

        function bindActionButtons(){
            $(document).off('click', '.btn-accept, .btn-reject').on('click', '.btn-accept, .btn-reject', function(e){
                e.preventDefault();
                const button = $(this);
                const row = button.closest('tr');
                const nama = row.find('td:nth-child(2)').text();
                const status = button.hasClass('btn-accept') ? 'acc' : 'reject';
                const idKompenDetail = button.data('kompendetail');
                const idKompen = button.data('kompen');
                const idMahasiswa = button.data('mahasiswa');


                const kompenId = $('#id_kompen').val();
                
                if(!idKompenDetail || !idKompen || !idMahasiswa ){
                    Swal.fire({
                        icon: 'error',
                        title: 'error',
                        text: 'Missing required data for processing'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi',
                    html: `Apakah anda yakin ingin <b>${status === 'acc' ? 'menerima' : 'menolak'}</b> pekerjaan mahasiswa <b>${nama}</b>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if(result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("konfirmasi_pekerjaan") }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                status: status,
                                id_kompen_detail: idKompenDetail,
                                id_kompen: idKompen,
                                id_mahasiswa: idMahasiswa,
                                id_kompen_inp: kompenId
                            },
                            success: function(response) {
                                if(response.status) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: response.massage
                                    }).then(() => {
                                        listPekerja.ajax.reload();
                                        dataKompenDilakukan.ajax.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: response.massage || 'Terjadi Kesalahan'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Terjadi kesalahan ' + (xhr.responseJSON?.massage || error)
                                });
                            }
                        });
                    }
                });
            }); 
        }
        bindActionButtons();
    });
</script>
@endempty