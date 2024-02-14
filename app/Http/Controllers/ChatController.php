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
        return view('editor.create', compact('users','СurrentUser'));
    }

    //Создание приватного чата
    public static function storePrivateChat(Request $request){

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

    }

    //Скрытие чата
    public function closeChat(Chat $chat){
        if($chat->id==1){
            return redirect()->route('chat.show',["chat"=>1]);
        }
        $chat->users()->detach(Auth::user()->id);
        if($chat->users->count()==0){
            //$chat->delete();
        }
        return redirect()->route('chat.show',["chat"=>1]);

    }

    //Удаление чата администратором
    public function destroyChat(Chat $chat){
        if($chat->id==1){
            return redirect()->route('chat.show',["chat"=>1]);
        }

        $chat->delete();
        return redirect()->route('chat.show',["chat"=>1]);

    }

    //Добавление чата
    public function chatstore(Request $request){
        if($request->members == null){
            return redirect()->route('editor.create');;
        }
        $СurrentUser = Auth::user();
        $members = $request->members;
        if(isset($request->title)){
            $data['title'] = $request->title;
        }else{
            $data['title'] = "Чат от ".Auth::user()->name;
        }
        $data['user_id'] = Auth::user()->id;
        $chat = Chat::create($data);
        $chat->users()->attach(Auth::user()->id);
        foreach ( $members as $member) {
            $chat->users()->attach($member);
        }
        return redirect()->route('editor.create');

    }
    public function editor(Request $request){
        $chats = Chat::where('type', 'like', 'chats')->get();
        $users = User::all();
        $currentuser = Auth::user();
        return view('editor.index', compact('chats','users', 'currentuser'));
    }
    public function show(Chat $chat, Request $request){
        $messages = $chat->messages;
        $members = $chat->users;
        $CurrentUser = Auth::user();
        return view('chat.index', compact('chat','messages','members','CurrentUser'));
    }
    public function getmsg(Chat $chat){
        $messages = $chat->messages->take(10)->reverse();
        $members = $chat->users;
        return view('chat.message', compact('messages','chat','members'));
    }
    public function getprevmsg(Chat $chat, Message $msg){
        $lastmsg = $msg->id;
        $messages = $chat->messages->where('id','<',$lastmsg)->take(10)->reverse();
        $members = $chat->users;
        return view('chat.message', compact('messages','chat','members'));
    }
    public function getnewmsg(Chat $chat, Message $msg){
        //Проверка по всем чатам новых сообщений
        $new_in_chats=[];
        $cuser = Auth::user();
        $checkchats = $cuser->chats;
        $hesaw = $cuser->status;
        foreach ($checkchats as $checkchat){
            $lastMsgChat = $checkchat->messages->where('user_id','!=',$cuser->id)->first();
            if($lastMsgChat && $lastMsgChat->id > $cuser->status){
                $notificate[]=$checkchat->id;
                $hesaw=$hesaw > $lastMsgChat->id ? $hesaw : $lastMsgChat->id;
            }
        }
        if($hesaw != $cuser->status){
            $cuser->status=$hesaw;
            //$cuser->save();
        }

        //Получение сообщений
        $lastmsg = $msg->id;
        $messages = $chat->messages->where('id','>',$lastmsg)->reverse();
        $members = $chat->users;
        $update=true;
        if($messages->count()>0){
            return view('chat.message', compact('messages','chat','members','update'));
        }
    }

    public function checkChats(){
        $notificate=[];
        $cuser = Auth::user();
        $checkchats = $cuser->chats;
        $hesaw = $cuser->status;
        $hesaw = '{"1":"162","8":"55","10":"66","11":"25"}';
        $arHS = json_decode($hesaw,true);
       // echo json_encode($arHS);
        foreach ($checkchats as $checkchat){
            $lastMsgChat = $checkchat->messages->where('user_id','!=',$cuser->id)->first();
            if($lastMsgChat && $lastMsgChat->id > $cuser->status){
                $arHS[$checkchat->id] = $lastMsgChat->id;
                $notificate[]=$checkchat->id;
                $hesaw=$hesaw > $lastMsgChat->id ? $hesaw : $lastMsgChat->id;
            }
        }
        dd($arHS);
        if($hesaw != $cuser->status){
            $cuser->status=$hesaw;
            //$cuser->save();
        }
        echo json_encode($notificate);
        return;
    }
    public function update(Chat $chat, Request $request){
        $data = $request->all();

        if($data['members']){
            $members[]=1;
            $members[] = $data['members'];
            $chat->users()->detach();
            foreach ( $members as $member) {
                $chat->users()->attach($member);
            }
            unset($data['members']);
        }

        $chat->update($data);
        $chat->save();
        return redirect()->route('editor.index');
    }
    public function clearChat(Chat $chat){
        $chat->messages()->delete();
        return redirect()->route('editor.index');
    }
}
