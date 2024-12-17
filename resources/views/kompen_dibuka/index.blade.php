@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title}}</h3>
            <div class="card-tools">
                {{-- <button onclick="modalAction('{{ url('/kompen/import') }}')" class="btn btn-info">Import Data Mahasiswa</button>
                <a href="{{ url('/kompen/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Data Mahasiswa</a>
                <a href="{{ url('/kompen/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Data Mahasiswa</a> --}}
                {{-- <button onclick="modalAction('{{ url('kompen/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button> --}}
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
                        {{-- <div class="col-3">
                            <select name="filter_kompetensi" id="filter_kompetensi" class="form-control" required>
                                <option value="">- Semua -</option>
                                @foreach ($kompetensi as $item)
                                    <option value="{{ $item->id_kompetensi }}">{{ $item->nama_kompetensi }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Kompetensi Kompen</small>
                        </div> --}}
                        <div class="col-3">
                            <select name="filter_jenis_kompen" id="filter_jenis_kompen" class="form-control" required>
                                <option value="">- Semua -</option>
                                @foreach ($jenis_kompen as $j)
                                    <option value="{{ $j->id_jenis_kompen }}">{{ $j->nama_jenis }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Jenis Kompen</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_kompen">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kompen</th>
                        <th>Pemberi Tugas</th>
                        <th>Jenis Kompen</th>
                        <th>Kuota</th>
                        <th>Jam Konversi</th>
                        <th>Tanggal Mulai Pengerjaan</th>
                        <th>Tanggal Selesai Pengerjaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="50%" aria-hidden="true"></div>
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

        var dataKompenDibuka;
        $(document).ready(function(){
            dataKompenDibuka = $('#table_kompen').DataTable({
                processing: true,
                //serverSide: true, jika ingin menggunakan server side processing
                serverSide: true,
                ajax:{
                    "url": "{{ url('kompen_dibuka/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d){
                        // d.id_kompetensi = $('#filter_kompetensi').val();
                        d.id_jenis_kompen = $('#filter_jenis_kompen').val();
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
                        data: "nama",
                        className: "text-center",
                        //orderable: true, jika ingin kolom bisa diurutkan
                        orderable: true,
                        //searchable: true, jika ingin kolom bisa dicari
                        searchable: true
                    },{
                        // mengambil data personil akademik dari hasil ORM berelasi
                        data: "personil_akademik.nama",
                        className: "text-center",
                        orderable: true,
                        searchable: true
                    },{
                        // mengambil data jenis kompen dari hasil ORM berelasi
                        data: "jenis_kompen.nama_jenis",
                        className: "text-center",
                        orderable: true,
                        searchable: true
                    },{
                        data: "kuota",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },{
                        data: "jam_kompen",
                        className: "text-center",
                        orderable: true,
                        searchable: false
                    },{
                        data: "tanggal_mulai",
                        className: "text-center",
                        orderable: true,
                        searchable: false
                    },{
                        data: "tanggal_selesai",
                        className: "text-center",
                        orderable: true,
                        searchable: false
                    },{
                        data: "aksi",
                        className: "text-center",
                        orderable:false,
                        searchable: false
                    }
                ]
            });
            $('#table_mahasiswa_filter input').unbind().bind().on('keyup', function(e){
                if(e.keyCode == 13){ // enter key
                    dataKompenDibuka.search(this.value).draw();
                }
            });
            // $('#filter_kompetensi').on('change', function(){
            //     dataKompenDibuka.ajax.reload();
            // });
            $('#filter_jenis_kompen').on('change', function(){
                dataKompenDibuka.ajax.reload();
            });
        });
    </script>
@endpush