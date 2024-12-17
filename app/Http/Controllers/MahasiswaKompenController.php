<?php

namespace App\Http\Controllers;

use App\Models\KompenDetailModel;
use Illuminate\Http\Request;
use App\Models\MahasiswaModel;
use App\Models\ProdiModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class MahasiswaKompenController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar mahasiswa kompen',
            'list' => ['Home', 'Mahasiswa Kompen']
        ];

        $page = (object)[
            'title' => 'Daftar mahasiswa yang sedang mengerjakan kompen'
        ];

        $activeMenu = 'mahasiswa_kompen'; // set menu yang sedang aktif

        $prodi = ProdiModel::all(); // ambil data prodi

        return view('mahasiswa_kompen.index', ['breadcrumb' => $breadcrumb, 'page' => $page,'prodi' => $prodi, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request){
        $mahasiswas = KompenDetailModel::select('id_mahasiswa' ,'id_kompen', 'status')
        ->where('status', 'progres')
        ->whereHas('kompen', function($query) {
            $query->where('status', 'progres')->where('is_selesai', 'no');
        })
        ->with('mahasiswa', 'kompen', 'mahasiswa.prodi');

        //Filter data mahasiswa berdasarkan id_prodi
        if($request->id_prodi){
            $id_prodi = $request->id_prodi;
            $mahasiswas->whereHas('mahasiswa', function($query) use($id_prodi) {
                $query->where('id_prodi', $id_prodi);
            });
        }

        return DataTables::of($mahasiswas)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($mahasiswa){ //menambahkan kolom aksi

                $btn = '<button onclick="modalAction(\''.url('/mahasiswa_kompen/'. $mahasiswa->id_mahasiswa . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                // $btn .= '<button onclick="modalAction(\''.url('/mahasiswa_kompen/' . $mahasiswa->id_mahasiswa . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                // $btn .= '<button onclick="modalAction(\''.url('/mahasiswa_kompen/' . $mahasiswa->id_mahasiswa . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function show_ajax(string $id){
        $mahasiswa_kompen = KompenDetailModel::select('id_kompen', 'id_mahasiswa', 'progres_1', 'progres_2', 'status')
        ->where('id_mahasiswa', $id)
        ->where('status', 'progres')
        ->with('kompen', 'mahasiswa', 'mahasiswa.prodi')
        ->first();

        return view('mahasiswa_kompen.show_ajax', ['mahasiswa_kompen' => $mahasiswa_kompen]);
    }

    public function export_excel(){
        $mahasiswa_kompen = KompenDetailModel::select(
            'kompen_detail.id_mahasiswa',  // Specify the table name
            'kompen_detail.id_kompen', 
            'kompen_detail.status'
        )
        ->where('kompen_detail.status', 'progres')  // Also good practice to specify table here
        ->whereHas('kompen', function($query) {
            $query->where('status', 'progres')->where('is_selesai', 'no');
        })
        ->with('mahasiswa', 'kompen', 'kompen.personilAkademik', 'mahasiswa.prodi')
        ->whereHas('mahasiswa')
        ->join('mahasiswa', 'kompen_detail.id_mahasiswa', '=', 'mahasiswa.id_mahasiswa')
        ->orderBy('mahasiswa.id_prodi')
        ->get();
        
        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Prodi');
        $sheet->setCellValue('C1', 'Nama Mahasiswa');
        $sheet->setCellValue('D1', 'NIM');
        $sheet->setCellValue('E1', 'Nama Kompen');
        $sheet->setCellValue('F1', 'Pemberi Tugas');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true); // bold Header

        $no = 1;    // nomor data dimulai dari 1
        $baris = 2; // baris data dimulai dari baris ke 2
        foreach($mahasiswa_kompen as $key => $value){
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->mahasiswa->prodi->nama_prodi);
            $sheet->setCellValue('C'.$baris, $value->mahasiswa->nama);
            $sheet->setCellValue('D'.$baris, $value->mahasiswa->nomor_induk);
            $sheet->setCellValue('E'.$baris, $value->kompen->nama);
            $sheet->setCellValue('F'.$baris, $value->kompen->personilAkademik->nama);
            $baris++;
            $no++;
        }

        foreach(range('A','F') as $columnID){
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }

        $sheet->setTitle('Data Mahasiswa Kompen'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Mahasiswa Kompen '.date('Y-m-d H:i:s').'.xlsx';

        header('Content-Type: appplication/vdn.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf(){
        $mahasiswa_kompen = KompenDetailModel::select(
            'kompen_detail.id_mahasiswa',  // Specify the table name
            'kompen_detail.id_kompen', 
            'kompen_detail.status'
        )
        ->where('kompen_detail.status', 'progres')  // Also good practice to specify table here
        ->whereHas('kompen', function($query) {
            $query->where('status', 'progres')->where('is_selesai', 'no');
        })
        ->with('mahasiswa', 'kompen', 'kompen.personilAkademik', 'mahasiswa.prodi')
        ->whereHas('mahasiswa')
        ->join('mahasiswa', 'kompen_detail.id_mahasiswa', '=', 'mahasiswa.id_mahasiswa')
        ->orderBy('mahasiswa.id_prodi')
        ->get();

        $pdf = Pdf::loadView('mahasiswa_kompen.export_pdf', ['mahasiswa_kompen' => $mahasiswa_kompen]);
        $pdf->setPaper('a4', 'landscape'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url 
        $pdf->render();

        return $pdf->stream('Data Mahasiswa Kompen '.date('Y-m-d H:i:s').'.pdf');
    }
}
