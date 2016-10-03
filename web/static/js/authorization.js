$(document).ready(function() {
    $('form#login').submit(function() {
        var result = id_ajax({url: urls['login'], data: $('form').serializeArray(), dataType: 'json', async: false}, view);
        console.log(result);
        return false;
    });
    $('#registration .orange-43').click(function() {
        var result = id_ajax({url: urls['registration'], data: $('form#registration').serializeArray(), dataType: 'json', async: false}, views);
        console.log($('form#registration').serializeArray());
        return false;
    });
    var steps = $("#registration form").children(".step");
    var navSteps = $("#registration ul li").children(".div-registration_li");
    $(steps[0]).show();
    $(navSteps[0]).addClass('active');
    var current_step = 0;
    $("input.next").click(function() {
        current_step++;
        changeStep(steps, current_step, navSteps);
    });
});
function changeStep(steps, i, navSteps) {
    $(steps).hide();
    $(navSteps).removeClass('active');
    $(navSteps[i]).addClass('active');
    $(steps[i]).show(200);
}
function views(result) {
    console.log(result);
}
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