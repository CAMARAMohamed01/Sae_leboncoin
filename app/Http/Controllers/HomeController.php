<?php
namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\TypeHebergement;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Récupérer les catégories pour le filtre
        $typesHebergement = TypeHebergement::all();

        // 2. Récupérer les 4 dernieres annonces et les trier
         
        $dernieresAnnonces = Annonce::query()
            ->with(['ville', 'typeHebergement', 'photos', 'dateEnregistrement'])
            ->withMin(['tarifs' => function ($query) {
                $query->where('disponibilite', true);
            }], 'prixjour')
            ->orderBy('idannonce', 'desc')
            ->take(4)
            ->get();

        return view('home', compact('typesHebergement', 'dernieresAnnonces'));
    }
}