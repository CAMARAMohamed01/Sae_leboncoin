<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class SireneController extends Controller
{
    public function lookup(Request $request)
    {
        if (!$request->has('siret')) {
            return response()->json([
                'error' => 'Le paramètre SIRET est manquant dans la requête.'
            ], 400);
        }

        $siret = preg_replace('/\D/', '', $request->query('siret'));
        
        if (empty($siret) || strlen($siret) !== 14) {
            return response()->json([
                'error' => 'SIRET invalide ou manquant. Doit contenir 14 chiffres.'
            ], 400);
        }
        $apiUrl = "https://api-adresse.data.gouv.fr/search/?q=siret:{$siret}";

        try {
            $response = Http::get($apiUrl);

            if ($response->clientError() || $response->serverError()) {
                return response()->json([
                    'error' => 'Échec de la connexion à l\'API externe ou erreur du SIRET.'
                ], $response->status() ?: 500);
            }

            $data = $response->json();
            
            if (!empty($data['features'])) {
                $feature = $data['features'][0];
                $properties = $feature['properties'];

                $companyName = $properties['name'] ?? null;
                $addressLabel = $properties['label'] ?? null;
                $postCode = $properties['postcode'] ?? null;
                $city = $properties['city'] ?? null;

                return response()->json([
                    'company_name' => $companyName,
                    'address'      => $addressLabel,
                    'post_code'    => $postCode,
                    'city'         => $city,
                ]);
            }

            return response()->json([
                'error' => 'Aucune information d\'entreprise trouvée pour ce SIRET.'
            ], 404);

        } catch (\Exception $e) {

            \Log::error("Erreur SIRENE lookup pour SIRET {$siret}: " . $e->getMessage());
            
            return response()->json([
                'error' => 'Erreur interne du serveur lors du traitement de la requête.'
            ], 500);
        }
    }
}