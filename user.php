<?php
    require_once 'M_MSQL.php';
    
        header("Content-Type: application/json");
    $login = M_MSQL::Instance() -> Escape(trim($_GET['login']));
    if (strlen($login) > 20) {
        echo 'error';
    } else {
        $query = "SELECT *
                  FROM users
                  WHERE login = '{$login}'";
        $user = (M_MSQL::Instance() -> Select($query))[0];
        if (!$user) {
            $object = array('login' => $login, 'password' => '', 'level' => '1');
            M_MSQL::Instance() -> Insert("users", $object);
            echo json_encode(['level' => '1']);
        }
        if ($user && $user['password']) {
            echo 'password';
        }
        if ($user && !$user['password']) {
            echo json_encode(['level' => $user['level']]);
        }
    }