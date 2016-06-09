<?php
namespace App\Action;

use App\Resource\CategoryResource;
use Slim\Http\Request;
use Slim\Http\Response;

class CategoryAction
{
    public $categoryResource;

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

    public function create(Request $request, Response $response, $args)
    {
        $result = $this->categoryResource->create($request->getParsedBody());
        return $response->withJson($result);
    }

    public function update(Request $request, Response $response, $args)
    {
        $categorySlug = isset($args['slug']) ? $args['slug'] : null;
        $result = $this->categoryResource->update($categorySlug, $request->getParsedBody());
        return $response->withJson($result);
    }

    public function remove(Request $request, Response $response, $args)
    {
        $categorySlug = isset($args['slug']) ? $args['slug'] : null;
        $result = $this->categoryResource->remove($categorySlug);
        return $response->withJson($result);
    }
}
