<?php
namespace App\Action;

use Doctrine\ORM\EntityManager;
use Slim\Http\Request;
use Slim\Http\Response;

class ProductAction
{
    private $doctrine;

    public function __construct(EntityManager $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function fetch(Request $request, Response $response, $args)
    {
        $products = $this->doctrine->getRepository('App\Entity\Product')->findAll();
        return $response->withJson($products);
    }
}
