@extends('layouts.main')
@section('content')
                    <div class="chat-header clearfix">
                        <div class="row">
                            <div class="col-lg-6">
                                <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">
                                </a>
                                <div class="chat-about">
                                    <h6 class="m-b-0">Общий чат</h6>
                                    <small>Online</small>
                                </div>
                            </div>
                            <div class="col-lg-6 hidden-sm text-right">
                                <a href="javascript:void(0);" class="btn btn-outline-secondary"><i class="fa fa-camera"></i></a>
                                <a href="javascript:void(0);" class="btn btn-outline-primary"><i class="fa fa-image"></i></a>
                                <a href="javascript:void(0);" class="btn btn-outline-info"><i class="fa fa-cogs"></i></a>
                                <a href="javascript:void(0);" class="btn btn-outline-warning"><i class="fa fa-question"></i></a>
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
                        <div class="input-group mb-0">
                            <input type="text" class="form-control" name="text" placeholder="Введите сообщение">
                            <input type="hidden" class="d-none chat_id" name="chat_id" value="2">
                            <input type="text" class="d-none" name="user_id" value="{{ $СurrentUser->id }}">
                            <input type="submit" class="fa fa-send btn-send" value="Отправить">
                        </div>
                        </form>
                    </div>
@endsection
