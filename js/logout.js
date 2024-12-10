$('document').ready(function(){
    get_sess();
});

function get_sess(){
    $.ajax({
        url: '../php/logout.php',
        type: 'POST',
        success: async function(jsonResult){
            if (!jsonResult){
                window.location.replace('index.html')
            } else {
                setTimeout(() => {
                    window.location.replace('../index.html');
                }, 100);
            };
        }
    });
};