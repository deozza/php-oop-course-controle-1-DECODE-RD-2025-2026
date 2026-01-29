<?php

use App\Lib\Http\Request;
use App\Lib\Http\Router;

require_once __DIR__ . '/../vendor/autoload.php';

try {
    
    $request = new Request();
    $response = Router::route($request);

    // Émettre les headers fournis par l'objet Response
    foreach ($response->getHeaders() as $name => $value) {
        header("$name: $value", true);
    }

    // Émettre le code HTTP
    http_response_code($response->getStatus());

    // Corps de la réponse
    echo $response->getContent();
    exit();
} catch(\Exception $e) {
    echo $e->getMessage();
}
