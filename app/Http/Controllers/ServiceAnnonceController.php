<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Equipement;
use App\Models\TypeEquipement;
use App\Models\Annonce;

class ServiceAnnonceController extends Controller
{
    /**
     * Liste des équipements existants
     */
    public function index()
    {
        $user = Auth::user();
        
        // Sécurité : Rôle requis
        if (!$user->isServiceAnnonce()) {
            return redirect()->route('home')->withErrors(['error' => "Accès refusé."]);
        }

        $equipements = Equipement::with('typeEquipement')->orderBy('idequipement', 'desc')->get();
        
        return view('admin.annonces.equipements.index', compact('equipements'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $user = Auth::user();
        if (!$user->isServiceAnnonce()) { return redirect()->route('home'); }

        $types = TypeEquipement::all();
        $annonces = Annonce::orderBy('idannonce', 'desc')->take(50)->get();

        return view('admin.annonces.equipements.create', compact('types', 'annonces'));
    }

    /**
     * Enregistrement intelligent (Création ou Récupération + Liaison sécurisée)
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->isServiceAnnonce()) { return back()->withErrors(['error' => "Accès refusé."]); }

        $request->validate([
            'nomequipement' => 'required|string|max:50',
            'idtypeequipement' => 'required|exists:typeequipement,idtypeequipement',
            'annonces' => 'nullable|array', 
            'annonces.*' => 'exists:annonce,idannonce'
        ]);

        try {
            DB::beginTransaction();

            // 1. ÉVITER LES DOUBLONS D'ÉQUIPEMENT
            // On cherche si un équipement avec ce nom existe déjà.
            // Si oui, on le récupère. Sinon, on le crée.
            $equipement = Equipement::firstOrCreate(
                ['nomequipement' => $request->nomequipement], // Critère de recherche
                ['idtypeequipement' => $request->idtypeequipement] // Valeurs à insérer si création
            );

            $messageAction = $equipement->wasRecentlyCreated ? "créé" : "récupéré (existant)";
            $messageDetails = "";

            // 2. ÉVITER LES DOUBLONS DE LIAISON
            if ($request->has('annonces')) {
                // syncWithoutDetaching retourne un tableau avec les clés ['attached', 'detached', 'updated']
                $changes = $equipement->annonces()->syncWithoutDetaching($request->annonces);
                
                $nbAjoutes = count($changes['attached']);
                $nbDejaPresents = count($request->annonces) - $nbAjoutes;

                if ($nbAjoutes > 0) {
                    $messageDetails .= " Lié à $nbAjoutes annonce(s).";
                }
                
                if ($nbDejaPresents > 0) {
                    $messageDetails .= " (Note : $nbDejaPresents annonce(s) avaient déjà cet équipement).";
                }
            }

            DB::commit();

            return redirect()->route('admin.annonces.equipements.index')
                             ->with('success', "L'équipement '{$equipement->nomequipement}' a été {$messageAction}.{$messageDetails}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => "Erreur : " . $e->getMessage()])->withInput();
        }
    }
}