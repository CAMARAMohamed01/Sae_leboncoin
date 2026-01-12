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
    /**
     * Affiche la liste des locations (réservations) de l'utilisateur connecté
     */
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

    /**
     * 1. Affiche le formulaire de réservation
     */
    public function create($id)
    {
        $annonce = Annonce::with(['ville', 'photos', 'prixPeriodes', 'proprietaire'])->findOrFail($id);
        
        // On récupère le prix de base (sécurité si null)
        $prixNuit = $annonce->prixPeriodes->min('prix') ?? 0;

        return view('reservations.create', compact('annonce', 'prixNuit'));
    }

    /**
     * 2. Traite la demande de réservation (CORRIGÉ)
     */
    public function store(Request $request, $id)
    {
        $annonce = Annonce::findOrFail($id);
        $user = Auth::user();

        // 1. Validation avec les nouveaux champs détaillés
        $request->validate([
            'date_arrivee' => 'required|date|after_or_equal:today',
            'date_depart'  => 'required|date|after:date_arrivee',
            'nbadulte'     => 'required|integer|min:1', // Au moins 1 adulte obligatoire
            'nbenfant'     => 'nullable|integer|min:0',
            'nbbebe'       => 'nullable|integer|min:0',
            'nbanimeaux'   => 'nullable|integer|min:0',
        ], [
            'date_depart.after' => 'La date de départ doit être postérieure à la date d\'arrivée.',
            'nbadulte.min'      => 'Il faut au moins 1 adulte responsable pour réserver.',
        ]);

        try {
            DB::beginTransaction();

            // 2. Gestion du Locataire (Création si inexistant)
            $locataire = $user->locataire;
            if (!$locataire) {
                // Création à la volée du profil locataire si l'utilisateur n'en a pas
                $dateNaiss = $user->particulier->datenaissance ?? now();

                $locataire = Locataire::create([
                    'idutilisateur' => $user->idutilisateur,
                    'idparticulier' => $user->particulier->idparticulier ?? null,
                    // SUPPRESSION DES CHAMPS QUI BLOQUAIENT (nomlocateur, prenomlocateur)
                    'telutilisateur' => $user->telutilisateur,
                    'solde' => $user->solde ?? 0,
                    'datenaissance' => $dateNaiss,
                    'motdepasse' => $user->motdepasse,
                    'statut_rgpd' => $user->statut_rgpd ?? true,
                ]);
            }

            // 3. Gestion des Dates (Table 'dates')
            $dateDebut = Dates::firstOrCreate(['dateacte' => $request->date_arrivee]);
            $dateFin = Dates::firstOrCreate(['dateacte' => $request->date_depart]);

            // 4. Calculs de durée
            $start = Carbon::parse($request->date_arrivee);
            $end = Carbon::parse($request->date_depart);
            $nbJours = $start->diffInDays($end);

            // 5. Création de la Réservation
            $reservation = new Reservation();
            $reservation->idannonce = $annonce->idannonce;
            $reservation->idutilisateur = $user->idutilisateur;
            $reservation->idlocateur = $locataire->idlocateur; 
            
            // Correction pour la colonne idparticulier si elle est requise
            $reservation->idparticulier = $locataire->idparticulier;

            $reservation->iddate = $dateDebut->iddate;
            $reservation->dat_iddate = $dateFin->iddate;
            // $reservation->date_reservation = Carbon::now(); // Colonne retirée car inexistante en BDD
            
            $reservation->nbjours = $nbJours;
            
            // Enregistrement détaillé des voyageurs
            $reservation->nbadulte = $request->nbadulte; 
            $reservation->nbenfant = $request->nbenfant ?? 0;
            $reservation->nbanimeaux = $request->nbanimeaux ?? 0;
            $reservation->nbbebe = $request->nbbebe ?? 0;
            
            $reservation->statut_reservation = 'En attente';

            $reservation->save();

            DB::commit();


            // 7. Redirection vers le paiement
            return redirect()->route('reservations.payment', $reservation->idreservation);

        } catch (\Exception $e) {
            DB::rollBack();
            // Retourne sur la page avec l'erreur visible
            return back()->withErrors(['error' => "Erreur lors de la réservation : " . $e->getMessage()])->withInput();
        }
    }

    /**
     * 3. Affiche la page de paiement
     */
    public function showPayment($id)
    {
        // On charge 'reglements' pour savoir ce qui a déjà été payé
        $reservation = Reservation::with(['annonce.prixPeriodes', 'reglements'])->findOrFail($id);
        
        if ($reservation->idutilisateur !== Auth::id()) {
            return redirect()->route('home')->withErrors(['error' => 'Accès non autorisé']);
        }

        // 1. Calcul du coût total théorique
        $prixNuit = $reservation->annonce->prixPeriodes->min('prix') ?? 0;
        $totalSejour = $prixNuit * $reservation->nbjours;
        $frais = round($totalSejour * 0.10, 2);
        $nouveauTotal = $totalSejour + $frais;

        // 2. Calcul du montant déjà réglé
        $dejaPaye = $reservation->reglements->sum('montant');

        // 3. Calcul du reste à payer (Supplément)
        $resteAPayer = max(0, $nouveauTotal - $dejaPaye);

        // Si tout est réglé, inutile de payer, on renvoie vers la liste
        if ($resteAPayer <= 0) {
             return redirect()->route('reservations.mes_locations')
                             ->with('success', 'Aucun paiement nécessaire. Votre réservation est à jour.');
        }

        // On passe 'resteAPayer' comme 'totalAPayer' pour la vue de paiement
        return view('reservations.paiement', [
            'reservation' => $reservation,
            'totalAPayer' => $resteAPayer, // Ce sera le montant affiché sur le bouton payer
            'frais' => $frais,
            'total' => $totalSejour,
            'dejaPaye' => $dejaPaye,
            'nouveauTotal' => $nouveauTotal
        ]);
    }


    /**
     * Simuler un Traite de paiement
     */
   public function processPayment(Request $request, $id)
    {
        // On charge les règlements pour vérifier si c'est un supplément
        $reservation = Reservation::with(['annonce.proprietaire', 'reglements'])->findOrFail($id);

        if ($reservation->idutilisateur !== Auth::id()) {
            return redirect()->route('home');
        }

        try {
            DB::beginTransaction();

            // Détection supplément
            $dejaPaye = $reservation->reglements->sum('montant');
            // Si déjà payé quelque chose > 0, alors c'est un supplément
            $labelMode = $dejaPaye > 0 ? 'CB (Supplément)' : 'Carte Bancaire';

            Reglement::create([
                'idreservation' => $reservation->idreservation,
                'idutilisateur' => Auth::id(),
                'modereglement' => $labelMode,
                'montant' => $request->montant_total,
                'statut_reglament' => 'Validé'
            ]);

            // Créditer le propriétaire
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
                    'unit_amount' => (int)($montantAPayer * 100), // Centimes
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

    /**
     * RETOUR SUCCÈS STRIPE
     * Valide le paiement en base de données au retour de Stripe
     */
    public function paiementSuccess(Request $request, $id) {
        // On charge explicitement annonce.proprietaire pour pouvoir créditer son solde
        $reservation = Reservation::with(['annonce.proprietaire', 'annonce.prixPeriodes', 'reglements'])->findOrFail($id);
        
        // Recalcul du montant qui vient d'être payé (car Stripe ne le renvoie pas directement en GET simple)
        $prixNuit = $reservation->annonce->prixPeriodes->min('prix') ?? 0;
        $total = ($prixNuit * $reservation->nbjours) * 1.10;
        $dejaPaye = $reservation->reglements->sum('montant');
        $montantPaye = max(0, round($total - $dejaPaye, 2));

        if ($montantPaye > 0) {
            DB::transaction(function () use ($reservation, $montantPaye) {
                // Détection supplément pour le libellé
                $dejaPayeCount = $reservation->reglements->count();
                $label = $dejaPayeCount > 0 ? 'Stripe (Supplément)' : 'Stripe CB';

                // A. Créer le règlement
                Reglement::create([
                    'idreservation' => $reservation->idreservation,
                    'idutilisateur' => Auth::id(),
                    'modereglement' => substr($label, 0, 20), // Coupe à 20 chars pour respecter la BDD
                    'montant' => $montantPaye,
                    'statut_reglament' => 'Validé'
                ]);

                // B. Créditer le propriétaire
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

    /**
     * RETOUR ANNULATION STRIPE
     */
    public function paiementCancel($id)
    {
        return redirect()->route('reservations.payment', $id)
                         ->withErrors(['error' => 'Le paiement a été annulé. Vous pouvez réessayer.']);
    }



    /**
     * Affiche le formulaire de modification
     */
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
        // On charge les relations nécessaires (prix, reglements)
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

            // Mise à jour BDD
            $reservation->iddate = $dateDebut->iddate;
            $reservation->dat_iddate = $dateFin->iddate;
            $reservation->nbjours = $nouveauxJours;
            $reservation->nbadulte = $request->nbadulte;
            $reservation->nbenfant = $request->nbenfant ?? 0;
            $reservation->nbanimeaux = $request->nbanimeaux ?? 0;
            $reservation->nbbebe = $request->nbbebe ?? 0;
            $reservation->save();

            DB::commit();

            // --- VÉRIFICATION FINANCIÈRE ---
            $prixNuit = $reservation->annonce->prixPeriodes->min('prix') ?? 0;
            $nouveauTotal = ($prixNuit * $nouveauxJours) * 1.10; // +10% frais
            
            // Calcul de ce qui a déjà été payé
            $dejaPaye = $reservation->reglements->sum('montant');

            // Si le nouveau total est plus élevé, on doit payer la différence
            if ($nouveauTotal > $dejaPaye) {
                // On redirige vers le paiement, on ne paie pas automatiquement ici
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

        // Sécurité : Seul le locataire (ou le proprio) peut écrire
        if ($reservation->idutilisateur !== $user->idutilisateur && $reservation->annonce->idutilisateur !== $user->idutilisateur) {
            return back()->withErrors(['error' => "Action non autorisée."]);
        }

        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        // Déterminer le destinataire
        $destinataireId = ($user->idutilisateur === $reservation->idutilisateur) 
            ? $reservation->annonce->proprietaire->idutilisateur 
            : $reservation->idutilisateur;

        // Création du message lié à la réservation
        Message::create([
            'idannonce' => $reservation->idannonce,
            'idreservation' => $reservation->idreservation, // Lien vital
            'idutilisateur' => $user->idutilisateur,         // Expéditeur
            'com_idutilisateur' => $destinataireId,          // Destinataire
            'contenu' => $request->message,
            'dateenvoi' => Carbon::now()
        ]);

        return back()->with('success', 'Message envoyé.');
    }
}