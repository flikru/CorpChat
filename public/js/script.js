var СurrentUser = $('.currentuser_id').val();
var ActivChatId = $('#main_chat').attr('chat-id');

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
$('#add_message_form').on('submit', function() {
    var chat_id = $('.chat_id').val();
    var data = $('#add_message_form').serialize();
    $.ajax({
        'type': 'POST',
        'url': '/message/addMessage',
        'data': data,
        'dataType': 'json',
        'success': function (data) {
            getMessage(null, chat_id);
            $('.form-control').val('');
        }
    });
    return false;
})

//Установка активного чата

//Получение сообщений
$(document).on("click", ".chat-list .get_message_chat", function(e) {
    if(window.location.pathname!="/"){
        document.location.href = '/?chat_id='+$(this).attr('chat-id');
    }
    setActivChat($(this));
    getMessage($(this));
});

//Создание приватного чата
$('.chat-list .user_chat_create').on('click',function (){
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
        'success': function (data) {
            getChats();
        }
    });
});



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

$(document).on("submit", ".message-data form", function(e) {
    deleteMessage($(this));
    return false;
})

//установка активного чата
function setActivChat(el){
    $('#main_chat').removeAttr('id');
    el.attr('id','main_chat');
    ActivChatId = el.attr('chat-id');
}


//Получение всех чатов
function getChats(){
    $.ajax({
        'type': 'GET',
        'url': '/chat/getchats',
        'dataType': 'html',
        'success': function( data )
        {
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

function lastMessageScroll() {

    var div = $('.chat-history');
    div.scrollTop(div.prop('scrollHeight'));
    // if($('#end_div_scroll').length>0){
    //
    //     setTimeout(function (){
    //         $('.chat-history').animate({
    //             scrollTop: $("#end_div_scroll").offset().top // класс объекта к которому приезжаем
    //         }, 300); // Скорость прокрутки
    //     }, 300);
    //
    // }

}

function updateMessage(thisEl=null, chat_id_f, lastMessage=0){

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

    $('.chat_id').val(chat_id);

    $.ajax({
        'type': 'GET',
        'url': '/message/updateMessage',
        'data': {
            "chat_id": chat_id,
            "last_message_id": lastMessage
        },
        'dataType': 'html',
        'success': function( data )
        {
            $('.chat-history ul').append(data);
            lastMessageScroll();
        }
    });

}


const intervalId = setInterval(function() {
    //getMessage(null, ActivChatId);
    lastMessage = $('.chat-history ul li:last').attr('id');
    updateMessage(null, ActivChatId, lastMessage);
    //console.log('Я выполняюсь каждую секунду')
}, 1000)
