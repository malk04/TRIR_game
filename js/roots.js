$('document').ready(function(){
    get_login();
});

function get_login(){
    $.ajax({
        url: '../php/lk.php',
        type: 'POST',
        success: async function(jsonResult){
            let data = JSON.parse(jsonResult);
            if (!data["successful"]){
                window.location.replace('../index.html')
            } else {
                $("#login_lk").html('<span id="log">'+data["login"]+'</span>');
                star = '<img id="star" src="../img/star.svg">';
                for (let i = 0; i < data["life"]; i++){
                    $('#stars').append(star);
                }
            };
        }
    });
};