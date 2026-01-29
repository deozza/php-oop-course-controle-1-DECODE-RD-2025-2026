<?php

use App\Lib\Http\Request;
use App\Lib\Http\Router;
use App\Lib\Http\Response;

require_once __DIR__ . '/../vendor/autoload.php';

function sendResponse(Response $response): void {
    http_response_code($response->getStatus());
    foreach ($response->getHeaders() as $name => $values) {
        if (is_array($values)) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value));
            }
            continue;
        }
        header(sprintf('%s: %s', $name, $values));
    }
    echo $response->getContent();
}


try {
    $request = new Request();
    $response = Router::route($request);

    sendResponse($response);
    exit();
} catch(\RuntimeException $e) {
    $response = new Response(
        json_encode(['error' => $e->getMessage()]),
        400,
        ['Content-Type' => 'application/json']
    );
    sendResponse($response);
}catch(\Exception $e) {
    echo $e->getMessage();
}

