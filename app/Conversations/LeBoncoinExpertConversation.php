<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeBoncoinExpertConversation extends Conversation
{
    protected $staticKnowledge = "
        CONTEXTE DU SITE : PLATEFORME DE LOCATION ENTRE PARTICULIERS
        1. COMMENT RÉSERVER (LE FLUX CLASSIQUE)
            Demande & Validation : Le locataire fait une demande sur des dates précises. Rien ne se passe tant que le propriétaire n'a pas validé la disponibilité.
            Paiement Séquestre : Une fois validé, le locataire paie sur le site. L'argent n'est pas versé direct au propriétaire. Le site le garde au chaud (compte de cantonnement) jusqu'au début de la location.
            Le Check-in : L'argent est débloqué pour le propriétaire environ 24h/48h après l'entrée dans les lieux, sauf si le locataire signale un problème immédiat (appart sale, non conforme, etc.).
        2. GESTION DES BIENS (LES ANNONCES)
            Présentation : Le nerf de la guerre. Photos claires, description honnête des équipements (Wifi, piscine, draps fournis ou pas).
            Calendrier : C'est la clé en location. Il doit refléter les vraies dispos. Si un mec réserve un truc déjà pris, ça crée de la frustration inutile.
            Prix & Durée : Le propriétaire fixe son prix à la nuitée et peut imposer une durée minimum (ex: 2 nuits min le week-end).
        3. SÉCURITÉ DE BASE (JUSTE POUR RAPPEL)
            Communication : On discute via la messagerie interne pour garder une trace en cas de litige.
            Transactions : Tout paiement hors du site (virement direct, espèces avant arrivée) retire toute protection. Si tu sors du site, tu sors de l'assurance
    ";

    public function run()
    {
        $incomingMessage = $this->bot->getMessage()->getText();
        
        // J'ai ajouté des mots clés pertinents pour de l'achat/vente
        $isGreeting = preg_match('/^(bonjour|hello|start|commencer|aide|test|vendre|acheter)$/i', $incomingMessage);

        if ($isGreeting) {
            $this->say('Salut ! Je suis ton expert Leboncoin. Besoin d\'aide pour louer, acheter ou éviter une arnaque ?');
        } else {
            $userContext = $this->getUserContext();
            $response = $this->getGeminiResponse($incomingMessage, $userContext);
            $this->say($response);
        }

        $this->askAi();
    }

    public function askAi()
    {
           $this->ask('Je t\'écoute ...', function(Answer $answer) {
               $question = $answer->getText();
               
               $userContext = $this->getUserContext();
               $response = $this->getGeminiResponse($question, $userContext);
   
               $this->say($response);
               
               $this->askAi();
           });
       }

       protected function getUserContext()
{
    try {
        if (!Auth::check()) {
            return "UTILISATEUR : Visiteur (Non connecté).";
        }

        $userId = Auth::id();

        $user = DB::table('compteutilisateur')
            ->leftJoin('particulier', 'compteutilisateur.idutilisateur', '=', 'particulier.idutilisateur')
            ->leftJoin('professionnel', 'compteutilisateur.idutilisateur', '=', 'professionnel.idutilisateur')
            ->where('compteutilisateur.idutilisateur', $userId)
            ->select(
                'compteutilisateur.emailutilisateur',
                'compteutilisateur.solde',
                'compteutilisateur.role',
                'particulier.prenomparticulier',
                'particulier.nomparticulier',
                'professionnel.nomprofessionnel',
                'professionnel.secteuractivite'
            )
            ->first();

        if (!$user) return "UTILISATEUR : Erreur (ID introuvable).";

        
        $displayName = !empty($user->prenomparticulier) 
            ? $user->prenomparticulier . ' ' . $user->nomparticulier 
            : ($user->nomprofessionnel . ' (Pro)' ?? 'Utilisateur');

        $reservations = DB::table('reservation')
            ->join('annonce', 'reservation.idannonce', '=', 'annonce.idannonce')
            ->where('reservation.idutilisateur', $userId)
            ->orderBy('reservation.idreservation', 'desc')
            ->limit(3)
            ->select('reservation.nbjours', 'annonce.titreannonce', 'annonce.statutannonce')
            ->get();

        $bookingText = "";
        if ($reservations->isEmpty()) {
            $bookingText = "Aucune réservation en cours.";
        } else {
            foreach ($reservations as $res) {
                $bookingText .= "- Réservation de {$res->nbjours} jours sur : \"{$res->titreannonce}\"\n";
            }
        }

        $myListings = DB::table('annonce')
            ->where('idutilisateur', $userId)
            ->orderBy('idannonce', 'desc')
            ->limit(3) 
            ->select('titreannonce', 'statutannonce', 'capacite')
            ->get();

        $listingsText = "";
        if ($myListings->isEmpty()) {
            $listingsText = "Aucune annonce publiée.";
        } else {
            foreach ($myListings as $ad) {
                
                $listingsText .= "- [{$ad->statutannonce}] \"{$ad->titreannonce}\" (Capacité: {$ad->capacite} pers.)\n";
            }
        }

        
        return "PROFIL UTILISATEUR :\n" .
               "Nom : {$displayName} ({$user->role})\n" .
               "Solde portefeuille : {$user->solde}€\n\n" .
               "=== SES RÉSERVATIONS (LOCATAIRE) ===\n" .
               $bookingText . "\n\n" .
               "=== SES ANNONCES (PROPRIÉTAIRE) ===\n" .
               $listingsText;

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Context Error: ' . $e->getMessage());
        return "UTILISATEUR : Erreur lecture données.";
    }
}
   
       protected function getGeminiResponse($message, $userContext)
    {
        $apiKey = getenv('GOOGLE_API_KEY');
        $model = 'gemini-3-flash-preview'; 

        $client = new Client([
            'verify' => false, 
            'timeout' => 15.0,
        ]);

        
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $cleanContext = str_replace(["\r", "\n"], " ", $this->staticKnowledge);
        $cleanUser = str_replace(["\r", "\n"], " ", $userContext);
        
        $systemPrompt = "Rôle: Expert Leboncoin. Contexte: {$this->staticKnowledge}
                Info Client: {$userContext}
                Question: {$message}
                Consigne: Réponds de manière courte, naturelle et utile, tu est un chat bot.Tutoie mais reste respectueux.
                IMPORTANT : N'utilise JAMAIS de mise en forme Markdown (pas de gras, pas d'italique, pas de titres). Écris en texte brut uniquement. ";


        try {
            $response = $client->post($url, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $systemPrompt]
                            ]
                        ]
                    ]
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                $botReply = $data['candidates'][0]['content']['parts'][0]['text'];
                return str_replace(['**', '##', '###', '*', '__'], '', $botReply);
            }
            
            return "Désolé, j'ai eu une erreur(Réponse vide).";

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            \Illuminate\Support\Facades\Log::error('Gemini Error: ' . $responseBodyAsString);
            return "Oups, je n'arrive pas à joindre le serveur (Erreur technique).";
        } catch (\Exception $e) {
            return "Erreur système : " . $e->getMessage();
        }
    }
}