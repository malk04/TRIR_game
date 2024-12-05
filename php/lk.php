<?php
    session_start();
    $login = $_SESSION['login'];
    $password = $_SESSION['password'];
    if ($login == null and $password == null){
        echo json_encode([
            "successful" => false,
            "login" => ""
        ]);
    } else { 
        echo json_encode([
            "successful" => true,
            "login" => $login,
            "life" => $_SESSION['life']
        ]);
    }
?>