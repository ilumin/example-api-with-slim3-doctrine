<?php
namespace App\Action;

use App\Resource\TagResource;
use Slim\Http\Request;
use Slim\Http\Response;

class TagAction
{
    private $tagResource;

    public function __construct(TagResource $tagResource)
    {
        $this->tagResource = $tagResource;
    }

    public function get(Request $request, Response $response, $args)
    {
        $tagSlug = isset($args['slug']) ? $args['slug'] : null;
        $tags = $this->tagResource->get($tagSlug);
        return $response->withJson($tags);
    }

    public function create(Request $request, Response $response, $args)
    {
        $result = $this->tagResource->create($request->getParsedBody());
        return $response->withJson($result);
    }

    public function update(Request $request, Response $response, $args)
    {
        $tagSlug = isset($args['slug']) ? $args['slug'] : null;
        $result = $this->tagResource->update($tagSlug, $request->getParsedBody());
        return $response->withJson($result);
    }

    public function remove(Request $request, Response $response, $args)
    {
        $tagSlug = isset($args['slug']) ? $args['slug'] : null;
        $result = $this->tagResource->remove($tagSlug);
        return $response->withJson($result);
    }
}
