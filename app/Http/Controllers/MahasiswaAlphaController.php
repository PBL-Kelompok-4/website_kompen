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

        $mahasiswa_alphas = MahasiswaModel::select(
            'id_mahasiswa',
            'id_prodi', 
            'nomor_induk', 
            'username', 
            'nama', 
            'id_periode', 
            'jam_alpha', 
            'jam_kompen', 
            'jam_kompen_selesai', 
            DB::raw('(jam_kompen - jam_kompen_selesai) AS sisa_kompen')
        )
        ->where('jam_alpha', '>=', '1')
        ->with('prodi', 'periode');

        //Filter data mahasiswa berdasarkan id_prodi
        if($request->id_prodi){
            $mahasiswa_alphas->where('id_prodi', $request->id_prodi);
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
                'nama' => 'string|min:3|max:150',
                'id_periode' => 'integer|min:1',
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

                $check->update([
                    'jam_alpha' => $request->jam_alpha,
                    'jam_kompen' => ($request->jam_alpha * 2)
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

    public function export_excel(){
        $mahasiswa_alpha = MahasiswaModel::select(
            'id_prodi', 
            'nomor_induk', 
            'nama', 
            'id_periode', 
            'jam_alpha', 
            'jam_kompen', 
            'jam_kompen_selesai', 
            DB::raw('(jam_kompen - jam_kompen_selesai) AS sisa_kompen')
        )
        ->where('jam_alpha', '>=', '1')
        ->orderBy('id_prodi')
        ->orderBy('nama')
        ->with('prodi', 'periode')
        ->get();
        
        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Prodi');
        $sheet->setCellValue('C1', 'Nama Mahasiswa');
        $sheet->setCellValue('D1', 'NIM');
        $sheet->setCellValue('E1', 'Tahun Masuk');
        $sheet->setCellValue('F1', 'Jumlah Jam Alpha');
        $sheet->setCellValue('G1', 'Jumlah Jam Kompen');
        $sheet->setCellValue('H1', 'Jumlah Jam Kompen Selesai Dilakukan');
        $sheet->setCellValue('I1', 'Sisa Jam Kompen');

        $sheet->getStyle('A1:I1')->getFont()->setBold(true); // bold Header

        $no = 1;    // nomor data dimulai dari 1
        $baris = 2; // baris data dimulai dari baris ke 2
        foreach($mahasiswa_alpha as $key => $value){
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->prodi->nama_prodi);
            $sheet->setCellValue('C'.$baris, $value->nama);
            $sheet->setCellValue('D'.$baris, $value->nomor_induk);
            $sheet->setCellValue('E'.$baris, $value->periode->periode);
            $sheet->setCellValue('F'.$baris, $value->jam_alpha);
            $sheet->setCellValue('G'.$baris, $value->jam_kompen);
            $sheet->setCellValue('H'.$baris, $value->jam_kompen_selesai);
            $sheet->setCellValue('I'.$baris, $value->sisa_kompen);
            $baris++;
            $no++;
        }

        foreach(range('A','I') as $columnID){
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }

        $sheet->setTitle('Data Mahasiswa Alpha'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Mahasiswa Alpha '.date('Y-m-d H:i:s').'.xlsx';

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
        $mahasiswa_alpha = MahasiswaModel::select(
            'id_prodi', 
            'nomor_induk', 
            'nama', 
            'id_periode', 
            'jam_alpha', 
            'jam_kompen', 
            'jam_kompen_selesai', 
            DB::raw('(jam_kompen - jam_kompen_selesai) AS sisa_kompen')
        )
        ->where('jam_alpha', '>=', '1')
        ->orderBy('id_prodi')
        ->orderBy('nama')
        ->with('prodi', 'periode')
        ->get();
        $pdf = Pdf::loadView('mahasiswa_alpha.export_pdf', ['mahasiswa_alpha' => $mahasiswa_alpha]);
        $pdf->setPaper('a4', 'landscape'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url 
        $pdf->render();

        return $pdf->stream('Data Mahasiswa Alpha '.date('Y-m-d H:i:s').'.pdf');
    }
}
