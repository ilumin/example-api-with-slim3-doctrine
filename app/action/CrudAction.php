<?php

namespace App\Action;


use App\Resource\ResourceInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class CrudAction
{
    public $resource;

    public function __construct(ResourceInterface $resource)
    {
        $this->resource = $resource;
    }

    public function get(Request $request, Response $response, $args)
    {
        $slug = isset($args['slug']) ? $args['slug'] : null;
        $result = $this->resource->get($slug);
        return $response->withJson($result);
    }

    public function create(Request $request, Response $response, $args)
    {
        $result = $this->resource->create($request->getParsedBody());
        return $response->withJson($result);
    }

    public function update(Request $request, Response $response, $args)
    {
        $slug = isset($args['slug']) ? $args['slug'] : null;
        $result = $this->resource->update($slug, $request->getParsedBody());
        return $response->withJson($result);
    }

    public function remove(Request $request, Response $response, $args)
    {
        $slug = isset($args['slug']) ? $args['slug'] : null;
        $result = $this->resource->remove($slug);
        return $response->withJson($result);
    }
}
