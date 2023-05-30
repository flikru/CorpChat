<?if(!isset($update))
    $update=false;
?>
@if(count($messages)==0 && $update!=true)
    <div class="row">
        <div class="col-12 text-center">Сообщений нет, напишите первым!</div>
    </div>
@endif
@foreach($messages as $message)
    <li class="clearfix" id='{{$message->id}}'>
        <div class="message-data {{ ($message->user_id==$СurrentUser->id) ? '' : 'text-right'}}">
        <div class="position-relative message {{ ($message->user_id==$СurrentUser->id) ? 'my-message' : 'other-message float-right'}}">
            <div class="from_message ">
                <span>{{$message->created_at}}</span>, <span>от <b>{{$message->user->name}}</b></span>
            </div>
            {{$message->text}}
            @if($message->user_id==$СurrentUser->id)
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
        $('.chat-about h6').html('<?=$infoChat->title?>');
    </script>
@endif
