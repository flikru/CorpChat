<?
if(isset($_GET['chat_id'])){
    $active_chat_id = $_GET['chat_id'];
}else{
    $active_chat_id = 1;
}

//$CurrentUser->id == $chat->user_id &&
?>
@foreach($listChats as $key => $chat)
    <li class="clearfix get_message_chat  @if($active_chat_id == $chat->id) active @endif" chat-id="{{ $chat->id }}" @if($active_chat_id == $chat->id) id="main_chat" @endif >
        <img src="/public/images/all.png" alt="avatar">
        <div class="about">
            <div class="name"><a class='chat_link' href="/?chat_id={{ $chat->id }}"></a>{{ $chat->title }}</div>
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
    </li>
@endforeach
