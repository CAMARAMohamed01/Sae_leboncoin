<?php
namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\TypeHebergement;
use Illuminate\Http\Request;

class RechercheController extends Controller
{
   public function index(Request $request)
{
    $periodeCherchee = 'Basse saison';

    if ($request->filled('date_arrivee')) {
        $date = $request->input('date_arrivee');
        $timestamp = strtotime($date);
        $mois = date('m', $timestamp);
        $jourSemaine = date('N', $timestamp);

        if ($jourSemaine >= 6) {
            $periodeCherchee = 'Week-end';
        } elseif (in_array($mois, ['06', '07', '08', '12'])) {
            $periodeCherchee = 'Haute saison';
        }
    }

    $query = Annonce::query()
        ->with(['ville', 'adresse', 'typeHebergement', 'photos', 'dateEnregistrement'])
        ->withMin(['prixPeriodes' => function($q) use ($periodeCherchee) {
            $q->where('nomperiode', $periodeCherchee);
        }], 'prix');

    if ($request->filled('localisation')) {
        $searchLoc = $request->input('localisation');
        $query->whereHas('ville', function ($q) use ($searchLoc) {
            $q->where('nomville', 'ILIKE', "%{$searchLoc}%");
        });
    }

    if ($request->filled('type_hebergement')) {
        $query->where('idtypehebergement', $request->input('type_hebergement'));
    }

    if ($request->filled('date_arrivee')) {
        $query->whereHas('prixPeriodes', function ($q) use ($periodeCherchee) {
            $q->where('nomperiode', $periodeCherchee);
        });
    }
    
    if ($request->filled('chambres')) {
        $query->where('nbchambre', '>=', $request->input('chambres'));
    }

    $query->whereHas('prixPeriodes', function ($q) use ($periodeCherchee, $request) {
        $q->where('nomperiode', $periodeCherchee);

        if ($request->filled('prix_min')) {
            $q->where('prix', '>=', $request->input('prix_min'));
        }

        if ($request->filled('prix_max')) {
            $q->where('prix', '<=', $request->input('prix_max'));
        }
    });

    if ($request->has('animaux')) {
        $query->whereHas('conditionHebergement', function ($q) {
            $q->where('animauxacceptes', true); 
        });
    }

    if ($request->has('fumeur')) {
        $query->whereHas('conditionHebergement', function ($q) {
            $q->where('fumeur', true);
        });
    }

    $annonces = $query->take(50)->get();
    $typesHebergement = TypeHebergement::all();
    
    return view('recherche.index', compact('annonces', 'typesHebergement'));
}
}