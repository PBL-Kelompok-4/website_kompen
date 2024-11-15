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

class KompenDitolakController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar kompen ditolak',
            'list' => ['Home', 'Kompen Ditolak']
        ];

        $page = (object)[
            'title' => 'Daftar kompen yang ditolak yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kompen_ditolak'; // set menu yang sedang aktif

        $jenis_kompen = JenisKompenModel::all(); // ambil data jenis kompen
        $kompetensi = KompetensiModel::all(); // ambil data kompetensi

        return view('kompen_ditolak.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'jenis_kompen' => $jenis_kompen, 'kompetensi' => $kompetensi, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request){
        $kompens = KompenModel::select('id_kompen' ,'nomor_kompen', 'nama', 'deskripsi', 'id_personil', 'id_jenis_kompen', 'kuota', 'jam_kompen', 'status', 'is_selesai', 'tanggal_mulai', 'tanggal_selesai', 'status_acceptance')->where('status', 'ditutup')->where('is_selesai', 'no')->where('status_acceptance', 'reject')->with('jenisKompen', 'personilAkademik')->get();

        //Filter data kompen berdasarkan id_jenis_kompen
        if($request->id_jenis_kompen){
            $kompens->where('id_jenis_kompen', $request->id_jenis_kompen);
        }

        if($request->id_kompetensi){
            $kompens->where('id_kompetensi', $request->id_kompetensi);
        }
        return DataTables::of($kompens)
        // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
        ->addIndexColumn()
        ->addColumn('aksi', function ($kompen){ //menambahkan kolom aksi

            $btn = '<button onclick="modalAction(\''.url('/kompen_ditolak/'. $kompen->id_kompen . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
            // $btn .= '<button onclick="modalAction(\''.url('/kompen_ditolak/' . $kompen->id_kompen . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
            // $btn .= '<button onclick="modalAction(\''.url('/kompen_ditolak/' . $kompen->id_kompen . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
            return $btn;
        })
        ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
        ->make(true);
    }

    public function show_ajax(string $id){
        $kompen_ditolak = KompenModel::find($id);

        return view('kompen_ditolak.show_ajax', ['kompen_ditolak' => $kompen_ditolak]);
    }
}
