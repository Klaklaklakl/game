<?php
    require_once 'M_MSQL.php';
    
        header("Content-Type: application/json");
    $login = M_MSQL::Instance() -> Escape(trim($_POST['login']));
    $password = M_MSQL::Instance() -> Escape(trim($_POST['password']));
    if (strlen($login) > 20) {
        echo 'error';
    } else {
        $query = "SELECT *
                  FROM users
                  WHERE login = '{$login}'";
        $user = (M_MSQL::Instance() -> Select($query))[0];
        if ($user && ($user['password'] == md5($login . $password . 'ndgdy34l'))) {
            setcookie ("password", $password, time() + 60*60, '/');
            echo json_encode(['level' => $user['level']]);
        } else {
            echo json_encode(['error' => 'error']);
        }
    }