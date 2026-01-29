<?php
namespace App\Controllers;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;

class ContactController extends AbstractController{
    public function process(Request $request): Response{
        $body = $request->getBody();

        // check if the body is json (transferred as array)
        if (!is_array($body)) {
            return new Response('Invalid JSON body', 400, []);
        }

        // check if body has right properties and missing properties, otherwise 400
        $allowed = ['email', 'subject', 'message'];

        $extraKeys = array_diff(array_keys($body), $allowed);
        if (!empty($extraKeys)) {
            return new Response("Unexpected properties : {array_values($extraKeys) } \n Expected properties: {array_values($allowed)}", 400, []);
        }

        foreach ($allowed as $key) {
            if (!array_key_exists($key, $body)) {
                return Response("Missing property: {$key} \n", 400, []);
            }
        }

        return new Response('hello world', 400, []);





    }
}