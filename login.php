<?php
session_start();

require_once 'functions.php';
require_once 'userdata.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = [];
    $invalid_controls = [];

    /**
     * Сформировать массив не валидных полей, если таковые найдутся.
     */
    if (!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
        $invalid_controls['email'] = 'Введите e-mail';
    }

    if (empty(trim($_POST['password']))) {
        $invalid_controls['password'] = 'Введите пароль';
    }

    /**
     * Если данные введены без ошибок, сформировать пару e-mail/пароль для процедуры логина
     */
    if (empty($invalid_controls)) {
        $login['email'] = trim($_POST['email'] ?? '');
        $login['password'] = trim($_POST['password'] ?? '');

        $check = array_search($login['email'], array_column($users, 'email'), true);

        // Если впользователь с этим e-mail находится, то для авторизации сверяется пароль
        if ($check !== false) {
            $this_user = $users[$check];
            $verify = password_verify($login['password'], $this_user['password']);

            if ($verify === true) {
                $_SESSION['user'] = $this_user;
                header('location: /');
                exit();
            } else {
                $invalid_controls['password'] = 'Вы ввели неверный пароль';
            }
        } else {
        // Если пользователь с этим e-mail не находится, выводится соответствующее сообщение
            $invalid_controls['email'] = 'Пользователь не найден';
        }
    }
}

echo renderTemplate('templates/top.php', ['html_title' => 'Вход']);
echo renderTemplate('templates/header.php');

echo renderTemplate('templates/nav.php');
echo renderTemplate('templates/login-form.php', compact('invalid_controls'));

echo renderTemplate('templates/footer.php');
