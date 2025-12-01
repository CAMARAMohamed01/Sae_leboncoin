<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entreprise;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log; 

class EntrepriseController extends Controller
{
    // RAPPEL : Cette méthode récupère le jeton d'accès OAuth2.
    // Les clés client_id et client_secret doivent être dans votre fichier .env et config/services.php
    private function getToken()
    {
        try {
            $response = Http::asForm()->post('https://api.insee.fr/token', [
                'grant_type' => 'client_credentials',
                'client_id' => config('services.sirene.key'), 
                'client_secret' => config('services.sirene.secret'),
            ]);

            if ($response->failed()) {
                 Log::error("Échec de la requête de token INSEE. Statut: " . $response->status() . " | Corps: " . $response->body());
                 return null;
            }

            return $response->json('access_token');
        } catch (\Exception $e) {
            Log::error("Erreur lors de la récupération du token INSEE: " . $e->getMessage());
            return null;
        }
    }


    // Méthode unique pour interroger l'API Sirene (utilisée par l'Ajax)
    public function getEntrepriseInfo($siret)
    {
        // 1. OBTENIR LE JETON D'ACCÈS
        $token = $this->getToken(); 

        if (!$token) {
            // L'échec ici signifie un problème d'authentification OAuth2
            return null;
        }

        // 2. APPEL À L'API SIRENE AVEC LE JETON
        $response = Http::withToken($token)
            ->withHeaders([
                'Accept' => 'application/json;charset=utf-8', 
            ])
            ->get("https://api.insee.fr/api-sirene/v3.11/siret/$siret");

        if ($response->failed()) {
            // Loggez la réponse complète de l'INSEE si la requête échoue
            Log::warning("API SIRET échouée pour: $siret. Statut HTTP: " . $response->status() . " | Corps: " . $response->body());
            return null;
        }

        $data = $response->json();
        // Le SIRET peut être trouvé, mais l'établissement non spécifié dans la réponse
        if (empty($data) || !isset($data['etablissement'])) {
            Log::info("Aucune information d'établissement trouvée pour le SIRET $siret.");
            return null;
        }

        return $data;
    }


    // MÉTHODE POUR LA VÉRIFICATION AJAX (POST /entreprise/verifier-siret)
    public function getEntrepriseInfoAjax(Request $request)
    {
        $siret = $request->input('siret');
        
        // Validation simple
        if (strlen($siret) !== 14 || !ctype_digit($siret)) {
            return Response::json([
                'success' => false, 
                'message' => 'Le format du SIRET est incorrect (doit être 14 chiffres).'
            ], 400);
        }

        // Tenter d'obtenir les infos via l'API
        $data = $this->getEntrepriseInfo($siret);

        if (!$data) {
            // Échec dans getEntrepriseInfo (auth échouée OU SIRET non trouvé)
            // Note: Le message guide l'utilisateur à vérifier le problème le plus probable (AUTH/SIRET)
            return Response::json([
                'success' => false, 
                'message' => 'Vérification impossible : SIRET non trouvé, ou problème d\'accès à l\'API INSEE (vérifiez les clés OAuth2).'
            ], 404);
        }

        // Succès : Préparation des données pour le front-end
        $etab = $data['etablissement'];
        $entreprise = [
            'siret'       => $siret,
            'nom'         => $etab['uniteLegale']['denominationUniteLegale'] ?? 
                             $etab['uniteLegale']['sigleUniteLegale'] ?? 'N/A', 
            'adresse'     => ($etab['adresseEtablissement']['numeroVoieEtablissement'] ?? '') . ' ' .
                             ($etab['adresseEtablissement']['libelleVoieEtablissement'] ?? ''),
            'ville'       => $etab['adresseEtablissement']['libelleCommuneEtablissement'] ?? 'N/A',
            'code_postal' => $etab['adresseEtablissement']['codePostalEtablissement'] ?? 'N/A',
        ];

        return Response::json(['success' => true, 'entreprise' => $entreprise], 200);
    }
    
    
    // MÉTHODE DE SOUMISSION FINALE DU FORMULAIRE DE CONFIRMATION (POST /inscription/entreprise/info)
    public function submitSiret(Request $request)
    {
        $request->validate([
            'siret'       => 'required|digits:14',
            'nom'         => 'required|string',
            'adresse'     => 'required|string',
            'ville'       => 'required|string',
            'code_postal' => 'required|digits:5',
        ]);

        // Ici, insérez la logique de sauvegarde de l'entreprise en base de données.

        return redirect('/inscription/success')->with('status', 'Inscription de l\'entreprise finalisée.');
    }
}