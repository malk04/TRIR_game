<?php
    session_start();
    $login = $_SESSION['login'];
    $password = $_SESSION['password'];
    $gamer;
    
    $file = file_get_contents('../data/results.json');
    $data = json_decode($file, true);
    
    if ($login == null and $password == null){
        echo json_encode([
            "successful" => false,
            "login" => "",
            "data" => ""
        ]);
    } else {

        $data_from_file = $data;
        $data_n = array();
        $best_results = array();
        $current_user = array();
        foreach ($data_from_file as $gamer => $current_user_data) {
            foreach ($current_user_data as $ind => $game_data) {
                //отсортируем по результату
                $current_user = $current_user_data;
                $id_arr = array();
                foreach ($current_user as $ind => $game_data) {
                    $id_arr[$ind] = $game_data['result'];
                }
                array_multisort($id_arr, SORT_DESC, SORT_NUMERIC, $current_user);
     
            }    
                array_push($data_n, array(
                    "gamer" => $gamer,
                    "best_result" => $current_user[0]['result']
                ));
                array_push($best_results, $current_user[0]['result']);
            
        }
        array_multisort($best_results, SORT_DESC, SORT_NUMERIC, $data_n);
  
        
        echo json_encode([
            "successful" => true,
            "login" => $login,
            "best" => $data_n
        ]);
    };
    
?>