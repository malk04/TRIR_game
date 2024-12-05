<?php
session_start();
$_SESSION['now_time'] -= 1;
if ($_SESSION['now_time'] >= 0){
    $data_send = array(
        "now_time" => $_SESSION['now_time'],
        "s_time" => "ok"
    );
} else{
    $data_send = array(
        "now_time" => $_SESSION['now_time'],
        "s_time" => "stop"
    );
}

echo json_encode($data_send);   