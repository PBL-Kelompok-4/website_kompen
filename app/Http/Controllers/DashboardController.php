<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MahasiswaModel;
use App\Models\PersonilAkademikModel;

class DashboardController extends Controller {
    
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Dashboard',
            'list' => ['Dashboard']
        ];

        $page = (object) [
            'title' => 'Dashboard'
        ];

        $activeMenu = 'Dashboard';

        if (auth()->user()->level->kode_level == "ADM") {
            // IKI KODE GAWE JUPUK DATA DEPEK KENE BUL, IKI SENEG GAWE ADMIN TERUS SENG NDEK [] IKU ISI EN DATA E GAWE NGERETURN E, MISAL ['data' => $data, 'admin' => $admin]

            return view('home', []);
        } elseif (auth()->user()->level->kode_level == "MHS" || auth()->user()->level->kode_level == "TDK") {
            // IKI SENG DIGAWE DOSEN AMBEK TENDIK, ENGKOK PODO DATA E LEBOKNO NDEK []

            return view('home', []);
        } else if (auth()->user()->level->kode_level == "MHS") {
            // LAK IKI DIGAWE NANG MAHASISWA ENGKOK YO PODO DATA SENG DIGAWE DEPEK NDEK [] GAWE NGIRIM DATA E NDEK HOME

            return view('home', []);
        }
    }

    public function mahasiswa() {
        $breadcrumb = (object) [
            'title' => 'Dashboard',
            'list' => ['Dashboard']
        ];

        $page = (object) [
            'title' => 'Dashboard'
        ];

        $activeMenu = 'Dashboard';
        $id_mahasiswa = auth()->user()->id_mahasiswa;

        $mahasiswa = MahasiswaModel::select('id_mahasiswa', 'jam_kompen', 'jam_kompen_selesai', 'jam_alpha')
            ->where('id_mahasiswa', $id_mahasiswa)
            ->first();

        return view('mahasiswa.dashboard_mhs', [
            'mahasiswa' => $mahasiswa,
            'activeMenu' => $activeMenu,
            'breadcrumb' => $breadcrumb,
            'page' => $page,
        ]);
    }


    public function admin() {
        $breadcrumb = (object) [
            'title' => 'Dashboard',
            'list' => ['Dashboard']
        ];

        $page = (object) [
            'title' => 'Dashboard'
        ];

        // Query untuk mengambil data dari tabel mahasiswa
        $data = DB::table('mahasiswa')
            ->select(
                DB::raw('SUM(jam_alpha) as total_alpha'),
                DB::raw('SUM(jam_kompen) as total_kompen'),
                DB::raw('SUM(jam_kompen_selesai) as total_kompen_selesai')
            )
            ->first();

        $activeMenu = 'Dashboard';

        // Mengirim data ke view
        return view('personil_akademik.dashboard_adm', [
            'data' => $data,
            'activeMenu' => $activeMenu,
            'breadcrumb' => $breadcrumb,
            'page' => $page,
        ]);
    }
}
