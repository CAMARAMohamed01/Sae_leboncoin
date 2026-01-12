<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CookieStat; // On utilise le modèle dédié
use Carbon\Carbon;

class ServiceJuridiqueController extends Controller
{
    /**
     * Enregistre un choix utilisateur (appelé via AJAX depuis le bandeau)
     * Public / Sans Auth nécessaire
     */
    public function recordChoice(Request $request)
    {
        $request->validate([
            'choix' => 'required|in:accepted,refused'
        ]);

        CookieStat::create([
            'choix' => $request->choix,
            'date_action' => now()
        ]);

        return response()->json(['status' => 'success']);
    }

    /**
     * Tableau de bord de conformité
     */
    public function index()
    {
        $currentUser = Auth::user();

        if (!$currentUser || !$currentUser->isServiceJuridique()) {
            return redirect()->route('home')->withErrors(['error' => "Accès refusé. Réservé au service juridique."]);
        }

        // --- STATISTIQUES BASÉES SUR LA TABLE COOKIESTATS ---
        
        $total = CookieStat::count();
        $accepted = CookieStat::where('choix', 'accepted')->count();
        $refused = CookieStat::where('choix', 'refused')->count();

        // Calcul des pourcentages (éviter la division par zéro)
        $percentAccepted = $total > 0 ? round(($accepted / $total) * 100, 1) : 0;
        $percentRefused = $total > 0 ? round(($refused / $total) * 100, 1) : 0;

        // Stats récentes (30 derniers jours)
        $lastMonthStats = CookieStat::where('date_action', '>=', Carbon::now()->subDays(30))
            ->orderBy('date_action', 'desc')
            ->take(10)
            ->get();

        return view('admin.juridique.index', compact(
            'total', 
            'accepted', 
            'refused', 
            'percentAccepted', 
            'percentRefused',
            'lastMonthStats'
        ));
    }
}