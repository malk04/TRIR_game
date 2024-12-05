<?php
    session_start();
    $data = json_decode(file_get_contents("php://input"));
    $file_data = json_decode(file_get_contents("../data/numbers.json"));
    
    if (!isset($_SESSION['time_game'])){
            $_SESSION['level'] = 0;
            $_SESSION['time_hide'] = 3000;
            $_SESSION['time_game'] = 6;
            $_SESSION['now_time'] = 6;
            $_SESSION['count_hide'] = 1;
            $_SESSION['life'] = 3;
            $_SESSION['oshibka'] = false;
    }
    
    //проверяем правильность
    $successful = true;
    $arr_right = [];

    for ($i = 0; $i < count($data); $i++){
        if ($data[$i]->right == $data[$i]->selected){
            $arr_right[$i] = true;
        } else {
            $arr_right[$i] = false;
            $successful = false;
        }
    }
    
    //если всё правильно
    if ($successful){
        $level = 0;
        $time_hide = 3000;
        $time_game = 6;
        $count_hide = 1;
        
        if (isset($_SESSION['level'])){
            $level = $_SESSION['level'];
        }
        $level += 1;
        $_SESSION['level'] = $level;
        
        if (($level == 0) && ($level <= 2)){
            $count_hide = 1;
        } elseif(($level > 2) && ($level <= 5)){
            $count_hide = 2;
        } elseif(($level > 5) && ($level <= 8)){
            $count_hide = 3;
        } elseif(($level > 8) && ($level <= 11)){
            $count_hide = 1;
        } elseif(($level > 11) && ($level <= 14)){
            $count_hide = 2;
        } elseif(($level > 14) && ($level <= 17)){
            $count_hide = 3;
        } elseif(($level > 17) && ($level <= 20)){
            $count_hide = 1;
        } elseif(($level > 20) && ($level <= 23)){
            $count_hide = 2;
        } elseif($level > 23) {
            $count_hide = 3;
        }
        $_SESSION['count_hide'] = $count_hide;
        
        if (isset($_SESSION['time_hide'])){
            if ($_SESSION['level'] == 3 || $_SESSION['level'] == 6 || $_SESSION['level'] == 9 || $_SESSION['level'] == 12 || $_SESSION['level'] == 15 || $_SESSION['level'] == 18 || $_SESSION['level'] == 21 || $_SESSION['level'] == 24){
                $_SESSION['time_hide'] = 3000;
            }
            $time_hide = $_SESSION['time_hide'];
        }
        
        if ($time_hide>1500){
            $time_hide -= 500;
        } else{
            $time_hide = 1500;
        }
        $_SESSION['time_hide'] = $time_hide;
        
        if (isset($_SESSION['time_game'])){
            if ($_SESSION['level'] == 3){
                $_SESSION['time_game'] = 7;
            } elseif ($_SESSION['level'] == 6){
                $_SESSION['time_game'] = 9;
            } elseif ($_SESSION['level'] == 9){
                $_SESSION['time_game'] = 7;
            } elseif ($_SESSION['level'] == 12){
                $_SESSION['time_game'] = 10;
            } elseif ($_SESSION['level'] == 15){
                $_SESSION['time_game'] = 12;
            } elseif ($_SESSION['level'] == 18){
                $_SESSION['time_game'] = 8;
            } elseif ($_SESSION['level'] == 21){
                $_SESSION['time_game'] = 11;
            } elseif ($_SESSION['level'] == 24){
                $_SESSION['time_game'] = 14;
            }
            $time_game = $_SESSION['time_game'];
        }
        
        if ($level < 24){
            $time_game -= 1;
        } elseif($time_game > 6){
            $time_game -= 1;
        } else{
            $time_game = 6;
        }
        
        $_SESSION['time_game'] = $time_game;
        $_SESSION['now_time'] = $time_game;
        $_SESSION['oshibka'] = false;
        
    } elseif($_SESSION['life'] > 1){
        $_SESSION['life']--;
        $_SESSION['oshibka'] = true;
        $_SESSION['now_time'] = $_SESSION['time_game'];
    } else{
        $res = 0;
        if (isset($_SESSION['level'])){
            $res = $_SESSION['level'];
        }
        add_result($_SESSION['login'], $res);
        
        $_SESSION['level'] = 0;
        $_SESSION['time_hide'] = 3000;
        $_SESSION['time_game'] = 6;
        $_SESSION['now_time'] = 6;
        $_SESSION['count_hide'] = 1;
        $_SESSION['life'] = 3;
        $_SESSION['oshibka'] = false;
    }
    
    
    
    $data_send = array(
        "arr_right" => $arr_right,
        "successful" => $successful,
        "level" => $_SESSION['level'],
        "time_hide" => $_SESSION['time_hide'],
        "life" => $_SESSION['life'],
        "oshibka" => $_SESSION['oshibka']
    );
    
    
    
    function add_result($login, $result){
        $file = file_get_contents('../data/results.json');
        $data = json_decode($file, true);
       
        if (!array_key_exists($login, $data)){
            $data[$login] = array();
        } 
        $current_user = $data[$login];
        $date_str = "Игра завершена " . date("d.m.y") . " в " . date("H:i:s");
        array_push($current_user, array("result" => $result, "date_str" => $date_str));

        $data[$login] = $current_user;
        save_results($data);
    }
    
    function save_results($data) {
        $json_str = json_encode($data);
        file_put_contents('../data/results.json', $json_str);
    }
    
    echo json_encode($data_send);