$('document').ready(function(){
    $(document.reg).submit(function(){
        check_registration();
    });
    $("#login").on("input", function(){
        check_vvod("#login");
    });
    $("#password").on("input", function(){
        check_vvod("#password");
    });
    $("#e-mail").on("input", function(){
        check_simvols();
    });
});

function check_registration(){
    let login = $("#login").val();
    let password = $("#password").val();
    let mail = $("#e-mail").val();
    let registration_data = {
        login: login,
        password: password,
        mail: mail
    };
    let json_registration_data = JSON.stringify(registration_data);
    $.ajax({
        url: '../php/check_registration.php',
        type: 'POST',
        contentType: 'applications/json',
        data: json_registration_data,
        success: async function(jsonResult){
            errors = JSON.parse(jsonResult);
            check_err(errors, '#login-error', 'login_err');
            check_err(errors, '#mail-error', 'mail_err');
            check_err(errors, '#password-error', 'password_err');
            if (errors['successful']){
                $("#successful").html(`<h2>Регистрация прошла успешно!</h2><button id="button" class="bb" onclick="document.location='../index.html'">Войти</button>`);    
            };
        }
    });
};

function check_err(data, p_id, error_id){
    let log_err = $(p_id);
    log_err.html(data[error_id]);
};

function check_vvod(id){
    let input = $(id);
    let proverka = new RegExp("^([a-z,A-Z,0-9])$");
    let res = "";
    for (elem of input.val()){
        if (proverka.test(elem) && res.length <= 20){
            res += elem;
        };
    };
    if (res == ""){
        input.val("");
    } else {
        input.val(res);
    }
};


function check_simvols(){
    let input = $("#e-mail");
    let res = "";
    for (elem of input.val()){
        if (res.length <= 20){
            res += elem;
        };
    };
    if (res == ""){
        input.val("");
    } else {
        input.val(res);
    }
};