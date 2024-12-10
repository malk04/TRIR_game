$('document').ready(function(){
    $(document.log).submit(function(){
        check_login();
    });
    $("#login").on("input", function(){
        check_vvod("#login");
    });
    $("#password").on("input", function(){
        check_vvod("#password");
    });
});

function check_login(){
    let login = $("#login").val();
    let password = $("#password").val();
    let login_data = {
        login: login,
        password: password
    };
    let json_login_data = JSON.stringify(login_data);
    $.ajax({
        url: 'php/check_login.php',
        type: 'POST',
        contentType: 'applications/json',
        data: json_login_data,
        success: async function(jsonResult){
            errors = JSON.parse(jsonResult);
            error(errors);
            if (errors['successful']){
                window.location.replace('html/lk.html');
            };
        }
    });
};

function error(data){
    let log_err = $("#log-error");
    if (data['login_err'] != ""){
        log_err.html(data['login_err']);
    };
    if (data['password_err'] != ""){
        log_err.html(data['password_err']);
    }; 
};

function check_vvod(id){
    let input = $(id);
    let proverka = new RegExp("^([a-z,A-Z,0-9])$");
    let res = "";
    for (element of input.val()){
        if (proverka.test(element) && res.length <= 20){
            res += element;
        };
    };
    if (res == ""){
        input.val("");
    } else {
        input.val(res);
    }
};

