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

Route::get('/chat', [App\Http\Controllers\ChatController::class, 'index'])->name('chat');
Route::post('/chat', [App\Http\Controllers\ChatController::class, 'store'])->name('chatStore');
Route::get('/viewMessage', [App\Http\Controllers\ChatController::class, 'viewMessage'])->name('viewMessage');


Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
