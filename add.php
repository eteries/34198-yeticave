<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}

require_once 'functions.php';
require_once 'connect.php';

$lot = [];
$invalid_controls = [];
$categories = findCategories($link);

/**
 * Сформировать массив не валидных полей, если таковые найдутся.
 */
foreach ($_POST as $name => $value) {
    $value = trim($value);

    if (empty($value)) {
        $invalid_controls[$name] = 'Заполните это поле';
    }

    if ($name == 'category' && $value == 'Выберите категорию') {
        $invalid_controls[$name] = 'Заполните это поле';
    }

    if (($name == 'lot-rate' || $name == 'lot-step') && !filter_var($value, FILTER_VALIDATE_INT)) {
        $invalid_controls[$name] = 'Введите число';
    }

    if ($name == 'lot-date' && (strtotime($value) < time() || strtotime($value) > strtotime('+1 month'))) {
        $invalid_controls[$name] = 'Выберите дату в течение ближайшего месяца';
    }
}

$img = verifyAndUploadImage('photo2');

/**
 * Если данные введены без ошибок, сформировать новый лот, добавить в БД и переключиться на страницу этого лота
 */
if (empty($invalid_controls) && !empty($_POST)) {
    $ending = date_create(trim($_POST['lot-date']));

    $lot['title'] = trim($_POST['lot-name']);
    $lot['category'] = $_POST['category'];
    $lot['price'] = trim($_POST['lot-rate']);
    $lot['step'] = (int) trim($_POST['lot-step']);
    $lot['description'] = trim($_POST['message']);
    $lot['img'] = !empty($img) ? $img : 'img/logo.svg';
    $lot['ending'] = date_format($ending, 'Y-m-d H:i:s');
    $lot['author_id'] = $_SESSION['user']['id'];


    if ($id = addLot($link, $lot)) {
        header('location: /lot.php?id='.$id);
        exit();
    }
}

echo renderTemplate('templates/top.php', ['html_title' => 'Добавление лота']);
echo renderTemplate('templates/header.php');

echo renderTemplate('templates/nav.php');

echo renderTemplate('templates/form.php', compact('invalid_controls', 'categories'));

echo renderTemplate('templates/footer.php', compact('categories'));
