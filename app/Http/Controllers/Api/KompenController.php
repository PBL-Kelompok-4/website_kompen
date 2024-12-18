<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KompenModel;
use App\Models\MahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KompenController extends Controller
{
    public function list(Request $request){
        try {
            $user_id = auth()->guard('api')->user()->id_mahasiswa;
            $mahasiswa = MahasiswaModel::select('id_mahasiswa', 'jam_kompen', 'jam_kompen_selesai', DB::raw('(jam_kompen - jam_kompen_selesai) AS sisa_kompen'))
            ->where('id_mahasiswa', $user_id)
            ->first();

            $id_level = $request->id_level;
            
            $query = KompenModel::select('id_kompen', 'nama', 'deskripsi', 'id_personil', 'id_jenis_kompen', 'kuota', 'jam_kompen', 'status','is_selesai', 'tanggal_mulai', 'tanggal_selesai')
            ->where('is_selesai', 'no')
            ->where('status', 'dibuka')
            ->where('jam_kompen', '<=' , $mahasiswa->sisa_kompen);
        
            if($request->id_level) {
                $query->whereHas('personilAkademik', function($query) use($id_level){
                    $query->where('id_level', $id_level);
                });
            }
            
            if($request->id_jenis_kompen){
                $query->where('id_jenis_kompen', $request->id_jenis_kompen);
            }
            
            $kompen = $query->with('personilAkademik', 'jenisKompen')->get();
            
            return response()->json([
                'success' => true,
                'data' => $kompen
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi Kesalahan'
            ]);
        }
    }

    public function show($id) {
        try {
            
            $kompen = KompenModel::find($id);
            return response()->json([
                'success' => true,
                'data' => $kompen
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi Kesalahan'
            ]);
        }
    }
}
