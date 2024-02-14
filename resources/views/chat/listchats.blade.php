<?
use Illuminate\Support\Facades\Auth;

//$CurrentUser->id == $chat->user_id &&

$CurrentUser = Auth::user();
$hiddenChats = explode(';',$CurrentUser->hiddenchats);
?>
@foreach($listChats as $key => $chat)
        <?
        $display = "";
        if(in_array($chat->id, $hiddenChats)){
            $display = " d-none";
        }
        ?>
    <li class="clearfix get_message_chat {{$display}}" chat-id="{{ $chat->id }}" >
        <a href="{{route('chat.show',$chat->id)}}">
            <img src="/public/images/all.png" alt="avatar">
            <div class="about">
                <div class="name"><span class='chat_link' href="{{route('chat.show',$chat->id)}}">{{ $chat->title }}</span></div>
                @if($chat->id != 1)
                    <form action="{{route('chat.close',$chat->id)}}" method="post">
                        @csrf
                        @method('delete')
                        <input type="submit" class="delete_chat" value="х">
                    </form>
                    @if($chat->type=="chats" && Auth::user()->group=="admin")

                        <form action="{{route('chat.destroy',$chat->id)}}" method="post">
                            @csrf
                            @method('delete')
                            <input type="submit" class="destroy_chat btn btn-danger" value="DEL">
                        </form>

                    @endif
                @endif
            </div>
            <div class="new_message"></div>
        </a>
    </li>
@endforeach
