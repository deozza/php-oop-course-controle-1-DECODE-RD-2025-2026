<?php

namespace App\Controllers;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;

class DeleteContactController extends AbstractController
{
    public function process(Request $request): Response
    {
        // Vérifier que la méthode est DELETE
        if ($request->checkMethod('DELETE') === false) {
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

        // Supprimer le fichier
        unlink($filepath);
        
        // Retourner 204 No Content
        return new Response('', 204, []);
    }
}
