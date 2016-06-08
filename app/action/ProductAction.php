<?php
namespace App\Action;

use App\Entity\Product;
use App\Resource\ProductResource;
use Slim\Http\Request;
use Slim\Http\Response;

class ProductAction
{
    private $productResource;

    public function __construct(ProductResource $productResource)
    {
        $this->productResource = $productResource;
    }

    public function get(Request $request, Response $response, $args)
    {
        $productSlug = isset($args['slug']) ? $args['slug'] : null;
        $products = $this->productResource->get($productSlug);
        return $response->withJson($products);
    }

    public function create(Request $request, Response $response, $args)
    {
        $result = $this->productResource->create($request->getParsedBody());
        return $response->withJson($result);
    }

    public function update(Request $request, Response $response, $args)
    {
        $productSlug = isset($args['slug']) ? $args['slug'] : null;
        $result = $this->productResource->update($productSlug, $request->getParsedBody());
        return $response->withJson($result);
    }

    public function remove(Request $request, Response $response, $args)
    {
        $productSlug = isset($args['slug']) ? $args['slug'] : null;
        $result = $this->productResource->remove($productSlug);
        return $response->withJson($result);
    }
}
