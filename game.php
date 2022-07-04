<?php
    require_once 'M_MSQL.php';
    require_once 'Bot.php';
    
    $response = (new Bot($_POST['playerCells'], $_POST['botCells']))->getResponse();
    echo $response;
    if ($response === ')')
        increaseLevel();
    if ($response[0] === '(')
        reduceLevel();
    function increaseLevel() {//echo 'inc';
        $login = M_MSQL::Instance() -> Escape(trim($_POST['login']));
        $query = "SELECT *
                  FROM users
                  WHERE login = '{$login}'";
        $user = (M_MSQL::Instance() -> Select($query))[0];//echo $user;
        if ($user['password']) {
            $password = M_MSQL::Instance() -> Escape($_COOKIE['password']);
            if ($user['password'] == md5($login . $password . 'ndgdy34l')) {//echo 'mm';//$user['level'];
                setLevel($login, $user['level'] + 1);
            }
            return;
        }
        setLevel($login, $user['level'] + 1);
    }
    function reduceLevel() {//echo 'r';
        $login = M_MSQL::Instance() -> Escape(trim($_POST['login']));
        $query = "SELECT *
                  FROM users
                  WHERE login = '{$login}'";
        $user = (M_MSQL::Instance() -> Select($query))[0];
        if ($user['password']) {
            $password = M_MSQL::Instance() -> Escape($_COOKIE['password']);
            if ($user['password'] == md5($login . $password . 'ndgdy34l')) {
                setLevel($login, $user['level'] - 1);
            }
            return;
        }
        setLevel($login, $user['level'] - 1);// echo 'BB'; echo $user['level'] - 1;
    }
    function setLevel($login, $level) {//echo 'ss';echo $level;
        if (($level < 1) or ($level > 100))
            return;
        $object = array('level' => $level);
        $where = "login = '$login'";
        M_MSQL::Instance() -> Update('users', $object, $where);
    }