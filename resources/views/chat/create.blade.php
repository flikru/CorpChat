@extends('layouts.main')
@section('content')
    <div class="chat-header clearfix">
        <div class="row">
            <div class="col-lg-6">
                <div class="chat-about">
                    <h6 class="m-b-0">Создание чата</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="chat-history container-fluid">
        <form action="{{route('chat.store')}}" method="post">
            @csrf
            <div class="row mb-4">
                <div class="col-12">Название чата</div><br>
                <div class="col-12">
                    <input type="text" id="name_chat" name="title" placeholder="Введите название чата"></div>
            </div>
            <div class="row mb-4">
                <div class="col-12">Выбирете участников чата</div>
                <div class="col-12">
                    @foreach($users as $user)
                        <?
                        if($user->id == Auth::user()->id){
                            continue;
                        };
                        ?>
                    <div class="form-check">
                        <input class="form-check-input" name="members[]" type="checkbox" value="{{$user->id}}" id="flexCheckDefault{{$user->id}}">
                        <label class="form-check-label" for="flexCheckDefault{{$user->id}}">
                            {{$user->name}}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12">
                    <input type="submit" value="Создать">
                </div>
            </div>
        </form>
    </div>
@endsection
