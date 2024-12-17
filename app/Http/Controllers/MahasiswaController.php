<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MahasiswaModel;
use App\Models\PeriodeModel;
use App\Models\ProdiModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;


class MahasiswaController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar mahasiswa',
            'list' => ['Home', 'Mahasiswa']
        ];

        $page = (object)[
            'title' => 'Daftar mahasiswa yang terdaftar dalam sistem'
        ];

        $activeMenu = 'mahasiswa'; // set menu yang sedang aktif

        $prodi = ProdiModel::all(); // ambil data prodi

        return view('mahasiswa.index', ['breadcrumb' => $breadcrumb, 'page' => $page,'prodi' => $prodi, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request){
        $mahasiswas = MahasiswaModel::select('id_mahasiswa' ,'id_prodi', 'nomor_induk', 'username', 'nama', 'id_periode', 'jam_alpha', 'jam_kompen', 'jam_kompen_selesai')->with('prodi', 'periode');

        //Filter data mahasiswa berdasarkan id_prodi
        if($request->id_prodi){
            $mahasiswas->where('id_prodi', $request->id_prodi);
        }

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
            ->make(true);
    }

    public function show_ajax(string $id){
        $mahasiswa = MahasiswaModel::find($id);

        return view('mahasiswa.show_ajax', ['mahasiswa' => $mahasiswa]);
    }

    public function create_ajax() {
        $prodi = ProdiModel::select('id_prodi', 'nama_prodi')->get();
        $periode = PeriodeModel::all();

        return view('mahasiswa.create_ajax')->with(['prodi' => $prodi, 'periode' => $periode]);
    }

    public function store_ajax(Request $request) {
        // cek apakah request berupa ajax
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'id_prodi' => 'required|integer',
                'nomor_induk' => 'required|string|max:10|unique:mahasiswa,nomor_induk',
                'nama' => 'required|string|min:3|max:150',
                'id_periode' => 'required|integer',
                'password' => 'required|min:6|max:20',
                'jam_alpha' => 'required|integer',
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

            MahasiswaModel::create([
                'id_prodi' => $request->id_prodi,
                'nomor_induk' => $request->nomor_induk,
                'username' => $request->nomor_induk,
                'nama' => $request->nama,
                'id_periode' => $request->id_periode,
                'password' => bcrypt($request->password),
                'jam_alpha' => $request->jam_alpha,
                'jam_kompen' => ($request->jam_alpha * 2)
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data mahasiswa berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax(string $id){
        $mahasiswa = MahasiswaModel::find($id);
        $prodi = ProdiModel::select('id_prodi', 'nama_prodi')->get();
        $periode = PeriodeModel::all();

        return view('mahasiswa.edit_ajax',['mahasiswa' => $mahasiswa, 'prodi' => $prodi, 'periode' => $periode]);
    }

    public function update_ajax(Request $request, $id){
        //cek apakah request dari ajax
        if($request->ajax() || $request->wantsJson()){
            $rules =[
                'id_prodi' => 'required|integer',
                'nomor_induk' => 'required|string|max:10|unique:mahasiswa,nomor_induk,'.$id.',id_mahasiswa',
                'username' => 'required|string|min:3|max:20|unique:mahasiswa,username,'.$id.',id_mahasiswa',
                'nama' => 'required|string|min:3|max:150',
                'id_periode' => 'required|integer',
                'password' => 'nullable|min:6|max:20',
                // 'jam_alpha' => 'required|integer',
                // 'jam_kompen' => 'required|integer',
                // 'jam_kompen_selesai' => 'required|integer',
                'id_level' => 'required|integer'
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
        $mahasiswa = MahasiswaModel::find($id);

        return view('mahasiswa.confirm_ajax', ['mahasiswa' => $mahasiswa]);
    }

    public function delete_ajax(Request $request, $id){
        // cek apakah request dari ajax
        if($request->ajax() || $request->wantsJson()){
            $mahasiswa = MahasiswaModel::find($id);
            if($mahasiswa){
                $mahasiswa->delete();
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
        $check = MahasiswaModel::find($id);
        if(!$check){ // untuk mengecek apakah data mahasiswa dengan id yang dimaksud ada atau tidak
            return redirect('/mahasiswa')->with('error', 'Data mahasiswa tidak ditemukan');
        }

        try{
            MahasiswaModel::destroy($id); //Hapus data mahasiswa
            return redirect('/mahasiswa')->with('success', 'Data mahasiswa berhasil dihapus');
        } catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/mahasiswa')->with('error', 'Data mahasiswa gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function import() {
        return view('mahasiswa.import');
    }

    public function import_ajax(Request $request){
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB
                'file_mahasiswa' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_mahasiswa'); // ambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // load reader file excel
            $reader->setReadDataOnly(true); // hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel
            $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
            $data = $sheet->toArray(null, false, true, true); // ambil data excel
            $insert = [];
            if (count($data) > 1) { // jika data lebih dari 1 baris
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // baris ke 1 adalah header, maka lewati
                        if($value['A'] == "TI"){
                            $id_prodi = 1;
                        } elseif ($value['A'] == "SIB") {
                            $id_prodi = 2;
                        } elseif ($value['A'] == "PPLS") {
                            $id_prodi = 3;
                        }
                        $insert[] = [
                            'id_prodi'=> $id_prodi,
                            'nama' => $value['B'],
                            'nomor_induk' => $value['C'],
                            'username' => $value['C'],
                            'password' => $value['C'],
                            'id_periode' => $value['D'],
                            'jam_alpha' => $value['E'],
                            'jam_kompen' => ($value['E'] * 2),
                            'created_at' => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    // insert data ke database, jika data sudah ada, maka diabaikan
                    MahasiswaModel::insertOrIgnore($insert);
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
        return redirect('/mahasiswa');
    }

    public function export_excel(){
        // Ambil data Level yang akan di export
        $mahasiswa = MahasiswaModel::select('nomor_induk', 'nama', 'id_periode', 'id_prodi')->with('prodi', 'periode')->orderBy('id_prodi')->orderBy('nama')->get();
        
        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Prodi');
        $sheet->setCellValue('C1', 'Nama Mahasiswa');
        $sheet->setCellValue('D1', 'NIM');
        $sheet->setCellValue('E1', 'Tahun Masuk');

        $sheet->getStyle('A1:E1')->getFont()->setBold(true); // bold Header

        $no = 1;    // nomor data dimulai dari 1
        $baris = 2; // baris data dimulai dari baris ke 2
        foreach($mahasiswa as $key => $value){
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->prodi->nama_prodi);
            $sheet->setCellValue('C'.$baris, $value->nama);
            $sheet->setCellValue('D'.$baris, $value->nomor_induk);
            $sheet->setCellValue('E'.$baris, $value->periode->periode);
            $baris++;
            $no++;
        }

        foreach(range('A','E') as $columnID){
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }

        $sheet->setTitle('Data Mahasiswa'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Mahasiswa '.date('Y-m-d H:i:s').'.xlsx';

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
        $mahasiswa = MahasiswaModel::select('nomor_induk', 'nama', 'id_periode', 'id_prodi')->with('prodi')->orderBy('id_prodi')->orderBy('nama')->get();
        $pdf = Pdf::loadView('mahasiswa.export_pdf', ['mahasiswa' => $mahasiswa]);
        $pdf->setPaper('a4', 'potrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url 
        $pdf->render();

        return $pdf->stream('Data Mahasiswa '.date('Y-m-d H:i:s').'.pdf');
    }
}
