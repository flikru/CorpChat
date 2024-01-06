$(window).on('load', function (){

});
$('.tabs .tab').click(function (){
    let tab = $(this).attr('tab');
    $('.tabs .tab').removeClass('active');
    $(this).addClass('active');
    $('.tab-cnt').removeClass('active');
    $('#'+tab).addClass('active')
});
$('#open-right-bar, .close-rb').click(function (){
    $("#right-bar").slideToggle(300);
});

//add smile to textarea
$(".smile").click(function (){
    let smile = $(this).html();
    insertText('text-area-main', smile);
})
$("#smile-open").click(function (e){
    e.preventDefault();
    $('#cnt-smile').toggle();
    return false;
})
$("body").on('click','.answer-btn', function (){
    let html = $(this).closest('.msg-item');
    let name = "<div>"+html.find('.name-msg').html()+"</div>";
    let text = "<div>"+html.find('.text-cnt').html()+"</div>";
    let answer = "<div class='answer-cnt'>"+name+text+"</div>\n";
    insertText('text-area-main', answer);

})

//Вставка текста по курсору
function insertText( id, text ) {
    //ищем элемент по id
    var txtarea = document.getElementById(id);
    //ищем первое положение выделенного символа
    var start = txtarea.selectionStart;
    //ищем последнее положение выделенного символа
    var end = txtarea.selectionEnd;
    // текст до + вставка + текст после (если этот код не работает, значит у вас несколько id)
    var finText = txtarea.value.substring(0, start) + text + txtarea.value.substring(end);
    // подмена значения
    txtarea.value = finText;
    // возвращаем фокус на элемент
    txtarea.focus();
    // возвращаем курсор на место - учитываем выделили ли текст или просто курсор поставили
    txtarea.selectionEnd = ( start == end )? (end + text.length) : end ;
}

//удаление чата
$(document).on("click", ".confirm-action", function(e) {
    text= $(this).attr('confirm')!==undefined ? $(this).attr('confirm') : "Подтвердите действие!";
    var Success = confirm(text);
    if(Success==false){
        return false;
    }
});
