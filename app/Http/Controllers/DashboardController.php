<?php

namespace App\Http\Controllers;

use App\Models\KompenModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MahasiswaModel;
use App\Models\PersonilAkademikModel;

class DashboardController extends Controller
{

    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Dashboard',
            'list' => ['Dashboard']
        ];

        $page = (object) [
            'title' => 'Dashboard'
        ];

        $activeMenu = 'dashboard';

        if (auth()->user()->level->kode_level == "ADM") {
            $data = DB::table('mahasiswa')
                ->select(
                    DB::raw('SUM(jam_alpha) as total_alpha'),
                    DB::raw('SUM(jam_kompen) as total_kompen'),
                    DB::raw('SUM(jam_kompen_selesai) as total_kompen_selesai')
                )
                ->first();

            return view('home', ['data' => $data, 'activeMenu' => $activeMenu, 'breadcrumb' => $breadcrumb, 'page' => $page,]);
        
        } elseif (auth()->user()->level->kode_level == "DSN" || auth()->user()->level->kode_level == "TDK") {
            // Data untuk dosen dan tenaga kependidikan
        
            $breadcrumb = (object) [
                'title' => 'Dashboard',
                'list' => ['Dashboard']
            ];
        
            $page = (object) [
                'title' => 'Dashboard'
            ];
        
            $activeMenu = ' dashboard';
        
            // Menghitung total status_acceptance berdasarkan id_personil 2 dan 3
            $totalReject = KompenModel::whereIn('id_personil', [2, 3])
                ->where('status_acceptance', 'reject')
                ->count();
        
            $totalPending = KompenModel::whereIn('id_personil', [2, 3])
                ->where('status_acceptance', 'pending')
                ->count();
        
            $totalAccept = KompenModel::whereIn('id_personil', [2, 3])
                ->where('status_acceptance', 'accept')
                ->count();
        
            return view('home', [
                'totalReject' => $totalReject,
                'totalPending' => $totalPending,
                'totalAccept' => $totalAccept,
                'activeMenu' => $activeMenu,
                'breadcrumb' => $breadcrumb,
                'page' => $page
            ]);
                    
        } else if (auth()->user()->level->kode_level == "MHS") {

            $breadcrumb = (object) [
                'title' => 'Dashboard',
                'list' => ['Dashboard']
            ];

            $page = (object) [
                'title' => 'Dashboard'
            ];

            $activeMenu = 'dashboard';
            $id_mahasiswa = auth()->user()->id_mahasiswa;

            // Ambil data mahasiswa langsung dari model
            $mahasiswa = MahasiswaModel::select('id_mahasiswa', 'jam_alpha', 'jam_kompen', 'jam_kompen_selesai')
                ->where('id_mahasiswa', $id_mahasiswa)
                ->first();

            return view('home', [
                'mahasiswa' => $mahasiswa,
                'activeMenu' => $activeMenu,
                'breadcrumb' => $breadcrumb,
                'page' => $page,
            ]);
        }

    }

    public function admin()
    {
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

        $activeMenu = 'dashboard';

        // Mengirim data ke view
        return view('home', [
            'data' => $data,
            'activeMenu' => $activeMenu,
            'breadcrumb' => $breadcrumb,
            'page' => $page,
        ]);
    }

    public function dosen()
    {
        $breadcrumb = (object) [
            'title' => 'Dashboard',
            'list' => ['Dashboard']
        ];

        $page = (object) [
            'title' => 'Dashboard'
        ];

        $activeMenu = 'Dashboard';

        $dosen = PersonilAkademikModel::select('dosen', )
            ->where('', )
            ->first();

        return view('home', [
            'dosen' => $dosen,
            'activeMenu' => $activeMenu,
            'breadcrumb' => $breadcrumb,
            'page' => $page,
        ]);
    }
    public function mahasiswa()
    {
        $breadcrumb = (object) [
            'title' => 'Dashboard',
            'list' => ['Dashboard']
        ];

        $page = (object) [
            'title' => 'Dashboard'
        ];

        $activeMenu = 'dashboard';
        $id_mahasiswa = auth()->user()->id_mahasiswa;

        $jam_alpha_mahasiswa = MahasiswaModel::select('jam_alpha')->where('id_mahasiswa', $id_mahasiswa);
        $jam_kompen_mahasiswa = MahasiswaModel::select('jam_kompen')->where('id_mahasiswa', $id_mahasiswa);
        $jam_kompen_selesai_mahasiswa = MahasiswaModel::select('jam_kompen_selesai')->where('id_mahasiswa', $id_mahasiswa);

        $mahasiswa = MahasiswaModel::select('id_mahasiswa', 'jam_alpha', 'jam_kompen', 'jam_kompen_selesai')
            ->where('id_mahasiswa', $id_mahasiswa)
            ->where('jam_alpha', $jam_alpha_mahasiswa)
            ->where('jam_kompen', $jam_kompen_mahasiswa)
            ->where('jam_kompen', $jam_kompen_selesai_mahasiswa)
            ->first();

        return view('mahasiswa.dashboard_mhs', [
            'mahasiswa' => $mahasiswa,
            'activeMenu' => $activeMenu,
            'breadcrumb' => $breadcrumb,
            'page' => $page,
        ]);
    }

}
