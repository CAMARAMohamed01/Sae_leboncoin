<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Adresse;
use App\Models\Ville;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AnnonceController extends Controller
{
    /**
     * Affiche la page d'accueil avec toutes les annonces par défaut.
     */
    public function index() 
    {
        // On charge les relations 'ville' et 'photos' pour éviter de faire trop de requêtes SQL dans la vue
        $annonces = Annonce::with(['ville', 'photos'])->get();

        return view("home", [
            'annonces' => $annonces,
            'searchCity' => ''
        ]);
    }

    public function rechercheParVille(Request $request)
    {
        // 1. Récupération du terme de recherche
        $villeRecherchee = $request->input('ville');
        $typeRecherche = $request->input('idtypehebergement');
        
        // On prépare la requête de base
        $query = Annonce::query();

        if (!empty($villeRecherchee)) {
            // 2. Si une recherche est faite, on fait les JOINTURES
            $query->join('ville', 'annonce.idville', '=', 'ville.idville')
                  // Jointure sur la table 'definit' (lien Annonce <-> Prix)
                  ->join('definit', 'annonce.idannonce', '=', 'definit.idannonce')
                  ->join('adresse', 'annonce.idadresse', '=', 'adresse.idadresse')
                  ->join('dates', 'annonce.iddate', '=', 'dates.iddate')
                  ->join('photo', 'annonce.idannonce', '=', 'photo.idannonce')
                  
                  
                  // On filtre sur le nom de la ville
                  ->where('ville.nomville', 'ILIKE', '%' . $villeRecherchee . '%')
                  
                  // 3. CRUCIAL : On sélectionne :
                  // - Toutes les infos de l'annonce ('annonce.*')
                  // - Le nom de la ville ('ville.nomville')
                  // - Le prix ('definit.prixjour') -> Correction ici : tout en minuscules selon votre erreur
                  ->select('annonce.*', 'ville.nomville', 'definit.prixjour' , 'adresse.*' , 'dates.*' , 'photo.*');
        }

        if (!empty($typeRecherche)) {
            // On précise 'annonce.idtypehebergement' pour éviter toute confusion s'il y a des jointures
            $query->where('annonce.idtypehebergement', $typeRecherche);
        }

        // 4. On exécute la requête en chargeant les relations nécessaires pour l'affichage
        $annonces = $query->with(['ville', 'photos'])->get();


        // 5. Renvoi des résultats à la vue
        return view('home', [
            'annonces' => $annonces, 
            'searchCity' => $villeRecherchee
        ]);
    }

    /**
     * Affiche les détails d'une annonce spécifique.
     */

    public function show($id)
    {
        // On récupère l'annonce avec TOUTES ses relations pour éviter de faire 50 requêtes SQL
        $annonce = Annonce::with([
            'ville', 
            'typeHebergement', 
            'photos', 
            'equipements', 
            'services',
            'tarifs'
        ])->findOrFail($id); // Renvoie une erreur 404 si l'ID n'existe pas

        // On calcule le prix min pour l'affichage (comme sur l'accueil)
        // On utilise la collection chargée 'tarifs' pour ne pas refaire de requête SQL
        $prixMin = $annonce->tarifs->min('prixjour');

        return view('annonces.show', compact('annonce', 'prixMin'));
    }
}