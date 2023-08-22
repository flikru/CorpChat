<?
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\ChatController;

if(isset($_GET['chat_id'])){
    $active_chat_id = $_GET['chat_id'];
}else{
    $active_chat_id = 1;
}

$CurrentUser = Auth::user();

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
    <link rel="stylesheet" href="{{asset('public/css/svg.css')}}">
    <link type="image/x-icon" href="/favicon.png" rel="shortcut icon">
    <link type="Image/x-icon" href="/favicon.png" rel="icon">
    <title>Чат</title>

</head>
<body>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card chat-app">
                <button class="btn btn-info w-100 select_chat">Меню</button>
                <div id="plist" class="people-list">
                    <div class="input-group">
                        <a href="{{route('home')}}">
                            <h3>{{ $CurrentUser->name }}
                            <i class="fa fa-cogs"></i></h3>
                        </a>
                    </div>
                    <div class="input-group w-100">
                        @if($CurrentUser->group == "admin")
                            <div class="name w-100 mb-2">
                                <a href="{{route('chat.create')}}" class="btn btn-info w-100" type="button" >
                                    Создать чат
                                </a>
                            </div>
                            <div class="name w-100 mb-2">
                                <a href="{{route('chats.show')}}" class="btn btn-info w-100" type="button" >
                                    Редактор чатов
                                </a>
                            </div>
                            <div class="name w-100">
                                <a href="{{route('register')}}" class="btn btn-info w-100" type="button" >
                                    Добавить участника
                                </a>
                            </div>
                        @endif
                    </div>
                    <ul class="list-unstyled chat-list mt-2 mb-0">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                            </div>
                            <input type="text" class="form-control search-input" placeholder="Поиск">
                        </div>
                        <div class="input-group">
                            <b>Чаты</b>
                        </div>
                        <div class="listChats">
                            <?=ChatController::getChats(); ?>
                        </div>
                        <div class="input-group" id="users-list">
                            <b>Пользователи</b>
                            <div class="arrow-4">
                                <span class="arrow-4-left"></span>
                                <span class="arrow-4-right"></span>
                            </div>
                        </div>
                        <ul class="users-list" style="display: none">
                        @foreach($users as $user)
                            <?
                            if($user->id == $CurrentUser->id){
                                continue;
                            };
                            ?>
                            <li class="clearfix user_chat_create" user-id="{{ $user->id }}">
                                <div class="position-relative img-users">
                                    <img src="{{ isset($user->photo_path) ? "/public".Storage::url("public/user_data/$user->photo_path") : "https://bootdey.com/img/Content/avatar/avatar1.png" }}" alt="avatar">
                                    @if(15 > (time() - $user->active)/60)
                                        <div class="status"><i class="fa fa-circle online"></i></div>
                                    @else
                                        <div class="status"><i class="fa fa-circle offline"></i></div>
                                    @endif
                                </div>
                               <div class="about">
                                    <div class="name">{{ $user->name }}</div>
                                    @if(isset($user->position))
                                        <div class="position">{{ $user->position }}</div>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                        </ul>
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
<input type="text" class="d-none currentuser_id" id="#" value="{{ $CurrentUser->id }}">
{{--<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>--}}
<script src="{{asset('public/js/jquery-3.7.0.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
{{--<script src="{{asset('public/js/bootstrap.bundle.min.js')}}">--}}
<script src="{{asset('public/js/popper.min.js')}}"></script>
<script src="{{asset('public/js/script.js')}}"></script>
</body>
</html>
