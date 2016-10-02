$(document).ready(function() {
    $('form').submit(function() {
        var result = id_ajax({url: urls['login'], data: $('form').serializeArray(), dataType: 'json', async: false}, view);
        console.log(result);
        return false;
    });
});
function view(result) {
    if (result['status']=='error') {
        $('#login .orange_delete').html(result['error']).slideDown(400);
    } else {
        window.location = urls['homepage'];
    }
}
function id_ajax(param, funct) {
    var arr = {
        type: 'POST',
        dataType: 'json',
        success: function(msg) {
            Response = msg;
            if (funct!=undefined) {
                funct(msg);
            }
        },
        error:function(msg){
            $('html').html(msg.responseText);
            console.log('ошибка',msg.responseText);
        }
    };
    $.extend(arr, param);
    arr['url'] = document.location.protocol+'//'+document.location.hostname+arr['url'];
    var Response=false;
    $.ajax(arr);
    return Response;
}
function registation_open() {
    $('#registration').css('display','block');
    $('#registration').animate({'opacity':'1'},400);
}
function registation_close() {
    $('#registration').animate({'opacity':'0'},400);
    $('#registration').hide();
}
function login_open() {
    $('#login').css('display','block');
    $('#login').animate({'opacity':'1'},400);
}
function login_close() {
    $('#login').animate({'opacity':'0'},400);
    $('#login').hide();
}