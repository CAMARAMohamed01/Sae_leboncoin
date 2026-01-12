<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;
use App\Models\Reservation;
use Carbon\Carbon;
use App\Models\Incident;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    public function voirlesIncidents($idAnnonceOptionnel = null)
    {
        $idUtilisateurConnecte = Auth::id();
        $incidentsQuery = Incident::query()->with(['reservation.annonce']);
        $incidentsQuery->where(function ($query) use ($idUtilisateurConnecte) {    
            $query->WhereHas('reservation.annonce', function ($q) use ($idUtilisateurConnecte) {
                      $q->where('idutilisateur', $idUtilisateurConnecte);
                  });
        });
        $incidents = $incidentsQuery
            ->orderByRaw("CASE WHEN statut_reglament = 'En relance' THEN 0 ELSE 1 END ASC")
            ->orderBy('statut_reglament', 'asc')
            ->orderBy('datedeclaration', 'asc')
            ->get();
        $annonce = null;
        if ($idAnnonceOptionnel) {
            $annonce = Annonce::find($idAnnonceOptionnel);
        }
        return view('gereincident.gererincident', compact('incidents', 'annonce'));
    }

    public function voirlesPlaintes($idAnnonceOptionnel = null)
    {
        $idUtilisateurConnecte = Auth::id();
        $plaintesQuery = Incident::query()->with(['reservation.annonce']);
        $plaintesQuery->where(function ($query) use ($idUtilisateurConnecte) {    
            $query->where('idutilisateur', $idUtilisateurConnecte);
        });
        $plaintes = $plaintesQuery
            ->orderByRaw("CASE WHEN statut_reglament = 'En litige' THEN 0 ELSE 1 END ASC")
            ->orderBy('statut_reglament', 'asc')
            ->orderBy('datedeclaration', 'asc')
            ->get();
        $annonce = null;
        if ($idAnnonceOptionnel) {
            $annonce = Annonce::find($idAnnonceOptionnel);
        }
        return view('gereplainte.gererplainte', compact('plaintes', 'annonce'));
    }

    public function index($idReservation) 
    {
        $idUtilisateur = Auth::id();
        
        $reservation = Reservation::with('annonce')->find($idReservation);

        if (!$reservation || $reservation->idutilisateur != $idUtilisateur) {
            return redirect()->route('reservations.mes_locations')->with('error', 'Réservation introuvable ou vous n\'êtes pas autorisé.');
        }

        $annonce = $reservation->annonce; 
        
        return view('incident.forulaireIncident', compact('annonce', 'idReservation'));
    }

    public function reconnaitre($id)
    {
        $incident = Incident::where('idincident', $id)->firstOrFail();

        $incident->reconnuparproprietaire = true;
        $incident->remboursementvalide = true;
        $incident->statut_reglament = 'Terminé';
        $incident->datecloture = now()->format('Y-m-d');

        $incident->save();

        return back()->with('success', 'Incident reconnu et clôturé avec succès.');
    }
    
    public function demanderinfo(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1024',
        ]);

        $incident = Incident::where('idincident', $id)->firstOrFail();

        $incident->reponse_locataire = $request->input('message'); 
        $incident->statut_reglament = 'En relance';

        $incident->save();

        return back()->with('success', 'Votre demande d\'informations a été enregistrée et transmise.');
    }

    public function refus(Request $request, $id)
    {
        $request->validate([
            'motif_refus' => 'required|string|max:1024',
        ]);

        $incident = Incident::where('idincident', $id)->firstOrFail();

        $incident->reponse_locataire = $request->input('motif_refus'); 
        $incident->statut_reglament = 'En justice';

        $incident->save();

        return back()->with('success', 'Votre refus a été enregistré. Un administrateur va examiner le dossier.');
    }

    public function annuler($id)
    {
        $incident = Incident::where('idincident', $id)->firstOrFail();

        $incident->statut_reglament = 'Terminé';
        $incident->datecloture = now()->format('Y-m-d');

        $incident->save();

        return back()->with('success', 'Incident reconnu et clôturé avec succès.');
    }

    public function contester(Request $request, $id)
    {
        $request->validate([
            'motif_refus' => 'required|string|max:1024',
        ]);

        $incident = Incident::where('idincident', $id)->firstOrFail();

        $incident->reponse_proprietaire = $request->input('motif_refus'); 
        $incident->reconnuparproprietaire = false;
        $incident->statut_reglament = 'En litige';

        $incident->save();

        return back()->with('success', 'Votre contestation a été enregistrée et transmise.');
    }

    public function store(Request $request)
    {
        $idReservation = $request->input('idReservation');


        $reservation = Reservation::find($idReservation);

        if (!$reservation) {
            return back()->withErrors(['erreur' => 'Réservation introuvable (ID: ' . $idReservation . '). Créez d\'abord une réservation.']);
        }

        $incident = new Incident();
        
        $incident->idreservation = $reservation->idreservation;
        $incident->idlocateur    = $reservation->idlocateur;
        $incident->idparticulier = $reservation->idparticulier;
        $incident->idutilisateur = $reservation->idutilisateur;

        $incident->typeincident = $request->input('typeincident');
        $incident->description  = $request->input('description');
        
        $incident->datedeclaration = Carbon::now();
        $incident->statut_reglament = 'En attente';

        $incident->save();

        return redirect('/')->with('success', 'Incident déclaré avec succès !');
    }
}