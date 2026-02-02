<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\NouvelleDemandeReservation;
use App\Models\Reservation;
use App\Models\Annonce;
use App\Models\Dates;
use App\Models\Locataire;
use App\Models\Reglement;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Message;
class ReservationController extends Controller
{
    public function mesLocations()
    {
        $user = Auth::user();

        $reservations = Reservation::where('idutilisateur', $user->idutilisateur)
            ->with([
                'annonce.ville', 
                'annonce.photos', 
                'dateDebut', 
                'messages' ,
                'dateFin'
            ])
            ->orderBy('idreservation', 'desc')
            ->get();

        return view('reservations.mes_locations', compact('reservations'));
    }

    public function create($id)
    {
        $annonce = Annonce::with(['ville', 'photos', 'prixPeriodes', 'proprietaire'])->findOrFail($id);

        $prixNuit = $annonce->prixPeriodes->min('prix') ?? 0;

        return view('reservations.create', compact('annonce', 'prixNuit'));
    }

    public function store(Request $request, $id)
    {
        $annonce = Annonce::findOrFail($id);
        $user = Auth::user();

        $request->validate([
            'date_arrivee' => 'required|date|after_or_equal:today',
            'date_depart'  => 'required|date|after:date_arrivee',
            'nbadulte'     => 'required|integer|min:1', 
            'nbenfant'     => 'nullable|integer|min:0',
            'nbbebe'       => 'nullable|integer|min:0',
            'nbanimeaux'   => 'nullable|integer|min:0',
        ], [
            'date_depart.after' => 'La date de départ doit être postérieure à la date d\'arrivée.',
            'nbadulte.min'      => 'Il faut au moins 1 adulte responsable pour réserver.',
        ]);

        try {
            DB::beginTransaction();

            $locataire = $user->locataire;
            if (!$locataire) {
                $dateNaiss = $user->particulier->datenaissance ?? now();

                $locataire = Locataire::create([
                    'idutilisateur' => $user->idutilisateur,
                    'idparticulier' => $user->particulier->idparticulier ?? null,
                    'telutilisateur' => $user->telutilisateur,
                    'solde' => $user->solde ?? 0,
                    'datenaissance' => $dateNaiss,
                    'motdepasse' => $user->motdepasse,
                    'statut_rgpd' => $user->statut_rgpd ?? true,
                ]);
            }

            $dateDebut = Dates::firstOrCreate(['dateacte' => $request->date_arrivee]);
            $dateFin = Dates::firstOrCreate(['dateacte' => $request->date_depart]);

            $start = Carbon::parse($request->date_arrivee);
            $end = Carbon::parse($request->date_depart);
            $nbJours = $start->diffInDays($end);

            $reservation = new Reservation();
            $reservation->idannonce = $annonce->idannonce;
            $reservation->idutilisateur = $user->idutilisateur;
            $reservation->idlocateur = $locataire->idlocateur; 
            
            $reservation->idparticulier = $locataire->idparticulier;

            $reservation->iddate = $dateDebut->iddate;
            $reservation->dat_iddate = $dateFin->iddate;
            
            $reservation->nbjours = $nbJours;
            
            $reservation->nbadulte = $request->nbadulte; 
            $reservation->nbenfant = $request->nbenfant ?? 0;
            $reservation->nbanimeaux = $request->nbanimeaux ?? 0;
            $reservation->nbbebe = $request->nbbebe ?? 0;
            
            $reservation->statut_reservation = 'En attente';

            $reservation->save();

            DB::commit();


            return redirect()->route('reservations.payment', $reservation->idreservation);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => "Erreur lors de la réservation : " . $e->getMessage()])->withInput();
        }
    }

    public function showPayment($id)
    {
        $reservation = Reservation::with(['annonce.prixPeriodes', 'reglements'])->findOrFail($id);
        
        if ($reservation->idutilisateur !== Auth::id()) {
            return redirect()->route('home')->withErrors(['error' => 'Accès non autorisé']);
        }

        $prixNuit = $reservation->annonce->prixPeriodes->min('prix') ?? 0;
        $totalSejour = $prixNuit * $reservation->nbjours;
        $frais = round($totalSejour * 0.10, 2);
        $nouveauTotal = $totalSejour + $frais;

        $dejaPaye = $reservation->reglements->sum('montant');

        $resteAPayer = max(0, $nouveauTotal - $dejaPaye);

        if ($resteAPayer <= 0) {
             return redirect()->route('reservations.mes_locations')
                             ->with('success', 'Aucun paiement nécessaire. Votre réservation est à jour.');
        }

        return view('reservations.paiement', [
            'reservation' => $reservation,
            'totalAPayer' => $resteAPayer, 
            'frais' => $frais,
            'total' => $totalSejour,
            'dejaPaye' => $dejaPaye,
            'nouveauTotal' => $nouveauTotal
        ]);
    }


    
   public function processPayment(Request $request, $id)
    {
        $reservation = Reservation::with(['annonce.proprietaire', 'reglements'])->findOrFail($id);

        if ($reservation->idutilisateur !== Auth::id()) {
            return redirect()->route('home');
        }

        try {
            DB::beginTransaction();

            $dejaPaye = $reservation->reglements->sum('montant');
            $labelMode = $dejaPaye > 0 ? 'CB (Supplément)' : 'Carte Bancaire';

            Reglement::create([
                'idreservation' => $reservation->idreservation,
                'idutilisateur' => Auth::id(),
                'modereglement' => $labelMode,
                'montant' => $request->montant_total,
                'statut_reglament' => 'Validé'
            ]);

            $proprietaire = $reservation->annonce->proprietaire;
            if ($proprietaire) {
                $proprietaire->solde += $request->montant_total;
                $proprietaire->save();
            }

            DB::commit();

            return redirect()->route('reservations.mes_locations')
                             ->with('success', 'Paiement accepté ! Votre réservation est confirmée.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => "Erreur lors du paiement : " . $e->getMessage()]);
        }
    }



   public function processPaymentStripe(Request $request, $id) {
        $reservation = Reservation::with(['annonce.prixPeriodes', 'reglements'])->findOrFail($id);
        
        $prixNuit = $reservation->annonce->prixPeriodes->min('prix') ?? 0;
        $totalSejour = $prixNuit * $reservation->nbjours;
        $frais = round($totalSejour * 0.10, 2);
        $totalTheorique = $totalSejour + $frais;
        
        $dejaPaye = $reservation->reglements->sum('montant');
        $montantAPayer = max(0, round($totalTheorique - $dejaPaye, 2));

        if ($montantAPayer <= 0) return redirect()->route('reservations.mes_locations');

        Stripe::setApiKey(env('STRIPE_SECRET'));
        
        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => (int)($montantAPayer * 100),
                    'product_data' => [
                        'name' => 'Réservation : ' . $reservation->annonce->titreannonce,
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('reservations.payment.success', ['id' => $reservation->idreservation]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('reservations.payment.cancel', ['id' => $reservation->idreservation]),
        ]);
        return redirect($checkout_session->url);
    }


    public function paiementSuccess(Request $request, $id) {
        $reservation = Reservation::with(['annonce.proprietaire', 'annonce.prixPeriodes', 'reglements'])->findOrFail($id);
        
        $prixNuit = $reservation->annonce->prixPeriodes->min('prix') ?? 0;
        $total = ($prixNuit * $reservation->nbjours) * 1.10;
        $dejaPaye = $reservation->reglements->sum('montant');
        $montantPaye = max(0, round($total - $dejaPaye, 2));

        if ($montantPaye > 0) {
            DB::transaction(function () use ($reservation, $montantPaye) {
                $dejaPayeCount = $reservation->reglements->count();
                $label = $dejaPayeCount > 0 ? 'Stripe (Supplément)' : 'Stripe CB';

                Reglement::create([
                    'idreservation' => $reservation->idreservation,
                    'idutilisateur' => Auth::id(),
                    'modereglement' => substr($label, 0, 20), 
                    'montant' => $montantPaye,
                    'statut_reglament' => 'Validé'
                ]);

                $proprietaire = $reservation->annonce->proprietaire;
                if ($proprietaire) {
                    $proprietaire->solde += $montantPaye;
                    $proprietaire->save();
                }
            });
        }

        return redirect()->route('reservations.mes_locations')
                         ->with('success', 'Paiement Stripe validé ! Votre réservation est confirmée.');
    }

    public function paiementCancel($id)
    {
        return redirect()->route('reservations.payment', $id)
                         ->withErrors(['error' => 'Le paiement a été annulé. Vous pouvez réessayer.']);
    }


    public function edit($id)
    {
        $reservation = Reservation::with(['annonce.prixPeriodes', 'annonce.ville', 'annonce.photos', 'dateDebut', 'dateFin'])
            ->findOrFail($id);

        if ($reservation->idutilisateur !== Auth::id()) {
            return redirect()->route('reservations.mes_locations')->withErrors(['error' => "Action non autorisée."]);
        }

        if ($reservation->statut_reservation === 'Refusée') {
            return redirect()->route('reservations.mes_locations')
                             ->withErrors(['error' => "Impossible de modifier une réservation qui a été refusée."]);
        }

        $prixNuit = $reservation->annonce->prixPeriodes->min('prix') ?? 0;

        return view('reservations.edit', compact('reservation', 'prixNuit'));
    }

     public function update(Request $request, $id)
    {
        $reservation = Reservation::with(['annonce.prixPeriodes', 'reglements'])->findOrFail($id);

        if ($reservation->idutilisateur !== Auth::id()) {
            return back()->withErrors(['error' => "Action non autorisée."]);
        }
        if ($reservation->statut_reservation === 'Refusée') {
            return back()->withErrors(['error' => "Modification impossible sur une réservation refusée."]);
        }

        $request->validate([
            'date_arrivee' => 'required|date|after_or_equal:today',
            'date_depart'  => 'required|date|after:date_arrivee',
            'nbadulte'     => 'required|integer|min:1',
            'nbenfant'     => 'nullable|integer|min:0',
            'nbbebe'       => 'nullable|integer|min:0',
            'nbanimeaux'   => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $dateDebut = Dates::firstOrCreate(['dateacte' => $request->date_arrivee]);
            $dateFin = Dates::firstOrCreate(['dateacte' => $request->date_depart]);
            $start = Carbon::parse($request->date_arrivee);
            $end = Carbon::parse($request->date_depart);
            $nouveauxJours = $start->diffInDays($end);

            $reservation->iddate = $dateDebut->iddate;
            $reservation->dat_iddate = $dateFin->iddate;
            $reservation->nbjours = $nouveauxJours;
            $reservation->nbadulte = $request->nbadulte;
            $reservation->nbenfant = $request->nbenfant ?? 0;
            $reservation->nbanimeaux = $request->nbanimeaux ?? 0;
            $reservation->nbbebe = $request->nbbebe ?? 0;
            $reservation->save();

            DB::commit();

            $prixNuit = $reservation->annonce->prixPeriodes->min('prix') ?? 0;
            $nouveauTotal = ($prixNuit * $nouveauxJours) * 1.10; 
            
            $dejaPaye = $reservation->reglements->sum('montant');

            if ($nouveauTotal > $dejaPaye) {
                return redirect()->route('reservations.payment', $reservation->idreservation)
                                 ->with('warning', 'Modification enregistrée. Un supplément est nécessaire pour valider les nouvelles dates.');
            }

            return redirect()->route('reservations.mes_locations')
                             ->with('success', 'Votre réservation a été modifiée avec succès (aucun supplément requis).');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => "Erreur : " . $e->getMessage()])->withInput();
        }
    }
    public function sendMessage(Request $request, $id)
    {
        $user = Auth::user();
        $reservation = Reservation::with('annonce.proprietaire')->findOrFail($id);

        if ($reservation->idutilisateur !== $user->idutilisateur && $reservation->annonce->idutilisateur !== $user->idutilisateur) {
            return back()->withErrors(['error' => "Action non autorisée."]);
        }

        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $destinataireId = ($user->idutilisateur === $reservation->idutilisateur) 
            ? $reservation->annonce->proprietaire->idutilisateur 
            : $reservation->idutilisateur;

        Message::create([
            'idannonce' => $reservation->idannonce,
            'idreservation' => $reservation->idreservation, 
            'idutilisateur' => $user->idutilisateur,         
            'com_idutilisateur' => $destinataireId,          
            'contenu' => $request->message,
            'dateenvoi' => Carbon::now()
        ]);

        return back()->with('success', 'Message envoyé.');
    }
}