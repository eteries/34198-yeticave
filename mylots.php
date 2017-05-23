<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}

require_once 'functions.php';
require_once 'lots_data.php';
require_once 'connect.php';

$this_user_lots = [];
$this_user_id = $_SESSION['user']['id'];

$this_user_lots_sql = <<<SQL
SELECT lots.id, lots.title, lots.picture, bids.placement_date,
       max(bids.bid_amount) as price, categories.title as category
FROM lots LEFT JOIN bids ON lots.id = bids.bid_lot
          JOIN categories ON lots.lot_category = categories.id
WHERE bid_author = ?
GROUP BY bids.id;
SQL;

$this_user_lots = queryDB($link, $this_user_lots_sql, ['author_id' => $this_user_id]);

array_walk($this_user_lots, function(&$lot) {
    $lot['placement_date'] = formatElapsedTime(strtotime($lot['placement_date']));
});

echo renderTemplate('templates/top.php', ['html_title' => 'Мои ставки']);
echo renderTemplate('templates/header.php');

echo renderTemplate('templates/nav.php');
echo renderTemplate('templates/my-lots.php', compact('this_user_lots'));

echo renderTemplate('templates/footer.php', compact('categories'));
