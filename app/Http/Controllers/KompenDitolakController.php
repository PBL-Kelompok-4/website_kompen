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
        if(auth()->user()->level->kode_level == "ADM"){
            $kompens = KompenModel::select('id_kompen' ,'nomor_kompen', 'nama', 'deskripsi', 'id_personil', 'id_jenis_kompen', 'kuota', 'jam_kompen', 'status', 'is_selesai', 'tanggal_mulai', 'tanggal_selesai', 'status_acceptance', 'alasan')
            ->where('status_acceptance', 'reject')
            ->with('jenisKompen', 'personilAkademik');
        } elseif (auth()->user()->level->kode_level == "DSN" || auth()->user()->level->kode_level == "TDK") {
            $id_personil = auth()->user()->id_personil;
            $kompens = KompenModel::select('id_kompen' ,'nomor_kompen', 'nama', 'deskripsi', 'id_personil', 'id_jenis_kompen', 'kuota', 'jam_kompen', 'status', 'is_selesai', 'tanggal_mulai', 'tanggal_selesai', 'status_acceptance', 'alasan')
            ->where('status_acceptance', 'reject')
            ->where('id_personil', $id_personil)
            ->with('jenisKompen', 'personilAkademik');
        }

        //Filter data kompen berdasarkan id_jenis_kompen
        if($request->id_jenis_kompen){
            $kompens->where('id_jenis_kompen', $request->id_jenis_kompen);
        }

        return DataTables::of($kompens)
        ->addIndexColumn()
        ->addColumn('aksi', function ($kompen){ //menambahkan kolom aksi
            $btn = '<button onclick="modalAction(\''.url('/kompen_ditolak/'. $kompen->id_kompen . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
            return $btn;
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function show_ajax(string $id){
        $kompen_ditolak = KompenModel::find($id);

        return view('kompen_ditolak.show_ajax', ['kompen_ditolak' => $kompen_ditolak]);
    }

    public function export_excel(){
        $kompen_ditolak = KompenModel::select('nomor_kompen', 'nama', 'deskripsi', 'id_personil', 'id_jenis_kompen', 'kuota', 'jam_kompen', 'status', 'is_selesai', 'tanggal_mulai', 'tanggal_selesai', 'status_acceptance', 'alasan')
        ->where('status_acceptance', 'reject')
        ->with('jenisKompen', 'personilAkademik')
        ->get();
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Kompen');
        $sheet->setCellValue('C1', 'Deskripsi');
        $sheet->setCellValue('D1', 'Pemberi Tugas');
        $sheet->setCellValue('E1', 'Jenis Kompen');
        $sheet->setCellValue('F1', 'Kuota');
        $sheet->setCellValue('G1', 'Jam Konversi');
        $sheet->setCellValue('H1', 'Tanggal Mulai');
        $sheet->setCellValue('I1', 'Tanggal Selesai');
        $sheet->setCellValue('J1', 'Alasan Ditolak');

        $sheet->getStyle('A1:J1')->getFont()->setBold(true); // bold Header

        $no = 1;    // nomor data dimulai dari 1
        $baris = 2; // baris data dimulai dari baris ke 2
        foreach($kompen_ditolak as $key => $value){
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->nama);
            $sheet->setCellValue('C'.$baris, $value->deskripsi);
            $sheet->setCellValue('D'.$baris, $value->personilAkademik->nama);
            $sheet->setCellValue('E'.$baris, $value->jenisKompen->nama_jenis);
            $sheet->setCellValue('F'.$baris, $value->kuota);
            $sheet->setCellValue('G'.$baris, $value->jam_kompen);
            $sheet->setCellValue('H'.$baris, $value->tanggal_mulai);
            $sheet->setCellValue('I'.$baris, $value->tanggal_selesai);
            $sheet->setCellValue('J'.$baris, $value->alasan);
            $baris++;
            $no++;
        }

        foreach(range('A','J') as $columnID){
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }

        $sheet->setTitle('Data Kompen Ditolak'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Kompen Ditolak '.date('Y-m-d H:i:s').'.xlsx';

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
        $kompen_ditolak = KompenModel::select('nomor_kompen', 'nama', 'deskripsi', 'id_personil', 'id_jenis_kompen', 'kuota', 'jam_kompen', 'status', 'is_selesai', 'tanggal_mulai', 'tanggal_selesai', 'status_acceptance', 'alasan')
        ->where('status_acceptance', 'reject')
        ->with('jenisKompen', 'personilAkademik')
        ->get();

        $pdf = Pdf::loadView('kompen_ditolak.export_pdf', ['kompen_ditolak' => $kompen_ditolak]);
        $pdf->setPaper('a4', 'landscape'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url 
        $pdf->render();

        return $pdf->stream('Data Kompen Ditolak '.date('Y-m-d H:i:s').'.pdf');
    }
}
