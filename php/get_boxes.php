<?php
    session_start();
    if (!isset($_SESSION['time_game'])){
            $_SESSION['level'] = 0;
            $_SESSION['time_hide'] = 3000;
            $_SESSION['time_game'] = 6;
            $_SESSION['now_time'] = 6;
            $_SESSION['count_hide'] = 1;
            $_SESSION['life'] = 3;
            $_SESSION['oshibka'] = false;
    }
    
    $chisla = [];
    $numbers = [];
    if (($_SESSION['level'] > 8) && ($_SESSION['level'] <= 17)){
        $random_num = [];
        $chisla = [];
        while (true) { //Погнали
            $rand2 = rand(10, 99); //Формируем рандомное число
            if (!in_array($rand2, $random_num)) { //Если такого числа в массиве нет
                $random_num[] = $rand2; //Добавляем его
                if (sizeof($random_num) == 10) { //Если массив заполнен до упора
                    break; //Выходим из цикла
                }
            }
        }
        $numbers = [];
        sort($random_num);
        foreach ($random_num as $element){
            $chisla[] = "<div class='box'><div class='num' draggable='true'><div id='".$element."' class='nomer'>".$element."</div></div></div>";
            $numbers[] = "<div class='box_play'><div class='num_play'><div id='".$element."' class='nomer_play'>".$element."</div></div></div>";
        };
    } else if ($_SESSION['level'] > 17){
        $random_num = [];
        $chisla = [];
        while (true) {
            $rand2 = rand(100, 999);
            if (!in_array($rand2, $random_num)) { 
                $random_num[] = $rand2; 
                if (sizeof($random_num) == 10) { 
                    break; 
                }
            }
        }
        $numbers = [];
        sort($random_num);
        foreach ($random_num as $element){
            $chisla[] = "<div class='box'><div class='num' draggable='true'><div id='".$element."' class='nomer'>".$element."</div></div></div>";
            $numbers[] = "<div class='box_play'><div class='num_play'><div id='".$element."' class='nomer_play'>".$element."</div></div></div>";
        };
    } else{
        $numbers = json_decode(file_get_contents("../data/numbers.json"));
        $chisla = json_decode(file_get_contents("../data/chisla.json"));
    }
    
    //рандомим числа
    $arr_box = [];
    for ($i = 0; $i < 5; $i++){
        $x = rand(0, 9);
        array_push($arr_box, $numbers[$x]);
    }
    
    //рандомим дропы
    $count_drop = $_SESSION['count_hide'];
    $arr_drop = [];
    while (true) { //Погнали
        $rand = rand(0, 4); //Формируем рандомное число
        if (!in_array($rand, $arr_drop)) { //Если такого числа в массиве нет
            $arr_drop[] = $rand; //Добавляем его
            if (sizeof($arr_drop) == $count_drop) { //Если массив заполнен до упора
                break; //Выходим из цикла
            }
        }
    }

    $data_send = array(
        "arr_box" => $arr_box,
        "arr_drop" => $arr_drop,
        "time_hide" => $_SESSION['time_hide'],
        "level" => $_SESSION['level'],
        "chisla" => $chisla
    );
    
    echo json_encode($data_send);   
       
?>