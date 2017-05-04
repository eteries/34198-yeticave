<?php
require_once 'functions.php';
require_once 'lots_data.php';

$lot = [];
$invalid_controls = [];

/**
 * Сформировать массив не валидных полей, если такоевые найдутся.
 */
foreach ($_POST as $name => $value) {
    if (!trim($value)) {
        $invalid_controls[$name] = 'Заполните это поле';
    }
    if ($name == 'category' && $value == 'Выберите категорию') {
        $invalid_controls[$name] = 'Заполните это поле';
    }

    if (($name == 'lot-rate' || $name == 'lot-rate') && !is_int((int) $value)) {
        $invalid_controls[$name] = 'Введите число';
    }
}

/**
 * Если данные введены без ошибок, сформировать новый лот.
 */
if (empty($invalid_controls) && !empty($_POST)) {
    $lot['title'] = trim($_POST['lot-name'] ?? '');
    $lot['category'] = $_POST['category'] ?? '';
    $lot['price'] = trim($_POST['lot-rate'] ?? '');
    $lot['min'] = (int) trim($_POST['lot-rate'] ?? '') + (int) $lot['price'];
    $lot['description'] = trim($_POST['message'] ?? '');
}

/**
 * Дополнить данные загруженной фотографией.
 */
if (isset($_FILES['photo2'])) {
    $original_name = $_FILES['photo2']['name'];
    $temp_name = $_FILES['photo2']['tmp_name'];
    $dir = 'img/';
    $destination = $dir.$original_name;

    if (move_uploaded_file($temp_name, $destination)) {
        $lot['img'] = $destination;
    }
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавление лота</title>
    <link href="../css/normalize.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<?= renderTemplate('templates/header.php'); ?>

<main>
    <?php
    echo renderTemplate('templates/nav.php');

    if (empty($lot)) {
        echo renderTemplate('templates/form.php', compact('invalid_controls'));
    } else {
        echo renderTemplate('templates/lot.php', compact('bets', 'lot', 'lot_time_remaining'));
    }
    ?>

</main>

<?= renderTemplate('templates/footer.php'); ?>

</body>
</html>
