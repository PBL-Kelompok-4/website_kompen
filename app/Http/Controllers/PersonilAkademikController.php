<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonilAkademikModel;
use App\Models\LevelModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class PersonilAkademikController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar personil akademik',
            'list' => ['Home', 'Personil Akademik']
        ];

        $page = (object)[
            'title' => 'Daftar personil akademik yang terdaftar dalam sistem'
        ];

        $activeMenu = 'personil_akademik'; // set menu yang sedang aktif

        $level = LevelModel::select('id_level', 'kode_level', 'nama_level')->where('kode_level', '!=', 'MHS')->get(); // ambil data level

        return view('personil_akademik.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request){
        $personils = PersonilAkademikModel::select('id_personil' ,'id_level', 'nomor_induk', 'username', 'nama', 'nomor_telp')->with('level');

        //Filter data personil akademik berdasarkan id_level
        if($request->id_level){
            $personils->where('id_level', $request->id_level);
        }

        return DataTables::of($personils)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($personil){ //menambahkan kolom aksi

                $btn = '<button onclick="modalAction(\''.url('/personil_akademik/'. $personil->id_personil . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/personil_akademik/' . $personil->id_personil . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/personil_akademik/' . $personil->id_personil . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create_ajax() {
        $level = LevelModel::select('id_level', 'nama_level')->where('kode_level', '!=', 'MHS')->get();

        return view('personil_akademik.create_ajax')->with('level', $level);
    }

    public function store_ajax(Request $request) {
        // cek apakah request berupa ajax
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'id_level' => 'required|integer',
                'nomor_induk' => 'required|string|max:18|unique:personil_akademik,nomor_induk',
                'username' => 'required|string|min:3|max:20|unique:personil_akademik,username',
                'nama' => 'required|string|min:3|max:150',
                'password' => 'required|min:6|max:20',
                'nomor_telp' => 'required|string|max:15'
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

            PersonilAkademikModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data personil akademik berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax(string $id){
        $personil = PersonilAkademikModel::find($id);
        $level = LevelModel::select('id_level', 'nama_level')->where('kode_level', '!=', 'MHS')->get();

        return view('personil_akademik.edit_ajax',['personil_akademik' => $personil, 'level' => $level]);
    }

    public function update_ajax(Request $request, $id){
        //cek apakah request dari ajax
        if($request->ajax() || $request->wantsJson()){
            $rules =[
                'id_level' => 'required|integer',
                'nomor_induk' => 'required|string|max:18|unique:personil_akademik,nomor_induk,'.$id.',id_personil',
                'username' => 'required|string|min:3|max:20|unique:personil_akademik,username,'.$id.',id_personil',
                'nama' => 'required|string|min:3|max:150',
                'semester' => 'required|integer',
                'password' => 'required|min:6|max:20',
                'nomor_telp' => 'required|integer|max:15'
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

            $check = PersonilAkademikModel::find($id);
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
        $personil = PersonilAkademikModel::find($id);

        return view('personil_akademik.confirm_ajax', ['personil_akademik' => $personil]);
    }

    public function delete_ajax(Request $request, $id){
        // cek apakah request dari ajax
        if($request->ajax() || $request->wantsJson()){
            $personil = PersonilAkademikModel::find($id);
            if($personil){
                $personil->delete();
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
        $check = PersonilAkademikModel::find($id);
        if(!$check){ // untuk mengecek apakah data personil akademik dengan id yang dimaksud ada atau tidak
            return redirect('/personil_akademik')->with('error', 'Data personil akademik tidak ditemukan');
        }

        try{
            PersonilAkademikModel::destroy($id); //Hapus data personil
            return redirect('/personil_akademik')->with('success', 'Data personil akademik berhasil dihapus');
        } catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/personil_akademik')->with('error', 'Data personil akademik gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function show_ajax(string $id){
        $personil = PersonilAkademikModel::find($id);

        return view('personil_akademik.show_ajax', ['personil_akademik' => $personil]);
    }

    public function import() {
        return view('personil_akademik.import');
    }

    public function import_ajax(Request $request){
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB
                'file_personil_akademik' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_personil_akademik'); // ambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // load reader file excel
            $reader->setReadDataOnly(true); // hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel
            $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
            $data = $sheet->toArray(null, false, true, true); // ambil data excel
            $insert = [];
            if (count($data) > 1) { // jika data lebih dari 1 baris
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // baris ke 1 adalah header, maka lewati
                        if($value['A'] == "Admin"){
                            $id_level = 1;
                        } elseif ($value['A'] == "Dosen") {
                            $id_level = 2;
                        } elseif ($value['A'] == "Tendik") {
                            $id_level = 3;
                        }
                        $insert[] = [
                            'id_level' => $id_level,
                            'nomor_induk' => $value['B'],
                            'nama' => $value['C'],
                            'username' => $value['B'],
                            'password' => $value['B'],
                            'nomor_telp' => $value['D'],
                            'created_at' => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    // insert data ke database, jika data sudah ada, maka diabaikan
                    PersonilAkademikModel::insertOrIgnore($insert);
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
        $personil_akademik = PersonilAkademikModel::select('nomor_induk', 'nama', 'nomor_telp', 'id_level')->with('level')->orderBy('id_level')->orderBy('nama')->get();
        
        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tingkatan');
        $sheet->setCellValue('C1', 'Nomor Induk');
        $sheet->setCellValue('D1', 'Nama Personil');
        $sheet->setCellValue('E1', 'Nomor Telepon');

        $sheet->getStyle('A1:E1')->getFont()->setBold(true); // bold Header

        $no = 1;    // nomor data dimulai dari 1
        $baris = 2; // baris data dimulai dari baris ke 2
        foreach($personil_akademik as $key => $value){
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->level->nama_level);
            $sheet->setCellValue('C'.$baris, $value->nomor_induk);
            $sheet->setCellValue('D'.$baris, $value->nama);
            $sheet->setCellValue('E'.$baris, $value->nomor_telp);
            $baris++;
            $no++;
        }

        foreach(range('A','E') as $columnID){
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }

        $sheet->setTitle('Data Personil Akademik'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Personil Akademik '.date('Y-m-d H:i:s').'.xlsx';

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
        $personil_akademik = PersonilAkademikModel::select('nomor_induk', 'nama', 'nomor_telp', 'id_level')->with('level')->orderBy('id_level')->orderBy('nama')->get();
        $pdf = Pdf::loadView('personil_akademik.export_pdf', ['personil_akademik' => $personil_akademik]);
        $pdf->setPaper('a4', 'potrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url 
        $pdf->render();

        return $pdf->stream('Data Personil Akademik '.date('Y-m-d H:i:s').'.pdf');
    }
}
