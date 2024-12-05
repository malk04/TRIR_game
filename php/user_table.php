<?php
    session_start();
    $login = $_SESSION['login'];
    $password = $_SESSION['password'];
    
    $file = file_get_contents('../data/results.json');
    $data = json_decode($file, true);
    
    if ($login == null and $password == null){
        echo json_encode([
            "successful" => false,
            "login" => "",
            "best" => "",
            "reverse" => ""
        ]);
    } else {
        
        $current_user = array();
        $reverse = array();
        if (array_key_exists($login, $data)) {
            $current_user = $data[$login];
            $reverse = array_reverse($current_user);
        }
        
        //отсортируем по результату
        $id_arr = array();
        foreach ($current_user as $ind => $game_data) {
            $id_arr[$ind] = $game_data['result'];
        }
        array_multisort($id_arr, SORT_DESC, SORT_NUMERIC, $current_user);
              
        if (count($reverse) != 0){
            echo json_encode([
                "successful" => true,
                "login" => $login,
                "best" => $current_user[0],
                "reverse" => $reverse
            ]);
        } else{
            echo json_encode([
                "successful" => true,
                "login" => $login,
                "best" => [],
                "reverse" => []
            ]);
        };
    };
    
?>