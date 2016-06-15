<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $file = __DIR__ . $_SERVER['REQUEST_URI'];
    if (is_file($file)) {
        return false;
    }
}

define('PROJECT_ROOT', __DIR__ . '/..');
define('VENDOR', PROJECT_ROOT . '/vendor');
define('BOOTSTRAP', PROJECT_ROOT . '/bootstrap');
define('CONFIG', PROJECT_ROOT . '/config');
define('APP', PROJECT_ROOT . '/app');

require VENDOR . '/autoload.php';

session_start();

$settings = require BOOTSTRAP . '/settings.php';
$app = new \Slim\App($settings);

require BOOTSTRAP . '/dependencies.php';
require BOOTSTRAP . '/middleware.php';
require BOOTSTRAP . '/routes.php';

// Run app
$app->run();
