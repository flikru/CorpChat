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

      //$chat = Chat::where('type','like',"1_2")->orWhere('type','like',"2_1")->first();

//        foreach ($chat->users as $user){
//            dd($user);
//            if($user == 1){
//                $haveUser=true;
//            }
//        }
        //dd($chat);
        $СurrentUser = Auth::user();
        $users = User::all();
        return view('chat.index',compact('users', 'СurrentUser'));
    }

    public function getChats(){
        $CurrentUser = Auth::user();

        foreach ($CurrentUser->chats as $item){
            $listChats[] = $item;
        }
        return view('chat.listchats',compact('listChats','CurrentUser'));
    }

    public function viewMessage(Request $request){
        $СurrentUser = Auth::user();
        $chat_id = $request->chat_id;
        $messages = Message::where('chat_id',$chat_id)->orderBy('id', 'asc')->take(40)->get();
        $infoChat = Chat::where('id', $chat_id)->first();
        return view('chat.message',compact('messages','infoChat','СurrentUser'));
    }

    public function store(Request $request){
        $data = $request->all();
        $message = Message::create($data);
        if($message)
            return json_encode('ok');
        else
            return json_encode('false');
    }

    //Создание чата
    public function chatcreate(){
        $users = User::all();
        $СurrentUser = Auth::user();
        return view('chat.create', compact('users','СurrentUser'));
    }

    //Создание приватного чата
    public function storePrivateChat(Request $request){

        $СurrentUserID = Auth::user()->id;
        $user_id = $request->user_id;

        //Поиск дублей чатов
        $name_private_chat = $СurrentUserID."_".$user_id;
        $name_private_chat2 = $user_id."_".$СurrentUserID;

        $user_name = User::find($user_id)->name;

        //Поиск существующего приватного чата
        $chat = Chat::where('type','like',$name_private_chat)->orWhere('type','like',$name_private_chat2)->first();

        if($chat == null){

            $chat = Chat::Create([
                'title' => $user_name,
                'user_id' => $СurrentUserID,
                'type' => $name_private_chat,
            ]);

            $create = (array) $chat;

            if($create['wasRecentlyCreated']==true){
                $chat->users()->attach($СurrentUserID);
                $chat->users()->attach($user_id);
            }
        }else{
            //Проверка есть ли в чате текущий пользователь
            $haveUser=false;
            foreach ($chat->users as $user){
                if($user->id == $СurrentUserID){
                    $haveUser=true;
                }
            }
            //Если нет то добавляем
            if($haveUser==false){
                $chat->users()->attach($СurrentUserID);
            }
        }
        $request->chat_id = $chat->id;
        return $this::viewMessage($request);

    }

    public function destroy(Chat $chat){
        if($chat->id==1){
            return redirect()->route('chat');
        }
        $chat->users()->detach(Auth::user()->id);
        //$chat->delete();
        return redirect()->route('chat');
    }

    public function chatstore(Request $request){
        $СurrentUser = Auth::user();
        $members = $request->members;
        $data['title'] = $request->title;
        $data['user_id'] = Auth::user()->id;
        $chat = Chat::create($data);
        $chat->users()->attach(Auth::user()->id);
        foreach ( $members as $member) {

            $chat->users()->attach($member);
        }
        //            $user = User::firstOrCreate(
//                ['email' =>  request('email')],
//                ['name' => request('name')]
//            );
        return redirect()->route('chat');
    }
    //

}
