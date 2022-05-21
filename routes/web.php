<?php

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

Route::post('/books',[ \App\Http\Controllers\BooksController::class, 'store']);
Route::patch('/books/{book}-{slug}',[ \App\Http\Controllers\BooksController::class, 'update']);
Route::delete('/books/{book}-{slug}',[ \App\Http\Controllers\BooksController::class, 'destroy']);
