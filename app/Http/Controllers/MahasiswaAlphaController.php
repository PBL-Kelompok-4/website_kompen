<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MahasiswaModel;
use App\Models\ProdiModel;
use App\Models\ListKompetensiMahasiswaModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class MahasiswaAlphaController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar mahasiswa alpha',
            'list' => ['Home', 'Mahasiswa Alpha']
        ];

        $page = (object)[
            'title' => 'Daftar mahasiswa alpha yang terdaftar dalam sistem'
        ];

        $activeMenu = 'mahasiswa_alpha'; // set menu yang sedang aktif

        $prodi = ProdiModel::all(); // ambil data prodi

        return view('mahasiswa_alpha.index', ['breadcrumb' => $breadcrumb, 'page' => $page,'prodi' => $prodi, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request){

        $mahasiswa_alphas = MahasiswaModel::select('id_mahasiswa' ,'id_prodi', 'nomor_induk', 'username', 'nama', 'semester', 'jam_alpha')->where('jam_alpha', '>=', '1')->with('prodi');

        //Filter data mahasiswa berdasarkan id_prodi
        if($request->id_prodi){
            $mahasiswa_alphas->where('id_prodi', $request->id_prodi);
        }

        if($request->semester){
            $mahasiswa_alphas->where('semester', $request->semester);
        }

        return DataTables::of($mahasiswa_alphas)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($mahasiswa_alpha){ //menambahkan kolom aksi

                $btn = '<button onclick="modalAction(\''.url('/mahasiswa_alpha/'. $mahasiswa_alpha->id_mahasiswa . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                if(auth()->user()->level->kode_level == "ADM"){
                    $btn .= '<button onclick="modalAction(\''.url('/mahasiswa_alpha/' . $mahasiswa_alpha->id_mahasiswa . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                }
                // $btn .= '<button onclick="modalAction(\''.url('/mahasiswa_alpha/' . $mahasiswa_alpha->id_mahasiswa . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function show_ajax(string $id){
        $mahasiswa_alpha = MahasiswaModel::find($id);

        return view('mahasiswa_alpha.show_ajax', ['mahasiswa_alpha' => $mahasiswa_alpha]);
    }

    public function edit_ajax(string $id){
        $mahasiswa_alpha = MahasiswaModel::find($id);
        $prodi = ProdiModel::select('id_prodi', 'nama_prodi')->get();

        return view('mahasiswa_alpha.edit_ajax',['mahasiswa_alpha' => $mahasiswa_alpha, 'prodi' => $prodi]);
    }

    public function update_ajax(Request $request, $id){
        //cek apakah request dari ajax
        if($request->ajax() || $request->wantsJson()){
            $rules =[
                'id_prodi' => 'integer',
                'nomor_induk' => 'string|max:10|unique:mahasiswa,nomor_induk,'.$id.',id_mahasiswa',
                'username' => 'string|min:3|max:20|unique:mahasiswa,username,'.$id.',id_mahasiswa',
                'nama' => 'string|min:3|max:150',
                'semester' => 'integer|min:1|max:14',
                'jam_alpha' => 'required|integer'
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

            $check = MahasiswaModel::find($id);
            if($check){
                if(!$request->filled('password')){//jika password tidak diisim maka hapus dari request
                    $request->request->remove('password');
                }

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
}
