<?php
    require_once 'M_MSQL.php';
    
    $login = M_MSQL::Instance() -> Escape(trim($_POST['login']));
    $password = M_MSQL::Instance() -> Escape(trim($_POST['password']));
    $password = md5($login . $password . 'ndgdy34l');
    if ((strlen($login) > 20) or !$password) {
        echo 'error';
    } else {
        $object = array('password' => $password);
        $where = "login = '$login'";
        M_MSQL::Instance() -> Update('users', $object, $where);
    }