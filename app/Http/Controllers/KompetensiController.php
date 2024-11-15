<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KompetensiModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class KompetensiController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar kompetensi',
            'list' => ['Home', 'Kompetensi']
        ];

        $page = (object)[
            'title' => 'Daftar kompetensi akademik yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kompetensi'; // set menu yang sedang aktif

        return view('kompetensi.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request){
        $kompetensis = KompetensiModel::select('id_kompetensi', 'nama_kompetensi', 'deskripsi_kompetensi');

        return DataTables::of($kompetensis)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kompetensi){ //menambahkan kolom aksi

                if(auth()->user()->level->kode_level == "DSN" || auth()->user()->level->kode_level == "TDK" || auth()->user()->level->kode_level == "MHS"){
                    $btn = '<button onclick="modalAction(\''.url('/kompetensi/'. $kompetensi->id_kompetensi . '/show_ajax').'\')" class="btn btn-info btn-sm col-12">Detail</button> ';
                } elseif(auth()->user()->level->kode_level == "ADM") {
                    $btn = '<button onclick="modalAction(\''.url('/kompetensi/'. $kompetensi->id_kompetensi . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                    $btn .= '<button onclick="modalAction(\''.url('/kompetensi/' . $kompetensi->id_kompetensi . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\''.url('/kompetensi/' . $kompetensi->id_kompetensi . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                }
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create_ajax() {
        
        return view('kompetensi.create_ajax');

    }

    public function store_ajax(Request $request) {
        // cek apakah request berupa ajax
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_kompetensi' => 'required|string|min:3|max:30',
                'deskripsi_kompetensi' => 'required|string|min:3|max:255|max:255'
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

            KompetensiModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data kompetensi berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax(string $id){
        $kompetensi = KompetensiModel::find($id);

        return view('kompetensi.edit_ajax')->with('kompetensi', $kompetensi);
    }

    public function update_ajax(Request $request, $id){
        //cek apakah request dari ajax
        if($request->ajax() || $request->wantsJson()){
            $rules =[
                'nama_kompetensi' => 'required|string|min:3|max:30',
                'deskripsi_kompetensi' => 'required|string|min:3|max:255|max:255'
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

            $check = KompetensiModel::find($id);
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
        return redirect('/');
    }

    public function confirm_ajax(string $id){
        $kompetensi = KompetensiModel::find($id);

        return view('kompetensi.confirm_ajax', ['kompetensi' => $kompetensi]);
    }

    public function delete_ajax(Request $request, $id){
        // cek apakah request dari ajax
        if($request->ajax() || $request->wantsJson()){
            $kompetensi = KompetensiModel::find($id);
            if($kompetensi){
                $kompetensi->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function destroy(string $id){
        $check = KompetensiModel::find($id);
        if(!$check){ // untuk mengecek apakah data kompetensi dengan id yang dimaksud ada atau tidak
            return redirect('/kompetensi')->with('error', 'Data kompetensi tidak ditemukan');
        }

        try{
            KompetensiModel::destroy($id); //Hapus data kompetensi
            return redirect('/kompetensi')->with('success', 'Data kompetensi berhasil dihapus');
        } catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/kompetensi')->with('error', 'Data kompetensi gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function show_ajax(string $id){
        $kompetensi = KompetensiModel::find($id);

        return view('kompetensi.show_ajax', ['kompetensi' => $kompetensi]);
    }
}
