<?php
session_start();

require_once 'functions.php';
require_once 'lots_data.php';

$lot_time_remaining = getRemainingTime();

echo renderTemplate('templates/top.php', ['html_title' => 'Главная']);
echo renderTemplate('templates/header.php');
echo renderTemplate('templates/main.php', compact('categories', 'active_lots', 'lot_time_remaining'));
echo renderTemplate('templates/footer.php', compact('categories'));
