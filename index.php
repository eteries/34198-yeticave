<?php
require_once 'functions.php';
require_once 'lots_data.php';

$lot_time_remaining = getRemainingTime();

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная</title>
    <link href="css/normalize.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<?php
echo renderTemplate('templates/header.php');
echo renderTemplate('templates/main.php', compact('categories', 'lots', 'lot_time_remaining'));
echo renderTemplate('templates/footer.php');
?>

</body>
</html>
