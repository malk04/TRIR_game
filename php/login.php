<?php
    $login = "";
    $password = "";
    session_start();
    if (isset($_SESSION['login'])){
        $login = $_SESSION['login'];
        $password = $_SESSION['password'];
    } else {
        session_destroy();
    };

    echo
        json_encode([
            "login_val" => "$login",
            "password_val" => "$password"
        ]);
?>