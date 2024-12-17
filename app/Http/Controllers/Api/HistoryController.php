<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KompenDetailModel;
use App\Models\KompenModel;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function list(){
        try {

            $user_id = auth()->guard('api')->user()->id_mahasiswa;
            $history_kompen = KompenDetailModel::select('id_kompen', 'id_mahasiswa', 'status')
            ->where('id_mahasiswa', $user_id)
            ->where('status', '!=', 'progres')
            ->whereHas('kompen', function($query){
                $query->where('is_selesai', 'yes');
            })
            ->with('kompen')
            ->get();

            return response()->json([
                'success' => true,
                'data' => $history_kompen
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
            
            $history_kompen = KompenModel::find($id);
            return response()->json([
                'success' => true,
                'data' => $history_kompen
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi Kesalahan'
            ]);
        }
    }
}
