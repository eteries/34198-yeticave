<?php
session_start();

require_once 'functions.php';
require_once 'connect.php';
require_once 'lots_data.php';

$user = [];
$invalid_controls = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /**
     * Сформировать массив не валидных полей, если таковые найдутся.
     */
    foreach ($_POST as $name => $value) {
        $value = trim($value);

        if (empty($value)) {
            $invalid_controls[$name] = 'Заполните это поле';
        }

        if ($name == 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $invalid_controls[$name] = 'Введите email';
        }

        if ($name == 'email' && !empty(queryDB($link, 'SELECT id FROM users WHERE email = ?', ['email' => $value]))) {
            $invalid_controls[$name] = 'Email уже зарегистрирован';
        }
    }

     $img = verifyAndUploadImage('photo2');

    /**
     * Если данные введены без ошибок, сформировать и записать нового пользователя.
     */
    if (empty($invalid_controls) && !empty($_POST)) {
        $user['email'] = trim($_POST['email'] ?? '');
        $user['password'] = password_hash(trim($_POST['password'] ?? ''), PASSWORD_BCRYPT);
        $user['username'] = trim($_POST['name'] ?? '');
        $user['contact_info'] = trim($_POST['message'] ?? '');
        $user['avatar'] = !empty($img) ? $img : 'img/user.jpg';

        $sql = <<<SQL
INSERT into users (
        email, 
        password, 
        username, 
        contact_info, 
        avatar, 
        reg_time) 
        values (?,?,?,?,?,NOW());
SQL;

        insertDataDB($link, $sql, $user);
        $_SESSION = [];
        header('location: /login.php?welcome=true');
        exit();
    }
}


echo renderTemplate('templates/top.php', ['html_title' => 'Регистрация аккаунта']);
echo renderTemplate('templates/header.php');

echo renderTemplate('templates/nav.php');
echo renderTemplate('templates/sign-up.php', compact('invalid_controls'));

echo renderTemplate('templates/footer.php', compact('categories'));
