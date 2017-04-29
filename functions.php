<?php
/**
 * Получает шаблон и его данные
 *
 * @param string $filename
 * @param array $data
 *
 * @return string
 */
function get_template(string $filename, array $data) : string
{
    if (!file_exists($filename)) {
        return '';
    }

    array_walk_recursive($data, function (&$item) {
        $item = htmlspecialchars($item);
    });

    extract($data);

    ob_start();

    include $filename;

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
