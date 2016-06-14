<?php

use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/products[/{slug}]',     'ProductCrudAction:get');
$app->post('/products',             'ProductCrudAction:create');
$app->put('/products/{slug}',       'ProductCrudAction:update');
$app->delete('/products/{slug}',    'ProductCrudAction:remove');

$app->get('/categories[/{slug}]',   'CategoryCrudAction:get');
$app->post('/categories',           'CategoryCrudAction:create');
$app->put('/categories/{slug}',     'CategoryCrudAction:update');
$app->delete('/categories/{slug}',  'CategoryCrudAction:remove');

$app->get('/tags[/{slug}]',         'TagCrudAction:get');
$app->post('/tags',                 'TagCrudAction:create');
$app->put('/tags/{slug}',           'TagCrudAction:update');
$app->delete('/tags/{slug}',        'TagCrudAction:remove');

$app->post('/variants',             'VariantCrudAction:create');
$app->post('/variants/{slug}',      'VariantCrudAction:update');
$app->delete('/variants/{slug}',    'VariantCrudAction:remove');

$app->get('/cart',                  'CartCrudAction:get');
$app->post('/cart',                 'CartCrudAction:create');
$app->put('/cart/item/{slug}',      'CartCrudAction:update');
$app->delete('/cart/item/{slug}',   'CartCrudAction:remove');

$app->post('/order',                'OrderAction:create');

$app->get('/', function (Request $request, Response $response, array $args) {
    $this->logger->info("Slim-Skeleton '/' route");

    $data = array(
        'hello' => 'world',
    );
    $response = $response->withJson($data);
    return $response;
});
