<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KompenModel;
use App\Models\KompetensiModel;
use App\Models\JenisKompenModel;
use App\Models\KompenDetailModel;
use App\Models\PengajuanKompenModel;
use App\Models\MahasiswaModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class KompenDibukaController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar kompen',
            'list' => ['Home', 'Kompen']
        ];

        $page = (object)[
            'title' => 'Daftar kompen yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kompen_dibuka'; // set menu yang sedang aktif

        $jenis_kompen = JenisKompenModel::all(); // ambil data jenis kompen
        $kompetensi = KompetensiModel::all(); // ambil data kompetensi

        return view('kompen_dibuka.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'jenis_kompen' => $jenis_kompen, 'kompetensi' => $kompetensi, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request){
        if(auth()->user()->level->kode_level == "ADM"){
            $kompens = KompenModel::select('id_kompen' ,'nomor_kompen', 'nama', 'deskripsi', 'id_personil', 'id_jenis_kompen', 'kuota', 'jam_kompen', 'status', 'is_selesai', 'tanggal_mulai', 'tanggal_selesai', 'status_acceptance')
            ->where('status', 'dibuka')
            ->where('is_selesai', 'no')
            ->where('status_acceptance', 'accept')
            ->with('jenisKompen', 'personilAkademik');
            
        } elseif (auth()->user()->level->kode_level == "DSN" || auth()->user()->level->kode_level == "TDK") {
            $id_personil = auth()->user()->id_personil;
            $kompens = KompenModel::select('id_kompen' ,'nomor_kompen', 'nama', 'deskripsi', 'id_personil', 'id_jenis_kompen', 'kuota', 'jam_kompen', 'status', 'is_selesai', 'tanggal_mulai', 'tanggal_selesai', 'status_acceptance')
            ->where('status', 'dibuka')
            ->where('is_selesai', 'no')
            ->where('status_acceptance', 'accept')
            ->where('id_personil', $id_personil)
            ->with('jenisKompen', 'personilAkademik');

        } elseif(auth()->user()->level->kode_level == "MHS"){

            $id_mahasiswa = auth()->user()->id_mahasiswa;
            $jam_kompen_mahasiswa = MahasiswaModel::select('jam_kompen')->where('id_mahasiswa', $id_mahasiswa);

            $kompens = KompenModel::select('id_kompen' ,'nomor_kompen', 'nama', 'deskripsi', 'id_personil', 'id_jenis_kompen', 'kuota', 'jam_kompen', 'status', 'is_selesai', 'tanggal_mulai', 'tanggal_selesai', 'status_acceptance')
            ->where('status', 'dibuka')
            ->where('is_selesai', 'no')
            ->where('status_acceptance', 'accept')
            ->where('jam_kompen', '<=', $jam_kompen_mahasiswa)
            ->with('jenisKompen', 'personilAkademik');
        }


        //Filter data kompen berdasarkan id_jenis_kompen
        if($request->id_jenis_kompen){
            $kompens->where('id_jenis_kompen', $request->id_jenis_kompen);
        }
        return DataTables::of($kompens)
        // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
        ->addIndexColumn()
        ->addColumn('aksi', function ($kompen){ //menambahkan kolom aksi

            $btn = '<button onclick="modalAction(\''.url('/kompen_dibuka/'. $kompen->id_kompen . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
            if(auth()->user()->level->kode_level == "ADM" || auth()->user()->level->kode_level == "DSN" || auth()->user()->level->kode_level == "TDK"){
                $btn .= '<button onclick="modalAction(\''.url('/kompen_dibuka/' . $kompen->id_kompen . '/list_pelamar_ajax').'\')" class="btn btn-success btn-sm">Daftar Pelamar</button> ';
            }
            return $btn;
        })
        ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
        ->make(true);
    }

    public function show_ajax(string $id){
        $kompen_dibuka = KompenModel::find($id);

        return view('kompen_dibuka.show_ajax', ['kompen_dibuka' => $kompen_dibuka]);
    }

    public function ajukan_kompen(Request $request){
        $id_mahasiswa = auth()->user()->id_mahasiswa;

        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'id_kompen' => 'required|integer'
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

            $pengajuan_lain = PengajuanKompenModel::where('id_mahasiswa', $id_mahasiswa)->where('status' , 'pending')->first();
            $pengajuan_sama = PengajuanKompenModel::where('id_mahasiswa', $id_mahasiswa)->where('status' , 'pending')->where('id_kompen', $request->id_kompen)->first();
            $sedang_kompen = KompenDetailModel::where('id_mahasiswa', $id_mahasiswa)->where('status', 'progres')->first();
            
            if($pengajuan_sama){
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Anda sudah melakukan pengajuan ke tugas kompen ini',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            } else if($pengajuan_lain) {
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Anda sedang melakukan pengajuan ke tugas kompen lain',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            } else if($sedang_kompen){
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Anda sedang mengerjakan tugas kompen lain, silahkan selesaikan tugas kompen sebelumnya dahulu',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            } else {
                PengajuanKompenModel::create([
                    'id_kompen' => $request->id_kompen,
                    'id_mahasiswa' => $id_mahasiswa,
                    'created_at' => now()
                ]);
                return response()->json([
                    'status' => true,
                    'message' => 'Silahkan tunggu persetujuan dari admin'
                ]);
            }
        }
        redirect('/');        
    }

    public function show_pelamar(string $id){
        $pengajuan_kompen = PengajuanKompenModel::select('id_pengajuan_kompen', 'id_kompen', 'id_mahasiswa', 'status')->where('id_kompen', $id)->get();
        $data_kompen = KompenModel::find($id);

        return view('kompen_dibuka.pelamar_kompen', ['pengajuan_kompen' => $pengajuan_kompen, 'data_kompen' => $data_kompen]);
    }

    public function list_pelamar(Request $request){ 
        $id = $request->input('id_kompen');
        
        $pengajuan_kompen = PengajuanKompenModel::select(
            'id_pengajuan_kompen', 
            'id_kompen', 
            'id_mahasiswa', 
            'status'
        )
        ->where('id_kompen', $id)
        ->with('mahasiswa', 'mahasiswa.prodi')
        ->get();
    
        return DataTables::of($pengajuan_kompen)
            ->addIndexColumn()
            ->addColumn('aksi', function ($pengajuan_kompen) {
                $btn = '';
                if ($pengajuan_kompen->status == "acc") {
                    $btn = '<button class="btn btn-primary btn-sm">Diterima</button>';
                } elseif ($pengajuan_kompen->status == "reject") {
                    $btn = '<button class="btn btn-warning btn-sm">Ditolak</button>';
                } else {
                    $btn = '<div class="btn-group" role="group">';
                    $btn .= '<button type="button" data-id="' . $pengajuan_kompen->id_pengajuan_kompen . '" ';
                    $btn .= 'data-mahasiswa="' . $pengajuan_kompen->id_mahasiswa . '" ';
                    $btn .= 'class="btn btn-success btn-sm btn-accept">Terima</button>';
                    $btn .= '<button type="button" data-id="' . $pengajuan_kompen->id_pengajuan_kompen . '" ';
                    $btn .= 'data-mahasiswa="' . $pengajuan_kompen->id_mahasiswa . '" ';
                    $btn .= 'class="btn btn-danger btn-sm btn-reject">Tolak</button>';
                    $btn .= '</div>';
                }
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    
    public function update_pengajuan_kompen(Request $request){      
        try {
            $validator = Validator::make($request->all(), [
                'id_pengajuan' => 'required|exists:pengajuan_kompen,id_pengajuan_kompen',
                'status' => 'required|in:acc,reject',
                'id_kompen' => 'required|exists:kompen,id_kompen',
                'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal: ' . $validator->errors()->first()
                ], 422);
            }
    
            $pengajuan_kompen = PengajuanKompenModel::findOrFail($request->id_pengajuan);
            
            if ($pengajuan_kompen->status !== 'pending') {
                return response()->json([
                    'status' => false,
                    'message' => 'Pengajuan ini sudah diproses sebelumnya'
                ], 422);
            }
            
            $pengajuan_kompen->status = $request->status;
            $pengajuan_kompen->save();
    
            if ($request->status == 'acc') {
                KompenDetailModel::create([
                    'id_mahasiswa' => $request->id_mahasiswa,
                    'id_kompen' => $request->id_kompen
                ]);
    
                return response()->json([
                    'status' => true,
                    'message' => 'Berhasil menerima pengajuan'
                ]);
            }
    
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menolak pengajuan'
            ]);
    
        } catch(\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memproses pengajuan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update_status(Request $request){
        //cek apakah request dari ajax
        if($request->ajax() || $request->wantsJson()){
            $rules =[
                'id_kompen' => 'required|integer'
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

            $check = KompenModel::findOrFail($request->id_kompen);
            if($check){
                $check->update([
                    'status' => 'progres'
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
        return redirect('/');
    }
}
