<?php

namespace App\Http\Controllers;

use App\Models\PeriodeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class PeriodeController extends Controller
{
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Daftar periode',
            'list' => ['Home', 'Periode']
        ];

        $page = (object) [
            'title' => 'Daftar periode yang terdaftar dalam sistem'
        ];

        $activeMenu = 'periode';

        $periode = PeriodeModel::all();

        return view('periode.index', ['breadcrumb' => $breadcrumb, 'page' => $page,'activeMenu' => $activeMenu, 'periode' => $periode]);
    }

    public function list(){
        
        $periode = PeriodeModel::all();

        return DataTables::of($periode)
            ->addIndexColumn()
            ->addColumn('aksi', function ($periode){
                $btn = '<button onclick="modalAction(\''.url('/periode/' . $periode->id_periode . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/periode/' . $periode->id_periode . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax(){
        return view('periode.create_ajax');
    }

    public function store_ajax(Request $request) {
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'periode' => 'required|integer|min:2000|max:3000'
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

            $has_periode = PeriodeModel::select('periode')
            ->where('periode', $request->periode)
            ->exists();
            if($has_periode){
                return response()->json([
                    'status' => false,
                    'message' => 'Periode sudah ada'
                ]);
            }

            PeriodeModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data periode berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax(string $id){
        $periode = PeriodeModel::find($id);

        return view('periode.edit_ajax', ['periode' => $periode]);
    }

    public function update_ajax(Request $request, $id){
        if($request->ajax() || $request->wantsJson()){
            $rules =[
                'periode' => 'required|integer|min:2000|max:3000'
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

            $has_periode = PeriodeModel::select('periode')
            ->where('periode', $request->periode)
            ->exists();
            if($has_periode){
                return response()->json([
                    'status' => false,
                    'message' => 'Periode sudah ada'
                ]);
            }

            $check = PeriodeModel::find($id);
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
        $periode = PeriodeModel::find($id);

        return view('periode.confirm_ajax', ['periode' => $periode]);
    }

    public function delete_ajax(Request $request, $id){
        if($request->ajax() || $request->wantsJson()){
            $periode = PeriodeModel::find($id);
            if($periode){
                $periode->delete();
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
        $check = PeriodeModel::find($id);
        if(!$check){ 
            return redirect('/periode')->with('error', 'Data periode tidak ditemukan');
        }

        try{
            PeriodeModel::destroy($id); //Hapus data kompetensi
            return redirect('/periode')->with('success', 'Data kompetensi berhasil dihapus');
        } catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/periode')->with('error', 'Data kompetensi gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
