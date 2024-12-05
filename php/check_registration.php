<?php
    $data = json_decode(file_get_contents("php://input"));
    $successful = True;
    $file_data = json_decode(file_get_contents("../data/users.json"));
    
    $login_err = check_login($data, $file_data);
    if ($login_err != ""){
        $successful = false;
    };
    
    $password_err = check_password($data);
    if ($password_err != ""){
        $successful = false;
    };
    
    $mail_err = check_mail($data);
    if ($mail_err != ""){
        $successful = false;
    };
    
    if ($login_err == "" && $mail_err == ""){
        $successful = True;
    };
    
    if ($successful){
        $new_user = array(
            "login" => $data->login,
            "password" => $data->password,
            "mail" => $data->mail
        );
        array_push($file_data, $new_user);
        file_put_contents("../data/users.json", json_encode($file_data));
    }
    $errors = array(
        "login_err" => $login_err,
        "password_err" => $password_err,
        "mail_err" => $mail_err,
        "successful" => $successful
    );
    echo json_encode($errors);

function check_password($data){
    $password = $data->password;
    if (($password!="") && strlen($password) < 4){
        return "Длина пароля должна быть не менее 4";
    };
    return "";
};

function check_mail($data){
    $mail = $data->mail;
    if (($mail!="") && !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        return "Некорректная почта!";
    } else{
        return "";
    }
};

function check_login($data, $file_data){
    $login = $data->login;
    foreach ($file_data as $element){
        if ($element->login == $login){
            return "Пользователь с таким логином уже существует!";
        };
    };
    if (($login!="") && strlen($login) < 6){
        return 'Логин должен содержать минимум 6 символов!';
    } else{
        return "";
    }
};
?>