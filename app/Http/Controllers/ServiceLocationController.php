<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Incident;
use Carbon\Carbon;

class ServiceLocationController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();

        if (!$currentUser || !$currentUser->isServiceLocation()) {
            return redirect()->route('home')->withErrors(['error' => "Accès refusé. Réservé au service location."]);
        }

        $incidents = Incident::whereNull('datecloture')
            ->with(['reservation.annonce', 'declarant'])
            ->orderBy('datedeclaration', 'asc')
            ->get();

        return view('admin.location.incidents', compact('incidents'));
    }

    public function classerSansSuite($id)
    {
        $currentUser = Auth::user();

        if (!$currentUser || !$currentUser->isServiceLocation()) {
            return back()->withErrors(['error' => "Accès refusé."]);
        }

        $incident = Incident::findOrFail($id);

        $incident->statut_reglament = 'Classé sans suite';
        $incident->datecloture = Carbon::now();
        $incident->save();

        return back()->with('success', "L'incident #{$id} a été classé sans suite et clôturé.");
    }
}