<?php

namespace App\Http\Controllers;

use App\Models\KompenModel;
use Illuminate\Http\Request;
use App\Models\KompetensiModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class KompetensiController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar kompetensi',
            'list' => ['Home', 'Kompetensi']
        ];

        $page = (object)[
            'title' => 'Daftar kompetensi akademik yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kompetensi'; // set menu yang sedang aktif

        return view('kompetensi.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request){
        $kompetensis = KompetensiModel::select('id_kompetensi', 'nama_kompetensi', 'deskripsi_kompetensi');

        return DataTables::of($kompetensis)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kompetensi){ //menambahkan kolom aksi

                if(auth()->user()->level->kode_level == "DSN" || auth()->user()->level->kode_level == "TDK" || auth()->user()->level->kode_level == "MHS"){
                    $btn = '<button onclick="modalAction(\''.url('/kompetensi/'. $kompetensi->id_kompetensi . '/show_ajax').'\')" class="btn btn-info btn-sm col-12">Detail</button> ';
                } elseif(auth()->user()->level->kode_level == "ADM") {
                    $btn = '<button onclick="modalAction(\''.url('/kompetensi/'. $kompetensi->id_kompetensi . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                    $btn .= '<button onclick="modalAction(\''.url('/kompetensi/' . $kompetensi->id_kompetensi . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\''.url('/kompetensi/' . $kompetensi->id_kompetensi . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                }
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create_ajax() {
        
        return view('kompetensi.create_ajax');

    }

    public function store_ajax(Request $request) {
        // cek apakah request berupa ajax
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_kompetensi' => 'required|string|min:3|max:30',
                'deskripsi_kompetensi' => 'required|string|min:3|max:255|max:255'
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

            KompetensiModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data kompetensi berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax(string $id){
        $kompetensi = KompetensiModel::find($id);

        return view('kompetensi.edit_ajax')->with('kompetensi', $kompetensi);
    }

    public function update_ajax(Request $request, $id){
        //cek apakah request dari ajax
        if($request->ajax() || $request->wantsJson()){
            $rules =[
                'nama_kompetensi' => 'required|string|min:3|max:30',
                'deskripsi_kompetensi' => 'required|string|min:3|max:255|max:255'
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

            $check = KompetensiModel::find($id);
            if($check){
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
        $kompetensi = KompetensiModel::find($id);

        return view('kompetensi.confirm_ajax', ['kompetensi' => $kompetensi]);
    }

    public function delete_ajax(Request $request, $id){
        // cek apakah request dari ajax
        if($request->ajax() || $request->wantsJson()){
            $kompetensi = KompetensiModel::find($id);
            if($kompetensi){
                $kompetensi->delete();
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
        $check = KompetensiModel::find($id);
        if(!$check){ // untuk mengecek apakah data kompetensi dengan id yang dimaksud ada atau tidak
            return redirect('/kompetensi')->with('error', 'Data kompetensi tidak ditemukan');
        }

        try{
            KompetensiModel::destroy($id); //Hapus data kompetensi
            return redirect('/kompetensi')->with('success', 'Data kompetensi berhasil dihapus');
        } catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/kompetensi')->with('error', 'Data kompetensi gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function show_ajax(string $id){
        $kompetensi = KompetensiModel::find($id);

        return view('kompetensi.show_ajax', ['kompetensi' => $kompetensi]);
    }

    public function import() {
        return view('kompetensi.import');
    }

    public function import_ajax(Request $request){
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB
                'file_kompetensi' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_kompetensi'); // ambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // load reader file excel
            $reader->setReadDataOnly(true); // hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel
            $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
            $data = $sheet->toArray(null, false, true, true); // ambil data excel
            $insert = [];
            if (count($data) > 1) { // jika data lebih dari 1 baris
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // baris ke 1 adalah header, maka lewati
                        $insert[] = [
                            'nama_kompetensi' => $value['A'],
                            'deskripsi_kompetensi' => $value['B'],
                            'created_at' => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    // insert data ke database, jika data sudah ada, maka diabaikan
                    KompetensiModel::insertOrIgnore($insert);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/personil_akademik');
    }

    public function export_excel(){
        // Ambil data Level yang akan di export
        $kompetensi = KompetensiModel::select('nama_kompetensi', 'deskripsi_kompetensi')->orderBy('nama_kompetensi')->get();
        
        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Kompetensi');
        $sheet->setCellValue('C1', 'Deskripsi');

        $sheet->getStyle('A1:C1')->getFont()->setBold(true); // bold Header

        $no = 1;    // nomor data dimulai dari 1
        $baris = 2; // baris data dimulai dari baris ke 2
        foreach($kompetensi as $key => $value){
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->nama_kompetensi);
            $sheet->setCellValue('C'.$baris, $value->deskripsi_kompetensi);
            $baris++;
            $no++;
        }

        foreach(range('A','C') as $columnID){
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }

        $sheet->setTitle('Data Kompetensi'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Kompetensi '.date('Y-m-d H:i:s').'.xlsx';

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
        $kompetensi = KompetensiModel::select('nama_kompetensi', 'deskripsi_kompetensi')->orderBy('nama_kompetensi')->get();
        $pdf = Pdf::loadView('kompetensi.export_pdf', ['kompetensi' => $kompetensi]);
        $pdf->setPaper('a4', 'potrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url 
        $pdf->render();

        return $pdf->stream('Data Kompetensi '.date('Y-m-d H:i:s').'.pdf');
    }
}
