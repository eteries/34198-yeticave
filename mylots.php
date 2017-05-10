<?php
session_start();

require_once 'functions.php';
require_once 'lots_data.php';

$my_lots = [];

/**
 * Если есть ставки, данные "моих лотов" собираются из информации о ставках и соответствующих им лотов
 */
if (isset($_COOKIE['my_bids'])) {
    $my_bids = json_decode($_COOKIE['my_bids'], true);

    foreach ($my_bids as $my_bid) {
        $id = $my_bid['id'];

        $my_lots[] = [
            'id' => $id,
            'title' => $lots[$id]['title'],
            'img' => $lots[$id]['img'],
            'category' => $lots[$id]['category'],
            'posted' => $my_bid['time'],
            'cost' => $my_bid['cost']
        ];
    }
}

echo renderTemplate('templates/top.php', ['html_title' => 'Мои ставки']);
echo renderTemplate('templates/header.php');

echo renderTemplate('templates/nav.php');
echo renderTemplate('templates/my-lots.php', compact('my_lots'));

echo renderTemplate('templates/footer.php');
