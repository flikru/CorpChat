<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
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


Route::get('/', [ChatController::class, 'index'])->name('chat')->middleware('auth');
Route::get('/chats', [ChatController::class, 'show'])->name('chats.show')->middleware('admin');
Route::patch('/chat/{chat}', [ChatController::class, 'update'])->name('chat.update')->middleware('admin');
Route::get('/chat/create', [ChatController::class, 'chatCreate'])->name('chat.create')->middleware('admin');
Route::post('/chat/storeprivate', [ChatController::class, 'storeprivatechat'])->name('chat.storeprivate')->middleware('auth');
Route::post('/chat/store', [ChatController::class, 'chatstore'])->name('chat.store')->middleware('auth');
Route::get('/chat/getchats', [ChatController::class, 'getChats'])->name('chat.getchats')->middleware('auth');
Route::delete('/chat/close/{chat}', [ChatController::class, 'closeChat'])->name('chat.close')->middleware('auth');
Route::delete('/chat/destroy/{chat}', [ChatController::class, 'destroyChat'])->name('chat.destroy')->middleware('admin');
Route::get('/chat/clear/{chat}', [ChatController::class, 'clearChat'])->name('chat.clear')->middleware('admin');

//Route::get('/chat/{chat}', [ChatController::class, 'index'])->name('chat.index')->middleware('auth');


Route::get('/message/getMessage', [MessageController::class, 'getMessage'])->name('message.getmessage')->middleware('auth');
Route::get('/message/updateMessage', [MessageController::class, 'updateMessage'])->name('message.updatemessage')->middleware('auth');
Route::get('/message/getPrevMessage', [MessageController::class, 'getPrevMessage'])->name('message.getPrevMessage')->middleware('auth');
Route::post('/message/addMessage', [MessageController::class, 'addMessage'])->name('message.store')->middleware('auth');
Route::delete('/message/delete/{message}', [MessageController::class, 'destroy'])->name('message.destroy')->middleware('auth');


Route::post('/user/{user}', [UserController::class, 'update'])->name('user.update')->middleware('auth');
Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show')->middleware('auth');
Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy')->middleware('admin');


Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
