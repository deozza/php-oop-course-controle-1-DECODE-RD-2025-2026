<?php

namespace App\Controllers\Contact;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;

class FetchContactController extends AbstractController {

    public function process(Request $request): Response {
        
        if ($request->getMethod() !== 'GET') {
            return new Response( json_encode(['error' => 'Method Not Allowed']),
                405,
                ['Content-Type' => 'application/json']
            );
        }

        // Récupérer le paramètre depuis l'URI
        $filename = $_GET['file'] ?? null;

        if ($filename === null) {
            return new Response(json_encode(['error' => 'Missing file parameter']),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        $directory = '/app/var/contacts';
        $filePath = $directory . '/' . $filename;

        if (!file_exists($filePath) || !is_file($filePath)) {
            return new Response( json_encode(['error' => 'Contact form not found']),
                404,
                ['Content-Type' => 'application/json']
            );
        }

        $content = file_get_contents($filePath);

        return new Response( $content, 200, ['Content-Type' => 'application/json']);
    }
}
