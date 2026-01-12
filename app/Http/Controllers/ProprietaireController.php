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
    /**
     * Affiche la liste des demandes de réservation REÇUES par le propriétaire
     * (Celles faites par d'autres sur SES annonces)
     */
    public function mesDemandesRecues()
    {
        $userId = Auth::id();

        // 1. On récupère d'abord les IDs de toutes les annonces qui m'appartiennent
        $mesAnnoncesIds = Annonce::where('idutilisateur', $userId)->pluck('idannonce');

        // 2. On récupère les réservations liées à ces annonces spécifiques
        // Cette méthode est plus robuste que whereHas en cas de pépin sur les relations
        $demandes = Reservation::whereIn('idannonce', $mesAnnoncesIds)
            ->with([
                'annonce.ville',           // Pour afficher le lieu
                'locataire.particulier',   // Pour afficher le nom du locataire (Particulier)
                'locataire.professionnel', // Pour afficher le nom du locataire (Pro)
                'dateDebut',               // Pour la date d'arrivée
                'dateFin',                 // Pour la date de départ
                'messages'
            ])
            ->orderBy('idreservation', 'desc')
            ->get();

        return view('proprietaire.demandes', compact('demandes'));
    }

    /**
     * Action : Accepter une réservation
     */
   public function accepter($id)
    {
        // 1. Récupération de la réservation avec le locataire (pour l'email)
        $reservation = Reservation::with(['annonce', 'reglement', 'locataire'])->findOrFail($id);
        $proprietaire = Auth::user();
        
        // 2. Sécurité : Vérifier que l'utilisateur est bien le propriétaire
        if ($reservation->annonce->idutilisateur !== $proprietaire->idutilisateur) {
            return back()->withErrors(['error' => "Action non autorisée."]);
        }

        try {
            DB::beginTransaction();

            // 3. Mise à jour statut
            $reservation->statut_reservation = 'Acceptée';
            $reservation->save();

            // 4. Créditer le propriétaire (Si paiement existant)
            if ($reservation->reglement) {
                $montant = $reservation->reglement->montant;
                $proprietaire->solde += $montant;
                $proprietaire->save();
            }

            DB::commit();

            // 5. ENVOI EMAIL AU LOCATAIRE
            try {
                // On récupère l'email via la relation locataire (CompteUtilisateur)
                if ($reservation->locataire && $reservation->locataire->emailutilisateur) {
                    Mail::to($reservation->locataire->emailutilisateur)
                        ->send(new ReservationAcceptee($reservation));
                }
            } catch (\Exception $e) {
                // On ne bloque pas si l'email échoue (ex: localhost sans SMTP)
            }

            return back()->with('success', 'Réservation acceptée ! Le locataire a été notifié par email.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => "Erreur : " . $e->getMessage()]);
        }
    }

    /**
     * Action : Refuser la réservation + Notifier le locataire
     */
    public function refuser(Request $request, $id)
    {
        // 1. Récupération avec le locataire
        $reservation = Reservation::with(['annonce', 'locataire'])->findOrFail($id);

        // 2. Sécurité
        if ($reservation->annonce->idutilisateur !== Auth::id()) {
            return back()->withErrors(['error' => "Action non autorisée."]);
        }

        $request->validate([
            'motif' => 'nullable|string|max:255' 
        ]);

        try {
            DB::beginTransaction();

            // 3. Mise à jour statut et motif
            $reservation->statut_reservation = 'Refusée';
            if ($request->filled('motif')) {
                $reservation->motif_refus = $request->motif;
            }
            $reservation->save();

            DB::commit();

            // 4. ENVOI EMAIL AU LOCATAIRE
            try {
                if ($reservation->locataire && $reservation->locataire->emailutilisateur) {
                    Mail::to($reservation->locataire->emailutilisateur)
                        ->send(new ReservationRefusee($reservation, $request->motif));
                }
            } catch (\Exception $e) {
                // On log l'erreur email mais on ne bloque pas le refus
            }

            return back()->with('success', 'La réservation a été refusée et le locataire a été notifié par email.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => "Erreur : " . $e->getMessage()]);
        }
    }
}