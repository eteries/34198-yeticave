<?php
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

<?= renderTemplate('templates/header.php'); ?>
<main>
    <?= renderTemplate('templates/nav.php'); ?>
    <?= renderTemplate('templates/lot.php', compact('bets', 'lot', 'lot_time_remaining')); ?>
</main>
<?=  renderTemplate('templates/footer.php'); ?>
?>

</body>
</html>
