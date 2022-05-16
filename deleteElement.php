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

$elementId = $_REQUEST['ELEMENT_ID'];

$results = $mysqli->query("DELETE FROM phoneBook WHERE id='$elementId'");

/** Закрыть соединение */
$mysqli->close();

echo json_encode([$error]);