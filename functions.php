<?php
require_once 'mysql_helper.php';

date_default_timezone_set('Europe/Moscow');

/**
 * Формирует строку для вставки шаблона и соответствующих ему данных
 *
 * @param string $filename
 * @param array $data
 *
 * @return string
 */
function renderTemplate(string $filename, array $data = []) : string
{
    $__filename = $filename;

    if (!file_exists($filename)) {
        return '';
    }

    array_walk_recursive($data, function (&$item) {
        $item = htmlspecialchars($item);
    });

    extract($data);

    ob_start();

    include $__filename;

    return ob_get_clean();
}

/**
 * Форматирует время, в зависимости от прошедшего к текущему моменту временному интервалу.
 *
 * @param int $timestamp
 *
 * @return string|bool Отформатированное время или false, в случае ошибки.
 */
function formatElapsedTime(int $timestamp)
{
    $elapsed_time = time() - $timestamp;

    if ($timestamp < 0 || $elapsed_time < 0) {
        return false;
    }

    $hours = round($elapsed_time / 3600);
    $minutes = round(($elapsed_time % 3600) / 60);

    if ($elapsed_time <  60) {
        return 'Только что';
    }

    if ($hours < 1) {
        return sprintf('%d минут%s назад', $minutes, getDeclension($minutes, 'у', 'ы', ''));
    }

    if ($hours < 24) {
        return sprintf('%d час%s назад', $hours, getDeclension($hours, '', 'а', 'ов'));
    }

    return date('d.m.y в H:i', $timestamp);
}

/**
 * Получает корректное склонение существительных после числительных.
 *
 * @param int $number
 * @param string $case1
 * @param string $case2
 * @param string $case5
 *
 * @return string
 */
function getDeclension(int $number, string $case1, string $case2, string $case5) : string
{
    $number = abs($number);

    $number %= 100;
    if ($number >= 5 && $number <= 20) {
        return $case5;
    }

    $number %= 10;
    if ($number == 1) {
        return $case1;
    }

    if ($number >= 2 && $number <= 4) {
        return $case2;
    }

    return $case5;
};

/**
 * Возвращает время до полуночи (по Москве) в формате ЧЧ:ММ.
 *
 * @return string
 */
function getRemainingTime()
{
    $tomorrow = strtotime('tomorrow midnight');
    $now = time();

    // расчёт оставшегося времени:
    $remaining_minutes_total = ($tomorrow - $now) / 60;
    $remaining_hours = floor($remaining_minutes_total / 60);
    $remaining_minutes = $remaining_minutes_total % 60;

    // двухзначный формат:
    $remaining_hours = ($remaining_hours < 10) ? '0' . $remaining_hours : $remaining_hours;
    $remaining_minutes = ($remaining_minutes < 10) ? '0' . $remaining_minutes : $remaining_minutes;

    return "$remaining_hours:$remaining_minutes";
}

/**
 * Делает запрос к БД на основе подготовленного шаблона и возвращает результат.
 *
 * @param mysqli $link
 * @param string $sql
 * @param array $values
 *
 * @return array|bool
 */
function queryDB(mysqli $link, string $sql, array $values = [])
{
    $data = [];
    $stmt = db_get_prepare_stmt($link, $sql, $values);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        return $data;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    mysqli_free_result($result);
    return $data;
}

/**
 * Вставляет переданные данные в БД с помощью готового SQL запроса.
 *
 * @param mysqli $link
 * @param string $sql
 * @param array $values
 *
 * @return bool|int
 */
function insertDataDB(mysqli $link, string $sql, array $values = [])
{
    if (!$stmt = db_get_prepare_stmt($link, $sql, $values)) {
        return false;
    }

    mysqli_stmt_execute($stmt);

    $last_id = mysqli_insert_id($link);

    if ($last_id == 0) {
        return false;
    }


    return $last_id;
}

/**
 * Подготавливает SQL запрос и данные, выполняет обновление записей в БД.
 *
 * @param mysqli $link
 * @param string $table_name
 * @param array $data
 * @param array $conditions
 *
 * @return bool|int
 */
function updateDataDB(mysqli $link, string $table_name, array $data, array $conditions)
{
    $set = [];
    $where = [];
    $values = [];
    $sql = 'UPDATE '.$table_name;

    // Обработка данных и их значений
    foreach ($data as $name => $value) {
        $set[] = $name.' = ?';
        $values[] = $value;
    }
    $sql .= ' SET '.implode(', ', $set);

    // Обработка условий и их значений
    foreach ($conditions as $name => $value) {
        $where[] = $name.' = ?';
        $values[] = $value;
    }
    if (!empty($where)) {
        $sql .= ' WHERE '.implode(', ', $where);
    }

    // Обновление базы
    if (!$stmt = db_get_prepare_stmt($link, $sql, $values)) {
        return false;
    }

    mysqli_stmt_execute($stmt);

    $updated_rows = mysqli_affected_rows($link);
    
    return $updated_rows;
}

/**
 * Принимает изображение из формы и в случае валидации записывает постоянный путь загруженному изображению.
 *
 * @param string $filename
 *
 * @return string
 */
function verifyAndUploadImage(string $filename) : string
{
    $img = '';

    if (isset($_FILES[$filename]) && $_FILES[$filename]['error'] == 0) {
        $original_name = $_FILES[$filename]['name'];
        $temp_name = $_FILES[$filename]['tmp_name'];
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
            $img =  $file_path;
        }
    }
    return $img;
}

