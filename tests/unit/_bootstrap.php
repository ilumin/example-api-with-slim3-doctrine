<?php
// Here you can initialize variables that will be available to your tests

error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('Asia/Bangkok');

define('PROJECT_ROOT', __DIR__ . '/../..');
define('VENDOR', PROJECT_ROOT . '/vendor');
define('BOOTSTRAP', PROJECT_ROOT . '/bootstrap');
define('CONFIG', PROJECT_ROOT . '/config');
define('APP', PROJECT_ROOT . '/app');

require VENDOR . '/autoload.php';
