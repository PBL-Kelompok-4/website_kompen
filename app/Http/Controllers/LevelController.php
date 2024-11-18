<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LevelModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class LevelController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar level user',
            'list' => ['Home', 'Level User']
        ];

        $page = (object)[
            'title' => 'Daftar level user akademik yang terdaftar dalam sistem'
        ];

        $activeMenu = 'level'; // set menu yang sedang aktif

        return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request){
        $levels = LevelModel::select('id_level', 'kode_level', 'nama_level');

        return DataTables::of($levels)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level){ //menambahkan kolom aksi

                $btn = '<button onclick="modalAction(\''.url('/level/'. $level->id_level . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                // $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->id_level . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                // $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->id_level . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create_ajax() {
        
        return view('level.create_ajax');
    }

    public function store_ajax(Request $request) {
        // cek apakah request berupa ajax
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_level' => 'required|string|max:10|unique:level,kode_level',
                'nama_level' => 'required|string|min:3|max:150'
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

            LevelModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data level berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax(string $id){
        $level = LevelModel::find($id);

        return view('level.edit_ajax')->with('level', $level);
    }

    public function update_ajax(Request $request, $id){
        //cek apakah request dari ajax
        if($request->ajax() || $request->wantsJson()){
            $rules =[
                'kode_level' => 'required|string|max:10|unique:level,kode_level,'.$id.',id_level',
                'nama_level' => 'required|string|min:3|max:150'
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

            $check = LevelModel::find($id);
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
        $level = LevelModel::find($id);

        return view('level.confirm_ajax', ['level' => $level]);
    }

    public function delete_ajax(Request $request, $id){
        // cek apakah request dari ajax
        if($request->ajax() || $request->wantsJson()){
            $level = LevelModel::find($id);
            if($level){
                $level->delete();
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
        $check = LevelModel::find($id);
        if(!$check){ // untuk mengecek apakah data level dengan id yang dimaksud ada atau tidak
            return redirect('/level')->with('error', 'Data level tidak ditemukan');
        }

        try{
            LevelModel::destroy($id); //Hapus data level
            return redirect('/level')->with('success', 'Data level berhasil dihapus');
        } catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/level')->with('error', 'Data level gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function show_ajax(string $id){
        $level = LevelModel::find($id);

        return view('level.show_ajax', ['level' => $level]);
    }
}
