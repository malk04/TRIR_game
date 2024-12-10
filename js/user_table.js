$('document').ready(function(){
    get_info();
});

function get_info(){
    $.ajax({
        url: '../php/user_table.php',
        type: 'POST',
        success: async function(jsonResult){
            let data = JSON.parse(jsonResult);
            $("#login_lk").html('<span id="log">'+data["login"]+'</span>'); 
            if (data["reverse"].length == 0){
                $("#rating").html("Вы ещё не играли");
            } else {
                $("#rating").html("Ваш лучший результат: "+data["best"]["result"]);
                let table = "<table class='ord' id='ord'><tr><th id='h_n'>Результат</th><th>Время</th></tr>";
                for (let i = 0; i < data["reverse"].length; i++){
                    table += '<tr>';
                    table += '<td id="n">'+data["reverse"][i]["result"]+'</td>';
                    table += '<td>'+data["reverse"][i]["date_str"]+'</td>';
                    table += '</tr>';
                }
                table += '</table>';
                $("#rating_table").html(table);
            };
        }
    });
    
};