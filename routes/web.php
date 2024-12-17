<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\KompenDiajukanController;
use App\Http\Controllers\KompenDibukaController;
use App\Http\Controllers\KompenSelesaiController;
use App\Http\Controllers\KompetensiController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MahasiswaAlphaController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PersonilAkademikController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KompenDilakukanController;
use App\Http\Controllers\KompenDitolakController;
use App\Http\Controllers\MahasiswaKompenController;
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

Route::pattern('id', '[0-9]+'); // artinya ketika ada parameter {id}, maka harus berupa angka

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:web,personil');

Route::middleware(['auth:web,personil'])->group(function () {

    // Route::group(['prefix' => 'mahasiswa', 'middleware' => 'authorize:MHS'], function () {
    Route::get('/', [DashboardController::class, 'mahasiswa']);
    // });

    // Route::group(['prefix' => '', 'middleware' => 'authorize:ADM,DSN,TDK'], function () {
    Route::get('/', [DashboardController::class, 'admin']);
    // });

    Route::get('/', [HomeController::class, 'index']);

    Route::get('/profil', [ProfilController::class, 'index']);
    Route::post('/profil/update', [ProfilController::class, 'update']);
    Route::post('/profil/update_data_diri', [ProfilController::class, 'updateDataDiri']);
    Route::post('/profil/update_password', [ProfilController::class, 'updatePassword']);
    
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
        Route::get('/dashboard', [MahasiswaController::class, 'dashboardData']);
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

        Route::get('/chartjs', [PersonilAkademikController::class, 'LoadChartJsPage']); 
    });
    
    Route::group(['prefix' => 'level', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [LevelController::class, 'index']);
        Route::post('/list', [LevelController::class, 'list']); // untuk list json datatables
        Route::get('/{id}/show_ajax', [LevelController::class, 'show_ajax']);
    });
    
    Route::group(['prefix' => 'kompetensi', 'middleware' => 'authorize:ADM,DSN,TDK,MHS'], function () {
        Route::get('/', [KompetensiController::class, 'index']);
        Route::post('/list', [KompetensiController::class, 'list']); // untuk list json datatables
        Route::get('/create_ajax', [KompetensiController::class, 'create_ajax'])->middleware('authorize:ADM');
        Route::post('/ajax', [KompetensiController::class, 'store_ajax'])->middleware('authorize:ADM');
        Route::get('/{id}/show_ajax', [KompetensiController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [KompetensiController::class, 'edit_ajax'])->middleware('authorize:ADM');
        Route::put('/{id}/update_ajax', [KompetensiController::class, 'update_ajax'])->middleware('authorize:ADM');
        Route::get('/{id}/delete_ajax', [KompetensiController::class, 'confirm_ajax'])->middleware('authorize:ADM');
        Route::delete('/{id}/delete_ajax', [KompetensiController::class, 'delete_ajax'])->middleware('authorize:ADM');
        Route::delete('/{id}', [KompetensiController::class, 'destroy'])->middleware('authorize:ADM'); // untuk proses hapus
        Route::get('/import', [KompetensiController::class, 'import'])->middleware('authorize:ADM'); // ajax form upload excel
        Route::post('/import_ajax', [KompetensiController::class, 'import_ajax'])->middleware('authorize:ADM'); // ajax import excel
        Route::get('/export_excel', [KompetensiController::class, 'export_excel'])->middleware('authorize:ADM'); // ajax import excel
        Route::get('/export_pdf', [KompetensiController::class, 'export_pdf'])->middleware('authorize:ADM'); // ajax export pdf
    });
    
    Route::group(['prefix' => 'mahasiswa_alpha', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [MahasiswaAlphaController::class, 'index']);
        Route::post('/list', [MahasiswaAlphaController::class, 'list']); // untuk list json datatables
        Route::get('/create_ajax', [MahasiswaAlphaController::class, 'create_ajax']);
        Route::get('/get_options', [MahasiswaAlphaController::class, 'get_mahasiswa_options']);
        Route::post('/ajax', [MahasiswaAlphaController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [MahasiswaAlphaController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [MahasiswaAlphaController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [MahasiswaAlphaController::class, 'update_ajax']);
        Route::get('/export_excel', [MahasiswaAlphaController::class, 'export_excel']); // ajax import excel
        Route::get('/export_pdf', [MahasiswaAlphaController::class, 'export_pdf']); // ajax export pdf
    });

    Route::group(['prefix' => 'mahasiswa_kompen', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [MahasiswaKompenController::class, 'index']);
        Route::post('/list', [MahasiswaKompenController::class, 'list']); // untuk list json datatables
        Route::get('/{id}/show_ajax', [MahasiswaKompenController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [MahasiswaKompenController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [MahasiswaKompenController::class, 'update_ajax']);
        Route::get('/import', [MahasiswaKompenController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [MahasiswaKompenController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [MahasiswaKompenController::class, 'export_excel']); // ajax import excel
        Route::get('/export_pdf', [MahasiswaKompenController::class, 'export_pdf']); // ajax export pdf
    });
    
    Route::group(['prefix' => 'kompen_diajukan', 'middleware' => 'authorize:ADM,DSN,TDK'], function () {
        Route::get('/', [KompenDiajukanController::class, 'index']);
        Route::post('/list', [KompenDiajukanController::class, 'list']); // untuk list json datatables
        Route::get('/create_ajax', [KompenDiajukanController::class, 'create_ajax']);
        Route::post('/ajax', [KompenDiajukanController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [KompenDiajukanController::class, 'show_ajax']);
        Route::post('/{id}/diterima', [KompenDiajukanController::class, 'diterima'])->middleware('authorize:ADM');
        Route::post('/{id}/ditolak', [KompenDiajukanController::class, 'ditolak'])->middleware('authorize:ADM');
    });

    Route::group(['prefix' => 'kompen_ditolak', 'middleware' => 'authorize:ADM,DSN,TDK'], function () {
        Route::get('/', [KompenDitolakController::class, 'index']);
        Route::post('/list', [KompenDitolakController::class, 'list']); // untuk list json datatables
        Route::get('/{id}/show_ajax', [KompenDitolakController::class, 'show_ajax']);
        Route::get('/export_excel', [KompenDitolakController::class, 'export_excel'])->middleware('authorize:ADM'); // ajax import excel
        Route::get('/export_pdf', [KompenDitolakController::class, 'export_pdf'])->middleware('authorize:ADM'); // ajax export pdf
    });
    
    Route::get('/profil', [ProfilController::class, 'index']);
    Route::post('/profil/update', [ProfilController::class, 'update']);
    Route::post('/profil/update_data_diri', [ProfilController::class, 'updateDataDiri']);
    Route::post('/profil/update_password', [ProfilController::class, 'updatePassword']);
    });

    Route::group(['prefix' => 'kompen_dibuka', 'middleware' => 'authorize:ADM,DSN,TDK,MHS'], function () {
        Route::get('/', [KompenDibukaController::class, 'index']);
        Route::post('/list', [KompenDibukaController::class, 'list']); // untuk list json datatables
        Route::get('/{id}/show_ajax', [KompenDibukaController::class, 'show_ajax']);
        Route::post('/ajukan_kompen', [KompenDibukaController::class, 'ajukan_kompen'])->name('ajukan_kompen')->middleware('authorize:MHS');
        Route::get('/{id}/list_pelamar_ajax', [KompenDibukaController::class, 'show_pelamar']);
        Route::post('/daftar_pelamar', [KompenDibukaController::class, 'list_pelamar']); // untuk list json datatables
        Route::post('update_pengajuan_kompen', [KompenDibukaController::class, 'update_pengajuan_kompen'])->name('update_pengajuan_kompen')->middleware('authorize:ADM');
        Route::post('update_status_kompen', [KompenDibukaController::class, 'update_status'])->name('update_status_kompen')->middleware('authorize:ADM');
    });
    
    Route::group(['prefix' => 'kompen_dilakukan', 'middleware' => 'authorize:ADM,DSN,TDK,MHS'], function () {
        Route::get('/', [KompenDilakukanController::class, 'index']);
        Route::post('/list', [KompenDilakukanController::class, 'list']); // untuk list json datatables
        Route::get('/{id}/show_ajax', [KompenDilakukanController::class, 'show_ajax']);
        Route::get('/{id}/list_pekerja_ajax', [KompenDilakukanController::class, 'show_pekerja']);
        Route::get('/{id}/upload_progres', [KompenDilakukanController::class, 'upload_progres'])->middleware('authorize:MHS');
        Route::put('/{id}/update_progres', [KompenDilakukanController::class, 'update_progres'])->middleware('authorize:MHS');
        Route::post('/list_pekerja', [KompenDilakukanController::class, 'list_pekerja'])->middleware('authorize:ADM,DSN,TDK'); // untuk list json datatables
        Route::get('/{id}/upload_bukti', [KompenDilakukanController::class, 'upload_bukti_kompen'])->middleware('authorize:MHS');
        Route::put('/{id}/store_bukti', [KompenDilakukanController::class, 'store_bukti'])->middleware('authorize:MHS');
        Route::post('/konfirmasi_pekerjaan', [KompenDilakukanController::class, 'konfirmasi_pekerjaan'])->name('konfirmasi_pekerjaan')->middleware('authorize:ADM,DSN,TDK');
        Route::post('/selesaikan_kompen', [KompenDilakukanController::class, 'selesaikan_kompen'])->middleware('authorize:ADM,DSN,TDK');
        Route::get('/export_bukti_kompen', [KompenDilakukanController::class, 'export_bukti_kompen']);
    });
    
    Route::group(['prefix' => 'kompen_selesai', 'middleware' => 'authorize:ADM,DSN,TDK,MHS'], function () {
        Route::get('/', [KompenSelesaiController::class, 'index']);
        Route::post('/list', [KompenSelesaiController::class, 'list']); // untuk list json datatables
        Route::get('/{id}/show_ajax', [KompenSelesaiController::class, 'show_ajax']);
        Route::get('/export_excel', [KompenSelesaiController::class, 'export_excel'])->middleware('authorize:ADM'); // ajax import excel
        Route::get('/export_pdf', [KompenSelesaiController::class, 'export_pdf'])->middleware('authorize:ADM'); // ajax export pdf
    });
});