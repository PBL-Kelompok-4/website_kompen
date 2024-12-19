<?php

namespace App\Http\Controllers;

use App\Models\JenisKompenModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class JenisKompenController extends Controller
{
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Jenis-jenis kompen',
            'list' => ['Home', 'Jenis kompen']
        ];

        $page = (object) [
            'title' => 'Daftar jenis kompen yang terdaftar dalam sistem'
        ];

        $activeMenu = 'jenis_kompen';

        $jenis_kompen = JenisKompenModel::all();

        return view('jenis_kompen.index', ['breadcrumb' => $breadcrumb, 'page' => $page,'activeMenu' => $activeMenu, 'jenis_kompen' => $jenis_kompen]);
    }

    public function list(){
        
        $jenis_kompen = JenisKompenModel::all();

        return DataTables::of($jenis_kompen)
            ->addIndexColumn()
            ->addColumn('aksi', function ($jenis_kompen){
                $btn = '<button onclick="modalAction(\''.url('/jenis_kompen/' . $jenis_kompen->id_jenis_kompen . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/jenis_kompen/' . $jenis_kompen->id_jenis_kompen . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax(){
        return view('jenis_kompen.create_ajax');
    }

    public function store_ajax(Request $request) {
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_jenis' => 'required|min:3|max:10',
                'nama_jenis' => 'required|min:3|max:255'
            ];

            //use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $has_kode_jenis = JenisKompenModel::select('kode_jenis')
            ->where('kode_jenis', $request->kode_jenis)
            ->exists();
            if($has_kode_jenis){
                return response()->json([
                    'status' => false,
                    'message' => 'Kode jenis kompen sudah ada'
                ]);
            }

            JenisKompenModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data jenis kompen berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax(string $id){
        $jenis_kompen = JenisKompenModel::find($id);

        return view('jenis_kompen.edit_ajax', ['jenis_kompen' => $jenis_kompen]);
    }

    public function update_ajax(Request $request, $id){
        if($request->ajax() || $request->wantsJson()){
            $rules =[
                'kode_jenis' => 'required|string|min:3|max:10|unique:jenis_kompen,kode_jenis,'.$id.',id_jenis_kompen',
                'nama_jenis' => 'required|string|min:3|max:255'
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

            $check = JenisKompenModel::find($id);
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
        $jenis_kompen = JenisKompenModel::find($id);

        return view('jenis_kompen.confirm_ajax', ['jenis_kompen' => $jenis_kompen]);
    }

    public function delete_ajax(Request $request, $id){
        if($request->ajax() || $request->wantsJson()){
            $jenis_kompen = JenisKompenModel::find($id);
            if($jenis_kompen){
                $jenis_kompen->delete();
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
        $check = JenisKompenModel::find($id);
        if(!$check){ 
            return redirect('/jenis_kompen')->with('error', 'Data jenis kompen tidak ditemukan');
        }

        try{
            JenisKompenModel::destroy($id); //Hapus data kompetensi
            return redirect('/jenis_kompen')->with('success', 'Data jenis kompen berhasil dihapus');
        } catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/jenis_kompen')->with('error', 'Data kompetensi gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
