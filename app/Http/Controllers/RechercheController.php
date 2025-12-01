<?php
namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\TypeHebergement;
use Illuminate\Http\Request;

class RechercheController extends Controller
{
    public function index(Request $request)
    {
        $query = Annonce::query()
            ->with(['ville', 'typeHebergement', 'photos'])
           
            ->withMin(['tarifs' => function ($query) {
                $query->where('disponibilite', true); // Si dispo = true dans la BDD
            }], 'prixjour');

        // --- Filtres existants (Localisation) ---
        if ($request->filled('localisation')) {
            $searchLoc = $request->input('localisation');
            $query->whereHas('ville', function ($q) use ($searchLoc) {
                $q->where('nomville', 'ILIKE', "%{$searchLoc}%");
            });
        }

        // --- Filtres existants (Type) ---
        if ($request->filled('type_hebergement')) {
            $query->where('idtypehebergement', $request->input('type_hebergement'));
        }

        if ($request->filled('date_arrivee')) { // par date de dispo Sp4
        $dateCherchee = $request->input('date_arrivee');

        // On cherche dans la relation 'tarifs' (table definit)
        $query->whereHas('tarifs', function ($q) use ($dateCherchee) {
            // Qui est disponible
            $q->where('disponibilite', true);
            
            // Et qui correspond à la date dans la table calendrier
            $q->whereHas('calendrier', function ($q2) use ($dateCherchee) {
                $q2->where('datejour', $dateCherchee);
            });
        });
    }
        // Pagination
        // $annonces = $query->paginate(12)->withQueryString();
        $annonces = $query->take(50)->get();
        $typesHebergement = TypeHebergement::all();
        
        return view('recherche.index', compact('annonces', 'typesHebergement'));
    }
}