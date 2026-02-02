<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\TypeHebergement;
use App\Models\Annonce;

class ServiceTypeHebergementController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->isServiceAnnonce()) {
            return redirect()->route('home')->withErrors(['error' => "Accès refusé."]);
        }

        $types = TypeHebergement::withCount('annonces')->orderBy('idtypehebergement', 'desc')->get();
        
        return view('admin.annonces.types.index', compact('types'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->isServiceAnnonce()) { return redirect()->route('home'); }

        $annonces = Annonce::with('typeHebergement')->orderBy('idannonce', 'desc')->take(50)->get();

        return view('admin.annonces.types.create', compact('annonces'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->isServiceAnnonce()) { return back()->withErrors(['error' => "Accès refusé."]); }

        $request->validate([
            'typehebergement' => 'required|string|max:50|unique:typehebergement,typehebergement',
            'annonces' => 'nullable|array', 
            'annonces.*' => 'exists:annonce,idannonce'
        ]);

        try {
            DB::beginTransaction();

            $nouveauType = TypeHebergement::create([
                'typehebergement' => $request->typehebergement
            ]);

            if ($request->has('annonces') && !empty($request->annonces)) {
                Annonce::whereIn('idannonce', $request->annonces)->update([
                    'idtypehebergement' => $nouveauType->idtypehebergement
                ]);
            }

            DB::commit();

            return redirect()->route('admin.annonces.types.index')
                             ->with('success', "Le type '{$nouveauType->typehebergement}' a été créé avec succès.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => "Erreur technique : " . $e->getMessage()])->withInput();
        }
    }
}