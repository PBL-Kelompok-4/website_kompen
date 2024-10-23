<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use Illuminate\Http\Request;
use App\Models\StokModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class StokController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Stok',
            'list' => ['Home', 'Stok']
        ];

        $page = (object)[
            'title' => 'Daftar stok yang tersedia dalam sistem'
        ];

        $stok = StokModel::all();
        $supplier = SupplierModel::all();
        $barang = BarangModel::all();
        $user = UserModel::all();

        $activeMenu = 'stok'; // set menu yang sedang aktif

        return view('stok.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'stok' => $stok, 'activeMenu' => $activeMenu, 'supplier' => $supplier, 'barang' => $barang, 'user' => $user]);
    }

    public function list(Request $request){
        $stoks = StokModel::select('stok_id', 'supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')->with('supplier', 'barang', 'user');

        //Filter data user berdasarkan supplier_id
        if($request->supplier_id){
            $stoks->where('supplier_id', $request->supplier_id);
        }

        if($request->barang_id){
            $stoks->where('barang_id', $request->barang_id);
        }

        if($request->user_id){
            $stoks->where('user_id', $request->user_id);
        }

        return DataTables::of($stoks)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($stok){ //menambahkan kolom aksi
                // $btn  = '<a href="' . url('/user/' . $user->user_id).'" class="btn btn-info btn-sm">Detail</a> '; 
                // $btn .= '<a href="' . url('/user/' . $user->user_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="'.url('/user/'.$user->user_id).'">'
                // .csrf_field().method_field('DELETE'). '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah anda yakin menghapus data ini?\');">Hapus</button></form>';

                $btn = '<button onclick="modalAction(\''.url('/stok/'. $stok->stok_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function destroy(string $id){
        $check = StokModel::find($id);
        if(!$check){ // untuk mengecek apakah data stok dengan id yang dimaksud ada atau tidak
            return redirect('/stok')->with('error', 'Data user tidak ditemukan');
        }

        try{
            StokModel::destroy($id); //Hapus data stok
            return redirect('/stok')->with('success', 'Data stok berhasil dihapus');
        } catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/stok')->with('error', 'Data stok gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function create_ajax() {
        // $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();
        // $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        // $user = UserModel::select('user_id', 'nama')->get();

        $supplier = SupplierModel::all();
        $barang = BarangModel::all();
        $user = UserModel::all();

        return view('stok.create_ajax')->with('supplier' , $supplier)->with('barang' , $barang)->with('user', $user);
    }

    public function store_ajax(Request $request) {
        // cek apakah request berupa ajax
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_id' => 'required|integer',
                'barang_id' => 'required|integer',
                'user_id' => 'required|integer',
                'stok_tanggal' => 'required',
                'stok_jumlah' => 'required|integer'
            ];

            //use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }

            StokModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function show_ajax(string $id){
        $stok = StokModel::find($id);

        return view('stok.show_ajax', ['stok' => $stok]);
    }

    public function edit_ajax(string $id){
        $stok = StokModel::find($id);
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();
        $user = UserModel::select('user_id', 'nama')->get();

        return view('stok.edit_ajax',['stok' => $stok, 'barang' => $barang, 'supplier' => $supplier, 'user' => $user]);
    }

    public function update_ajax(Request $request, String $id){
        //cek apakah request dari ajax
        if($request->ajax() || $request->wantsJson()){
            $rules =[
                'supplier_id' => 'required|integer',
                'barang_id' => 'required|integer',
                'user_id' => 'required|integer',
                'stok_tanggal' => 'required',
                'stok_jumlah' => 'required|integer'
            ];

            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json([
                    'status' => false, //respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }

            $check = StokModel::find($id);
            if($check){
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/stok');
    }
}
