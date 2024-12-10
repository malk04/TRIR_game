$('document').ready(function(){
    $(window).on("unload", clear_session());
    get_login();

});

let item; //перетаскиваемый в данный момент номер
function dragstart(e){
    item = e.target;
}

function dragover(e){
    e.preventDefault();
}

function dragdrop(e){
    let copy = item.cloneNode(true);
    copy.removeAttribute('draggable');
    if (e.target.className == "box_play"){
        e.target.append(copy);
    } else{
        e.target.parentNode.replaceWith(copy);
    }
}

function setter(){
    $.ajax({
        url: '../php/get_boxes.php',
        type: 'POST',
        success: function(jsonResult){
            let data = JSON.parse(jsonResult);
            $('#tek').html(data['level']);
            if (data['level'] <= 8){
                $('.main').css('font-size', '6.5vw');
            } else if (data['level'] > 8 && data['level'] <= 17){
                $('.main').css('font-size', '4.5vw');
            } else if (data['level'] > 17){
                $('.main').css('font-size', '3.5vw');
            }
            
            data['chisla'].forEach(function(box){
                let x = box;
                var doc = new DOMParser().parseFromString(x, "text/html");
                var new_box = $(doc).find('.box');
                let iner = $('.numbers');
                iner.append(new_box);
            });
            
            data['arr_box'].forEach(function(box){
                let x = box;
                var doc = new DOMParser().parseFromString(x, "text/html");
                var new_box = $(doc).find('.box_play');
                let iner = $('.play');
                iner.append(new_box);
            });
            
            //добавляем функциональность drag
            var nums = Array.from($('.num'));
            nums.forEach(function(element){
                $(element).on("dragstart", dragstart);
            });
    
            var drop_boxes = Array.from($('[id=drop]'));
            drop_boxes.forEach(function(box){
                $(box).on("dragover", dragover);
                $(box).on("drop", dragdrop);
            });
            
            var boxes_play = Array.from($('.box_play'));
            
            //прячем цифры
            function set(){
                data['arr_drop'].forEach(function(box_drop){
                    $(boxes_play[box_drop]).attr("id", "drop");
                    $(boxes_play[box_drop]).find('.num_play').attr("id", "hide");
                });
            
                //добавляем функциональность drop
                var drop_boxes = Array.from($('[id=drop]'));
                drop_boxes.forEach(function(box){
                    $(box).on("dragover", dragover);
                    $(box).on("drop", dragdrop);
                });
            };
            
            let h = data['time_hide'];
            setTimeout(set, h);
            setTimeout(create_game, h-1000);    
        }
    });
};

function get_play_boxes(){
    setter(); 
};

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

function clear_session(){
    $.ajax({
        url: '../php/session_clear.php',
        type: 'POST',
        success: async function(jsonResult){
        }
    });
};


function get_check(){
    let arr = [];
    let i = 0;
    var drop_boxes = Array.from($('[id=drop]'));
    drop_boxes.forEach(function(box){
        let f = $(box).find(".num_play").find(".nomer_play");
        if ($(box).find('.num').length > 0){
            let k = $(box).find('.num').find('.nomer');
            let d = {
                right: f.attr('id'),
                selected: k.attr('id')
            };
            arr[i] = d;
            i++;
        } else{
            let d = {
                right: f.attr('id'),
                selected: '0'
            };
            arr[i] = d;
            i++;
        };
    });
    return arr;
}

function check_end(){
    let arr_select = Array.from(get_check());
    let successful_ret = false;
    
    let json_data = JSON.stringify(arr_select);
    $.ajax({
        url: '../php/check_drop.php',
        type: 'POST',
        contentType: 'applications/json',
        data: json_data,
        async: false,
        success: function(jsonResult){
            let data_p = JSON.parse(jsonResult);
            let right = data_p['arr_right'];
            var drop_boxes = Array.from($('[id=drop]'));
            for (let i = 0; i < drop_boxes.length; i++){
                if (right[i]){
                    $(drop_boxes[i]).find('.num').css('backgroundColor', '#41ba3f');
                    $(drop_boxes[i]).css('backgroundColor', '#caadf7');
                } else{
                    if ($(drop_boxes[i]).find('.num').length > 0){
                        $(drop_boxes[i]).find('.num').css('backgroundColor', '#e8545d');
                    } else{
                       $(drop_boxes[i]).css('backgroundColor', '#e8545d'); 
                    }
                    star = '<img id="star" src="../img/star.svg">';
                    $('#stars').empty();
                    $('#stars').html('<span>Жизни: </span>');
                    for (let i = 0; i < data_p["life"]; i++){
                        $('#stars').append(star);
                    }
                }
            }
            
            successful_ret = data_p['successful'] || data_p['oshibka'];
            var drop_boxes = Array.from($('[id=drop]'));
            drop_boxes.forEach(function(box){
                $(box).off("dragover", dragover);
                $(box).off("drop", dragdrop);
            });
        }
    });
    return successful_ret;
}



//Таймер
let timeout; //TimerID
function get_time(){
    $.ajax({
        url: '../php/timer.php',
        type: 'GET',
        success: function(jsonResult){
            let data = JSON.parse(jsonResult);
            if(data['s_time'] != "stop"){
                if (data['now_time'] < 10){
                    $(".time").html(`00:0${data['now_time']}`);
                } else {
                    $(".time").html(`00:${data['now_time']}`);
                }
            } else{
                clearInterval(timeout);
                if (check_end()){
                    level_over();
                } else{
                    game_over();
                    clear_session();
                }
            }
        }
    })   
}

function create_game(){
    timeout = setInterval(get_time, 1000);
}

function level_over(){
    setTimeout(next, 1000);
    function next(){
        $(".time").html('Запоминай');
        $(".play").html('');
        $(".numbers").html('');
        get_play_boxes();
    }
}

function start_game(){
    $("#ochki").css("display","block");
    $("#stars").css("display","block");
    $(".play").html('');
    $(".numbers").html('');
    $(".time").html('Запоминай');
    but = $('<input type="button" id="start" value="Завершить игру" onclick="end()">')
    $(".end").html(but);
    get_play_boxes();
}

function game_over(){
    $("#ochki").css("display","none");
    $("#stars").css("display","none");
    $(".time").html('Game over');
    $(".end").empty();
    $(".end").html('<input type="button" id="start" value="Заново" onclick="zanovo()">');
    var drop_boxes = Array.from($('[id=drop]'));
    drop_boxes.forEach(function(box){
        $(box).off("dragover", dragover);
        $(box).off("drop", dragdrop);
    });
}

function zanovo(){
    clear_session();
    start_game();
}

function end(){
    $("#ochki").css("display","none");
    $("#stars").css("display","none");
    save();
    location.reload();
}

function save(){
    $.ajax({
        url: '../php/end.php',
        type: 'POST',
        success: async function(jsonResult){
        }
    })   
}