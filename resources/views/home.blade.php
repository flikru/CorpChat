@extends('layouts.app')
<?
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;
    use App\Models\User;
$СurrentUser = Auth::user();
?>
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-8">
            <div class="card">
                <div class="card-header">Профиль</div>
                <form action="{{ route('user.update', $СurrentUser->id) }}"  enctype="multipart/form-data" method="post" class="p-3 user-form">
                    @csrf
                    @if(isset($СurrentUser->photo_path))
                        <div class="row">
                            <img src="/public{{ Storage::url("public/user_data/$СurrentUser->photo_path") }}" alt="">
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-3">Загрузить фото:</div>
                        <div class="col-9">
                            <input type="file" name ="file_upload" value="">
                        </div>
                    </div>

                    <div class="row"><div class="col-3">Имя:</div>
                        <div class="col-9">
                            <input type="text" name = 'name' placeholder="Введите имя" value="{{$СurrentUser->name}}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">Должность:</div>
                        <div class="col-9">
                            <input type="text" name = 'position' placeholder="Введите имя" value="{{$СurrentUser->position}}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">О себе:</div>
                        <div class="col-9">
                            <textarea name = 'about' placeholder="Информация о себе" col=20 row='30'>{{$СurrentUser->about}}</textarea>
                        </div>
                    </div>
                    <button class="btn btn-info">Сохранить</button>
                </form>
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
