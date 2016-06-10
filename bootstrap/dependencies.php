<?php
// DIC configuration

use App\Resource\ProductResource;
use App\Resource\CategoryResource;
use App\Resource\TagResource;

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
    //$databaseConfig = require __DIR__ . '/../config/database.php';
    return require __DIR__ . '/../bootstrap/doctrine.php';
};

$container['ProductCrudAction'] = function ($c) {
    $productResource = new ProductResource($c->get('doctrine'));
    return new App\Action\CrudAction($productResource);
};
$container['CategoryCrudAction'] = function ($c) {
    $categoryResource = new CategoryResource($c->get('doctrine'));
    return new App\Action\CrudAction($categoryResource);
};
$container['TagCrudAction'] = function ($c) {
    $tagResource = new TagResource($c->get('doctrine'));
    return new App\Action\CrudAction($tagResource);
};
