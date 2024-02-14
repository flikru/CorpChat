<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    //Загрузка сообщение в чат
   /* public static function getMessage(Request $request){

        $СurrentUser = Auth::user();
        $chat_id = $request->chat_id;
        //$messages = Message::where('chat_id',$chat_id)->orderBy('id', 'asc')->take(40)->get();
        $messages = Message::where('chat_id',$chat_id)->orderBy('id', 'desc')->take(40)->get()->reverse();
        $infoChat = Chat::where('id', $chat_id)->first();
        $members = $infoChat->users;

        if($infoChat->type != "chats"){
            if($infoChat->user_id != $СurrentUser->id){
                $infoChat->title = User::where('id',$infoChat->user_id)->first()->name;
            }
        }

        return view('chat.message',compact('messages','infoChat', 'СurrentUser', 'members'));
    }
*/
   /* public static function getPrevMessage(Request $request){
        $last_id = $request->first_message_id;
        $chat_id = $request->chat_id;

        $СurrentUser = Auth::user();

        //$messages = Message::where('chat_id',$chat_id)->where('id','<',$last_id)->orderBy('id', 'desc')->take(2)->get();
        $messages = Message::where('chat_id',$chat_id)->where('id','<',$last_id)->orderBy('id', 'desc')->take(22)->get()->reverse();
        $infoChat = Chat::where('id', $chat_id)->first();
        $update=true;
        return view('chat.message',compact('messages','infoChat', 'СurrentUser','update'));
    }*/

    public static function updateMessage(Request $request){
        $new_in_chats=[];
        $chats = Auth::user()->chats;
        $CurrentUser = Auth::user();
        foreach($chats as $chat){
            $msg = Message::where("chat_id", $chat->id)->orderby('id', 'desc')->first();
            if(isset($msg->id)){
                if($msg->id > $CurrentUser->status )
                    $new_in_chats[] = $msg->chat_id;
            }
        }
        $last_id = $request->last_message_id;
        $СurrentUser = Auth::user();
        if($СurrentUser->status < $last_id){
            $СurrentUser->status = $last_id;
            $СurrentUser->save();
        }
        $chat_id = $request->chat_id;

        $messages = Message::where('chat_id',$chat_id)->where('id','>',$last_id)->orderBy('id', 'asc')->take(40)->get();
        $infoChat = Chat::where('id', $chat_id)->first();
        $update=true;
        return view('chat.message',compact('messages','infoChat', 'СurrentUser', 'update', 'new_in_chats'));
    }

    public function addMessage(Request $request){
        date_default_timezone_set('Asia/Yekaterinburg');
        $data = $request->all();
        $privateId = $data['chat_id'];
        $chatPrivate = Chat::find($privateId);
        if($chatPrivate->type != "chats"){
            $ret = explode('_',$chatPrivate->type);
            $userTwo = ($ret[0] == Auth::user()->id) ? $ret[1] : $ret[0];
            $addUser = true;
            foreach ($chatPrivate->users as $user){
                if($userTwo == $user->id){
                    $addUser=false;
                }
            }
            if($addUser==true) {
                $chatPrivate->users()->attach($userTwo);
            }
        }
        $СurrentUser = Auth::user();
        $СurrentUser->active = time();
        $СurrentUser->save();
        if ($request->isMethod('post') && $request->file('file_upload')) {
            $file = $request->file('file_upload');
            $upload_folder = 'public/message_data/';
            $filename = date('dmyhi').$file->getClientOriginalName(); // image.jpg
            $path = Storage::putFileAs($upload_folder, $file, $filename);
            $data["file_path"] = $filename;
            unset($data["file_upload"]);
        }
        if($data['text']==null && $request->file('file_upload')){
            $data['text']="Файл";
        }
        if($data['text']==null){
            return json_encode('false');
        }
        $message = Message::create($data);
        if($message)
            return json_encode($data);
        else
            return json_encode('false');
    }

    public function destroy(Message $message, Request $request){

        $request->chat_id = $message->chat_id;
        $СurrentUser = Auth::user();
        if($message->user_id==$СurrentUser->id or $СurrentUser->group=='admin'){
            $message->delete();
            return self::getMessage($request);
            return redirect()->route('chat');
        }else{
            return redirect()->route('chat');
        }

    }

}
