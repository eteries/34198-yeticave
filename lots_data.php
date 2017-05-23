<?php
require_once 'connect.php';
require_once 'functions.php';

$categories = queryDB($link, 'SELECT * from categories;');

$lots_sql = <<<SQL
SELECT lots.*, count(bids.id) as count, 
       max(bids.bid_amount) as max, categories.title as category
FROM lots LEFT JOIN bids ON lots.id = bids.bid_lot
          JOIN categories ON lots.lot_category = categories.id
GROUP BY lots.id
ORDER BY lots.creation_date DESC;
SQL;

$lots = queryDB($link, $lots_sql);

$active_lots_sql = <<<SQL
SELECT lots.id, lots.title, lots.starting_price, lots.picture, count(bids.id) as count, 
       max(bids.bid_amount) as max, categories.title as category
FROM lots LEFT JOIN bids ON lots.id = bids.bid_lot
          JOIN categories ON lots.lot_category = categories.id
WHERE ending_date > NOW()
GROUP BY lots.id
ORDER BY lots.creation_date DESC;
SQL;

$active_lots = queryDB($link, $active_lots_sql);

$lot_time_remaining = getRemainingTime();

