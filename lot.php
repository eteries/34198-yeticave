<?php
session_start();

require_once 'functions.php';
require_once 'connect.php';
require_once 'lots_data.php';

if (!filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) {
    header('HTTP/1.1 404 Not Found');
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

foreach ($lots as $item) {
    if ($item['id'] == $id) {
        $lot = $item;
    }
}

if (!isset($lot)) {
    header('HTTP/1.1 404 Not Found');
    exit;
}

$lot['price'] = $lot['starting_price'];
$lot['remaining_time'] = 'Торги завершены';
$lot_is_active = false;

foreach ($active_lots as $item) {
    if ($item['id'] == $id) {
        $lot['active'] = true;
        $lot['price'] = $lot['max'] ?? $lot['starting_price'];
        $lot['min'] = $lot['bid_step'] ? $lot['price'] + $lot['bid_step'] : $lot['price']+1;
        $lot['remaining_time'] = $lot_time_remaining;
    }
}

$this_user_bids = [];
if (isset($_SESSION['user'])) {
    $this_user_bids = queryDB(
        $link,
        'select max(bid_amount) as user_max 
        FROM bids where bid_author = ? AND bid_lot = ? AND bid_amount IS NOT null GROUP BY id;',
        ['bid_author' => 2, 'bid_lot' => $id]
    );
}

$this_lot_bids = queryDB(
    $link,
    'SELECT bids.bid_amount,
    bids.placement_date, 
    users.username
    FROM bids LEFT JOIN users ON bids.bid_author = users.id 
    where bids.bid_lot = ? 
    GROUP BY bids.id;',
    ['bid_lot' => $id]
);

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
        $sql = 'INSERT into bids (bid_amount, bid_author, bid_lot, placement_date) VALUES (?,?,?, NOW());';
        $values = [
            'bid_amount' => $new_bid['cost'],
            'bid_author' => $_SESSION['user']['id'],
            'bid_lot' => $new_bid['id']
        ];
        insertDataDB($link, $sql, $values);

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
