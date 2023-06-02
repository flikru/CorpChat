<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Chat;
use App\Http\Controllers\MessageController;

class ChatController extends Controller
{

    //Загрузка главной
    public function index(){
        //dd($chat);
        $СurrentUser = Auth::user();
        $users = User::all();
        $members = Chat::find(1)->first()->users;
        return view('chat.index',compact('users', 'СurrentUser', 'members'));
    }

    //Получение списка чата
    public static function getChats(){
        $CurrentUser = Auth::user();

        foreach ($CurrentUser->chats as $item){

            if($item->type != "chats"){
                if($item->user_id != $CurrentUser->id){
                    $item->title = User::where('id',$item->user_id)->first()->name;
                }
            }

            $listChats[] = $item;
        }

        if(isset($listChats)){
            return view('chat.listchats',compact('listChats','CurrentUser'));
        }
    }



    //Доблавние сообщения в чат


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
        return $chat->id;
        //return MessageController::getMessage($request);

    }

    //Удаление чата
    public function closeChat(Chat $chat){

        if($chat->id==1){
            return redirect()->route('chat');
        }
        $chat->users()->detach(Auth::user()->id);
        if($chat->users->count()==0){
            $chat->delete();
        }
        return redirect()->route('chat');

    }

    //Добавление чата
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
        return redirect()->route('chat');

    }
    //

}
