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

    public function fetch(Request $request, Response $response, $args)
    {
        $products = $this->productResource->get();
        return $response->withJson($products);
    }
}
