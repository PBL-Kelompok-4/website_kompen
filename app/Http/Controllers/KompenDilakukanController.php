<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KompenModel;
use App\Models\KompetensiModel;
use App\Models\JenisKompenModel;
use App\Models\KompenDetailModel;
use App\Models\PengajuanKompenModel;
use App\Models\MahasiswaModel;
// use BaconQrCode\Encoder\QrCode;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class KompenDilakukanController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar kompen',
            'list' => ['Home', 'Kompen']
        ];

        $page = (object)[
            'title' => 'Daftar kompen yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kompen_dilakukan'; // set menu yang sedang aktif

        $jenis_kompen = JenisKompenModel::all(); // ambil data jenis kompen
        $kompetensi = KompetensiModel::all(); // ambil data kompetensi

        return view('kompen_dilakukan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'jenis_kompen' => $jenis_kompen, 'kompetensi' => $kompetensi, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request){
        if(auth()->user()->level->kode_level == "ADM"){
            $kompens = KompenModel::select('id_kompen' ,'nomor_kompen', 'nama', 'deskripsi', 'id_personil', 'id_jenis_kompen', 'kuota', 'jam_kompen', 'status', 'is_selesai', 'tanggal_mulai', 'tanggal_selesai', 'status_acceptance')
            ->where('status', 'progres')
            ->where('is_selesai', 'no')
            ->where('status_acceptance', 'accept')
            ->with('jenisKompen', 'personilAkademik');
        } elseif (auth()->user()->level->kode_level == "DSN" || auth()->user()->level->kode_level == "TDK") {
            $id_personil = auth()->user()->id_personil;
            $kompens = KompenModel::select('id_kompen' ,'nomor_kompen', 'nama', 'deskripsi', 'id_personil', 'id_jenis_kompen', 'kuota', 'jam_kompen', 'status', 'is_selesai', 'tanggal_mulai', 'tanggal_selesai', 'status_acceptance')
            ->where('status', 'progres')
            ->where('is_selesai', 'no')
            ->where('status_acceptance', 'accept')
            ->where('id_personil', $id_personil)
            ->with('jenisKompen', 'personilAkademik');
        } elseif (auth()->user()->level->kode_level == "MHS") {
            $id_mahasiswa = auth()->user()->id_mahasiswa;
            $kompens = KompenModel::select('id_kompen' ,'nomor_kompen', 'nama', 'deskripsi', 'id_personil', 'id_jenis_kompen', 'kuota', 'jam_kompen', 'status', 'is_selesai', 'tanggal_mulai', 'tanggal_selesai', 'status_acceptance')
            ->where('status', 'progres')
            ->where('is_selesai', 'no')
            ->where('status_acceptance', 'accept')
            ->whereHas('kompenDetail', function($query) use ($id_mahasiswa){
                $query->where('id_mahasiswa', $id_mahasiswa);
            })
            ->with('jenisKompen', 'personilAkademik', 'kompenDetail');
        }

        //Filter data kompen berdasarkan id_jenis_kompen
        if($request->id_jenis_kompen){
            $kompens->where('id_jenis_kompen', $request->id_jenis_kompen);
        }
        return DataTables::of($kompens)
        ->addIndexColumn()
        ->addColumn('aksi', function ($kompen){ //menambahkan kolom aksi

            $btn = '<button onclick="modalAction(\''.url('/kompen_dilakukan/'. $kompen->id_kompen . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
            if(auth()->user()->level->kode_level == "ADM" || auth()->user()->level->kode_level == "DSN" || auth()->user()->level->kode_level == "TDK"){
                $btn .= '<button onclick="modalAction(\''.url('/kompen_dilakukan/' . $kompen->id_kompen . '/list_pekerja_ajax').'\')" class="btn btn-primary btn-sm">Daftar Pekerja Kompen</button> ';
                // $btn .= '<button onclick="selesaikanKompen('. $kompen->id_kompen.')" class="btn btn-success btn-sm">Selesaikan Kompen</button>';
            } elseif (auth()->user()->level->kode_level == "MHS") {
                $btn .= '<button onclick="modalAction(\''.url('/kompen_dilakukan/' . $kompen->id_kompen . '/upload_progres').'\')" class="btn btn-primary btn-sm">Upload Progress</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kompen_dilakukan/' . $kompen->id_kompen . '/upload_bukti').'\')" class="btn btn-warning btn-sm">Bukti Kompen</button> ';
            }
            return $btn;
        })
        ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
        ->make(true);
    }

    public function show_ajax(string $id){
        $kompen_dilakukan = KompenModel::find($id);

        return view('kompen_dilakukan.show_ajax', ['kompen_dilakukan' => $kompen_dilakukan]);
    }

    public function upload_progres(string $id){
        $id_mahasiswa = auth()->user()->id_mahasiswa;
        $progres_kompen = KompenDetailModel::select('id_kompen_detail', 'id_kompen', 'id_mahasiswa', 'progres_1', 'progres_2', 'status')
        ->where('id_kompen', $id)
        ->where('id_mahasiswa', $id_mahasiswa)
        ->with('kompen')
        ->first();
        return view('kompen_dilakukan.upload_progres', ['progres_kompen' => $progres_kompen]);
    }

    public function update_progres(Request $request, $id){
        if($request->ajax() || $request->wantsJson()){
            $rules =[
                'progres_1' => 'max:255',
                'progres_1' => 'max:255'
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
            // $id_mahasiswa = auth()->user()->id_mahasiswa;
            $check = KompenDetailModel::findOrFail($id);

            if($check){
                $check->update([
                    'progres_1' => $request->progres_1,
                    'progres_2' => $request->progres_2
                ]);
                return response()->json([
                    'status' => true,
                    'message' => 'Progres berhasil disimpan'
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

    public function show_pekerja(string $id){
        $pekerja_kompen = KompenDetailModel::select('id_kompen_detail' ,'id_kompen', 'id_mahasiswa', 'progres_1', 'progres_2', 'status', 'bukti_kompen')->where('id_kompen', $id)->with('kompen')->get();

        $kompen = KompenModel::find($id);
        
        return view('kompen_dilakukan.pekerja_kompen', ['pekerja_kompen' => $pekerja_kompen, 'kompen' => $kompen]);
    }

    public function list_pekerja(Request $request){
        $id_kompen = $request->id_kompen;
        $pekerja_kompen = KompenDetailModel::select('id_kompen_detail' ,'id_kompen', 'id_mahasiswa', 'progres_1', 'progres_2', 'status', 'bukti_kompen')
        ->where('id_kompen', $id_kompen)
        ->with('kompen', 'mahasiswa', 'mahasiswa.prodi')
        ->get();

        return DataTables::of($pekerja_kompen)
            ->addIndexColumn()
            ->addColumn('progres_1', function($pekerja_kompen){
                $btn = '';
                if($pekerja_kompen->progres_1 != null ){
                    $btn .= '<a target="_blank" rel="noopener noreferrer" href="{{ $pekerja_kompen->progres_1 }}">';
                    $btn .= '<button class="btn btn-sm btn-success">Link Progres</button>' ;
                    $btn .= '</a>';
                } else {
                    $btn .= '<button class="btn btn-sm btn-warning">Belum ada progres</button>';
                }
                return $btn;
            })
            ->addColumn('progres_2', function($pekerja_kompen){
                $btn = '';
                if($pekerja_kompen->progres_2 != null ){
                    $btn .= '<a target="_blank" rel="noopener noreferrer" href="{{ $pekerja_kompen->progres_2 }}">';
                    $btn .= '<button class="btn btn-sm btn-success">Link Progres</button>' ;
                    $btn .= '</a>';
                } else {
                    $btn .= '<button class="btn btn-sm btn-warning">Belum ada progres</button>';
                }
                return $btn;
            })
            ->addColumn('bukti_kompen', function($pekerja_kompen){
                $btn = '';
                if($pekerja_kompen->bukti_kompen == null){
                    $btn .= '<a href="#" class="btn btn-danger btn-sm" download>';
                    $btn .= '<i class="fa fa-file-pdf"></i>';
                    $btn .= 'Belum ada bukti kompen';
                    $btn .= '</a>';
                } else {
                    $btn .= '<a href="'.asset($pekerja_kompen->bukti_kompen) .'" class="btn btn-warning btn-sm" download><i class="fa fa-file-pdf"></i>Download Bukti Kompen</a>';
                }
                return $btn;
            })
            ->addColumn('aksi', function($pekerja_kompen){
                $btn = '';
                if ($pekerja_kompen->status == "diterima") {
                    $btn = '<button class="btn btn-primary btn-sm">Diterima</button>';
                } elseif ($pekerja_kompen->status == "ditolak") {
                    $btn = '<button class="btn btn-warning btn-sm">Ditolak</button>';
                } else {
                    $btn = '<div class="btn-group " role="group">';
                    $btn .= '<button type="button" data-kompendetail="' . $pekerja_kompen->id_kompen_detail . '" ';
                    $btn .= 'data-mahasiswa="' . $pekerja_kompen->id_mahasiswa . '" ';
                    $btn .= 'data-kompen="' . $pekerja_kompen->id_kompen . '" ';
                    $btn .= 'class="btn btn-success btn-sm btn-accept">Terima</button>';
                    $btn .= '<button type="button" data-kompenDetail="' . $pekerja_kompen->id_kompen_detail . '" ';
                    $btn .= 'data-mahasiswa="' . $pekerja_kompen->id_mahasiswa . '" ';
                    $btn .= 'data-kompen="' . $pekerja_kompen->id_kompen . '" ';
                    $btn .= 'class="btn btn-danger btn-sm btn-reject">Tolak</button>';
                    $btn .= '</div>';
                }
                return $btn;
            })
            ->rawColumns(['aksi', 'progres_1', 'progres_2', 'bukti_kompen'])
            ->make(true);
    }

    public function konfirmasi_pekerjaan(Request $request){
        try {
            $validator = Validator::make($request->all(),[
                'id_kompen_detail' => 'required|exists:kompen_detail,id_kompen_detail',
                'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
                'id_kompen' => 'required|exists:kompen,id_kompen',
                'status' => 'required|in:acc,reject'
            ]);

            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'massage' => 'Konfirmasi Gagal'
                ]);
            }

            $kompen_detail = KompenDetailModel::findOrFail($request->id_kompen_detail);

            if($kompen_detail->status != 'progres'){
                return response()->json([
                    'status' => false,
                    'massage' => 'Pekerja ini sudah dikonfirmasi'
                ]);
            }

            if($kompen_detail->id_kompen != $request->id_kompen || $kompen_detail->id_mahasiswa != $request->id_mahasiswa){
                return response()->json([
                    'status' => false,
                    'massage' => 'Terjadi Kesalahan Saat Konfirmasi'
                ]);
            }

            if($request->status == 'acc'){
                $kompen_detail->status = 'diterima';
                $kompen_detail->updated_at = now();
                $kompen_detail->save();

                return response()->json([
                    'status' => true,
                    'massage' => 'Pekerjaan mahasiswa berhasil diterima'
                ]);
            } elseif($request->status == 'reject') {
                $kompen_detail->status = 'ditolak';
                $kompen_detail->updated_at = now();
                $kompen_detail->save();
                return response()->json([
                    'status' => true,
                    'massage' => 'Pekerjaan mahasiswa berhasil ditolak'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'massage' => 'Kesalahan Sistem'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'massage' => 'Terjadi kesalahan pada sistem'
            ]);
        }
    }

    public function upload_bukti_kompen($id){
        $id_mahasiswa = auth()->user()->id_mahasiswa;
        $kompen_detail = KompenDetailModel::select('id_kompen_detail', 'id_kompen', 'id_mahasiswa','status', 'bukti_kompen')
        ->where('id_mahasiswa', $id_mahasiswa)
        ->where('id_kompen', $id)
        ->with('kompen', 'mahasiswa', 'kompen.personilAkademik')
        ->first();

        return view('kompen_dilakukan.upload_bukti', ['kompen_detail' => $kompen_detail]);
    }

    public function store_bukti_kompen(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'bukti_kompen' => 'required|file|mimes:pdf|max:1024'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $kompen_detail = KompenDetailModel::find($id);
            $file = $request->file('bukti_kompen');
            $fileName = $file->hashName() . '.'. $file->getClientOriginalExtension();
            $uploadPath = public_path('uploads/dokumen');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }
            $file->move($uploadPath, $fileName);
            $kompen_detail->bukti_kompen = 'uploads/dokumen/' . $fileName;
            $kompen_detail->save();
            return response()->json([
                'status' => true,
                'message' => 'Upload Bukti Kompen Berhasil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'massage' => 'Terjadi kesalahan ketika upload'
            ]);
        }
    }

    public function export_bukti_kompen(){
        $id_mahasiswa = auth()->user()->id_mahasiswa;
        $bukti_kompen = KompenDetailModel::select('id_kompen_detail' ,'id_mahasiswa', 'id_kompen', 'status')
            ->where('id_mahasiswa', $id_mahasiswa)
            ->where('status', 'progres')
            ->with('kompen','kompen.personilAkademik' ,'mahasiswa', 'mahasiswa.prodi')
            ->first();

        // Generate QR sebagai SVG
        $qr_code = QrCode::size(200)
            ->generate(
                '[
                    Nama Mahasiswa : ' .$bukti_kompen->mahasiswa->nama.', 
                    Nama Kompen : ' .$bukti_kompen->kompen->nama. ', 
                    Pemberi Tugas : ' .$bukti_kompen->kompen->personilAkademik->nama . ', 
                    Jumlah Jam : ' .$bukti_kompen->kompen->jam_kompen. '
                ]'
            );

        $pdf = PDF::loadView('kompen_dilakukan.export_bukti_kompen', 
            ['bukti_kompen' => $bukti_kompen, 
            'qr_code' => base64_encode($qr_code)
            ]);
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->render();
        return $pdf->stream('Bukti_Kompen '.date('Y-m-d H:i:s'). '.pdf');
    }

    public function selesaikan_kompen(Request $request){
        DB::beginTransaction();
        try {
            $id_kompen = $request->id_kompen;
            
            $has_progres = KompenDetailModel::select('id_kompen_detail', 'id_kompen', 'id_mahasiswa', 'status')
            ->where('id_kompen', $id_kompen)
            ->where('status', 'progres')
            ->exists();
    
            if($has_progres){
                return response()->json([
                    'status' => false,
                    'massage' => "Masih ada mahasiswa yang belum dikonfirmasi"
                ]);
            }
            
            $kompen = KompenModel::find($id_kompen);
            if(!$kompen) {
                return response()->json([
                    'status' => false,
                    'massage' => "Data kompen tidak ditemukan"
                ]);
            }

            $pekerja_kompen = KompenDetailModel::select('id_kompen_detail', 'id_kompen', 'id_mahasiswa', 'status')
            ->where('id_kompen', $id_kompen)
            ->where('status', 'diterima')
            ->get();

            foreach ($pekerja_kompen as $value) {
                $value->mahasiswa->jam_kompen_selesai = ($kompen->jam_kompen + $value->mahasiswa->jam_kompen_selesai);
                $value->mahasiswa->save();
            }

            $kompen->is_selesai = 'yes';
            $kompen->updated_at = now();
            $kompen->save();
            DB::commit();
            return response()->json([
                'status' => true,
                'massage' => 'Kompen berhasil diselesaikan'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'massage' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
