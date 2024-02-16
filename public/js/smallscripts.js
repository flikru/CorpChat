$(document).ready(function (){
    $('#userstatus').change(function (){
        //console.log($(this).val());
    });
});
function setstatus(url,ob,remove){
    let status;
    $('#userstatus').removeClass(remove);
    $('#userstatus').addClass("status-"+$(ob).val());
    status = { 'userstatus': $(ob).val()};
    setdata('patch',url,status);
}

function setdata(method,url,data){
    let active=[];
    $('.newmessage').each(function (){
        active.push($(this).closest('.get_message_chat').attr('chat-id'));
    })
    $.ajax({
        'type': method,
        'url': url,
        'data': data,
        'dataType': 'json',
        'success': function( res ){

        }
    });
}
