<?php

use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/products[/{slug}]', 'App\Action\ProductAction:get');
$app->post('/products', 'App\Action\ProductAction:create');
$app->post('/products/{slug}', 'App\Action\ProductAction:update');
$app->delete('/products/{slug}', 'App\Action\ProductAction:remove');

$app->get('/categories[/{slug}]', 'App\Action\CategoryAction:get');
$app->post('/categories', 'App\Action\CategoryAction:create');
$app->post('/categories/{slug}', 'App\Action\CategoryAction:update');
$app->delete('/categories/{slug}', 'App\Action\CategoryAction:remove');

$app->get('/tags[/{slug}]', 'App\Action\TagAction:get');
$app->post('/tags', 'App\Action\TagAction:create');
$app->post('/tags/{slug}', 'App\Action\TagAction:update');
$app->delete('/tags/{slug}', 'App\Action\TagAction:remove');

$app->get('/product/{id}', function (Request $request, Response $response, array $args) {
    /** @var Container $container */
    $container = $this->getContainer();

    /** @var EntityManager $doctrine */
    $doctrine = $container->get('doctrine');

    /** @var Product $product */
    $product = $doctrine->find('\App\Entity\Product', $args['id']);
    if (!$product) {
        throw new \Exception('product not found');
    }

    return $response->withJson($product);
});

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    $name = $request->getAttribute('name');
    $data = array(
        'hello' => $name,
    );
    $response = $response->withJson($data);
    return $response;
});
