<?
use Illuminate\Support\Facades\Auth;
use App\Models\User;
if(isset($_GET['chat_id'])){
    $active_chat_id = $_GET['chat_id'];
}else{
    $active_chat_id = 1;
}
$CurrentUser = Auth::user();
foreach ($CurrentUser->chats as $item){
    $listChats[] = $item;
}
$users = User::all();
?>
    <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('public/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/css/style.css')}}">
    <title>Чат</title>

</head>
<body>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card chat-app">
                <div id="plist" class="people-list">
                    <div class="input-group">
                        <h3>{{ $СurrentUser->name }}</h3>
                    </div>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Поиск">
                    </div>
                    <ul class="list-unstyled chat-list mt-2 mb-0">
                        <div class="input-group">
                            <b>Чаты</b>
                        </div>


                        <div class="input-group"><div class="name">
                            <a class="name" href="/chatcreate">Создать чат</a>
                            </div>
                            <li class="clearfix w-100">
                                <div class="about">

                                </div>
                            </li>
                        </div>
                        <div class="listChats">
                        @if(isset($listChats))
                            @foreach($listChats as $chat)
                                <li class="clearfix get_message_chat" chat-id="{{ $chat->id }}" @if($chat->id==$active_chat_id){{ "id=main_chat" }}@endif>
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
                        @endif
                        </div>
                        <div class="input-group">
                            <b>Пользователи</b>
                        </div>
                        @foreach($users as $user)
                            <?
                                if($user->id == $CurrentUser->id){
                                    continue;
                                };
                            ?>
                            <li class="clearfix user_chat_create" user-id="{{ $user->id }}">
                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="avatar">
                                <div class="about">
                                    <div class="name">{{ $user->name }}</div>
                                    <div class="status"> <i class="fa fa-circle online"></i> Онлайн  </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <form class="d-none get-token" action="" method="post">
                        @csrf
                    </form>
                </div>
                <div class="chat">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>
@include('include.AddChatPopup')
<input type="text" class="d-none currentuser_id" id="#" value="{{ $СurrentUser->id }}">
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('public/js/script.js')}}"></script>
</body>
</html>
