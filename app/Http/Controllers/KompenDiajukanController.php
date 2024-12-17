<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KompenModel;
use App\Models\KompetensiModel;
use App\Models\JenisKompenModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class KompenDiajukanController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar kompen diajukan',
            'list' => ['Home', 'Kompen Diajukan']
        ];

        $page = (object)[
            'title' => 'Daftar kompen yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kompen_diajukan'; // set menu yang sedang aktif

        $jenis_kompen = JenisKompenModel::all(); // ambil data jenis kompen
        $kompetensi = KompetensiModel::all(); // ambil data kompetensi

        return view('kompen_diajukan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'jenis_kompen' => $jenis_kompen, 'kompetensi' => $kompetensi, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request){
        if(auth()->user()->level->kode_level == "ADM"){
            $kompens = KompenModel::select('id_kompen' ,'nomor_kompen', 'nama', 'deskripsi', 'id_personil', 'id_jenis_kompen', 'kuota', 'jam_kompen', 'status', 'is_selesai', 'tanggal_mulai', 'tanggal_selesai', 'status_acceptance')
            ->where('status', 'ditutup')
            ->where('is_selesai', 'no')
            ->where('status_acceptance', 'pending')
            ->with('jenisKompen', 'personilAkademik');
        } elseif (auth()->user()->level->kode_level == "DSN" || auth()->user()->level->kode_level == "TDK") {
            $id_personil = auth()->user()->id_personil;
            $kompens = KompenModel::select('id_kompen' ,'nomor_kompen', 'nama', 'deskripsi', 'id_personil', 'id_jenis_kompen', 'kuota', 'jam_kompen', 'status', 'is_selesai', 'tanggal_mulai', 'tanggal_selesai', 'status_acceptance')
            ->where('status', 'ditutup')
            ->where('is_selesai', 'no')
            ->where('status_acceptance', 'pending')
            ->where('id_personil', $id_personil)
            ->with('jenisKompen', 'personilAkademik');
        }

        //Filter data kompen berdasarkan id_jenis_kompen
        if($request->id_jenis_kompen){
            $kompens->where('id_jenis_kompen', $request->id_jenis_kompen);
        }

        // if($request->id_kompetensi){
        //     $kompens->where('id_kompetensi', $request->id_kompetensi);
        // }
        return DataTables::of($kompens)
        // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
        ->addIndexColumn()
        ->addColumn('aksi', function ($kompen){ //menambahkan kolom aksi

            $btn = '<button onclick="modalAction(\''.url('/kompen_diajukan/'. $kompen->id_kompen . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
            // $btn .= '<button onclick="modalAction(\''.url('/kompen_diajukan/' . $kompen->id_kompen . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
            // $btn .= '<button onclick="modalAction(\''.url('/kompen_diajukan/' . $kompen->id_kompen . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
            return $btn;
        })
        ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
        ->make(true);
    }

    public function show_ajax(string $id){
        $kompen_diajukan = KompenModel::find($id);

        return view('kompen_diajukan.show_ajax', ['kompen_diajukan' => $kompen_diajukan]);
    }

    public function create_ajax() {
        $jenis_kompen = JenisKompenModel::select('id_jenis_kompen', 'nama_jenis')->get();

        return view('kompen_diajukan.create_ajax')->with('jenis_kompen', $jenis_kompen);
    }

    public function store_ajax(Request $request) {
        // cek apakah request berupa ajax
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'id_personil' => 'required|integer',
                'nama' => 'required|string|min:3|max:40',
                'deskripsi' => 'required|string|min:3|max:255',
                'id_jenis_kompen' => 'required|integer',
                'kuota' => 'required|integer|min:1',
                'jam_kompen' => 'required|integer|min:1',
                'tanggal_mulai' => 'required',
                'tanggal_selesai' => 'required'
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

            $uuid = Str::uuid();

            // KompenModel::create($request->all());
            KompenModel::create([
                'nomor_kompen' => $uuid,
                'id_personil' => $request->id_personil,
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'id_jenis_kompen' => $request->id_jenis_kompen,
                'kuota' => $request->kuota,
                'jam_kompen' => $request->jam_kompen,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Pengajuan Kompen berhasil dilakukan'
            ]);
        }
        redirect('/');
    }

    public function ditolak(Request $request, string $id){

        $rules = [
            'alasan' => 'required'
        ];

        //use Illuminate\Support\Facades\Validator;
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json([
                'status' => false, // response status, false: error/gagal, true: berhasil
                'message' => 'Mohon Isi Kolom Alasan',
                'msgField' => $validator->errors(), // pesan error validasi
            ]);
        }

        $check = KompenModel::find($id);
        if($check){
            $check->update([
                'alasan' => $request->alasan,
                'status_acceptance' => 'reject'
            ]);
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

    public function diterima(Request $request, string $id){

        $rules = [
            'alasan' => 'required'
        ];

        //use Illuminate\Support\Facades\Validator;
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json([
                'status' => false, // response status, false: error/gagal, true: berhasil
                'message' => 'Mohon Isi Kolom Alasan',
                'msgField' => $validator->errors(), // pesan error validasi
            ]);
        }

        $check = KompenModel::find($id);
        if($check){
            $check->update([
                'status' => 'dibuka',
                'alasan' => $request->alasan,
                'status_acceptance' => 'accept'
            ]);
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
}
