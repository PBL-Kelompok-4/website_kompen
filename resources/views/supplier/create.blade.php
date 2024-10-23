@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            <form action="{{ url('supplier')}}" method="POST" class="form-horizontal">
                @csrf
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Supplier Kode</label>
                    <div class="col-11">
                        <input type="text" class="form-control" id="supplier_kode" name="supplier_kode" value="{{ old('supplier_kode') }}" required>
                        @error('supplier_kode')
                            <small class="form-text text-danger">{{ $massage }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Supplier Nama</label>
                    <div class="col-11">
                        <input type="text" class="form-control" id="supplier_nama" name="supplier_nama" value="{{ old('supplier_nama') }}" required>
                        @error('supplier_nama')
                            <small class="form-text text-danger">{{ $massage }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Supplier Alamat</label>
                    <div class="col-11">
                        <input type="text" class="form-control" id="supplier_alamat" name="supplier_alamat" value="{{ old('supplier_alamat') }}" required>
                        @error('supplier_alamat')
                            <small class="form-text text-danger">{{ $massage }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label"></label>
                    <div class="col-11">
                        <button class="btn btn-primary btn-sm">Simpan</button>
                        <a href="{{ url('supplier') }}" class="btn btn-sm btn-default ml-1">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('css')
@endpush
@push('js')
@endpush