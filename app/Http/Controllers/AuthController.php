<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(){

        if(Auth::guard('web')->check() || Auth::guard('personil')->check()){ //jika sudah login, maka redirect ke halaman home
            return redirect('/');
        }
        return view('auth.login');
    }

    public function postlogin(Request $request){
        if($request->ajax() || $request->wantsJson()){
            $credentials = $request->only('username', 'password');

            if($request->user_role == "mahasiswa"){
                if(Auth::guard('web')->attempt($credentials)){
                    return response()->json([
                        'status' => true,
                        'massage' => 'Login Mahasiswa Berhasil',
                        'redirect' => url('/')
                    ]);
                }
            } elseif($request->user_role == "personil_akademik"){
                if(Auth::guard('personil')->attempt($credentials)){
                    return response()->json([
                        'status' => true,
                        'massage' => 'Login Personil Akademik Berhasil',
                        'redirect' => url('/')
                    ]);
                }
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
}
