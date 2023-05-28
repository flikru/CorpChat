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

        $users = User::all();
        return view('chat.index',compact('users', 'СurrentUser'));
    }

    public function viewMessage(Request $request){
        $СurrentUser = Auth::user();
        $chat_id = $request->chat_id;
        $messages = Message::where('chat_id',$chat_id)->orderBy('id', 'asc')->take(40)->get();
        $infoChat = Chat::where('id', $chat_id)->first();
        foreach ($messages as $message){
            $arrMessages[] = [
                'chat_title' => $infoChat->title,
                'user_create_id' => $infoChat->user_id,
                'id' => $message->id,
                'text' => $message->text,
                'user_id' => $message->user_id,
                'user_name' => $message->user->name,
                'created_at' => date('d-m-Y, H:i:s', strtotime($message->created_at)),
            ];
        }
        return view('chat.message',compact('messages','infoChat','СurrentUser'));
        //return json_encode($arrMessages);
    }

    public function store(Request $request){
        $data = $request->all();
        $message = Message::create($data);
        if($message)
            return json_encode('ok');
        else
            return json_encode('false');
    }

    public function chatcreate(){
        $users = User::all();
        $СurrentUser = Auth::user();
        return view('chat.create', compact('users','СurrentUser'));
    }
    public function chatstore(Request $request){
        $СurrentUser = Auth::user();
        $members = $request->members;
        $data['title'] = $request->title;
        $data['user_id'] = Auth::user()->id;
        $chat = Chat::create($data);
        $chat->users()->attach(Auth::user()->id);
        foreach ( $members as $member) {
//            $user = User::firstOrCreate(
//                ['email' =>  request('email')],
//                ['name' => request('name')]
//            );
            $chat->users()->attach($member);
        }
        return redirect()->route('chat');
    }
    //

}
