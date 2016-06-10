<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;

class ApiMiddleware
{
    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        /** @var ResponseInterface $response */
        $response = $next($request, $response);
        $newResponse = $response->withHeader('content-type', 'application/json');
        return $newResponse;
    }
}
