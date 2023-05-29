@if(count($messages)==0)
    <div class="row">
        <div class="col-12 text-center">Сообщений нет, напишите первым!</div></div>
@endif
@foreach($messages as $message)
    <li class="clearfix" id='{{$message->id}}'>
        <div class="message-data {{ ($message->user_id==$СurrentUser->id) ? '' : 'text-right'}}">
        <div class="message {{ ($message->user_id==$СurrentUser->id) ? 'my-message' : 'other-message float-right'}}">
            <div class="from_message ">
                <span>{{$message->created_at}}</span>, <span>от <b>{{$message->user->name}}</b></span>
            </div>
            {{$message->text}}
        </div>
        </div>
    </li>
@endforeach

<script>
        $('.chat-about h6').html('<?=$infoChat->title?>');
</script>
