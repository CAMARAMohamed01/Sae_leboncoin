<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Equipement;
use App\Models\TypeEquipement;
use App\Models\Annonce;
use Illuminate\Support\Facades\Storage;


class ServiceAnnonceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->isServiceAnnonce()) {
            return redirect()->route('home')->withErrors(['error' => "Accès refusé."]);
        }

        $equipements = Equipement::with('typeEquipement')->orderBy('idequipement', 'desc')->get();
        
        return view('admin.annonces.equipements.index', compact('equipements'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->isServiceAnnonce()) { return redirect()->route('home'); }

        $types = TypeEquipement::all();
        $annonces = Annonce::orderBy('idannonce', 'desc')->take(50)->get();

        return view('admin.annonces.equipements.create', compact('types', 'annonces'));
    }


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

            $equipement = Equipement::firstOrCreate(
                ['nomequipement' => $request->nomequipement], 
                ['idtypeequipement' => $request->idtypeequipement] 
            );

            $messageAction = $equipement->wasRecentlyCreated ? "créé" : "récupéré (existant)";
            $messageDetails = "";

            if ($request->has('annonces')) {
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

    public function listeValidation()
    {
        $user = Auth::user();
        
        // Sécurité
        if (!$user->isServiceAnnonce()) {
            return redirect()->route('home')->withErrors(['error' => "Accès refusé. Réservé au service petites annonces."]);
        }

        // annonces 'En attente'
        $annonces = Annonce::where('statutannonce', 'En attente')
            ->with(['ville', 'typeHebergement', 'proprietaire', 'photos', 'dateEnregistrement'])
            ->orderBy('idannonce', 'desc')
            ->get();

        return view('admin.annonces.validation.index', compact('annonces'));
    }

    public function validerAnnonce($id)
    {
        $user = Auth::user();
        if (!$user->isServiceAnnonce()) return back()->withErrors(['error' => "Accès refusé."]);

        $annonce = Annonce::findOrFail($id);
        $annonce->statutannonce = 'En ligne';
        $annonce->save();

        return back()->with('success', "L'annonce \"{$annonce->titreannonce}\" a été validée et mise en ligne.");
    }

    public function refuserAnnonce($id)
    {
        $user = Auth::user();
        if (!$user->isServiceAnnonce()) return back()->withErrors(['error' => "Accès refusé."]);

        $annonce = Annonce::findOrFail($id);

        try {
            DB::beginTransaction();

            foreach ($annonce->photos as $photo) {
                $relativePath = str_replace('/storage/', 'public/', $photo->lienurl);
                if (Storage::exists($relativePath)) {
                    Storage::delete($relativePath);
                }
            }
            
            $annonce->photos()->delete();       
            $annonce->prixPeriodes()->delete(); 
            $annonce->equipements()->detach();  
            $annonce->services()->detach();     
            
            $titre = $annonce->titreannonce; 
            $annonce->delete();

            DB::commit();

            return back()->with('success', "L'annonce \"{$titre}\" a été refusée et supprimée définitivement.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => "Erreur lors de la suppression : " . $e->getMessage()]);
        }
    }
}