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
            'title' => 'Daftar mahasiswa',
            'list' => ['Home', 'Mahasiswa']
        ];

        $page = (object)[
            'title' => 'Daftar mahasiswa yang terdaftar dalam sistem'
        ];

        $activeMenu = 'mahasiswa_alpha'; // set menu yang sedang aktif

        $prodi = ProdiModel::all(); // ambil data prodi

        return view('mahasiswa_alpha.index', ['breadcrumb' => $breadcrumb, 'page' => $page,'prodi' => $prodi, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request){

        $mahasiswas = MahasiswaModel::select('id_mahasiswa' ,'id_prodi', 'nomor_induk', 'username', 'nama', 'semester', 'jam_alpha')->where('jam_alpha', '>=', '1')->with('prodi');

        //Filter data mahasiswa berdasarkan id_prodi
        if($request->id_prodi){
            $mahasiswas->where('id_prodi', $request->id_prodi);
        }

        if($request->semester){
            $mahasiswas->where('semester', $request->semester);
        }

        // $totalRecords = $query->count();
        // $mahasiswas = $query->skip($start)->take($length)->get();

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
            // ->setTotalRecords($totalRecords)
            ->make(true);
    }
}
