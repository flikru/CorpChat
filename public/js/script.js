$(window).load(function (){
    var СurrentUser = $('.currentuser_id').val();
    var ActivChatId = $('#main_chat').attr('chat-id');
    $('.chat_id').val(ActivChatId);

    getMessage($('#main_chat'));

});

$('#add_message_form').on('submit', function() {
    var chat_id = $('.chat_id').val();
    var data = $('#add_message_form').serialize();
    $.ajax({
        'type': 'POST',
        'url': '/',
        'data': data,
        'dataType': 'json',
        'success': function (data) {
            getMessage(null, chat_id);
            $('.form-control').val('');
        }
    });
    return false;
})


//Получение сообщений
    $('.chat-list .get_message_chat').on('click',function (){
        if(window.location.pathname!="/"){
            document.location.href = '/?chat_id='+$(this).attr('chat-id');
        }
        getMessage($(this));
    });

$(document).on("click", ".chat-list .get_message_chat", function(e) {
    if(window.location.pathname!="/"){
        document.location.href = '/?chat_id='+$(this).attr('chat-id');
    }
    getMessage($(this));
});

//Создание приватного чата
    $('.chat-list .user_chat_create').on('click',function (){
        var user_id=$(this).attr('user-id');
        var csrf=$('.get-token input').val();
        $.ajax({
            'type': 'post',
            'url': '/storeprivatechat',
            'data': {
                'user_id': user_id,
                '_token': csrf,
            },
            'dataType': 'html',
            'success': function (data) {
                console.log(data);
                getChats();
            }
        });
    });

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
            'url': '/viewMessage',
            'data': "chat_id="+chat_id,
            'dataType': 'html',
            'success': function( data )
            {
                $('.chat-history ul').html(data);
                lastMessageScroll();
            }
        });
    }

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

function lastMessageScroll() {

    if($('#end_div_scroll').length>0){

        setTimeout(function (){
            $('.chat-history').animate({
                scrollTop: $("#end_div_scroll").offset().top // класс объекта к которому приезжаем
            }, 300); // Скорость прокрутки
        }, 300);

    }

}
$('.delete_chat')

$(document).on("click", ".delete_chat", function(e) {
    var isAdmin = confirm("При удалении стираются все сообщения у обоих собеседников. Вы уверены?");
    if(isAdmin==false){
        return false;
    }
});

function getChats(){
    $.ajax({
        'type': 'GET',
        'url': '/getChats',
        'dataType': 'html',
        'success': function( data )
        {
            console.log(data);
            $('.listChats').html(data);
        }
    });
}
