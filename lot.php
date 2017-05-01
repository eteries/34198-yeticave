<?php
require_once 'functions.php';
require_once 'lots_data.php';

if (!filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) {
    header('HTTP/1.1 404 Not Found');
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$lot = $lots[$id];

if (!isset($lots[$id])) {
    header('HTTP/1.1 404 Not Found');
    exit;
}

$lot_time_remaining = getRemainingTime();

// ставки пользователей, которыми надо заполнить таблицу
$bets = [
    ['name' => 'Иван', 'price' => 11500, 'ts' => strtotime('-' . rand(1, 50) .' minute')],
    ['name' => 'Константин', 'price' => 11000, 'ts' => strtotime('-' . rand(1, 18) .' hour')],
    ['name' => 'Евгений', 'price' => 10500, 'ts' => strtotime('-' . rand(25, 50) .' hour')],
    ['name' => 'Семён', 'price' => 10000, 'ts' => strtotime('last week')]
];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= $lot['title'] ?></title>
    <link href="css/normalize.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<?php
echo renderTemplate('templates/header.php');
echo renderTemplate('templates/lot.php', compact('bets', 'lot', 'lot_time_remaining'));
echo renderTemplate('templates/footer.php');
?>

</body>
</html>
