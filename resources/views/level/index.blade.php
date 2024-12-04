@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title}}</h3>
            <div class="card-tools">
                {{-- <button onclick="modalAction('{{ url('/level/import') }}')" class="btn btn-info">Import Data Mahasiswa</button>
                <a href="{{ url('/level/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Data Mahasiswa</a>
                <a href="{{ url('/level/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Data Mahasiswa</a> --}}
                {{-- <button onclick="modalAction('{{ url('level/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button> --}}
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table_level">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Level</th>
                        <th>Nama Level</th>
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

        var dataLevel;
        $(document).ready(function(){
            dataLevel = $('#table_level').DataTable({
                processing: true,
                //serverSide: true, jika ingin menggunakan server side processing
                serverSide: true,
                ajax:{
                    "url": "{{ url('level/list') }}",
                    "dataType": "json",
                    "type": "POST"
                },
                columns:[
                    {
                        //nomor urut dari laravel datatable addIndexColumn()
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },{
                        data: "kode_level",
                        className: "",
                        //orderable: true, jika ingin kolom bisa diurutkan
                        orderable: true,
                        //searchable: true, jika ingin kolom bisa dicari
                        searchable: true
                    },{
                        data: "nama_level",
                        className: "",
                        orderable:true,
                        searchable: true
                    },{
                        data: "aksi",
                        className: "text-center",
                        orderable:false,
                        searchable: false
                    }
                ]
            });
            $('#table_level_filter input').unbind().bind().on('keyup', function(e){
                if(e.keyCode == 13){ // enter key
                    dataPersonil.search(this.value).draw();
                }
            });
        });
    </script>
@endpush