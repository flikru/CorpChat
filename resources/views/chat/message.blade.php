<?
$datemsg=null;
if(!isset($update))
    $update=false;
$СurrentUser = Auth::user();
?>

@if(count($messages)==0 && $update!=true)
    <div class="row">
        <div class="col-12 text-center">Сообщений нет, напишите первым!</div>
    </div>
@endif

@foreach($messages as $key=>$message)
    @php
        //echo $key;
        if($datemsg==null || $datemsg!=date("d.m.y",strtotime($message->created_at))){
            $datemsg = date("d.m.y",strtotime($message->created_at));
            $newdate=true;
        }else{
            $newdate=false;
        }
    @endphp
    @if($newdate && $update==false)
        <li class="clearfix data-list-msg">{{$datemsg}}</li>
    @endif
    <li class="msg-item clearfix" id='{{$message->id}}'>
        <div class="message-data {{ ($message->user_id==$СurrentUser->id) ? '' : 'text-right'}}">
        <div class="position-relative message {{ ($message->user_id==$СurrentUser->id) ? 'my-message' : 'other-message float-right'}}">
            <div class="from_message ">
                <span class="date-msg">{{date("h:i",strtotime($message->created_at))}}</span>,
                <span class="name-msg"><b>{{$message->user->name}}</b></span>
            </div>
            @if(isset($message->file_path))
                <div class="from_message ">
                    Прикрепленный файл: <a href="/public{{ Storage::url("public/message_data/$message->file_path") }}" download="">{{$message->file_path}}</a>
                    <?
                    if(isset($message->file_path)){
                        $path = "/public".Storage::url("public/message_data/".$message->file_path);
                        if(pathinfo($message->file_path, PATHINFO_EXTENSION)=='jpg' ||
                            pathinfo($message->file_path, PATHINFO_EXTENSION)=='png' ||
                            pathinfo($message->file_path, PATHINFO_EXTENSION)=='jpeg'
                        ){?>
                        <div><img src="/public{{ Storage::url("public/message_data/$message->file_path") }}" class="chat_image" alt=""></div>
                        <?}
                    }
                    ?>
                </div>
            @endif
            <div style="white-space: pre-line">{{$message->text}}</div>
            @if($message->user_id == $СurrentUser->id or $СurrentUser->group=='admin')
            <form action="{{route('message.destroy',$message->id)}}" method="post">
                @csrf
                @method('delete')
                <input type="hidden" name='message_id' value="{{$message->id}}">
                <input type="submit" class="delete_chat"  value="х" attr-message="Удалить сообщение?">
            </form>
            @endif
        </div>
        </div>
    </li>
@endforeach
@if($update==false)
    <script>
        $('.chat-about h6').html('<?=$chat->title?>');
        var html="";
        @foreach($members as $member)
            html+='<a class="dropdown-item" href="#">{{$member->name}}</a>'
        @endforeach
        $('.members-list').html(html);
    </script>
@endif

@if($update==true)
        <?php exit;?>
    <script>

        @foreach($new_in_chats as $chat)

            var haveChat = true;
            var MessageChat = {{ $chat }};
            $('.get_message_chat').each(function (){

                if(($(this).attr('chat-id') == MessageChat)){
                    haveChat = false;
                }
            });

            if(haveChat == true){
                update = true;
            }

        @endforeach

        if(update == true){
            update = false;
            clearInterval(mainIntervalID);
            mainIntervalID = reloadChat();
            $.ajax({
                'type': 'GET',
                'url': '/chat/getchats',
                'data':{'chat_id': ActivChatId},
                'dataType': 'html',
                'success': function( data ){
                    $('.listChats').html(data);
                }
            });
        }

        @foreach($new_in_chats as $chat)

            $('.get_message_chat[chat-id="{{ $chat }}"] .new_message').addClass('bg-success');

            if(titleAlertOn == false){
                titleAlertOn = true;
                titleAlert();
            }

        @endforeach
    </script>
@endif
