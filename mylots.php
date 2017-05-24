<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}

require_once 'functions.php';
require_once 'connect.php';

$this_user_lots = [];
$this_user_id = $_SESSION['user']['id'];
$categories = findCategories($link);

$this_user_lots = findLotsWithUserBids($link, $this_user_id);
array_walk($this_user_lots, function(&$lot) {
    $lot['placement_date'] = formatElapsedTime(strtotime($lot['placement_date']));
});

echo renderTemplate('templates/top.php', ['html_title' => 'Мои ставки']);
echo renderTemplate('templates/header.php');

echo renderTemplate('templates/nav.php');
echo renderTemplate('templates/my-lots.php', compact('this_user_lots'));

echo renderTemplate('templates/footer.php', compact('categories'));
