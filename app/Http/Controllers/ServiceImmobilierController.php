<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Annonce;
use App\Models\Avis;

class ServiceImmobilierController extends Controller
{
    
    // Liste des annonces en attente d'expertise.
    public function index()
    {
        $currentUser = Auth::user();

        if (!$currentUser || !$currentUser->isServiceImmobilier()) {
            return redirect()->route('home')->withErrors(['error' => "Accès refusé. Réservé au service immobilier."]);
        }

        $annonces = Annonce::whereDoesntHave('avis', function (Builder $query) {
            $query->whereIn('avis_expert', ['Positif', 'Négatif']);
        })
        ->with(['ville', 'typeHebergement', 'proprietaire', 'photos'])
        ->orderBy('idannonce', 'desc')
        ->get();

        return view('admin.immobilier.index', compact('annonces'));
    }

    
    //  Action : Enregistrer l'avis de l'expert
     
    public function storeAvis(Request $request, $id)
    {
        $currentUser = Auth::user();

        if (!$currentUser || !$currentUser->isServiceImmobilier()) {
            return back()->withErrors(['error' => "Accès refusé."]);
        }

        // 1. Validation conditionnelle
        $request->validate([
            'avis' => 'required|in:Positif,Négatif',
            // Si l'avis est négatif, le commentaire est OBLIGATOIRE
            'commentaire_expert' => 'required_if:avis,Négatif|nullable|string|min:5'
        ], [
            'commentaire_expert.required_if' => 'Vous devez fournir une explication pour un avis négatif.',
            'commentaire_expert.min' => 'L\'explication doit être explicite (min 5 caractères).'
        ]);

        // 2. Préparation du commentaire
        $commentaireFinal = $request->commentaire_expert;
        
        // Si positif et pas de commentaire, on met un message standard
        if ($request->avis === 'Positif' && empty($commentaireFinal)) {
            $commentaireFinal = "Annonce validée et conforme aux standards de qualité.";
        }

        // 3. Création de l'avis
        Avis::create([
            'idannonce' => $id,
            'idutilisateur' => $currentUser->idutilisateur,
            'idreservation' => null,
            'note' => ($request->avis === 'Positif') ? 5 : 1,
            'commentaire' => $commentaireFinal, // On enregistre le vrai commentaire ici
            'avis_expert' => $request->avis
        ]);

        $couleur = $request->avis === 'Positif' ? 'success' : 'warning';
        return back()->with($couleur, "Avis enregistré avec succès.");
    }
}