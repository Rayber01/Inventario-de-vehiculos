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
     if (auth()->check()) {
        return redirect()->route('home');
    } else {
        return redirect()->route('login');
    }
});
Auth::routes(['register' => false]);
//Auth::routes();
Route::get('/inventario', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/cars', [App\Http\Controllers\CarsController::class, 'index'])->name('inventary')->middleware('auth');
Route::get('/car/show/{id}', [App\Http\Controllers\CarsController::class, 'show'])->name('show')->middleware('auth');
Route::post('/car/search', [App\Http\Controllers\CarsController::class, 'search'])->name('search')->middleware('auth');
Route::post('/upload/image/{id}', [App\Http\Controllers\CarsController::class, 'uploadImage'])->name('upload')->middleware('auth');
Route::post('/delete/image/{id}', [App\Http\Controllers\CarsController::class, 'removeImage'])->name('removeImage')->middleware('auth');
Route::post('/car/store', [App\Http\Controllers\CarsController::class, 'store'])->name('store')->middleware('auth');
Route::put('/car/update/{id}', [App\Http\Controllers\CarsController::class, 'update'])->name('update')->middleware('auth');
Route::put('/car/sold/{id}', [App\Http\Controllers\CarsController::class, 'updateSold'])->name('updateSold')->middleware('auth');
Route::delete('/car/delete/{id}', [App\Http\Controllers\CarsController::class, 'destroy'])->name('delete')->middleware('auth');
Route::get('/api/get-cars', [App\Http\Controllers\InformacionController::class, 'index'])->middleware('auth.api');
Route::get('/api/filter-cars', [App\Http\Controllers\InformacionController::class, 'search'])->middleware('auth.api');
Route::get('/api/car-view/{id}', [App\Http\Controllers\InformacionController::class, 'view'])->middleware('auth.api');
Route::any('{any}', [App\Http\Controllers\HomeController::class, 'index'])->where('any', '.*')->middleware('auth');