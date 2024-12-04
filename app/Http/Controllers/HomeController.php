<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Dashboard',
            'list' => ['Home', 'Welcome']
        ];

        $activeMenu = 'dashboard';

        return view('home', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }
}
