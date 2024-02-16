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
//По чату
//Route::get('/', [ChatController::class, 'index'])->name('chat')->middleware('auth');
Route::get('/', function (){ return redirect()->route('chat.show',["chat"=>1]); });
Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chat.show')->middleware('auth');
//Получить сообщения
Route::get('/chat/{chat}', [ChatController::class, 'getmsg'])->name('chat.getmsg')->middleware('auth');
//Получить новые сообщения
Route::get('/chat/getnewmsg/{chat}/{msg}', [ChatController::class, 'getnewmsg'])->name('chat.getnewmsg')->middleware('auth');
//подгрузить старые сообщения
Route::get('/chat/{chat}/{msg}', [ChatController::class, 'getprevmsg'])->name('chat.getprevmsg')->middleware('auth');
Route::get('/chatsget', [ChatController::class, 'getChats'])->name('chat.getchats')->middleware('auth');
Route::get('/chatscheck', [ChatController::class, 'checkChats'])->name('chats.check')->middleware('auth');

//По редактору
Route::get('/editor', [ChatController::class, 'editor'])->name('editor.index')->middleware('admin');
Route::patch('/editor/{chat}', [ChatController::class, 'update'])->name('editor.update')->middleware('admin');
Route::get('/editor/create', [ChatController::class, 'chatCreate'])->name('editor.create')->middleware('admin');
Route::post('/editor/store', [ChatController::class, 'chatstore'])->name('editor.store')->middleware('auth');
Route::post('/editor/storeprivate', [ChatController::class, 'storeprivatechat'])->name('editor.storeprivate')->middleware('auth');
Route::get('/editor/clear/{chat}', [ChatController::class, 'clearChat'])->name('chat.clear')->middleware('admin');
Route::delete('/editor/destroy/{chat}', [ChatController::class, 'destroyChat'])->name('chat.destroy')->middleware('admin');
Route::delete('/editor/close/{chat}', [ChatController::class, 'closeChat'])->name('chat.close')->middleware('auth');
Route::post('/editor/storeprivate', [ChatController::class, 'storeprivatechat'])->name('chat.storeprivate')->middleware('auth');


//Route::get('/', [ChatController::class, 'index'])->name('chat')->middleware('auth');
//Route::get('/editor/', [ChatController::class, 'show'])->name('chats.show')->middleware('admin');

//Route::get('/chat/{chat}', [ChatController::class, 'index'])->name('chat.index')->middleware('auth');


//Route::get('/message/getMessage', [MessageController::class, 'getMessage'])->name('message.getmessage')->middleware('auth');
//Route::get('/message/updateMessage', [MessageController::class, 'updateMessage'])->name('message.updatemessage')->middleware('auth');
//Route::get('/message/getPrevMessage', [MessageController::class, 'getPrevMessage'])->name('message.getPrevMessage')->middleware('auth');
//Route::get('/message/getprev/{chat}', [MessageController::class, 'getprev'])->name('message.getprev')->middleware('auth');
Route::post('/message/addMessage', [MessageController::class, 'addMessage'])->name('message.store')->middleware('auth');
Route::delete('/message/delete/{message}', [MessageController::class, 'destroy'])->name('message.destroy')->middleware('auth');


Route::post('/user/{user}', [UserController::class, 'update'])->name('user.update')->middleware('auth');
Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show')->middleware('auth');
Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy')->middleware('admin');
Route::patch('/user/{user}', [UserController::class, 'setStatus'])->name('user.setStatus');


Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
