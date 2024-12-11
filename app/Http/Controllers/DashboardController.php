<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MahasiswaModel;

class DashboardController extends Controller
{
    /**
     * Show the dashboard with the pie chart data.
     *
     * @return \Illuminate\View\View
     */
    
     public function index()
    {
        
        $breadcrumb = (object)[
            'title' => 'Dashboard',
            'list' => ['Dashboard']
        ];

        $page = (object)[
            'title' => 'Dashboard'
        ];

        $activeMenu = 'Dashboard'; // set menu yang sedang aktif
        $id_mahasiswa = auth()->user()->id_mahasiswa; // Ambil username pengguna yang sedang login
        $mahasiswa = MahasiswaModel::select('id_mahasiswa', 'jam_kompen', 'jam_kompen_selesai', 'jam_alpha')
         ->first();
        
        return view('home', ['mahasiswa' => $mahasiswa, 'activeMenu' => $activeMenu, 'breadcrumb' => $breadcrumb, 'page' => $page]);
    }

}    

