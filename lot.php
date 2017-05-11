<?php
session_start();

require_once 'functions.php';
require_once 'lots_data.php';

if (!filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) {
    header('HTTP/1.1 404 Not Found');
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

if (!isset($lots[$id])) {
    header('HTTP/1.1 404 Not Found');
    exit;
}

$lot = $lots[$id];
$lot['min'] = $lot['min'] ??  $lot['price']+1;

/**
 * Прочитать существующие ставки и определить в них место текущего лота (null в случае отсутствия)
 */
$existing_bids = [];
$my_lots_id = null;
$error = '';

if (isset($_COOKIE['my_bids'])) {
    $existing_bids = json_decode($_COOKIE['my_bids'], true);

    foreach ($existing_bids as $index => $existing_bid) {
        if ($existing_bid['id'] == $id) {
            $my_lots_id = $index;
            break;
        }
    }
}

/**
 * Если ценовое предложение сделано, сформировать ставку и проверить. В случае успеха добавить к существующим ставкам.
 */
if (isset($_POST['cost'])) {
    $new_bid = [
        'cost' => trim($_POST['cost']),
        'time' => time(),
        'id' => $id
    ];

    if (!filter_var($new_bid['cost'], FILTER_VALIDATE_INT, ['options' => ['min_range'=>$lot['min']]])) {
        $error = 'Ставка должна быть не меньше '.$lot['min'];
    } else {
        $existing_bids[] = $new_bid;
        setcookie('my_bids', json_encode($existing_bids), strtotime('+1 year'));
        header('location: /mylots.php');
        exit();
    }
}

echo renderTemplate('templates/top.php', ['html_title' => $lot['title']]);
echo renderTemplate('templates/header.php');

echo renderTemplate('templates/nav.php');
echo renderTemplate(
    'templates/lot.php',
    compact('bets', 'lot', 'lot_time_remaining', 'error', 'existing_bids', 'my_lots_id')
);

echo renderTemplate('templates/footer.php');
