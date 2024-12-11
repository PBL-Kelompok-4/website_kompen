@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title}}</h3>
            <div class="card-tools">
                {{-- <button onclick="modalAction('{{ url('/mahasiswa/import') }}')" class="btn btn-info">Import Data Mahasiswa</button>
                <a href="{{ url('/mahasisawa/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Data Mahasiswa</a>
                <a href="{{ url('/mahasisawa/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Data Mahasiswa</a> --}}
                {{-- <button onclick="modalAction('{{ url('mahasiswa/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button> --}}
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
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
                        <div class="col-3">
                            <select name="filter_semester" id="filter_semester" class="form-control" required>
                                <option value="">- Semua -</option>
                                <option value="1">Semester 1</option>
                                <option value="2">Semester 2</option>
                                <option value="3">Semester 3</option>
                                <option value="4">Semester 4</option>
                                <option value="5">Semester 5</option>
                                <option value="6">Semester 6</option>
                                <option value="7">Semester 7</option>
                                <option value="8">Semester 8</option>
                                <option value="9">Semester 9</option>
                                <option value="10">Semester 10</option>
                                <option value="11">Semester 11</option>
                                <option value="12">Semester 12</option>
                                <option value="13">Semester 13</option>
                                <option value="14">Semester 14</option>
                            </select>
                            <small class="form-text text-muted">Semester Mahasiswa</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_mahasiswa_alpha">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Semester</th>
                        <th>Prodi</th>
                        <th>Jam Alpha</th>
                        {{-- <th>List Kompetensi</th> --}}
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        function modalAction(url = ''){
            $('#myModal').load(url,function(){
                $('#myModal').modal('show');
            });
        }

        var dataMahasiswaAlpha;
        $(document).ready(function(){
            dataMahasiswaAlpha = $('#table_mahasiswa_alpha').DataTable({
                processing: true,
                //serverSide: true, jika ingin menggunakan server side processing
                serverSide: true,
                ajax:{
                    "url": "{{ url('mahasiswa_alpha/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d){
                        d.id_prodi = $('#filter_prodi').val();
                        d.semester = $('#filter_semester').val();
                    }
                },
                columns:[
                    {
                        //nomor urut dari laravel datatable addIndexColumn()
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },{
                        data: "nomor_induk",
                        className: "",
                        //orderable: true, jika ingin kolom bisa diurutkan
                        orderable: true,
                        //searchable: true, jika ingin kolom bisa dicari
                        searchable: true
                    },{
                        data: "username",
                        className: "",
                        orderable:true,
                        searchable: true
                    },{
                        data: "nama",
                        className: "",
                        orderable: true,
                        searchable: true
                    },{
                        data: "semester",
                        className: "",
                        orderable: true,
                        searchable: true
                    },{
                        // mengambil data prodi dari hasil ORM berelasi
                        data: "prodi.nama_prodi",
                        className: "",
                        orderable: false,
                        searchable: false
                    },{
                        data: "jam_alpha",
                        className: "",
                        orderable: true,
                        searchable: false
                    // },{
                    //     data: "jam_alpha",
                    //     className: "",
                    //     orderable: true,
                    //     searchable: false
                        
                        // data: 'list_kompetensi',
                        // className: '',
                        // orderable: false,
                        // searchable: false,
                        // render: function (data, type, row) {
                        //     var listKompetensi = data.split('|');
                        //     var html = '<ul>';
                        //     $.each(listKompetensi, function(index, kompetensi) {
                        //         html += '<li>' + kompetensi + '</li>';
                        //     });
                        //     html += '</ul>';
                        //     return html;
                        // }
                    },{
                        data: "aksi",
                        className: "text-center",
                        orderable:false,
                        searchable: false
                    }
                ]
            });
            $('#table_mahasiswa_alpha_filter input').unbind().bind().on('keyup', function(e){
                if(e.keyCode == 13){ // enter key
                    dataMahasiswaAlpha.search(this.value).draw();
                }
            });
            $('#filter_prodi').on('change', function(){
                dataMahasiswaAlpha.ajax.reload();
            });
            $('#filter_semester').on('change', function(){
                dataMahasiswaAlpha.ajax.reload();
            });
        });
    </script>
@endpush