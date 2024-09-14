<?php

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/gallery', [App\Http\Controllers\ImageController::class, 'index'])->name('gallery');
Route::post('/upload', [App\Http\Controllers\ImageController::class, 'store'])->name('image.store');
Route::put('/update/{id}', [App\Http\Controllers\ImageController::class, 'update'])->name('image.update');
Route::delete('/delete/{id}', [App\Http\Controllers\ImageController::class, 'destroy'])->name('image.delete');
