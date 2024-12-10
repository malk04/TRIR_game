$('document').ready(function(){
    get_info();
});

function get_info(){
    $.ajax({
        url: '../php/rating.php',
        type: 'GET',
        success: async function(jsonResult){
            let data = JSON.parse(jsonResult);
            $("#login_lk").html('<span id="log">'+data["login"]+'</span>'); 
            if (data["best"].length == 0){
                $("#rating").html("В эту игру ещё никто не играл");
            } else {
                let place = 0;
                let table = "<table class='rat' id='rat'><tr><th id='h_n'>Место</th><th>Игрок</th><th>Результат</th></tr>";
                for (let i = 0; i < data["best"].length; i++){
                    table += '<tr>';
                    if (i == 0){
                        table += '<td id="n"><img id="place" src="../img/1mesto.png"></td>';
                    } else if (i == 1){
                        table += '<td id="n"><img id="place" src="../img/2mesto.png"></td>';
                    }else if (i == 2){
                        table += '<td id="n"><img id="place" src="../img/3mesto.png"></td>';
                    }else{
                        table += '<td id="n">'+(i+1)+'</td>';
                    }
                    table += '<td>'+data["best"][i]["gamer"]+'</td>';
                    table += '<td>'+data["best"][i]["best_result"]+'</td>';
                    table += '</tr>'
                    if (data["best"][i]["gamer"] == data["login"]){
                        place = i+1;
                    }
                }
                table += '</table>';
                $("#rating").html('Вы на '+place+' месте!');
                $("#rating_table").html(table);
            };
        }
    });
    
};