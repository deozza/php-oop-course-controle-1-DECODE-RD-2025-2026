<?php

namespace App\Controllers;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;

class GetContactController extends AbstractController
{
    public function process(Request $request): Response
    {
        // Vérifier que la méthode est GET
        if ($request->checkMethod('GET') === false) {
            return new Response(json_encode(['error' => 'Method Not Allowed']), 405, ['Content-Type' => 'application/json']);
        }

        // Récupérer le filename depuis le path parameter
        $filename = $request->getPathParam('filename');

        // Vérifier que le paramètre filename est présent
        if (empty($filename)) {
            return new Response(json_encode(['error' => 'Filename parameter is required']), 400, ['Content-Type' => 'application/json']);
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

        // Retourner le contact avec le format demandé
        $contact = [
            'email' => $contactData['email'],
            'subject' => $contactData['subject'],
            'message' => $contactData['message'],
            'dateOfCreation' => $contactData['CreationDate'],
            'dateOfLastUpdate' => $contactData['LastUpdateDate']
        ];
        
        return new Response(json_encode($contact), 200, ['Content-Type' => 'application/json']);
    }
}
