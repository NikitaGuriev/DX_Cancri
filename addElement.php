<?php
/**
 * Логирование ошибок PHP
 */
ini_set('display_errors', 0);
ini_set('log_errors', 'on');
ini_set('error_log', __DIR__ . '/error.log');

/**
 * Блок подключения модулей
 */
require_once __DIR__ . '/dbConnect.php';

/**
 * Блок определения переменных
 */
$FIO = $_REQUEST['FIOInput'];
$phone = $_REQUEST['phoneInput'];
$whoHasTo = $_REQUEST['whoHasToInput'];

/**
 * Блок алгоритма работы
 */

/**
 * Добавление в БД
 */
$error = false;

if ($mysqli->connect_errno) {
  $error = true;
  file_put_contents(
    'log.log',
    date('d.m.Y H:i:s') . "\n" .
    print_r($mysqli->connect_error, 1) .
    "\n\n",
    FILE_APPEND
  );
  exit();
}

$results = $mysqli->query("INSERT INTO phoneBook (FIO, phone, who_has_to) VALUES ('$FIO', '$phone', '$whoHasTo')");


/** Закрыть соединение */
$mysqli->close();

echo json_encode(['error' => $error]);