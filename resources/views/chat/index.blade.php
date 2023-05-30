@extends('layouts.main')
@section('content')
                    <div class="chat-header clearfix">
                        <div class="row">
                            <div class="col-sm-6 col-sm-6 col-lg-4">
                                <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">
                                </a>
                                <div class="chat-about">
                                    <h6 class="m-b-0">Общий чат</h6>
                                    <small>Online</small>
                                </div>
                            </div>
                            <div class="col-sm-6 col-sm-6 col-lg-4 text-center">
                                <div class="dropdown">
                                    <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Участники чата
                                    </button>
                                    <div class="dropdown-menu members-list" aria-labelledby="dropdownMenuButton">
                                        @foreach($members as $member)
                                            <a class="dropdown-item" href="#">{{$member->name}}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="chat-history">
                        <ul class="m-b-0">
                        </ul>
                        <div id="end_div_scroll"></div>
                    </div>
                    <div class="chat-message clearfix">
                        <form action="" method="post" id="add_message_form">
                            @csrf
                            @method('post')
                        <div class="input-group mb-0 bg-light row">

                            <input type="text" class="form-control" name="text" placeholder="Введите сообщение">
                            <input type="hidden" class="d-none chat_id" name="chat_id" value="1">
                            <input type="text" class="d-none" name="user_id" value="{{ $СurrentUser->id }}">
                            <input type="file" class="fa fa-image btn btn-outline-primary" value="" placeholder="Загрузить">
                            <input type="submit" class="fa fa-send btn-send" value="Отправить">

                        </div>
                        </form>
                    </div>
@endsection
