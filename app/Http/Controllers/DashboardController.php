<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MahasiswaModel;
use App\Models\PersonilAkademikModel;

class DashboardController extends Controller
{
    /**
     * Show the dashboard with the pie chart data.
     *
     * @return \Illuminate\View\View
     */

     public function mahasiswa()
     {
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

