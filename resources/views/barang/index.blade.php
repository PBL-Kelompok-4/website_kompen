@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Barang</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/barang/import') }}')" class="btn btn-info">Import Barang</button>
                <a href="{{ url('/barang/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Barang</a>
                <a href="{{ url('/barang/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Barang</a>
                <button onclick="modalAction('{{ url('barang/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_date" class="col-md-1 col-form-label">Filter:</label>
                            <div class="col-md-3">
                                <select name="filter_kategori" id="filter_kategori" class="form-control form-control filter_kategori" required>
                                    <option value="">- Semua -</option>
                                    @foreach ($kategori as $item)
                                        <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Kategori Barang</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_barang">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Barang Kode</th>
                        <th>Barang Nama</th>
                        <th>Kategori Barang</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
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
        var dataBarang
        $(document).ready(function(){
            dataBarang = $('#table_barang').DataTable({
                processing: true,
                //serverSide: true, jika ingin menggunakan server side processing
                serverSide: true,
                ajax:{
                    "url": "{{ url('barang/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d){
                        d.kategori_id = $('#filter_kategori').val();
                    }
                },
                columns:[
                    {
                        //nomor urut dari laravel datatable addIndexColumn()
                        data: "DT_RowIndex",
                        className: "text-center",
                        width: "5%",
                        orderable: false,
                        searchable: false
                    },{
                        data: "barang_kode",
                        className: "",
                        width: "10%",
                        //orderable: true, jika ingin kolom bisa diurutkan
                        orderable: true,
                        //searchable: true, jika ingin kolom bisa dicari
                        searchable: true
                    },{
                        data: "barang_nama",
                        className: "",
                        width: "37%",
                        orderable:true,
                        searchable: true
                    },{
                        data: "kategori.kategori_nama",
                        className: "",
                        width: "14%",
                        orderable:true,
                        searchable: true
                    },{
                        data: "harga_beli",
                        className: "",
                        width: "10%",
                        orderable:true,
                        searchable: true,
                        render: function(data, type, row){
                            return new Intl.NumberFormat('id-ID').format(data);
                        }
                    },{
                        data: "harga_jual",
                        className: "",
                        width: "10%",
                        orderable:true,
                        searchable: true,
                        render: function(data, type, row){
                            return new Intl.NumberFormat('id-ID').format(data);
                        }
                    },{
                        data: "aksi",
                        className: "",
                        width: "14%",
                        orderable:false,
                        searchable: false
                    }
                ]
            });

            $('#table_barang_filter input').unbind().bind().on('keyup', function(e){
                if(e.keyCode == 13){ // enter key
                    dataBarang.search(this.value).draw();
                }
            });

            $('#filter_kategori').on('change', function(){
                dataBarang.draw();
            });
        });
    </script>
@endpush