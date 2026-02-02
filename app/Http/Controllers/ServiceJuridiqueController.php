<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CookieStat;
use Carbon\Carbon;

class ServiceJuridiqueController extends Controller
{
    
     
    public function recordChoice(Request $request)
    {
        $request->validate([
            'choix' => 'required|in:accepted,refused'
        ]);
        $userId = Auth::check() ? Auth::id() : null;

        CookieStat::create([
            'choix' => $request->choix,
            'date_action' => now(),
            'idutilisateur' => $userId
        ]);

        return response()->json(['status' => 'success']);
    }

    public function index()
    {
        $currentUser = Auth::user();

        if (!$currentUser || !$currentUser->isServiceJuridique()) {
            return redirect()->route('home')->withErrors(['error' => "Accès refusé. Réservé au service juridique."]);
        }

        // --- STATISTIQUES COOKIESTATS ---
        
        $total = CookieStat::count();
        $accepted = CookieStat::where('choix', 'accepted')->count();
        $refused = CookieStat::where('choix', 'refused')->count();

        $percentAccepted = $total > 0 ? round(($accepted / $total) * 100, 1) : 0;
        $percentRefused = $total > 0 ? round(($refused / $total) * 100, 1) : 0;

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