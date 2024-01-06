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

    //Загрузка главной не используется
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
    //открыть чат загрузка сообщений
    public function show(Chat $chat, Request $request){
        $CurrentUser = Auth::user();
        $msgfiles=[];
        $messages = $chat->messages->take(40)->reverse();
        $msgfiles = $chat->messages->where('file_path','!=',null);
        //dd($msgfiles);
        $members = $chat->users;
        if($members->where('id',$CurrentUser->id)->count()==0){
            return redirect()->route('chat.show',["chat"=>1]);
        }
        $msgview = view('chat.message', compact('messages'));
        //dd($messages);
        return view('chat.index', compact('chat','messages','members','CurrentUser','msgview','msgfiles'));
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
        if($messages->count()>0)
            return view('chat.message', compact('messages','chat','members'));
    }
    public function getnewmsg(Chat $chat, Message $msg){
        //Проверка по всем чатам новых сообщений
        $new_in_chats=[];
        $cuser = Auth::user();
        $hesaw = $cuser->newmessages;
        $arHS = json_decode($hesaw,true);
        if(!is_array($arHS) && !empty($arHS)){
            $cuser->newmessages=json_encode([]);
            $cuser->save();
        }
        //Получение сообщений
        $lastmsg = $msg->id;
        $messages = $chat->messages->where('id','>',$lastmsg)->reverse();
        $members = $chat->users;
        $update=true;
        if($messages->count()>0){
            if( !isset($arHS[$chat->id]) || $arHS[$chat->id]<$messages->last()->id){
                $arHS[$chat->id]=$messages->last()->id;
                $cuser->newmessages=json_encode($arHS);
                $cuser->save();
            }
            return view('chat.message', compact('messages','chat','members','update'));
        }else{
            if( !isset($arHS[$chat->id]) || $arHS[$chat->id]<$lastmsg){
                $arHS[$chat->id]=$lastmsg;
                $cuser->newmessages=json_encode($arHS);
                $cuser->save();
            }
        }
    }

    public function checkChats(){
        $notificate=[];
        $cuser = Auth::user();
        $checkchats = $cuser->chats;
        $hesaw = $cuser->newmessages;
        //$hesaw = '{"1":"15","8":"55","10":"66","11":"25"}';
        $arHS = json_decode($hesaw,true);
        $new=[];
       // echo json_encode($arHS);
        foreach ($checkchats as $checkchat){
            $lastMsgChat = $checkchat->messages->where('user_id','!=',$cuser->id)->first();
            if($lastMsgChat!==null){
                if(!isset($arHS[$checkchat->id]) || isset($arHS[$checkchat->id]) && $arHS[$checkchat->id]<$lastMsgChat->id){
                    $new[$checkchat->id] = $lastMsgChat->id;
                }
            }
        }
        echo json_encode($new);
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
