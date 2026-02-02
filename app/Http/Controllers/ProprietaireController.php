<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Annonce;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use app\Mail\ReservationAcceptee;
use app\Mail\ReservationRefusee;

class ProprietaireController extends Controller
{
    public function mesDemandesRecues()
    {
        $userId = Auth::id();

        $mesAnnoncesIds = Annonce::where('idutilisateur', $userId)->pluck('idannonce');

        $demandes = Reservation::whereIn('idannonce', $mesAnnoncesIds)
            ->with([
                'annonce.ville',           
                'locataire.particulier',   
                'locataire.professionnel', 
                'dateDebut',               
                'dateFin',                 
                'messages'
            ])
            ->orderBy('idreservation', 'desc')
            ->get();

        return view('proprietaire.demandes', compact('demandes'));
    }


   public function accepter($id)
    {
        $reservation = Reservation::with(['annonce', 'reglement', 'locataire'])->findOrFail($id);
        $proprietaire = Auth::user();
        
        if ($reservation->annonce->idutilisateur !== $proprietaire->idutilisateur) {
            return back()->withErrors(['error' => "Action non autorisée."]);
        }

        try {
            DB::beginTransaction();

            $reservation->statut_reservation = 'Acceptée';
            $reservation->save();

            if ($reservation->reglement) {
                $montant = $reservation->reglement->montant;
                $proprietaire->solde += $montant;
                $proprietaire->save();
            }

            DB::commit();

            try {
                if ($reservation->locataire && $reservation->locataire->emailutilisateur) {
                    Mail::to($reservation->locataire->emailutilisateur)
                        ->send(new ReservationAcceptee($reservation));
                }
            } catch (\Exception $e) {

            }

            return back()->with('success', 'Réservation acceptée ! Le locataire a été notifié par email.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => "Erreur : " . $e->getMessage()]);
        }
    }

    public function refuser(Request $request, $id)
    {
        $reservation = Reservation::with(['annonce', 'locataire'])->findOrFail($id);

        if ($reservation->annonce->idutilisateur !== Auth::id()) {
            return back()->withErrors(['error' => "Action non autorisée."]);
        }

        $request->validate([
            'motif' => 'nullable|string|max:255' 
        ]);

        try {
            DB::beginTransaction();

            $reservation->statut_reservation = 'Refusée';
            if ($request->filled('motif')) {
                $reservation->motif_refus = $request->motif;
            }
            $reservation->save();

            DB::commit();

            try {
                if ($reservation->locataire && $reservation->locataire->emailutilisateur) {
                    Mail::to($reservation->locataire->emailutilisateur)
                        ->send(new ReservationRefusee($reservation, $request->motif));
                }
            } catch (\Exception $e) {
                
            }

            return back()->with('success', 'La réservation a été refusée et le locataire a été notifié par email.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => "Erreur : " . $e->getMessage()]);
        }
    }
}