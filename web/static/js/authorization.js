var steps = $("#registration form").children(".step");
var navSteps = $("#registration ul li").children(".div-registration_li");
$(document).ready(function() {
    $('form#login').submit(function() {
        var result = id_ajax({url: urls['login'], data: $('form').serializeArray(), dataType: 'json', async: false}, view);
        console.log(result);
        return false;
    });
    $('form#registration').submit(function() {
        var result = id_ajax({url: urls['registration'], data: $('form#registration').serializeArray(), dataType: 'json', async: false}, views);
        console.log($('form#registration').serializeArray());
        return false;
    });
    $(steps[0]).show();
    $(navSteps[0]).addClass('active');
    var current_step = 0;
    $("input.next").click(function() {
        validate(steps, current_step, navSteps);
    });
    if(getUrlVars()["confirm"] == 1) {
        modal_open('#registration5');
    }
});
function email_confirm() {
    if($('#user_registration_email').val() == '') {
        $('#user_registration_email').addClass('input-error');
    } else {
       var url = urls['validate'];
        url = url.replace('|email|', $('#user_registration_email').val());
        sendAjax(url, confirmEmail); 
    }
}
function confirmEmail(msg) {
    var pass = $('#user_registration_password').val();
    var passRepeat = $('#user_registration_passwordRepeat').val();
    if(pass.length >=6 && pass == passRepeat) {
        $('input').removeClass('input-error');
        if(msg['status'] == 'error') {
            $('#user_registration_email').addClass('input-error');
            $('span#email').html(msg['error']);
        } else {
            $('span#email').html('');
            $(steps[1]).hide();
            $(navSteps[1]).removeClass('active');
            $(navSteps[2]).addClass('active');
            $(steps[2]).show(200);
        }
    } else {
        $('#user_registration_password, #user_registration_passwordRepeat').addClass('input-error');
        $('span#email').html('Password invalid');
    }
}
function validate(steps, current_step, navSteps) {
    $(steps[current_step]).find('input[type=text]').each(function () {
        if($(this).val() == '') {
            $(this).addClass('input-error');
            console.log($(this));
        } else {
            $(this).addClass('not-error').removeClass('input-error');
        }
    });
    var sizeEmpty = $(steps[current_step]).find('.input-error').size();
    if(sizeEmpty == 0) {
        current_step++;
        changeStep(steps, current_step, navSteps);
    }
}
function sendAjax(url, funct) {
    var Response=false;
    $.ajax({
        type: 'POST',
        url: url,
        success: function(msg) {
            Response=msg;
            if (funct!=undefined) {
                funct(msg);
            }
        },
        error:function(msg){
            $('html').html(msg.responseText);
            console.log('ошибка',msg.responseText);
        }
    });
    return Response;
}
function changeStep(steps, i, navSteps) {
    $(steps).hide();
    $(navSteps).removeClass('active');
    $(navSteps[i]).addClass('active');
    $(steps[i]).show(200);
}
function views(result) {
    if(result['status'] == 'ok') {
        registation_close();
        modal_open('#registration4');
    } else {
        window.location = urls['homepage'];
    }
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
function modal_open(modal) {
    $(modal).css('display','block');
    $(modal).animate({'opacity':'1'},400);
}
function modal_close(modal) {
    $(modal).animate({'opacity':'0'},400);
    $(modal).hide();
}
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}