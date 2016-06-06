<?php
// DIC configuration

use App\Resource\ProductResource;

$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

$container['doctrine'] = function ($c) {
    $databaseConfig = require __DIR__ . '/../config/database.php';
    return App\Core\Doctrine::bootstrap($databaseConfig);
};

$container['App\Action\ProductAction'] = function ($c) {
    $productResource = new ProductResource($c->get('doctrine'));
    return new App\Action\ProductAction($productResource);
};
