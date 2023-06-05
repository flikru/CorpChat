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
            <div class="status"><i class="fa fa-circle online"></i>Online</div>
            @if($chat->id != 1)
                <form action="{{route('chat.close',$chat->id)}}" method="post">
                    @csrf
                    @method('delete')
                    <input type="submit" class="delete_chat" value="х">
                </form>
            @endif
        </div>
    </li>
@endforeach
