@foreach($listChats as $chat)
    <li class="clearfix get_message_chat" chat-id="{{ $chat->id }}" @if($chat == end($listChats)) id="main_chat" @endif >
        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="avatar">
        <div class="about">
            <div class="name"><a class='chat_link' href="/?chat_id={{ $chat->id }}"></a>{{ $chat->title }}</div>
            <div class="status"> <i class="fa fa-circle online"></i>Online</div>
            @if($CurrentUser->id == $chat->user_id && $chat->id!=1)
                <form action="{{route('chat.delete',$chat->id)}}" method="post">
                    @csrf
                    @method('delete')
                    <input type="submit" class="delete_chat" value="х">
                </form>
            @endif
        </div>
    </li>
@endforeach
