<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CompteUtilisateur;
use App\Models\DemandeSuppression; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; 

class RGPDController extends Controller
{
    /**
     * Tableau de bord DPO
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        if (!$currentUser || !$currentUser->isDPO()) {
            return redirect()->route('home')->withErrors(['error' => "Accès refusé. Réservé au DPO."]);
        }

        $usersCibles = collect();
        $hasSearch = false; 

        $query = CompteUtilisateur::query()
            ->whereNotIn('role', ['admin', 'dpo'])
            ->with(['particulier', 'professionnel']);

        // Filtres (Date création, Dernière connexion, Email)
        if ($request->filled('date_limite')) {
            $dateLimite = Carbon::parse($request->date_limite);
            $query->where('date_creation', '<=', $dateLimite);
            $hasSearch = true;
        }
        if ($request->filled('date_connexion')) {
            $dateConnexion = Carbon::parse($request->date_connexion);
            $query->where('date_derniere_connexion', '<=', $dateConnexion);
            $hasSearch = true;
        }
        if ($request->filled('email_search')) {
            $query->where('emailutilisateur', 'LIKE', '%' . $request->email_search . '%');
            $hasSearch = true;
        }

        if ($hasSearch) {
            $usersCibles = $query->orderBy('date_creation', 'asc')->get();
        }

        // On passe $dateLimite à la vue même s'il est null (pour éviter l'erreur variable undefined)
        $dateLimite = $request->filled('date_limite') ? Carbon::parse($request->date_limite) : null;

        return view('admin.rgpd.index', compact('usersCibles', 'hasSearch', 'dateLimite'));
    }

    /**
     * Exécuter l'anonymisation sur une SÉLECTION
     */
    public function anonymiser(Request $request)
    {
        $currentUser = Auth::user();
        if (!$currentUser || !$currentUser->isDPO()) { return back()->withErrors(['error' => "Accès refusé."]); }

        $request->validate([
            'selected_users' => 'required|array|min:1',
            'selected_users.*' => 'exists:compteutilisateur,idutilisateur',
            'confirmation' => 'required|accepted'
        ]);

        $users = CompteUtilisateur::whereIn('idutilisateur', $request->selected_users)
            ->whereNotIn('role', ['admin', 'dpo'])
            ->get();

        $count = 0;
        foreach ($users as $user) {
            $user->anonymiser();
            $count++;
        }

        return redirect()->route('admin.rgpd.index')
            ->with('success', "Opération terminée. $count utilisateurs ont été anonymisés avec succès.");
    }

    public function supprimer(Request $request)
    {
        $currentUser = Auth::user();
        
        // Sécurité Rôle
        if (!$currentUser || !$currentUser->isDPO()) { 
            return back()->withErrors(['error' => "Accès refusé."]); 
        }

        // Validation
        $request->validate([
            'selected_users' => 'required|array|min:1',
            'selected_users.*' => 'exists:compteutilisateur,idutilisateur',
            'confirmation' => 'required|accepted'
        ], [
            'selected_users.required' => 'Veuillez sélectionner au moins un compte à supprimer.',
        ]);

        // Récupération sécurisée (hors admins)
        $users = CompteUtilisateur::whereIn('idutilisateur', $request->selected_users)
            ->whereNotIn('role', ['admin', 'dpo']) 
            ->get();

        $count = 0;
        foreach ($users as $user) {
            try {
                // Appel de la méthode de suppression totale définie dans le modèle
                $user->supprimerTotalement();
                $count++;
            } catch (\Exception $e) {
                // En cas d'erreur technique sur un utilisateur, on continue les autres
                Log::error("Erreur suppression user {$user->idutilisateur}: " . $e->getMessage());
            }
        }

        return redirect()->route('admin.rgpd.index')
            ->with('success', "Opération terminée. $count utilisateurs et toutes leurs données ont été supprimés définitivement de la base.");
    }

    /**
     * Lister les demandes de suppression reçues des utilisateurs
     */
    public function listeDemandes()
    {
        $currentUser = Auth::user();
        
        // Sécurité : Réservé au DPO ou ID 433 spécifiquement
        if (!$currentUser->isDPO() && $currentUser->idutilisateur != 433) {
            return redirect()->route('home')->withErrors(['error' => "Accès réservé au DPO."]);
        }

        // Récupérer les demandes en attente avec les infos de l'utilisateur
        $demandes = DemandeSuppression::where('statut', 'En attente')
            ->with(['utilisateur.particulier', 'utilisateur.professionnel']) // Charger les relations pour afficher les noms
            ->orderBy('date_demande', 'asc')
            ->get();

        return view('admin.rgpd.demandes', compact('demandes'));
    }

    

    /**
     * Valider une demande spécifique (Exécuter l'anonymisation)
     */
    public function validerDemande($idDemande)
    {
        $currentUser = Auth::user();
        
        // Sécurité
        if (!$currentUser->isDPO() && $currentUser->idutilisateur != 433) { 
            return back()->withErrors(['error' => "Accès refusé."]); 
        }

        $demande = DemandeSuppression::findOrFail($idDemande);
        $user = $demande->utilisateur;

        if ($user) {
            // Exécuter l'anonymisation réelle (méthode du modèle CompteUtilisateur)
            $user->anonymiser();
        }

        // Marquer la demande comme traitée pour qu'elle disparaisse de la liste
        $demande->statut = 'Traitée';
        $demande->save();

        return back()->with('success', "Le compte a été anonymisé avec succès et la demande clôturée.");
    }
}