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


Route::get('/', [App\Http\Controllers\ChatController::class, 'index'])->name('chat')->middleware('auth');
Route::post('/', [App\Http\Controllers\ChatController::class, 'store'])->name('chatStore')->middleware('auth');

Route::get('/chatcreate', [App\Http\Controllers\ChatController::class, 'chatcreate'])->name('chatcreate')->middleware('auth');

Route::post('/storeprivatechat', [App\Http\Controllers\ChatController::class, 'storeprivatechat'])->name('storeprivatechat')->middleware('auth');
Route::post('/chatstore', [App\Http\Controllers\ChatController::class, 'chatstore'])->name('chatstore')->middleware('auth');

Route::get('/viewMessage', [App\Http\Controllers\ChatController::class, 'viewMessage'])->name('viewMessage')->middleware('auth');
Route::get('/getChats', [App\Http\Controllers\ChatController::class, 'getChats'])->name('getChats')->middleware('auth');

Route::delete('/deleteChat/{chat}', [App\Http\Controllers\ChatController::class, 'destroy'])->name('chat.delete')->middleware('auth');

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
