<?php

namespace App;

use Slim\Http\Response;

class AbstractCrud
{
    public function responseSuccess(Response $response, $result)
    {
        return $response->withJson([
            'status' => 'success',
            'data'   => $result,
        ]);
    }
}
