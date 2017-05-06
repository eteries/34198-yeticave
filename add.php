<?php
require_once 'functions.php';
require_once 'lots_data.php';

$lot = [];
$invalid_controls = [];

/**
 * Сформировать массив не валидных полей, если таковые найдутся.
 */
foreach ($_POST as $name => $value) {
    $value = trim($value);

    if (empty($value)) {
        $invalid_controls[$name] = 'Заполните это поле';
    }

    if ($name == 'category' && $value == 'Выберите категорию') {
        $invalid_controls[$name] = 'Заполните это поле';
    }

    if (($name == 'lot-rate' || $name == 'lot-step') && !filter_var($value, FILTER_VALIDATE_INT)) {
        $invalid_controls[$name] = 'Введите число';
    }
}

/**
 * Загрузить, проверить изображение и в случае успеха
 */
if (isset($_FILES['photo2']) && $_FILES['photo2']['error'] == 0) {
    $original_name = $_FILES['photo2']['name'];
    $temp_name = $_FILES['photo2']['tmp_name'];
    $dir = 'img/';

    $mimes = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif'
    ];

    $f_info = new finfo;
    $file_info = $f_info->file($temp_name, FILEINFO_MIME_TYPE);

    $check = array_search($file_info, $mimes, true);
    $extension = pathinfo($original_name, PATHINFO_EXTENSION);
    $allowed = array_keys($mimes);

    // Проверка  соответствия MIME и заявленного расширения разрешенным, загрузка с новым именем в случае успеха
    if ($check !== false && in_array($extension, $allowed)) {
        $file_path = $dir.time().'.'.$check;
        $uploaded = move_uploaded_file($temp_name, $file_path);
    }

    if (isset($uploaded) && $uploaded === true) {
        $img = $file_path;
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
    $lot['img'] = $img ?? 'img/logo.svg';
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
        echo renderTemplate('templates/form.php', compact('invalid_controls', 'categories'));
    } else {
        echo renderTemplate('templates/lot.php', compact('bets', 'lot', 'lot_time_remaining'));
    }
    ?>

</main>

<?= renderTemplate('templates/footer.php'); ?>

</body>
</html>
