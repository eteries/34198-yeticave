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

echo renderTemplate('templates/top.php', ['html_title' => $lot['title']]);
echo renderTemplate('templates/header.php');

echo renderTemplate('templates/nav.php');
echo renderTemplate('templates/lot.php', compact('bets', 'lot', 'lot_time_remaining'));

echo renderTemplate('templates/footer.php');
