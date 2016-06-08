<?php
namespace App\Action;

use App\Resource\CategoryResource;
use Slim\Http\Request;
use Slim\Http\Response;

class CategoryAction
{
    public function __construct(CategoryResource $categoryResource)
    {
        $this->categoryResource = $categoryResource;
    }

    public function get(Request $request, Response $response, $args)
    {
        $categorySlug = isset($args['slug']) ? $args['slug'] : null;
        $category = $this->categoryResource->get($categorySlug);
        return $response->withJson($category);
    }
}
