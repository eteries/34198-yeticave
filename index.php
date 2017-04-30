<?php
require_once 'functions.php';

// устанавливаем часовой пояс в Московское время
date_default_timezone_set('Europe/Moscow');

// записать в эту переменную оставшееся время в этом формате (ЧЧ:ММ)
$lot_time_remaining = '00:00';

// временная метка для полночи следующего дня
$tomorrow = strtotime('tomorrow midnight');

// временная метка для настоящего времени
$now = time();

// расчёт оставшегося времени:
// 1. всего до полуночи (в минутах)
$remaining_minutes_total = ($tomorrow - $now) / 60;
// 2. из них целых часов
$remaining_hours = floor($remaining_minutes_total / 60);
// 3. остаток минут
$remaining_minutes = $remaining_minutes_total % 60;

// двухзначный формат
$remaining_hours = ($remaining_hours < 10) ? '0' . $remaining_hours : $remaining_hours;
$remaining_minutes = ($remaining_minutes < 10) ? '0' . $remaining_minutes : $remaining_minutes;

// отформатированная строка из вычисленных часов:минут
$lot_time_remaining = "$remaining_hours:$remaining_minutes";

$categories = ['Доски и лыжи', "Крепления", 'Ботинки', 'Инструменты', 'Одежда', 'Разное'];

$lots = [
     [
         'title' => '2014 Rossignol District Snowboard',
         'category' => 'Доски и лыжи',
         'price' => 10999,
         'img' => 'img/lot-1.jpg',
     ],
     [
        'title' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 159999,
        'img' => 'img/lot-2.jpg',
     ],
     [
        'title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 'Крепления',
        'price' => 8000,
        'img' => 'img/lot-3.jpg',
     ],
     [
        'title' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => 'Ботинки',
        'price' => 10999,
        'img' => 'img/lot-4.jpg',
     ],
     [
        'title' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => 'Одежда',
        'price' => 7500,
        'img' => 'img/lot-5.jpg',
     ],
     [
        'title' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'price' => 5400,
        'img' => 'img/lot-6.jpg',
     ]
];

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная</title>
    <link href="css/normalize.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<?php
echo renderTemplate('templates/header.php');
echo renderTemplate('templates/main.php', compact('categories', 'lots', 'lot_time_remaining'));
echo renderTemplate('templates/footer.php');
?>

</body>
</html>
