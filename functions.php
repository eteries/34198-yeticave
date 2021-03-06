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

/**
 * Находит в БД все категории.
 *
 * @param mysqli $link
 *
 * @return array|bool
 */
function findCategories(mysqli $link) {
    return queryDB($link, 'SELECT * from categories;');
}

/**
 * Находит в БД всю информацию, необходимую для вывода всех лотов.
 *
 * @param mysqli $link
 *
 * @return array|bool
 */
function findLots(mysqli $link) {
    $sql = <<<SQL
SELECT lots.*, count(bids.id) as count, 
       max(bids.bid_amount) as max, categories.title as category
FROM lots LEFT JOIN bids ON lots.id = bids.bid_lot
          JOIN categories ON lots.lot_category = categories.id
GROUP BY lots.id
ORDER BY lots.creation_date DESC;
SQL;

    return queryDB($link, $sql);
}

/**
 * Находит в БД всю информацию, необходимую для вывода только открытых лотов.
 *
 * @param mysqli $link
 *
 * @return array|bool
 */
function findActiveLots(mysqli $link) {
    $sql = <<<SQL
SELECT lots.id, lots.title, lots.starting_price, lots.picture, count(bids.id) as count, 
       max(bids.bid_amount) as max, categories.title as category
FROM lots LEFT JOIN bids ON lots.id = bids.bid_lot
          JOIN categories ON lots.lot_category = categories.id
WHERE ending_date > NOW()
GROUP BY lots.id
ORDER BY lots.creation_date DESC;
SQL;

    return queryDB($link, $sql);
}

/**
 * Находит в БД пользователя по адресу эл. почты.
 *
 * @param mysqli $link
 * @param string $email
 *
 * @return array|bool
 */
function findUserByEmail(mysqli $link, string $email) {
    return queryDB($link, 'SELECT * FROM users WHERE email = ?', ['email' => $email]);
}

/**
 * Находит в БД ставки для указанного лота.
 *
 * @param mysqli $link
 * @param int $lot_id
 *
 * @return array|bool
 */
function findBidsByLot(mysqli $link, int $lot_id) {
    return queryDB(
        $link,
        'SELECT bids.bid_amount,
    bids.placement_date, 
    users.username
    FROM bids LEFT JOIN users ON bids.bid_author = users.id 
    where bids.bid_lot = ? 
    GROUP BY bids.id;',
        ['bid_lot' => $lot_id]
    );
}

/**
 * Добавляет в БД нового пользователя.
 *
 * @param mysqli $link
 * @param array $data
 *
 * @return bool
 */
function addUser(mysqli $link, array $data) {
    $sql = <<<SQL
INSERT into users (
        email, 
        password, 
        username, 
        contact_info, 
        avatar, 
        reg_time) 
        values (?,?,?,?,?,NOW());
SQL;

    $id = insertDataDB($link, $sql, $data);

    if (!$id) {
        return false;
    }

    return true;
}

/**
 * Добавляет в БД новую ставку.
 *
 * @param mysqli $link
 * @param array $data
 *
 * @return bool
 */
function addBid(mysqli $link, array $data) {
    $sql = 'INSERT into bids (bid_amount, bid_author, bid_lot, placement_date) VALUES (?,?,?, NOW());';

    $id = insertDataDB($link, $sql, $data);

    if (!$id) {
        return false;
    }

    return true;
}

/**
 * Добавляет в БД новый лот и возвращает идентификатор в случае успеха.
 *
 * @param mysqli $link
 * @param array $data
 *
 * @return bool|int
 */
function addLot(mysqli $link, array $data) {
    $sql = <<<SQL
INSERT into lots(
        title, 
        lot_category, 
        starting_price,
        bid_step, 
        description, 
        picture,
        ending_date,
        author_id,
        creation_date) 
        values (?,?,?,?,?,?,?,?,NOW());
SQL;

    $id = insertDataDB($link, $sql, $data);

    if (!$id) {
        return false;
    }

    return $id;
}


/**
 * Находит лоты, в торгах которых принимал участие пользователь с данным id.
 *
 * @param mysqli $link
 * @param int $user_id
 *
 * @return array|bool
 */
function findLotsWithUserBids(mysqli $link, int $user_id) {
    $sql = <<<SQL
SELECT lots.id, lots.title, lots.picture, bids.placement_date,
       max(bids.bid_amount) as price, categories.title as category
FROM lots LEFT JOIN bids ON lots.id = bids.bid_lot
          JOIN categories ON lots.lot_category = categories.id
WHERE bid_author = ?
GROUP BY bids.id;
SQL;

    return queryDB($link, $sql, ['author_id' => $user_id]);
}

/**
 * Находит ставки указанного пользователя, сделанные для указанного лота.
 *
 * @param mysqli $link
 * @param int $user_id
 * @param int $lot_id
 *
 * @return array|bool
 */
function findBidsByUserAndLot(mysqli $link, int $user_id, int $lot_id) {
    return queryDB(
        $link,
        'select max(bid_amount) as user_max 
        FROM bids where bid_author = ? AND bid_lot = ? AND bid_amount IS NOT null GROUP BY id;',
        ['bid_author' => $user_id, 'bid_lot' => $lot_id]
    );
}

