@extends('layouts.app')
<?

use App\Models\User;
?>
@section('content')
    <div class="container">
        <div>
            <a href="{{ route('home') }}">Назад</a>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-8">
                <div class="card">
                    <div class="card-header">Профиль</div>
                    <div class="p-3 user-form">
                        @csrf
                        @if(isset($user->photo_path))
                            <div class="row">
                                <img src="/public{{ Storage::url("public/user_data/$user->photo_path") }}" alt="">
                            </div>
                        @endif

                        <div class="row"><div class="col-3">Имя:</div>
                            <div class="col-9">
                              {{$user->name}}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-3">Должность:</div>
                            <div class="col-9">
                                {{$user->position}}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-3">О себе:</div>
                            <div class="col-9">
                            <p>
                                {{$user->about}}
                            </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-4">
                <div class="card">
                    <div class="card-header">Пользователи</div>
                    <div class="p-3">
                        @foreach(User::all() as $user)
                            <a href="{{route('user.show', $user->id)}}">{{ $user->name }}</a><br>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
