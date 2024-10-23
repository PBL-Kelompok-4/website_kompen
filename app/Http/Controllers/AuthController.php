<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserModel;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(){

        if(Auth::check()){ //jika sudah login, maka redirect ke halaman home
            return redirect('/');
        }
        return view('auth.login');
    }

    public function postlogin(Request $request){
        if($request->ajax() || $request->wantsJson()){
            $credentials = $request->only('username', 'password');

            if(Auth::attempt($credentials)){
                return response()->json([
                    'status' => true,
                    'massage' => 'Login Berhasil',
                    'redirect' => url('/')
                ]);
            }

            return response()->json([
                'status' => false,
                'massage' => 'Login Gagal'
            ]);
        }
        return response('login');
    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }

    public function register(){

        return view('auth.register');
    }

    public function postregister(Request $request){
        if($request->ajax() || $request->wantsJson()){
            $rules = [
                'username' => 'required|string|min:3|max:20|unique:m_user,username',
                'nama' => 'required|string|min:3|max:100', // nama harus diisi, berupa string dan maksimal 100 karakter
                'password' => 'required|min:5', // password harus diisi dan minimal 5 karakter
                'level_id' => 'required|integer' // level_id harus diisi dan berupa angka
            ];

            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                    'redirect' => url('register')
                ]);
            }

            UserModel::create([
                'username' => $request->username,
                'nama' => $request->nama,
                'password' => bcrypt($request->password), //password dienskripsi sebelum disimpan
                'level_id' => $request->level_id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Registrasi Berhasil',
                'redirect' => url('login')
            ]);
        }

        return redirect()->route('login');
    }
}
