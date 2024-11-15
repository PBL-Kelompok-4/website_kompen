<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MahasiswaModel;
use App\Models\ProdiModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;


class MahasiswaController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar mahasiswa',
            'list' => ['Home', 'Mahasiswa']
        ];

        $page = (object)[
            'title' => 'Daftar mahasiswa yang terdaftar dalam sistem'
        ];

        $activeMenu = 'mahasiswa'; // set menu yang sedang aktif

        $prodi = ProdiModel::all(); // ambil data prodi

        return view('mahasiswa.index', ['breadcrumb' => $breadcrumb, 'page' => $page,'prodi' => $prodi, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request){
        $mahasiswas = MahasiswaModel::select('id_mahasiswa' ,'id_prodi', 'nomor_induk', 'username', 'nama', 'semester', 'jam_alpha', 'jam_kompen', 'jam_kompen_selesai')->with('prodi');

        //Filter data mahasiswa berdasarkan id_prodi
        if($request->id_prodi){
            $mahasiswas->where('id_prodi', $request->id_prodi);
        }

        if($request->semester){
            $mahasiswas->where('semester', $request->semester);
        }

        return DataTables::of($mahasiswas)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($mahasiswa){ //menambahkan kolom aksi

                $btn = '<button onclick="modalAction(\''.url('/mahasiswa/'. $mahasiswa->id_mahasiswa . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/mahasiswa/' . $mahasiswa->id_mahasiswa . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/mahasiswa/' . $mahasiswa->id_mahasiswa . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function show_ajax(string $id){
        $mahasiswa = MahasiswaModel::find($id);

        return view('mahasiswa.show_ajax', ['mahasiswa' => $mahasiswa]);
    }

    public function create_ajax() {
        $prodi = ProdiModel::select('id_prodi', 'nama_prodi')->get();

        return view('mahasiswa.create_ajax')->with('prodi', $prodi);
    }

    public function store_ajax(Request $request) {
        // cek apakah request berupa ajax
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'id_prodi' => 'required|integer',
                'nomor_induk' => 'required|string|max:10|unique:mahasiswa,nomor_induk',
                'username' => 'required|string|min:3|max:20|unique:mahasiswa,username',
                'nama' => 'required|string|min:3|max:150',
                'semester' => 'required|integer',
                'password' => 'required|min:6|max:20',
                'jam_alpha' => 'required|integer',
                'jam_kompen' => 'required|integer',
                'jam_kompen_selesai' => 'required|integer',
                'id_level' => 'required|integer'
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

            MahasiswaModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data mahasiswa berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax(string $id){
        $mahasiswa = MahasiswaModel::find($id);
        $prodi = ProdiModel::select('id_prodi', 'nama_prodi')->get();

        return view('mahasiswa.edit_ajax',['mahasiswa' => $mahasiswa, 'prodi' => $prodi]);
    }

    public function update_ajax(Request $request, $id){
        //cek apakah request dari ajax
        if($request->ajax() || $request->wantsJson()){
            $rules =[
                'id_prodi' => 'required|integer',
                'nomor_induk' => 'required|string|max:10|unique:mahasiswa,nomor_induk,'.$id.',id_mahasiswa',
                'username' => 'required|string|min:3|max:20|unique:mahasiswa,username,'.$id.',id_mahasiswa',
                'nama' => 'required|string|min:3|max:150',
                'semester' => 'required|integer',
                'password' => 'nullable|min:6|max:20',
                'jam_alpha' => 'required|integer',
                'jam_kompen' => 'required|integer',
                'jam_kompen_selesai' => 'required|integer',
                'id_level' => 'required|integer'
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

    public function confirm_ajax(string $id){
        $mahasiswa = MahasiswaModel::find($id);

        return view('mahasiswa.confirm_ajax', ['mahasiswa' => $mahasiswa]);
    }

    public function delete_ajax(Request $request, $id){
        // cek apakah request dari ajax
        if($request->ajax() || $request->wantsJson()){
            $mahasiswa = MahasiswaModel::find($id);
            if($mahasiswa){
                $mahasiswa->delete();
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
        $check = MahasiswaModel::find($id);
        if(!$check){ // untuk mengecek apakah data mahasiswa dengan id yang dimaksud ada atau tidak
            return redirect('/mahasiswa')->with('error', 'Data mahasiswa tidak ditemukan');
        }

        try{
            MahasiswaModel::destroy($id); //Hapus data mahasiswa
            return redirect('/mahasiswa')->with('success', 'Data mahasiswa berhasil dihapus');
        } catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/mahasiswa')->with('error', 'Data mahasiswa gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
