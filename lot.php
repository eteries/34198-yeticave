<?php
session_start();

require_once 'functions.php';
require_once 'connect.php';

if (!filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) {
    header('HTTP/1.1 404 Not Found');
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$lots = findLots($link);

foreach ($lots as $item) {
    if ($item['id'] == $id) {
        $lot = $item;
        break;
    }
}

if (!isset($lot)) {
    header('HTTP/1.1 404 Not Found');
    exit;
}

$lot['price'] = $lot['starting_price'];
$lot['remaining_time'] = 'Торги завершены';
$lot_is_active = false;
$categories = findCategories($link);
$active_lots = findActiveLots($link);

foreach ($active_lots as $item) {
    if ($item['id'] == $id) {
        $lot['active'] = true;
        $lot['price'] = $lot['max'] ?? $lot['starting_price'];
        $lot['min'] = $lot['bid_step'] ? $lot['price'] + $lot['bid_step'] : $lot['price']+1;
        $lot['remaining_time'] = getRemainingTime();
    }
}

$this_user_bids = [];
if (isset($_SESSION['user'])) {
    $this_user_bids = findBidsByUserAndLot($link, $_SESSION['user']['id'], $id);
}

$this_lot_bids = findBidsByLot($link, $id);

array_walk($this_lot_bids, function(&$bid) {
    $bid['placement_date'] = formatElapsedTime(strtotime($bid['placement_date']));
});

$error = '';

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
        $values = [
            'bid_amount' => $new_bid['cost'],
            'bid_author' => $_SESSION['user']['id'],
            'bid_lot' => $new_bid['id']
        ];

        addBid($link, $values);

        header('location: /mylots.php');
        exit();
    }
}

echo renderTemplate('templates/top.php', ['html_title' => $lot['title']]);
echo renderTemplate('templates/header.php');

echo renderTemplate('templates/nav.php');
echo renderTemplate(
    'templates/lot.php',
    compact('lot', 'error', 'this_lot_bids', 'this_user_bids')
);

echo renderTemplate('templates/footer.php', compact('categories'));
