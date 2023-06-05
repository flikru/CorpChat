var СurrentUser = $('.currentuser_id').val();
var ActivChatId = $('#main_chat').attr('chat-id');
var scroll = false;

$(window).on('load', function (){
    $('.chat_id').val(ActivChatId);
    getMessage($('#main_chat'));
});


$(document).on("click", ".select_chat", function(e) {
    if($('.people-list').hasClass('visible-people-list')){
        $('.people-list').removeClass('visible-people-list')
    }else{
        $('.people-list').addClass('visible-people-list')
    }
})

//Добавление сообщения в чат
$('#add_message_form').on('submit', function() {
    //var chat_id = $('.chat_id').val();
    var form_data = new FormData();
    var data = $('#add_message_form').serialize();
    var text = $('input[name="text"]').val();
    var user_id = $('input[name="user_id"]').val();
    var chat_id = $('input[name="chat_id"]').val();
    var token = $('input[name="_token"]').val();

    if($('#file_upload').prop('files').length>0){
        var file_data = $('#file_upload').prop('files')[0];
        form_data.append('file_upload', file_data);
    }

    form_data.append('text', text);
    form_data.append('user_id', user_id);
    form_data.append('chat_id', chat_id);
    form_data.append('_token', token);
    form_data.append('_method', "post");

        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    })
    $.ajax({
        'type': 'POST',
        'url': '/message/addMessage',
        'data': form_data,
        'dataType': 'html',
        'cache': false,
        'contentType': false,
        'processData': false,
        'success': function (data) {
            console.log(data);
            getMessage(null, chat_id);
            $('.form-control').val('');
        }
    });
    return  false;

})

//Установка активного чата

//Получение сообщений
$(document).on("click", ".chat-list .get_message_chat", function(e) {
    if(window.location.pathname!="/"){
        document.location.href = '/?chat_id='+$(this).attr('chat-id');
    }
    $(this).find(".new_message").removeClass('bg-success')
    if($('.people-list').hasClass('visible-people-list')){
        $('.people-list').removeClass('visible-people-list')
    }else{
        $('.people-list').addClass('visible-people-list')
    }

    setActivChat($(this));
    getMessage($(this));
});


//Создание приватного чата
$('body').on('click', '.chat-list .user_chat_create', function (){
    var user_id=$(this).attr('user-id');
    var csrf=$('.get-token input').val();
    $.ajax({
        'type': 'post',
        'url': '/chat/storeprivate',
        'data': {
            'user_id': user_id,
            '_token': csrf,
        },
        'dataType': 'html',
        'success': function (chat_id) {
            ActivChatId = chat_id;
            getChats();
            getMessage(null, chat_id);
            console.log(ActivChatId);
            //setActivChat($(".get_message_chat[chat-id="+chat_id+"]"))
        }
    });
});


//пока не нужный попап
$(document).ready(function () {
    $("a.myLinkModal").click(function (event) {
        event.preventDefault();
        $("#myOverlay").fadeIn(297, function () {
            $("#popup_add_chat")
                .css("display", "block")
                .animate({ opacity: 1 }, 198);
        });
    });

    $("#popup_close_chat, #myOverlay").click(function () {
        $("#popup_add_chat").animate({ opacity: 0 }, 198, function () {
            $(this).css("display", "none");
            $("#myOverlay").fadeOut(297);
        });
    });
});
//пока не нужный попап



//удаление чата
$(document).on("click", ".delete_chat", function(e) {
    if($(this).attr('attr-message')){
        text= $(this).attr('attr-message');
    }else{
        text="Вы уверены что хотите выйти из чата?";
    }
    var Success = confirm(text);
    if(Success==false){
        return false;
    }
});


//удаление сообщения
$(document).on("submit", ".message-data form", function(e) {
    deleteMessage($(this));
    return false;
})


//подгрузка сообщений
$('#btn_load_message').on('click', function (){
    getPrevMessage();
})


//Отключение автопрокрутки
var oldScrollPosition = 0;
document.querySelector('.chat-history').addEventListener('wheel', function(evt) {

    div = $('.chat-history');
    if(div.scrollTop() == 0 && $('.chat-history li').length>39){
        $(".btn_load_message").show();
    }
    scroll = true;
    $('#end_div_scroll').show();
     if(evt.deltaY < 0 && window.scrollY === 0) {
        scroll=true;
    }else{
         var div = $('.chat-history');
         if(oldScrollPosition == div.scrollTop()){
             scroll = false;
         }else{
             oldScrollPosition=div.scrollTop();
         }
    }
});

//установка активного чата
function setActivChat(el){
    $('#main_chat').removeAttr('id');
    el.attr('id','main_chat');
    ActivChatId = el.attr('chat-id');
    $(".btn_load_message").hide();
}


//Получение всех чатов
function getChats(){
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


function getMessage(thisEl=null,chat_id_f){

    $('.chat-list .get_message_chat').removeClass('active');

    if(thisEl!=null){
        thisEl.addClass('active');
        if(thisEl.attr('chat-id')){
            var chat_id = $(thisEl).attr('chat-id')
        }
    }else{
        if(chat_id_f!=null){
            chat_id=chat_id_f;
        }
    }
   $('.chat-list .get_message_chat[chat-id='+ActivChatId+']').addClass('active');

    $('.chat_id').val(chat_id);

    $.ajax({
        'type': 'GET',
        'url': '/message/getMessage',
        'data': "chat_id="+chat_id,
        'dataType': 'html',
        'success': function( data )
        {
            $('.chat-history ul').html(data);
            lastMessageScroll();
        }
    });

}


function deleteMessage(form){
    var message_id = form.find('input[name=message_id]').val();
    var formData = form.serialize();
    $.ajax({
        'type': 'POST',
        'url': '/message/delete/'+message_id,
        'data': formData,
        'dataType': 'html',
        'success': function( data )
        {
            getMessage(null , ActivChatId);
        }
    });
}

$('#end_div_scroll .strelka-bottom-1').on("click", function (){
    $('#end_div_scroll').hide();
    scroll=false;
    lastMessageScroll();
});
//Скролл к последнему сообщению
function lastMessageScroll() {
    if(scroll==false){
        //console.log('Автопрокрутка работает')
       var div = $('.chat-history');
       div.scrollTop(div.prop('scrollHeight'));
    }
}

function updateMessage(thisEl=null, chat_id_f, lastMessage= 0){

    if(thisEl!=null){
        thisEl.addClass('active');
        if(thisEl.attr('chat-id')){
            var chat_id = $(thisEl).attr('chat-id')
        }
    }else{
        if(chat_id_f!=null){
            chat_id=chat_id_f;
        }
    }
    $.ajax({
        'type': 'GET',
        'url': '/message/updateMessage',
        'data': {
            "chat_id": chat_id,
            "last_message_id": lastMessage,
        },
        'dataType': 'html',
        'success': function( data )
        {
            $('.chat-history ul').append(data);
            lastMessageScroll();
        }
    });

}

function getPrevMessage(){
    console.log( $('.chat-history li').first().attr('id'))
    first_message_id=$('.chat-history li').first().attr('id');
    if(first_message_id>0){
        $.ajax({
            'type': 'GET',
            'url': '/message/getPrevMessage',
            'data': {
                "chat_id": ActivChatId,
                "first_message_id": $('.chat-history li').first().attr('id'),
            },
            'dataType': 'html',
            'success': function( data )
            {
                $('.chat-history ul').prepend(data);
            }
        });
    }
}

const intervalId = setInterval(function() {
    //getMessage(null, ActivChatId);
    lastMessage = $('.chat-history ul li:last').attr('id');
    updateMessage(null, ActivChatId, lastMessage);
    //console.log('Я выполняюсь каждую секунду')
}, 2000)
