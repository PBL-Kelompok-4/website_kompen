<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\KompenDiajukanController;
use App\Http\Controllers\KompenDibukaController;
use App\Http\Controllers\KompenSelesaiController;
use App\Http\Controllers\KompetensiController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\MahasiswaAlphaController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PersonilAkademikController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\KompenDitolakController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () { return view('welcome'); });

Route::pattern('id', '[0-9]+'); // artinya ketika ada parameter {id}, maka harus berupa angka


Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:web,personil');

Route::middleware(['auth:web,personil'])->group(function () {

    Route::get('/', [HomeController::class, 'index']);
    
    Route::group(['prefix' => 'mahasiswa', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [MahasiswaController::class, 'index']);
        Route::post('/list', [MahasiswaController::class, 'list']); // untuk list json datatables
        Route::get('/create_ajax', [MahasiswaController::class, 'create_ajax']);
        Route::post('/ajax', [MahasiswaController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [MahasiswaController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [MahasiswaController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [MahasiswaController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [MahasiswaController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [MahasiswaController::class, 'delete_ajax']);
        Route::delete('/{id}', [MahasiswaController::class, 'destroy']); // untuk proses hapus
        Route::get('/import', [MahasiswaController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [MahasiswaController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [MahasiswaController::class, 'export_excel']); // ajax import excel
        Route::get('/export_pdf', [MahasiswaController::class, 'export_pdf']); // ajax export pdf
    });
    
    Route::group(['prefix' => 'personil_akademik', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [PersonilAkademikController::class, 'index']);
        Route::post('/list', [PersonilAkademikController::class, 'list']); // untuk list json datatables
        Route::get('/create_ajax', [PersonilAkademikController::class, 'create_ajax']);
        Route::post('/ajax', [PersonilAkademikController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [PersonilAkademikController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [PersonilAkademikController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [PersonilAkademikController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [PersonilAkademikController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [PersonilAkademikController::class, 'delete_ajax']);
        Route::delete('/{id}', [PersonilAkademikController::class, 'destroy']); // untuk proses hapus
        Route::get('/import', [PersonilAkademikController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [PersonilAkademikController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [PersonilAkademikController::class, 'export_excel']); // ajax import excel
        Route::get('/export_pdf', [PersonilAkademikController::class, 'export_pdf']); // ajax export pdf
    });
    
    Route::group(['prefix' => 'level', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [LevelController::class, 'index']);
        Route::post('/list', [LevelController::class, 'list']); // untuk list json datatables
        Route::get('/create_ajax', [LevelController::class, 'create_ajax']);
        Route::post('/ajax', [LevelController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [LevelController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']);
        Route::delete('/{id}', [LevelController::class, 'destroy']); // untuk proses hapus
        Route::get('/import', [LevelController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [LevelController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [LevelController::class, 'export_excel']); // ajax import excel
        Route::get('/export_pdf', [LevelController::class, 'export_pdf']); // ajax export pdf
    });
    
    Route::group(['prefix' => 'kompetensi', 'middleware' => 'authorize:ADM,DSN,TDK,MHS'], function () {
        Route::get('/', [KompetensiController::class, 'index']);
        Route::post('/list', [KompetensiController::class, 'list']); // untuk list json datatables
        Route::get('/create_ajax', [KompetensiController::class, 'create_ajax']);
        Route::post('/ajax', [KompetensiController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [KompetensiController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [KompetensiController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [KompetensiController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [KompetensiController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [KompetensiController::class, 'delete_ajax']);
        Route::delete('/{id}', [KompetensiController::class, 'destroy']); // untuk proses hapus
        Route::get('/import', [KompetensiController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [KompetensiController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [KompetensiController::class, 'export_excel']); // ajax import excel
        Route::get('/export_pdf', [KompetensiController::class, 'export_pdf']); // ajax export pdf
    });
    
    Route::group(['prefix' => 'mahasiswa_alpha', 'middleware' => 'authorize:ADM,DSN,TDK'], function () {
        Route::get('/', [MahasiswaAlphaController::class, 'index']);
        Route::post('/list', [MahasiswaAlphaController::class, 'list']); // untuk list json datatables
        Route::get('/create_ajax', [MahasiswaAlphaController::class, 'create_ajax']);
        Route::post('/ajax', [MahasiswaAlphaController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [MahasiswaAlphaController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [MahasiswaAlphaController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [MahasiswaAlphaController::class, 'update_ajax']);
        // Route::get('/{id}/delete_ajax', [MahasiswaAlphaController::class, 'confirm_ajax']);
        // Route::delete('/{id}/delete_ajax', [MahasiswaAlphaController::class, 'delete_ajax']);
        // Route::delete('/{id}', [MahasiswaAlphaController::class, 'destroy']); // untuk proses hapus
        Route::get('/import', [MahasiswaAlphaController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [MahasiswaAlphaController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [MahasiswaAlphaController::class, 'export_excel']); // ajax import excel
        Route::get('/export_pdf', [MahasiswaAlphaController::class, 'export_pdf']); // ajax export pdf
    });
    
    Route::group(['prefix' => 'kompen_dibuka', 'middleware' => 'authorize:ADM,DSN,TDK,MHS'], function () {
        Route::get('/', [KompenDibukaController::class, 'index']);
        Route::post('/list', [KompenDibukaController::class, 'list']); // untuk list json datatables
        Route::get('/create_ajax', [KompenDibukaController::class, 'create_ajax']);
        Route::post('/ajax', [KompenDibukaController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [KompenDibukaController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [KompenDibukaController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [KompenDibukaController::class, 'update_ajax']);
        // Route::get('/{id}/delete_ajax', [KompenDibukaController::class, 'confirm_ajax']);
        // Route::delete('/{id}/delete_ajax', [KompenDibukaController::class, 'delete_ajax']);
        // Route::delete('/{id}', [KompenDibukaController::class, 'destroy']); // untuk proses hapus
        Route::get('/import', [KompenDibukaController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [KompenDibukaController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [KompenDibukaController::class, 'export_excel']); // ajax import excel
        Route::get('/export_pdf', [KompenDibukaController::class, 'export_pdf']); // ajax export pdf
    });
    
    Route::group(['prefix' => 'kompen_selesai', 'middleware' => 'authorize:ADM,DSN,TDK,MHS'], function () {
        Route::get('/', [KompenSelesaiController::class, 'index']);
        Route::post('/list', [KompenSelesaiController::class, 'list']); // untuk list json datatables
        Route::get('/{id}/show_ajax', [KompenSelesaiController::class, 'show_ajax']);
        // Route::get('/import', [KompenSelesaiController::class, 'import']); // ajax form upload excel
        // Route::post('/import_ajax', [KompenSelesaiController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [KompenSelesaiController::class, 'export_excel']); // ajax import excel
        Route::get('/export_pdf', [KompenSelesaiController::class, 'export_pdf']); // ajax export pdf
    });
    
    Route::group(['prefix' => 'kompen_diajukan', 'middleware' => 'authorize:ADM,DSN,TDK,MHS'], function () {
        Route::get('/', [KompenDiajukanController::class, 'index']);
        Route::post('/list', [KompenDiajukanController::class, 'list']); // untuk list json datatables
        Route::get('/{id}/show_ajax', [KompenDiajukanController::class, 'show_ajax']);
        // Route::get('/import', [KompenDiajukanController::class, 'import']); // ajax form upload excel
        // Route::post('/import_ajax', [KompenDiajukanController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [KompenDiajukanController::class, 'export_excel']); // ajax import excel
        Route::get('/export_pdf', [KompenDiajukanController::class, 'export_pdf']); // ajax export pdf
    });

    Route::get('/profil', [ProfilController::class, 'index']);
    Route::post('/profil/update', [ProfilController::class, 'update']);
    Route::post('/profil/update_data_diri', [ProfilController::class, 'updateDataDiri']);
    Route::post('/profil/update_password', [ProfilController::class, 'updatePassword']);

    Route::group(['prefix' => 'kompen_ditolak', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [KompenDitolakController::class, 'index']);
        Route::post('/list', [KompenDitolakController::class, 'list']); // untuk list json datatables
        Route::get('/{id}/show_ajax', [KompenDitolakController::class, 'show_ajax']);
        Route::get('/export_excel', [KompenDitolakController::class, 'export_excel']); // ajax import excel
        Route::get('/export_pdf', [KompenDitolakController::class, 'export_pdf']); // ajax export pdf
    });

});


// Route::get('/', [HomeController::class, 'index']);
    
//     Route::group(['prefix' => 'mahasiswa'], function () {
//         Route::get('/', [MahasiswaController::class, 'index']);
//         Route::post('/list', [MahasiswaController::class, 'list']); // untuk list json datatables
//         Route::get('/create_ajax', [MahasiswaController::class, 'create_ajax']);
//         Route::post('/ajax', [MahasiswaController::class, 'store_ajax']);
//         Route::get('/{id}/show_ajax', [MahasiswaController::class, 'show_ajax']);
//         Route::get('/{id}/edit_ajax', [MahasiswaController::class, 'edit_ajax']);
//         Route::put('/{id}/update_ajax', [MahasiswaController::class, 'update_ajax']);
//         Route::get('/{id}/delete_ajax', [MahasiswaController::class, 'confirm_ajax']);
//         Route::delete('/{id}/delete_ajax', [MahasiswaController::class, 'delete_ajax']);
//         Route::delete('/{id}', [MahasiswaController::class, 'destroy']); // untuk proses hapus
//         Route::get('/import', [MahasiswaController::class, 'import']); // ajax form upload excel
//         Route::post('/import_ajax', [MahasiswaController::class, 'import_ajax']); // ajax import excel
//         Route::get('/export_excel', [MahasiswaController::class, 'export_excel']); // ajax import excel
//         Route::get('/export_pdf', [MahasiswaController::class, 'export_pdf']); // ajax export pdf
//     });
    
//     Route::group(['prefix' => 'personil_akademik'], function () {
//         Route::get('/', [PersonilAkademikController::class, 'index']);
//         Route::post('/list', [PersonilAkademikController::class, 'list']); // untuk list json datatables
//         Route::get('/create_ajax', [PersonilAkademikController::class, 'create_ajax']);
//         Route::post('/ajax', [PersonilAkademikController::class, 'store_ajax']);
//         Route::get('/{id}/show_ajax', [PersonilAkademikController::class, 'show_ajax']);
//         Route::get('/{id}/edit_ajax', [PersonilAkademikController::class, 'edit_ajax']);
//         Route::put('/{id}/update_ajax', [PersonilAkademikController::class, 'update_ajax']);
//         Route::get('/{id}/delete_ajax', [PersonilAkademikController::class, 'confirm_ajax']);
//         Route::delete('/{id}/delete_ajax', [PersonilAkademikController::class, 'delete_ajax']);
//         Route::delete('/{id}', [PersonilAkademikController::class, 'destroy']); // untuk proses hapus
//         Route::get('/import', [PersonilAkademikController::class, 'import']); // ajax form upload excel
//         Route::post('/import_ajax', [PersonilAkademikController::class, 'import_ajax']); // ajax import excel
//         Route::get('/export_excel', [PersonilAkademikController::class, 'export_excel']); // ajax import excel
//         Route::get('/export_pdf', [PersonilAkademikController::class, 'export_pdf']); // ajax export pdf
//     });
    
//     Route::group(['prefix' => 'level'], function () {
//         Route::get('/', [LevelController::class, 'index']);
//         Route::post('/list', [LevelController::class, 'list']); // untuk list json datatables
//         Route::get('/create_ajax', [LevelController::class, 'create_ajax']);
//         Route::post('/ajax', [LevelController::class, 'store_ajax']);
//         Route::get('/{id}/show_ajax', [LevelController::class, 'show_ajax']);
//         Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);
//         Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']);
//         Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);
//         Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']);
//         Route::delete('/{id}', [LevelController::class, 'destroy']); // untuk proses hapus
//         Route::get('/import', [LevelController::class, 'import']); // ajax form upload excel
//         Route::post('/import_ajax', [LevelController::class, 'import_ajax']); // ajax import excel
//         Route::get('/export_excel', [LevelController::class, 'export_excel']); // ajax import excel
//         Route::get('/export_pdf', [LevelController::class, 'export_pdf']); // ajax export pdf
//     });
    
//     Route::group(['prefix' => 'kompetensi'], function () {
//         Route::get('/', [KompetensiController::class, 'index']);
//         Route::post('/list', [KompetensiController::class, 'list']); // untuk list json datatables
//         Route::get('/create_ajax', [KompetensiController::class, 'create_ajax']);
//         Route::post('/ajax', [KompetensiController::class, 'store_ajax']);
//         Route::get('/{id}/show_ajax', [KompetensiController::class, 'show_ajax']);
//         Route::get('/{id}/edit_ajax', [KompetensiController::class, 'edit_ajax']);
//         Route::put('/{id}/update_ajax', [KompetensiController::class, 'update_ajax']);
//         Route::get('/{id}/delete_ajax', [KompetensiController::class, 'confirm_ajax']);
//         Route::delete('/{id}/delete_ajax', [KompetensiController::class, 'delete_ajax']);
//         Route::delete('/{id}', [KompetensiController::class, 'destroy']); // untuk proses hapus
//         Route::get('/import', [KompetensiController::class, 'import']); // ajax form upload excel
//         Route::post('/import_ajax', [KompetensiController::class, 'import_ajax']); // ajax import excel
//         Route::get('/export_excel', [KompetensiController::class, 'export_excel']); // ajax import excel
//         Route::get('/export_pdf', [KompetensiController::class, 'export_pdf']); // ajax export pdf
//     });
    
//     Route::group(['prefix' => 'mahasiswa_alpha'], function () {
//         Route::get('/', [MahasiswaAlphaController::class, 'index']);
//         Route::post('/list', [MahasiswaAlphaController::class, 'list']); // untuk list json datatables
//         Route::get('/create_ajax', [MahasiswaAlphaController::class, 'create_ajax']);
//         Route::post('/ajax', [MahasiswaAlphaController::class, 'store_ajax']);
//         Route::get('/{id}/show_ajax', [MahasiswaAlphaController::class, 'show_ajax']);
//         Route::get('/{id}/edit_ajax', [MahasiswaAlphaController::class, 'edit_ajax']);
//         Route::put('/{id}/update_ajax', [MahasiswaAlphaController::class, 'update_ajax']);
//         // Route::get('/{id}/delete_ajax', [MahasiswaAlphaController::class, 'confirm_ajax']);
//         // Route::delete('/{id}/delete_ajax', [MahasiswaAlphaController::class, 'delete_ajax']);
//         // Route::delete('/{id}', [MahasiswaAlphaController::class, 'destroy']); // untuk proses hapus
//         Route::get('/import', [MahasiswaAlphaController::class, 'import']); // ajax form upload excel
//         Route::post('/import_ajax', [MahasiswaAlphaController::class, 'import_ajax']); // ajax import excel
//         Route::get('/export_excel', [MahasiswaAlphaController::class, 'export_excel']); // ajax import excel
//         Route::get('/export_pdf', [MahasiswaAlphaController::class, 'export_pdf']); // ajax export pdf
//     });
    
//     Route::group(['prefix' => 'kompen_dibuka'], function () {
//         Route::get('/', [KompenDibukaController::class, 'index']);
//         Route::post('/list', [KompenDibukaController::class, 'list']); // untuk list json datatables
//         Route::get('/create_ajax', [KompenDibukaController::class, 'create_ajax']);
//         Route::post('/ajax', [KompenDibukaController::class, 'store_ajax']);
//         Route::get('/{id}/show_ajax', [KompenDibukaController::class, 'show_ajax']);
//         Route::get('/{id}/edit_ajax', [KompenDibukaController::class, 'edit_ajax']);
//         Route::put('/{id}/update_ajax', [KompenDibukaController::class, 'update_ajax']);
//         // Route::get('/{id}/delete_ajax', [KompenDibukaController::class, 'confirm_ajax']);
//         // Route::delete('/{id}/delete_ajax', [KompenDibukaController::class, 'delete_ajax']);
//         // Route::delete('/{id}', [KompenDibukaController::class, 'destroy']); // untuk proses hapus
//         Route::get('/import', [KompenDibukaController::class, 'import']); // ajax form upload excel
//         Route::post('/import_ajax', [KompenDibukaController::class, 'import_ajax']); // ajax import excel
//         Route::get('/export_excel', [KompenDibukaController::class, 'export_excel']); // ajax import excel
//         Route::get('/export_pdf', [KompenDibukaController::class, 'export_pdf']); // ajax export pdf
//     });
    
//     Route::group(['prefix' => 'kompen_selesai'], function () {
//         Route::get('/', [KompenSelesaiController::class, 'index']);
//         Route::post('/list', [KompenSelesaiController::class, 'list']); // untuk list json datatables
//         Route::get('/{id}/show_ajax', [KompenSelesaiController::class, 'show_ajax']);
//         // Route::get('/import', [KompenSelesaiController::class, 'import']); // ajax form upload excel
//         // Route::post('/import_ajax', [KompenSelesaiController::class, 'import_ajax']); // ajax import excel
//         Route::get('/export_excel', [KompenSelesaiController::class, 'export_excel']); // ajax import excel
//         Route::get('/export_pdf', [KompenSelesaiController::class, 'export_pdf']); // ajax export pdf
//     });
    
//     Route::group(['prefix' => 'kompen_diajukan'], function () {
//         Route::get('/', [KompenDiajukanController::class, 'index']);
//         Route::post('/list', [KompenDiajukanController::class, 'list']); // untuk list json datatables
//         Route::get('/{id}/show_ajax', [KompenDiajukanController::class, 'show_ajax']);
//         // Route::get('/import', [KompenDiajukanController::class, 'import']); // ajax form upload excel
//         // Route::post('/import_ajax', [KompenDiajukanController::class, 'import_ajax']); // ajax import excel
//         Route::get('/export_excel', [KompenDiajukanController::class, 'export_excel']); // ajax import excel
//         Route::get('/export_pdf', [KompenDiajukanController::class, 'export_pdf']); // ajax export pdf
//     });