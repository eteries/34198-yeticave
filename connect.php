<?php

/**
 * Создаёт и проверяет соединение
 */
function connectDB()
{
    $con = mysqli_connect('127.0.0.1', 'root', '', 'yeticave');
    if ($con == false) {
        echo ("Ошибка подключения: " . mysqli_connect_error());
        exit();
    } else {
        return $con;
    }
}

$link = connectDB();
