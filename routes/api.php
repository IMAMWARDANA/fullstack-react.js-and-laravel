<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\StokBKController;
use App\Http\Controllers\StokBMController;
use App\Http\Controllers\StokBRController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KeperluanController;
use App\Http\Controllers\TransaksiMasukController;
use App\Http\Controllers\TransaksiKeluarController;
use App\Http\Controllers\TransaksimasukdetailController;
use App\Http\Controllers\TransaksikeluardetailController;

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

Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');
Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');
Route::middleware('auth:api')->get('/datauser', function (Request $request) {
    Log::info('Data user request received', ['user' => $request->user()]);
    return $request->user();
});


//admin
// user
Route::post('/user/store', [UserController::class, 'store']);
Route::get('/user/show/{id}', [UserController::class, 'show']);
Route::patch('/user/update/{id}', [UserController::class, 'update']);
Route::delete('/user/destroy/{id}', [UserController::class, 'destroy']);
//role
Route::post('/role/store', [RoleController::class, 'store']);
Route::get('/role/show/{id}', [RoleController::class, 'show']);
Route::patch('/role/update/{id}', [RoleController::class, 'update']);
Route::delete('/role/destroy/{id}', [RoleController::class, 'destroy']);
//user stok
//barang
Route::post('/barang/store', [BarangController::class, 'store']);
Route::get('/barang/show/{id}', [BarangController::class, 'show']);
Route::patch('/barang/update/{id}', [BarangController::class, 'update']);
Route::delete('/barang/destroy/{id}', [BarangController::class, 'destroy']);
//kategori
Route::post('/kategori/store', [KategoriController::class, 'store']);
Route::get('/kategori/show/{id}', [KategoriController::class, 'show']);
Route::patch('/kategori/update/{id}', [KategoriController::class, 'update']);
Route::delete('/kategori/destroy/{id}', [KategoriController::class, 'destroy']);
//satuan
Route::post('/satuan/store', [SatuanController::class, 'store']);
Route::get('/satuan/show/{id}', [SatuanController::class, 'show']);
Route::patch('/satuan/update/{id}', [SatuanController::class, 'update']);
Route::delete('/satuan/destroy/{id}', [SatuanController::class, 'destroy']);
//stok
Route::get('/stok-keluar', [StokController::class, 'getStokKeluar']);
Route::get('/stok-keluar-details/{barang_id}', [StokController::class, 'getStokKeluarDetails']);
Route::post('/stok/store', [StokController::class, 'store']);
Route::get('/stok/show/{id}', [StokController::class, 'show']);
Route::patch('/stok/update/{id}', [StokController::class, 'update']);
Route::delete('/stok/destroy/{id}', [StokController::class, 'destroy']);
//stokmasuk
Route::get('/stok-masuk-details/{id}', [StokBMController::class, 'show']);
Route::post('/stokmasuk/store', [StokBMController::class, 'store']);
Route::get('/stokmasuk/show/{id}', [StokBMController::class, 'show']);
Route::patch('/stokmasuk/update/{id}', [StokBMController::class, 'update']);
Route::delete('/stokmasuk/destroy/{id}', [StokBMController::class, 'destroy']);
//stokkeluar
Route::post('/stokkeluar/store', [StokBKController::class, 'store']);
Route::get('/stokkeluar/show/{id}', [StokBKController::class, 'show']);
Route::patch('/stokkeluar/update/{id}', [StokBKController::class, 'update']);
Route::delete('/stokkeluar/destroy/{id}', [StokBKController::class, 'destroy']);
//stokrusak
Route::post('/stokrusak/store', [StokBRController::class, 'store']);
Route::get('/stokrusak/show/{id}', [StokBRController::class, 'show']);
Route::patch('/stokrusak/update/{id}', [StokBRController::class, 'update']);
Route::delete('/stokrusak/destroy/{id}', [StokBRController::class, 'destroy']);
//transaksikeluar
Route::get('/transaksikeluar/show/{id}', [TransaksiKeluarController::class, 'show']);
Route::post('/transaksikeluar/store', [TransaksiKeluarController::class, 'store']);
Route::delete('/transaksikeluar/destroy/{id}', [TransaksiKeluarController::class, 'destroy']);
//transaksikeluardetail
Route::get('/transaksikeluardetail/show/{id}', [TransaksikeluardetailController::class, 'show']);
Route::post('/transaksikeluardetail/store', [TransaksikeluardetailController::class, 'store']);
//transaksimasuk
Route::get('/transaksimasukdetail/show/{id}', [TransaksimasukdetailController::class, 'show']);
Route::get('/transaksimasuk/show/{id}', [TransaksiMasukController::class, 'show']);
Route::post('/transaksimasuk/store', [TransaksiMasukController::class, 'store']);
// Koreksi Transaksi Masuk route
Route::post('/transaksimasuk/koreksi', [TransaksiMasukController::class, 'koreksi']);
//keperluan
Route::post('/keperluan/store', [KeperluanController::class, 'store']);
Route::get('/keperluan/show/{id}', [KeperluanController::class, 'show']);
Route::patch('/keperluan/update/{id}', [KeperluanController::class, 'update']);
Route::delete('/keperluan/destroy/{id}', [KeperluanController::class, 'destroy']);

//Management

//Marketing


Route::apiResource('user', UserController::class);
Route::apiResource('role', RoleController::class);
Route::apiResource('barang', BarangController::class);
Route::apiResource('kategori', KategoriController::class);
Route::apiResource('satuan', SatuanController::class);
Route::apiResource('stok', StokController::class);
Route::apiResource('stokmasuk', StokBMController::class);
Route::apiResource('stokkeluar', StokBKController::class);
Route::apiResource('stokrusak', StokBRController::class);
Route::apiResource('transaksikeluar', TransaksiKeluarController::class);
Route::apiResource('transaksikeluardetail', TransaksikeluardetailController::class);
Route::apiResource('transaksimasuk', TransaksiMasukController::class);
Route::apiResource('keperluan', KeperluanController::class);



Route::patch('/transaksimasuk/update/{id}', [TransaksiMasukController::class, 'update']);
Route::post('transaksimasuk/store', [TransaksiMasukController::class, 'store']);
Route::apiResource('transaksimasukdetail', TransaksimasukdetailController::class);
