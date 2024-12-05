<?php
    session_start();
    add_result($_SESSION['login'], $_SESSION['level']);
    
    
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