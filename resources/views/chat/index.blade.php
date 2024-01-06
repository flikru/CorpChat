<?php
$CurrentUser = Auth::user();
?>
@extends('layouts.main')
@section('content')
    <div class="chat-header clearfix">
        <div class="row">
            <div class="col-6 col-lg-4">
                <div class="chat-about">
                    <h6 class="m-b-0">{{$chat->title}}</h6>
                </div>
            </div>
            <div class="col-6 col-lg-4 text-center">
                <div class="dropdown text-left">
                    <button class="btn btn-info" id="open-right-bar">
                        Меню чата
                    </button>
                </div>
                <div>
                </div>
            </div>
        </div>
    </div>
    <div class="right-bar" id="right-bar" style="display: none">
        <div class="cnt-right-bar">
            <div class="tabs">
                <div class="tab members-chat active" tab="members-tab">Участники</div>
                <div class="tab members-chat" tab="files-tab">Файлы</div>
            </div>
            <div class="tab-cnt active" id="members-tab">
                <ul>
                @foreach($members as $member)
                    <li class="" href="#">{{$member->name}}</li>
                @endforeach
                </ul>
            </div>
            <div class="tab-cnt" id="files-tab">
                <ul>
                @foreach($msgfiles as $file)
                    <? $url = 'http://'.request()->getHost()."/public".Storage::url("public/message_data/".$file->file_path) ?>
                        <li>{{date('d.m.y',strtotime($file->created_at))}}, <b>{{$file->text}}</b>, скачать - <a href="/public{{ Storage::url("public/message_data/$file->file_path") }}" download="">{{$file->file_path}}</a></li>

                    @endforeach
                </ul>
            </div>
        </div>
        <span class="close-rb">
            <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:2px;}</style></defs><title/><g id="cross"><line class="cls-1" x1="7" x2="25" y1="7" y2="25"/><line class="cls-1" x1="7" x2="25" y1="25" y2="7"/></g></svg>
        </span>
    </div>
    <div class="chat-history">
        <div class="btn_load_message" style="display: none">
            <button name = 'btn_load_message' id = 'btn_load_message' class="btn bg-info text-center text-light rounded pointer-curs w-100 mb-3">Загрузить еще</button>
        </div>
        <ul class="m-b-0">
            {!!$msgview!!}
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
            <input type="text" class="d-none" name="user_id" value="{{ $CurrentUser->id }}">
            <div class="">
                <div class="position-relative text-area-input">
                    <button class="btn" id="smile-open">🙂</button>
                    @include("over.smile")
                    <textarea class="form-control text-area-main" id="text-area-main" name="text" placeholder="Введите сообщение"></textarea>
                    <label for="file_upload" class="label_upload btn btn-info">
                      <svg data-name="Livello 1" id="Livello_1" viewBox="0 0 128 128" xmlns="http://www.w3.org/2000/svg"><title/><path d="M61.88,93.12h0a3,3,0,0,0,.44.36l.24.13a1.74,1.74,0,0,0,.59.24l.25.07h0a3,3,0,0,0,1.16,0l.26-.08.3-.09a3,3,0,0,0,.3-.16l.21-.12a3,3,0,0,0,.46-.38L93,66.21A3,3,0,1,0,88.79,62L67,83.76V3a3,3,0,0,0-6,0V83.76L39.21,62A3,3,0,0,0,35,66.21Z"/><path d="M125,88a3,3,0,0,0-3,3v22a9,9,0,0,1-9,9H15a9,9,0,0,1-9-9V91a3,3,0,0,0-6,0v22a15,15,0,0,0,15,15h98a15,15,0,0,0,15-15V91A3,3,0,0,0,125,88Z"/></svg>
                    </label>
                    <button class="btn btn-success btn-send" value="Отправить">
                        <svg width="40px" height="40px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.2199 21.63C13.0399 21.63 11.3699 20.8 10.0499 16.83L9.32988 14.67L7.16988 13.95C3.20988 12.63 2.37988 10.96 2.37988 9.78001C2.37988 8.61001 3.20988 6.93001 7.16988 5.60001L15.6599 2.77001C17.7799 2.06001 19.5499 2.27001 20.6399 3.35001C21.7299 4.43001 21.9399 6.21001 21.2299 8.33001L18.3999 16.82C17.0699 20.8 15.3999 21.63 14.2199 21.63ZM7.63988 7.03001C4.85988 7.96001 3.86988 9.06001 3.86988 9.78001C3.86988 10.5 4.85988 11.6 7.63988 12.52L10.1599 13.36C10.3799 13.43 10.5599 13.61 10.6299 13.83L11.4699 16.35C12.3899 19.13 13.4999 20.12 14.2199 20.12C14.9399 20.12 16.0399 19.13 16.9699 16.35L19.7999 7.86001C20.3099 6.32001 20.2199 5.06001 19.5699 4.41001C18.9199 3.76001 17.6599 3.68001 16.1299 4.19001L7.63988 7.03001Z" fill="#ffffff"/><path d="M10.11 14.4C9.92005 14.4 9.73005 14.33 9.58005 14.18C9.29005 13.89 9.29005 13.41 9.58005 13.12L13.16 9.53C13.45 9.24 13.93 9.24 14.22 9.53C14.51 9.82 14.51 10.3 14.22 10.59L10.64 14.18C10.5 14.33 10.3 14.4 10.11 14.4Z" fill="#ffffff"/></svg>
                    </button>
                </div>
            </div>
            <div class="d-none">
                <div class="col-12">
                    <input type="file" class="" name='file_upload' id="file_upload" placeholder="Загрузить">
                </div>
            </div>

        </form>
    </div>
    <script>
       /* $(document).ready(function (){
            getmsg({{$chat->id}});
        });*/
    </script>
@endsection
