<?php
// Routes

$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    $name = $request->getAttribute('name');
    $data = array(
        'hello' => $name,
    );
    $response = $response->withJson($data);
    return $response;
});
