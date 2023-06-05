@extends('layouts.main')
@section('content')
                    <div class="chat-header clearfix">
                        <div class="row">
                            <div class="col-6 col-lg-4">
                                <div class="chat-about">
                                    <h6 class="m-b-0">Общий чат</h6>
                                </div>
                            </div>
                            <div class="col-6 col-lg-4 text-center">
                                <div class="dropdown text-left">
                                    <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Участники
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
                        <div class="btn_load_message" style="display: none">
                            <button name = 'btn_load_message' id = 'btn_load_message' class="btn bg-info text-center text-light rounded pointer-curs w-100 mb-3">Загрузить еще</button>
                        </div>
                        <ul class="m-b-0">
                        </ul>
                        <div id="end_div_scroll" style="display: none">
                            <svg class="strelka-bottom-1" viewbox="0 0 60 100"><path d="M 50,0 L 60,10 L 20,50 L 60,90 L 50,100 L 0,50 Z"></path></svg>
                        </div>

                    </div>
                    <div class="chat-message clearfix">
                        <form action="" method="post" id="add_message_form" enctype="multipart/form-data">
                            @csrf
                            @method('post')
                            <input type="hidden" class="d-none chat_id" name="chat_id" value="1">
                            <input type="text" class="d-none" name="user_id" value="{{ $СurrentUser->id }}">
{{--                            <input type="file" class="fa fa-image btn btn-outline-primary" value="" placeholder="Загрузить">--}}
                            <div class="row">
                                <div class="col-sm-8 col-md-12 col-lg-9 position-relative">
                                    <input type="text" class="form-control" name="text" placeholder="Введите сообщение">
                                    <label for="file_upload" class="label_upload btn btn-info">Загрузить</label>
                                </div>
                                <div class="col-sm-4 col-md-12 col-lg-3">
                                    <input type="submit" class="btn btn-success btn-send" value="Отправить">
                                </div>
                            </div>
                            <div class="d-none">
                                <div class="col-12">
                                    <input type="file" class="" name='file_upload' id="file_upload" placeholder="Загрузить">
                                </div>
                            </div>

                        </form>
                    </div>
@endsection
