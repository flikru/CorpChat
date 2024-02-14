var СurrentUser = $('.currentuser_id').val();
var scroll = false;
var titleAlertOn = false;
var chat_id;

let url = window.location.pathname.split('/');
if(url[2] && url[1]=='chats'){
    chat_id = url[2];
}else{
    chat_id = 0;
}
$(window).on('load', function (){
    $(document).on("click", "#users-list", function(e) {
        $('.users-list').slideToggle(300);
        $(this).toggleClass("open");
    });

    $(document).on("click", ".open_list_member", function(e) {
        $(this).closest('.cnt-list-member').find('.list_member').slideToggle(300);
    });

});
$(window).ready(function (){
    getChats();
})
//открытие меню для мобилки
$(document).on("click", ".select_chat", function(e) {
    if($('.people-list').hasClass('visible-people-list')){
        $('.people-list').removeClass('visible-people-list')
    }else{
        $('.people-list').addClass('visible-people-list')
    }
})

//Добавление сообщения в чат
$(document).keydown(function(e) {
    if(e.keyCode === 13) {
        $("#add_message_form").submit();
    }
});
$('#add_message_form').on('submit', function() {
    let id;

    var form_data = new FormData();
    var data = $('#add_message_form').serialize();
    var text = $('textarea[name="text"]').val();
    var user_id = $('input[name="user_id"]').val();
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
            getmsg(chat_id);
            $('.form-control').val('');
            $('#file_upload').val('');
        }
    });
    return  false;
});

$(document).on("click", "html", function(e) {
    $(".listChats .active").find(".new_message").removeClass('bg-success');
    if(titleAlertOn == true){
        titleAlertOn = false;
        clearInterval(timerIdAlert);
        document.title = "Чат";
    }
})
//Получение сообщений


//Создание приватного чата
$('body').on('click', '.chat-list .user_chat_create', function (){
    var user_id=$(this).attr('user-id');
    var csrf=$('.get-token input').val();
    $.ajax({
        'type': 'post',
        'url': '/editor/storeprivate',
        'data': {
            'user_id': user_id,
            '_token': csrf,
        },
        'dataType': 'html',
        'success': function (chat_id) {
            getChats();
            getMessage(null, chat_id);
        }
    });
});



//закрыть чат
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

//удаление чата
$(document).on("click", ".destroy_chat", function(e) {
    text="Вы уверены что хотите удалить чат навсегда? Все сообщения исчезнут!";
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

$('.search-input').keyup( function (){
    $('.chat-list li').addClass('d-none');
    var search = $(this).val().toLowerCase();
    $('.chat-list li').each(function (index){
        var str = $(this).find('.name').html().toLowerCase();
        if(str.search(search) !== -1){
            $(this).removeClass('d-none');
        }

    });
})
//Отключение автопрокрутки
var oldScrollPosition = 0;
document.querySelector('.chat-history').addEventListener('wheel', function(evt) {

    div = $('.chat-history');
    if(div.scrollTop() == 0 && $('.chat-history li').length>9){
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


var timerIdAlert;
function titleAlert(){
    timerIdAlert = setInterval(()=>{
         titleFlag = document.title;
         if(titleFlag == "Чат"){
             titleFlag = "!НОВОЕ СООБЩЕНИЕ!"
         }else{
             titleFlag = "Чат"
         }
         document.title = titleFlag;
     },2000);
}

//Получение всех чатов
function getChats(){
    $.ajax({
        'type': 'GET',
        'url': '/chatsget',
        'dataType': 'html',
        'success': function( data ){
            $('.listChats').html(data);
            let id;
            let url = window.location.pathname.split('/');
            if(url[2] && url[1]=='chats'){
                id=url[2];
                $('.get_message_chat[chat-id='+id+']').addClass('active');
            }
        }
    });
}

function checkchats(){
    $.ajax({
        'type': 'GET',
        'url': '/chatscheck',
        'dataType': 'json',
        'success': function( data ){
            if(data){
                data.forEach((el) => {
                    if(!$('[chat-id='+el+'] .new_message' ).hasClass("newmessage")){
                        $('[chat-id='+el+'] .new_message').addClass("newmessage");
                    }
                    console.log(el);
                });
            }
        }
    });
}

function getmsg(chat){
    $.ajax({
        'type': 'get',
        'url': '/chat/'+chat,
        'dataType': 'html',
        'success': function( data )
        {
           // getChats();
            $('.chat-history ul').html(data);
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
            getMessage(null );
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

function updatemsg(lastMessage= 0){
    $.ajax({
        'type': 'GET',
        'url': '/chat/getnewmsg/'+chat_id+'/'+lastMessage,
        'data': {
        },
        'dataType': 'html',
        'success': function( data ){
            $('.chat-history ul').append(data);
            lastMessageScroll();
        }
    });
}
function getPrevMessage(){
    first_message_id=$('.chat-history .msg-item').first().attr('id');
    if(first_message_id>0){
        $.ajax({
            'type': 'GET',
            //'url': '/message/getPrevMessage',
            'url': '/chat/'+chat_id+'/'+first_message_id,
            'data': {
               // "chat_id": chat_id,
                "first_message_id": first_message_id,
            },
            'dataType': 'html',
            'success': function( data )
            {
                $('.chat-history ul').prepend(data);
            }
        });
    }
}

var update = false;
var mainIntervalID = reloadChat();
function reloadChat(){
    update = false;
    const intervalId = setInterval(function() {
        lastMessage = $('.chat-history ul li:last').attr('id');
        updatemsg( lastMessage );
    }, 2000);
    return intervalId;
}

    let intervalId2 = setInterval(function() {
        checkchats();
    }, 5000);
