<?php

namespace App\Controllers\Contact;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;

class GetContactController extends AbstractController {

    public function process(Request $request): Response
    {
       
        if ($request->getMethod() !== 'GET') {
            return new Response( json_encode(['error' => 'Method Not Allowed']),
                405,
                ['Content-Type' => 'application/json']
            );
        }

        $directory = '/app/var/contacts';

        if (!is_dir($directory)) {
            return new Response(
                json_encode([]), 200, ['Content-Type' => 'application/json']
            );
        }

        $files = scandir($directory);
        $contacts = [];

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $filePath = $directory . '/' . $file;

            if (is_file($filePath)) {
                $content = file_get_contents($filePath);
                $decoded = json_decode($content, true);

                if (is_array($decoded)) {
                    $contacts[] = $decoded;
                }
            }
        }

        return new Response( json_encode($contacts, JSON_PRETTY_PRINT), 200, ['Content-Type' => 'application/json']);
    }
}
