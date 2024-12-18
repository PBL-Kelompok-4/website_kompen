<?php

use App\Http\Controllers\Api\HistoryController;
use App\Http\Controllers\Api\KompenController;
use App\Http\Controllers\Api\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');
Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');

Route::middleware('auth:api')->group(function(){
    
    Route::get('/profile', App\Http\Controllers\Api\ProfileController::class)->name('profile');
    
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('progres-kompen', [DashboardController::class, 'progres_kompen'])->name('progres-kompen');
    Route::get('progres-kompen/{id}/show', [DashboardController::class, 'show_progres']);
    
    Route::prefix('/kompen')->group(function(){
        Route::post('/list', [KompenController::class, 'list'])->name('kompen.index');
        Route::get('/{id}/show', [KompenController::class, 'show'])->name('kompen.show');
    });
    
    Route::prefix('/history-kompen')->group(function(){
        Route::get('/list', [HistoryController::class, 'list'])->name('histori-kompen.index');
        Route::get('/{id}/show', [HistoryController::class, 'show']);
    });
    
});
