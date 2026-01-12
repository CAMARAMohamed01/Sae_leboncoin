<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HoistoriqueRecherche; 
use App\Models\Ville;
use App\Models\Region; 
use App\Models\TypeHebergement; 
use App\Models\Dates as DateModel;

class HoistoriqueRechercheController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
    }

    public function index()
    {
        return view('historique.index', [
            'recherches' => HoistoriqueRecherche::where('idutilisateur', Auth::id())
                ->with(['ville', 'typeHebergement'])
                ->latest()
                ->paginate(15)
        ]);
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'localisation' => 'nullable|string|max:255',
            'type_hebergement' => 'nullable|integer', 
            'date_arrivee' => 'nullable|date_format:Y-m-d', 
            'prix_min' => 'nullable|integer|min:0',
            'prix_max' => 'nullable|integer|min:0',
            'chambres' => 'nullable|integer|min:1',
            'animaux' => 'nullable|in:1', 
            'fumeur' => 'nullable|in:1',
        ]);

        $idVille = null;
        $idVilleMere = null;

        if ($request->filled('localisation')) {
            $villeData = $this->getOrCreateVille($request->localisation);
            $idVille = $villeData['idville'] ?? null;
            $t_108 = $villeData['t_108'] ?? 1;

        }
        $idDate = $this->getDateIdFromDateString($request->date_arrivee);
        
        $idTypeHebergement = $request->input('type_hebergement'); 

        
        $dataToInsert = [

            'idutilisateur' => Auth::id(),
            'iddate' => $idDate,                     
            'idville' => $idVille,                   
            't_108' => $t_108,                               
            'idtypehebergement' => $idTypeHebergement,

            'prix_min' => $request->input('prix_min'),
            'prix_max' => $request->input('prix_max'),
            'nbchambre_min' => $request->input('chambres'),       
        
            'animaux_acceptes' => $request->has('animaux'), 
            'fumeurs_autorises' => $request->has('fumeur'),   
        ];

        $finalDataToInsert = array_filter($dataToInsert, function($value) {
            return !is_null($value);
        });

        try {
            HoistoriqueRecherche::create($finalDataToInsert);
            
            return back()->with('success', 'Votre recherche a été enregistrée avec succès!');
            
        } catch (\Exception $e) {
            dd($e->getMessage()); 
        }
    }

    protected function getOrCreateVille(string $nomVille) 
    {
        $ville = Ville::where('nomville', 'ILIKE', $nomVille)->first();

        if ($ville) {
            return [
                'idville' => $ville->idville, 
                't_108' => $ville->t_108 ?? $ville->idville 
            ];
        }

        try {

            $nouvelleVille = Ville::create([
                'nomville' => $nomVille,
                'cpville' => '00000',
                't_108' => 1,
            ]);

            return [
                'idville' => $nouvelleVille->idville, 
                't_108' => $nouvelleVille->t_108
            ];

        } catch (\Exception $e) {
            dd($e->getMessage()); 
        }
    }

    protected function getDateIdFromDateString(?string $dateString)
    {
        if (empty($dateString)) {
            return null;
        }

        $dateModel = DateModel::where('dateacte', $dateString)->first();
        
        if ($dateModel) {
            return $dateModel->iddate;
        }

        try {
            $nouvelleDate = DateModel::create(['dateacte' => $dateString]);
            return $nouvelleDate->iddate;
        } catch (\Exception $e) {
            \Log::error("Erreur de création de date: " . $e->getMessage());
            return null;
        }
        $date = App\Models\Date::create(['dateacte' => '2025-12-31']);
    }
}