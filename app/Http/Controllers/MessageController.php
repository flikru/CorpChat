<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    //Загрузка сообщение в чат
    public static function getMessage(Request $request){
        $СurrentUser = Auth::user();
        $chat_id = $request->chat_id;
        $messages = Message::where('chat_id',$chat_id)->orderBy('id', 'asc')->take(40)->get();
        $infoChat = Chat::where('id', $chat_id)->first();

        return view('chat.message',compact('messages','infoChat', 'СurrentUser'));
    }

    public static function updateMessage(Request $request){
        $last_id = $request->last_message_id;
        $СurrentUser = Auth::user();
        $chat_id = $request->chat_id;

        $messages = Message::where('chat_id',$chat_id)->where('id','>',$last_id)->orderBy('id', 'asc')->take(40)->get();
        $infoChat = Chat::where('id', $chat_id)->first();
        $update=true;
        return view('chat.message',compact('messages','infoChat', 'СurrentUser','update'));
    }

    public function addMessage(Request $request){
        $data = $request->all();
        $message = Message::create($data);
        if($message)
            return json_encode('ok');
        else
            return json_encode('false');
    }

    public function destroy(Message $message, Request $request){
        $request->chat_id = $message->chat_id;

        $СurrentUser = Auth::user();
        if($message->user_id==$СurrentUser->id or $СurrentUser->role=='admin'){
            $message->delete();
            return self::getMessage($request);
            return redirect()->route('chat');
        }else{
            return redirect()->route('chat');
        }

    }
}
