<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KompenDetailModel;
use App\Models\MahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard() {
        try {
            $user_id = auth()->guard('api')->user()->id_mahasiswa;
            $mahasiswa = MahasiswaModel::select('id_mahasiswa','nomor_induk', 'nama', 'jam_alpha', 'jam_kompen', 'jam_kompen_selesai')
            ->where('id_mahasiswa', $user_id)
            ->first();
    
            return response()->json([
                'success' => true,
                'data' => $mahasiswa
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi Kesalahan'
            ]);
        }

        
    }

    public function progres_kompen(){
        try {

            $user_id = auth()->guard('api')->user()->id_mahasiswa;
            $progres_kompen = KompenDetailModel::select(
                'id_kompen_detail',
                'id_kompen', 
                'id_mahasiswa', 
                'progres_1', 
                'progres_2', 
                'status',
                DB::raw('CASE 
                    WHEN progres_1 IS NULL AND progres_2 IS NULL THEN 0
                    WHEN progres_1 IS NOT NULL AND progres_2 IS NULL THEN 50
                    WHEN progres_1 IS NOT NULL AND progres_2 IS NOT NULL THEN 100
                    END AS persen_progres'
                ))
            ->where('id_mahasiswa', $user_id)
            ->where('status', 'progres')
            ->with('kompen', 'mahasiswa')
            ->first();
                    
            return response()->json([
                'success' => true,
                'data' => $progres_kompen
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi Kesalahan'
            ]);
        }
    }

    public function show_progres($id){
        try {
            $progres_kompen = KompenDetailModel::select(
                'id_kompen_detail',
                'id_kompen', 
                'id_mahasiswa', 
                'progres_1', 
                'progres_2', 
                'status',
                DB::raw('CASE 
                    WHEN progres_1 IS NULL AND progres_2 IS NULL THEN 0
                    WHEN progres_1 IS NOT NULL AND progres_2 IS NULL THEN 50
                    WHEN progres_1 IS NOT NULL AND progres_2 IS NOT NULL THEN 100
                    END AS persen_progres'
                ))
            ->where('id_kompen_detail', $id)
            ->with('kompen', 'mahasiswa')
            ->first();

            return response()->json([
                'success' => true,
                'data' => $progres_kompen
            ]);
             
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi Kesalahan'
            ]);
        }
    }
}
