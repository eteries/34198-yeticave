<?php
session_start();

require_once 'functions.php';
require_once 'userdata.php';

$login = [];
$invalid_controls = [];

/**
 * Сформировать массив не валидных полей, если таковые найдутся.
 */
foreach ($_POST as $name => $value) {
    $value = trim($value);

    if ($name == 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
        $invalid_controls[$name] = 'Введите e-mail';
    }

    if ($name == 'password' && empty($value)) {
        $invalid_controls[$name] = 'Введите пароль';
    }
}

/**
 * Если данные введены без ошибок, сформировать пару e-mail/пароль для процедуры логина
 */
if (empty($invalid_controls) && !empty($_POST)) {
    $login['email'] = trim($_POST['email'] ?? '');
    $login['password'] = trim($_POST['password'] ?? '');
}

/**
 * Если данные для логина сформированы, идёт проверка по e-mail
 */
if (!empty($login)) {
    $check = array_search($login['email'], array_column($users, 'email'), true);
}

// Если в ходе проверки, пользователь с этим e-mail находится, то для авторизации сверяется пароль
if (isset($check) && $check !== false) {
    $this_user = $users[$check];
    $verify = password_verify($login['password'], $this_user['password']);

    if ($verify === true) {
        $_SESSION['user'] = $this_user;
        header('location: /');
        exit();
    } else {
        $invalid_controls['password'] = 'Вы ввели неверный пароль';
    }
}
// Если в ходе проверки, пользователь с этим e-mail не находится, выводится соответствующее сообщение
if (isset($check) && $check === false) {
    $invalid_controls['email'] = 'Пользователь не найден';
}

echo renderTemplate('templates/top.php', ['html_title' => 'Вход']);
echo renderTemplate('templates/header.php');

echo renderTemplate('templates/nav.php');
echo renderTemplate('templates/login-form.php', compact('invalid_controls'));

echo renderTemplate('templates/footer.php');
