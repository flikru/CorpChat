<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Chat;

class ChatController extends Controller
{
    public function index(){
        $СurrentUser = Auth::user();
        if (Auth::check()) {
            $userId = Auth::user()->id;
        }else{
            $userId = 5;
        }
        $user = User::find($userId);
        foreach ($user->chats as $item){
            $listChats[] = $item;
        }
       $users = User::all();
        return view('chat.index',compact('listChats','users', 'СurrentUser'));
    }

    public function viewMessage(Request $request){
        $messages = Message::where('chat_id',$request->chat_id)->orderBy('id', 'asc')->take(40)->get();
        $infoChat = Chat::where('id', $request->chat_id)->first();
        foreach ($messages as $message){
            $arrMessages[] = [
                'chat_title' => $infoChat->title,
                'user_create_id' => $infoChat->user_id,
                'id' => $message->id,
                'text' => $message->text,
                'user_id' => $message->user_id,
                'created_at' => date('d-m-Y, H:i:s', strtotime($message->created_at)),
            ];
        }
        return json_encode($arrMessages);
    }

    public function store(Request $request){
        $data = $request->all();
//        $data['text'] = $request->message;
//        $data['user_id'] = Auth::user()->id;
//        $data['chat_id'] = $request->chat_id;
        $message = Message::create($data);
        if($message)
            return json_encode('ok');
        else
            return json_encode('false');
        //$message->chat()->attach($data['chat_id']);
    }
    //

}
