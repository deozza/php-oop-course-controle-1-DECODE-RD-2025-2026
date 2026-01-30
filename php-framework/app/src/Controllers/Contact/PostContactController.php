<?php

namespace App\Controllers\Contact;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;

class PostContactController extends AbstractController {

    public function process(Request $request): Response {

        $rawBody = file_get_contents('php://input');
        $body = json_decode($rawBody, true);

        if (!is_array($body)) {
            return new Response(
                json_encode(['error' => 'Invalid JSON body']), 400, ['Content-Type' => 'application/json']
            );
        }

        $allowedFields = ['email', 'subject', 'message'];

        $extraFields = array_diff(array_keys($body), $allowedFields);
        if (!empty($extraFields)) {
            return new Response(
                json_encode(['error' => 'Only email, subject and message properties are allowed']),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        foreach ($allowedFields as $field) {
            if (!isset($body[$field])) {
                return new Response(
                    json_encode(['error' => "Missing field: $field"]), 400, ['Content-Type' => 'application/json']
                );
            }
        }

        if (!filter_var($body['email'], FILTER_VALIDATE_EMAIL)) {
            return new Response(
                json_encode(['error' => 'Invalid email format']), 400, ['Content-Type' => 'application/json']
            );
        }

        // Timestamp et date formatée
        $timestamp = time();
        $formattedDate = date('Y-m-d_H-i-s', $timestamp);

        $email = $body['email'];

        // Nom fichier demandé
        $storageFilename = "/app/var/contacts/{$timestamp}_{$email}.json";

        $fileContent = [
            'email' => $body['email'],
            'subject' => $body['subject'],
            'message' => $body['message'],
            'dateOfCreation' => $timestamp,
            'dateOfLastUpdate' => $timestamp,
        ];

        // Création dossier si nécessaire
        if (!is_dir('/app/var/contacts')) {
            mkdir('/app/var/contacts', 0777, true);
        }

        file_put_contents($storageFilename, json_encode($fileContent));

        $responseFilename = "{$formattedDate}_{$email}.json";
        return new Response( json_encode(['file' => $responseFilename]), 201, ['Content-Type' => 'application/json']);

    }
}
