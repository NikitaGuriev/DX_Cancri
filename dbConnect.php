<?php
const SERVER = '';
const DB_NAME = '';
const DB_USERNAME = '';
const DB_PASSWORD = '';

$mysqli = new mysqli(
  SERVER,
  DB_USERNAME,
  DB_PASSWORD,
  DB_NAME
);
$mysqli->set_charset("utf8");