<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompteUtilisateur;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function listeDemandesInscription()
    {
        $user = Auth::user();

        if (!$user || !$user->isServiceInscription()) {
            return redirect()->route('home')->withErrors(['error' => "Accès refusé. Réservé au service inscription."]);
        }

        $utilisateurs = CompteUtilisateur::has('identite')
            ->with(['particulier', 'professionnel', 'identite'])
            ->orderBy('idutilisateur', 'desc')
            ->get();

        return view('admin.demandes_inscription', compact('utilisateurs'));
    }

    public function togglePhoneVerification($userId)
    {
        $user = Auth::user();

        if (!$user || !$user->isServiceAnnonce()) {
            return back()->withErrors(['error' => "Accès refusé. Réservé au service petites annonces."]);
        }

        $targetUser = CompteUtilisateur::findOrFail($userId);

        $targetUser->telephone_verifie = !$targetUser->telephone_verifie;
        $targetUser->save();

        $status = $targetUser->telephone_verifie ? 'vérifié' : 'non vérifié';

        return back()->with('success', "Le téléphone de cet utilisateur est maintenant marqué comme $status.");
    }
    public function toggleAnnonceGarantie($idAnnonce)
    {
    $annonce = \App\Models\Annonce::findOrFail($idAnnonce);
    
    if (!$annonce->proprietaire->telephone_verifie) {
        return back()->withErrors(['error' => "Impossible de garantir cette annonce : le téléphone du vendeur n'a pas encore été vérifié par un Administrateur."]);
    }

    $annonce->est_garantie = !$annonce->est_garantie;
    $annonce->save();

    return back()->with('success', "Statut de garantie de l'annonce mis à jour.");
}

}