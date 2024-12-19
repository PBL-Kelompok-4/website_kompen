@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a href="{{ url('/mahasiswa_alpha/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i>
                    Export Data Mahasiswa Alpha</a>
                <a href="{{ url('/mahasiswa_alpha/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i>
                    Export Data Mahasiswa Alpha</a>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalTambah">
                    Tambah Data Mahasiswa Alpha
                </button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select name="filter_prodi" id="filter_prodi" class="form-control" required>
                                <option value="">- Semua -</option>
                                @foreach ($prodi as $item)
                                    <option value="{{ $item->id_prodi }}">{{ $item->nama_prodi }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Prodi Mahasiswa</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_mahasiswa_alpha">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Periode</th>
                        <th>Prodi</th>
                        <th>Jam Alpha</th>
                        <th>Jam Kompen</th>
                        <th>Jam Kompen Selesai</th>
                        <th>Sisa Jam Kompen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ url('/mahasiswa_alpha/ajax') }}" method="POST" id="form-tambah">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahLabel">Tambah Data Mahasiswa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input value="5" type="number" name="id_level" id="id_level" class="form-control" hidden>
                        <div class="form-group">
                            <label>Mahasiswa</label>
                            <select name="id_mahasiswa" id="id_mahasiswa" class="form-control select2" style="width: 100%;">
                                <option value="">- Pilih Mahasiswa -</option>
                            </select>
                            <small id="error-id_mahasiswa" class="error-text form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label>Jumlah Jam Alpha</label>
                            <input type="number" name="jam_alpha" id="jam_alpha" class="form-control" required>
                            <small id="error-jam_alpha" class="error-text form-text text-danger"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        function reloadMahasiswaOptions() {
            $.ajax({
                url: "{{ url('mahasiswa_alpha/get_options')}}",
                type: 'GET',
                success: function(response) {
                    if (response.status) {
                        let select = $('#id_mahasiswa');
                        select.empty(); // Kosongkan dropdown

                        // Tambahkan opsi default
                        select.append('<option value="">- Pilih Mahasiswa -</option>');

                        // Tambahkan opsi-opsi mahasiswa
                        response.data.forEach(function(data) {
                            select.append(`<option value="${data.id_mahasiswa}">${data.nomor_induk} - ${data.nama}</option>`);
                        });

                        // Reset Select2
                        select.trigger('change');
                    }
                }
            });
        }
        var dataMahasiswaAlpha;
        $(document).ready(function() {
            // Initialize Select2
            $('#id_mahasiswa').select2({
                dropdownParent: $('#modalTambah'),
                width: '100%',
                placeholder: '- Pilih Mahasiswa -',
                allowClear: true
            });

            dataMahasiswaAlpha = $('#table_mahasiswa_alpha').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('mahasiswa_alpha/list') }}",
                    dataType: "json",
                    type: "POST",
                    data: function(d) {
                        d.id_prodi = $('#filter_prodi').val();
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "nomor_induk",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "nama",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "periode.periode",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "prodi.nama_prodi",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "jam_alpha",
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: "jam_kompen",
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: "jam_kompen_selesai",
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: "sisa_kompen",
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ],
                drawCallback: function(settings) {
                    reloadMahasiswaOptions();
                }
            });

            // Form validation and submission
            $("#form-tambah").validate({
                rules: {
                    id_mahasiswa: {
                        required: true
                    },
                    jam_alpha: {
                        required: true,
                        number: true,
                        min: 0
                    }
                },
                messages: {
                    id_mahasiswa: {
                        required: "Silakan pilih mahasiswa"
                    },
                    jam_alpha: {
                        required: "Jumlah jam alpha harus diisi",
                        number: "Masukkan angka yang valid",
                        min: "Jumlah jam tidak boleh negatif"
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                $('#modalTambah').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                dataMahasiswaAlpha.ajax.reload();
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
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

            // Search functionality
            $('#table_mahasiswa_alpha_filter input').unbind().bind('keyup', function(e) {
                if (e.keyCode == 13) {
                    dataMahasiswaAlpha.search(this.value).draw();
                }
            });

            // Filter functionality
            $('#filter_prodi').on('change', function() {
                dataMahasiswaAlpha.ajax.reload();
            });
        });
    </script>
@endpush
