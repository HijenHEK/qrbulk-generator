<?php

use App\Http\Controllers\CleanStorageController;
use App\Http\Controllers\QrCodeBulkGenerator;
use App\Http\Controllers\DownloadsController;
use App\Http\Controllers\ZipController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register'=> false , 'login'=>false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('qr_code_bulk_generate' , QrCodeBulkGenerator::class)->name('qr_code_bulk_generate');
Route::post('generate_zip_file/{folder}' , ZipController::class)->name('generate_zip_file');
Route::post('clean_storage/{folder}' , CleanStorageController::class)->name('clean_storage');

Route::get('downloads/{file}' , DownloadsController::class)->name('downloads');