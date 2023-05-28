$(window).load(function (){
    console.log('local');
    console.log('local');
    console.log('local');
    console.log('local');
    console.log('local');
    var СurrentUser = $('.currentuser_id').val();
    var ActivChatId = 1;
    $('.chat_id').val(ActivChatId);

    getMessage($('#main_chat'));

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
    $('.chat-list .clearfix').on('click',function (){
        getMessage($(this));
    });


    function getMessage(thisEl=null,chat_id_f){
        $('.chat-list .clearfix').removeClass('active');
        if(thisEl!=null){
            thisEl.addClass('active');
            if(thisEl.attr('chat-id')){
                var chat_id = $(thisEl).attr('chat-id')
            }else{
                var chat_id = $(thisEl).attr('user-id')
            }
        }else{
            if(chat_id_f!=null){
                chat_id=chat_id_f;
            }
        }
        console.log(chat_id)
        $('.chat_id').val(chat_id);
        $.ajax({
            'type': 'GET',
            'url': '/viewMessage',
            'data': "chat_id="+chat_id,
            'dataType': 'html',
            'success': function( data )
            {   console.log(chat_id);
                $('.chat-history ul').html(data);
                lastMessageScroll();
            }
        });
    }




    // function getMessage1(thisEl=null,chat_id_f){
    //     $('.chat-list .clearfix').removeClass('active');
    //     if(thisEl!=null){
    //         thisEl.addClass('active');
    //         if(thisEl.attr('chat-id')){
    //             var chat_id = $(thisEl).attr('chat-id')
    //         }else{
    //             var chat_id = $(thisEl).attr('user-id')
    //         }
    //     }else{
    //         if(chat_id_f!=null){
    //             chat_id=chat_id_f;
    //         }
    //     }
    //     console.log(chat_id)
    //     $('.chat_id').val(chat_id);
    //     $.ajax({
    //         'type': 'GET',
    //         'url': '/viewMessage',
    //         'data': "chat_id="+chat_id,
    //         'dataType': 'json',
    //         'success': function( data )
    //         {
    //             console.log(data);
    //             $('.chat-about h6').html(data[1].chat_title);
    //             $('.chat-history ul').html("");
    //             data.forEach(function (item,index){
    //                 createDate = item.created_at;
    //                 var from = "<div class='from_message'>от <b>"+item.user_name+"</b></div>";
    //                 if(item.user_id == СurrentUser){
    //                     $('.chat-history ul').append(
    //                         '<li class="clearfix" id = '+item.id+'>' +
    //                         '<div class="message-data text-right">' +
    //                         '<span class="message-data-time">'+createDate+'</span>' +
    //                         '</div>' +
    //                         '<div class="message other-message float-right">'+ from +item.text+'</div></li>'
    //                     )
    //                 }else{
    //                     $('.chat-history ul').append(
    //                         '<li class="clearfix" id = '+item.id+'>' +
    //                         '<div class="message-data">'  +
    //                         '<span class="message-data-time">'+ createDate+'</span>' +
    //                         '</div>' +
    //                         '<div class="message my-message">'+ from +item.text+'</div></li>'
    //                     )
    //                 }
    //             });
    //             lastMessageScroll();
    //         }
    //     });
    // }

})

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
    setTimeout(function (){
        $('.chat-history').animate({

            scrollTop: $("#end_div_scroll").offset().top // класс объекта к которому приезжаем
        }, 300); // Скорость прокрутки
    }, 300);

}
