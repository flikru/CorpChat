@extends('layouts.main')
@section('content')
    <div class="chat-header clearfix">
        <div class="row">
            <div class="col-lg-6">
                <div class="chat-about">
                    <h6 class="m-b-0">Редактирование</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="chat-history container-fluid">
        <div class="row mb-4 border-bottom border-dark pb-2">
            <div class="col-1">ID</div>
            <div class="col-4">
                Название
            </div>
            <div class="col-4">
                Участники
            </div>
            <div class="col-1">
            </div>
        </div>
        @foreach($chats as $chat)
            <form action="{{route('chat.update', $chat->id)}}" method="post">
                @csrf
                @method('patch')
                <div class="row mb-4 border-bottom border-dark pb-2">
                    <div class="col-1">{{$chat->id}}</div>
                    <div class="col-3">
                        <input type="text" id="name_chat" name="title" placeholder="Введите название чата" value="{{$chat->title}}">
                    </div>
                    <div class="col-7 cnt-list-member">
                        <a class="open_list_member">Открыть/закрыть список</a>
                        <div class="list_member" style="display: none">
                         @foreach($users as $user)
                             <?
                             if($user->id == Auth::user()->id){
                                 continue;
                             };
                             ?>
                             <div class="d-inline-block margin-inline-checkbox ml-4">
                                 <input class="form-check-input" name="members[]" type="checkbox" value="{{$user->id}}"
                                        @foreach($chat->users as $member)
                                            @if($member->id == $user->id)
                                                checked
                                            @endif
                                        @endforeach
                                        id="flexCheckDefault{{$user->id}}_{{$chat->id}}">
                                 <label class="form-check-label" for="flexCheckDefault{{$user->id}}_{{$chat->id}}">
                                     {{$user->name}}
                                 </label>
                             </div>
                         @endforeach
                        </div>
                    </div>
                    <div class="col-1">
                        <button class="btn btn-info w-100">SAVE</button>
                    </div>
                </div>
            </form>

        @endforeach

    </div>
@endsection
