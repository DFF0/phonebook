<?php

ini_set('display_errors', 1);

session_start();

define('APP_PATH', __DIR__ . '/application/');

require_once(APP_PATH . 'init.php');

$app = new App;
