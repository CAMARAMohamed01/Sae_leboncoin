<?php
namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\TypeHebergement;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $typesHebergement = TypeHebergement::all();
         
        $dernieresAnnonces = Annonce::query()
            ->with(['ville', 'typeHebergement', 'photos', 'dateEnregistrement'])
            ->withMin('prixPeriodes', 'prix')
            ->orderBy('idannonce', 'desc')
            ->take(16)
            ->get();

        return view('home', compact('typesHebergement', 'dernieresAnnonces'));
    }
}