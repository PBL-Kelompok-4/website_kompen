<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MahasiswaModel;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __invoke(Request $request) {
        try {
            $user_id = auth()->guard('api')->user()->id_mahasiswa;
            $mahasiswa = MahasiswaModel::find($user_id);

            return response()->json([
                'success' => true,
                'data' => $mahasiswa
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'masssage' => 'Terjadi Kesalahan'
            ]);
        }
    }
}
