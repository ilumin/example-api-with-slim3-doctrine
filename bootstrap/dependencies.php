<?php
// DIC configuration

use App\Resource\CartResource;
use App\Resource\OrderResource;
use App\Resource\ProductResource;
use App\Resource\CategoryResource;
use App\Resource\TagResource;
use App\Resource\VariantResource;
use Slim\Http\Request;
use Slim\Http\Response;

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
$container['VariantCrudAction'] = function ($c) {
    $variantResource = new VariantResource($c->get('doctrine'));
    return new App\Action\CrudAction($variantResource);
};
$container['CartCrudAction'] = function ($c) {
    $cartResource = new CartResource($c->get('doctrine'));
    return new App\Action\CrudAction($cartResource);
};
$container['OrderAction'] = function ($c) {
    $orderResource = new OrderResource($c->get('doctrine'));
    return new App\Action\CrudAction($orderResource);
};

$container['errorHandler'] = function ($c) {
    return function (Request $request, Response $response, \Exception $e) use ($c) {
        $data['status'] = 'error';
        $data['message'] = $e->getMessage();
        $data['trace'] = $e->getTrace();

        return $response
            ->withStatus(500)
            ->withJson($data);
    };
};

$container['notFoundHandler'] = function ($c) {
    return function (Request $request, Response $response) use ($c) {
        $data['status'] = 'error';
        $data['message'] = 'Request not found';

        return $response
            ->withStatus(404)
            ->withJson($data);
    };
};

$container['notAllowedHandler'] = function ($c) {
    return function (Request $request, Response $response, $methods) use ($c) {
        $data['status'] = 'error';
        $data['message'] = 'Allow only method: ' . implode(', ', $methods);

        return $response
            ->withStatus(405)
            ->withHeader('Allow', implode(', ', $methods))
            ->withJson($data);
    };
};
