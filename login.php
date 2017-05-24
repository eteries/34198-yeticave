<?php
session_start();

require_once 'functions.php';
require_once 'connect.php';

$is_new_user = false;
$categories = findCategories($link);

if (isset($_GET['welcome'])) {
    $is_new_user = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $invalid_controls = [];

    /**
     * Сформировать массив не валидных полей, если таковые найдутся.
     */
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $invalid_controls['email'] = 'Введите e-mail';
    }

    if (empty($password)) {
        $invalid_controls['password'] = 'Введите пароль';
    }

    /**
     * Если данные введены без ошибок, сформировать пару e-mail/пароль для процедуры логина
     */
    if (empty($invalid_controls)) {
        $existing_user = findUserByEmail($link, $email);

        // Если впользователь с этим e-mail находится, то для авторизации сверяется пароль
        if (!empty($existing_user)) {
            $this_user = $existing_user[0];
            $verify = password_verify($password, $this_user['password']);

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
echo renderTemplate('templates/login-form.php', compact('invalid_controls', 'is_new_user'));

echo renderTemplate('templates/footer.php', compact('categories'));
