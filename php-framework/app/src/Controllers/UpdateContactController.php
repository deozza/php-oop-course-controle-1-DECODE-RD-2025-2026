<?php

namespace App\Controllers;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;

class UpdateContactController extends AbstractController
{
    public function process(Request $request): Response
    {
        // Vérifier que la méthode est PATCH
        if ($request->checkMethod('PATCH') === false) {
            return new Response(json_encode(['error' => 'Method Not Allowed']), 405, ['Content-Type' => 'application/json']);
        }

        // Récupérer le filename depuis le path parameter
        $filename = $request->getPathParam('filename');

        // Vérifier que le paramètre filename est présent
        if (empty($filename)) {
            return new Response(json_encode(['error' => 'Filename parameter is required']), 400, ['Content-Type' => 'application/json']);
        }

        // Vérifier que la requête n'est pas vide
        $bodyRaw = $request->getBody();
        if (empty($bodyRaw)) {
            return new Response(json_encode(['error' => 'Empty request body']), 400, ['Content-Type' => 'application/json']);
        }

        // Vérifier le format JSON
        if (!Request::isJson($bodyRaw)) {
            return new Response(json_encode(['error' => 'Invalid JSON format']), 400, ['Content-Type' => 'application/json']);
        }

        // Décoder le JSON
        $body = json_decode($bodyRaw, true);

        // Champs autorisés pour la mise à jour
        $allowedFields = ['email', 'subject', 'message'];

        // Vérifier que seuls les champs autorisés sont présents
        $providedFields = array_keys($body);
        $invalidFields = array_diff($providedFields, $allowedFields);
        if (count($invalidFields) > 0) {
            return new Response(json_encode(['error' => 'Invalid fields in request body']), 400, ['Content-Type' => 'application/json']);
        }

        // Vérifier qu'au moins un champ est fourni
        if (count($providedFields) === 0) {
            return new Response(json_encode(['error' => 'At least one field must be provided']), 400, ['Content-Type' => 'application/json']);
        }

        // Chemin du répertoire des contacts
        $dir = __DIR__ . '/../../var/contacts/';
        $filepath = $dir . $filename;

        // Vérifier si le fichier existe
        if (!file_exists($filepath)) {
            return new Response(json_encode(['error' => 'Contact not found']), 404, ['Content-Type' => 'application/json']);
        }

        // Lire le contenu du fichier
        $content = file_get_contents($filepath);
        $contactData = json_decode($content, true);

        if (!$contactData) {
            return new Response(json_encode(['error' => 'Invalid contact data']), 500, ['Content-Type' => 'application/json']);
        }

        // Sauvegarder l'ancien email pour gérer le renommage du fichier
        $oldEmail = $contactData['email'];

        // Mettre à jour les champs fournis
        foreach ($allowedFields as $field) {
            if (isset($body[$field])) {
                $contactData[$field] = $body[$field];
            }
        }

        // Mettre à jour la date de dernière modification
        $contactData['LastUpdateDate'] = time();

        // Sauvegarder les modifications
        file_put_contents($filepath, json_encode($contactData, JSON_PRETTY_PRINT));

        // Si l'email a changé, renommer le fichier
        if (isset($body['email']) && $body['email'] !== $oldEmail) {
            $newFilename = $contactData['CreationDate'] . '_' . $contactData['email'] . '.json';
            $newFilepath = $dir . $newFilename;
            rename($filepath, $newFilepath);
        }

        // Retourner le contact mis à jour
        $updatedContact = [
            'email' => $contactData['email'],
            'subject' => $contactData['subject'],
            'message' => $contactData['message'],
            'dateOfCreation' => $contactData['CreationDate'],
            'dateOfLastUpdate' => $contactData['LastUpdateDate']
        ];

        return new Response(json_encode($updatedContact), 200, ['Content-Type' => 'application/json']);
    }
}
