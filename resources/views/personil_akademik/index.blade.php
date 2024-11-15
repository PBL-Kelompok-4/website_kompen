@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title}}</h3>
            <div class="card-tools">
                {{-- <button onclick="modalAction('{{ url('/mahasiswa/import') }}')" class="btn btn-info">Import Data Mahasiswa</button>
                <a href="{{ url('/mahasisawa/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Data Mahasiswa</a>
                <a href="{{ url('/mahasisawa/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Data Mahasiswa</a> --}}
                <button onclick="modalAction('{{ url('personil_akademik/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button>
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
                            <select name="filter_level" id="filter_level" class="form-control" required>
                                <option value="">- Semua -</option>
                                @foreach ($level as $item)
                                    <option value="{{ $item->id_level }}">{{ $item->nama_level }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Level Personil Akademik</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_personil_akademik">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Level Personil</th>
                        <th>Nomor Induk</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Nomor Telfon</th>
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

        var dataPersonil;
        $(document).ready(function(){
            dataPersonil = $('#table_personil_akademik').DataTable({
                processing: true,
                //serverSide: true, jika ingin menggunakan server side processing
                serverSide: true,
                ajax:{
                    "url": "{{ url('personil_akademik/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d){
                        d.id_level = $('#filter_level').val();
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
                        data: "level.nama_level",
                        className: "",
                        //orderable: true, jika ingin kolom bisa diurutkan
                        orderable: true,
                        //searchable: true, jika ingin kolom bisa dicari
                        searchable: true
                    },{
                        data: "nomor_induk",
                        className: "",
                        orderable:true,
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
                        data: "nomor_telp",
                        className: "",
                        orderable: true,
                        searchable: true
                    },{
                        data: "aksi",
                        className: "text-center",
                        orderable:false,
                        searchable: false
                    }
                ]
            });
            $('#table_personil_akademik_filter input').unbind().bind().on('keyup', function(e){
                if(e.keyCode == 13){ // enter key
                    dataPersonil.search(this.value).draw();
                }
            });
            $('#filter_level').on('change', function(){
                dataPersonil.ajax.reload();
            });
        });
    </script>
@endpush