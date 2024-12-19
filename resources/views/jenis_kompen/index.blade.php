@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title}}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('jenis_kompen/create_ajax') }}')" class="btn btn-success">Tambah Data Jenis Kompen</button>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table_jenis_kompen">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama</th>
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

        var dataJenisKompen;
        $(document).ready(function(){
            dataJenisKompen = $('#table_jenis_kompen').DataTable({
                processing: true,
                serverSide: true,
                ajax:{
                    "url": "{{ url('jenis_kompen/list') }}",
                    "dataType": "json",
                    "type": "POST"
                },
                columns:[
                    {
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },{
                        data: "kode_jenis",
                        className: "",
                        orderable: true,
                        searchable: true
                    },{
                        data: "nama_jenis",
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
            $('#table_jenis_kompen input').unbind().bind().on('keyup', function(e){
                if(e.keyCode == 13){ // enter key
                    dataJenisKompen.search(this.value).draw();
                }
            });
        });
    </script>
@endpush