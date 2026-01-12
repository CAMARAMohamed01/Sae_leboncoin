<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\RechercheController;
use App\Http\Controllers\SireneController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ProfessionnelController;
use App\Http\Controllers\ParticulierController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\ReservationController; 
use App\Http\Controllers\HoistoriqueRechercheController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FavorisController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\AideController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ServiceLocationController; 
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServiceImmobilierController; 
use App\Http\Controllers\ProprietaireController;
use App\Http\Controllers\ServiceAnnonceController;
use App\Http\Controllers\ServiceTypeHebergementController;
use App\Http\Controllers\ServiceJuridiqueController;

use App\Http\Controllers\BotManController;
use App\Http\Controllers\RGPDController;
use App\Http\Controllers\LocataireController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


// ---------------------------
// 1. Page d'accueil
// ---------------------------

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/carte-osm', function () {
    return view('osm_map');
});

// ---------------------------
// 2. Connexion 
// ---------------------------


Route::get('/login', [LoginController::class, 'show'])->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ---------------------------
// 3. Recherche d'annonces
// ---------------------------


Route::get('/recherche', [RechercheController::class, 'index'])->name('recherche.index');

Route::post('/hoistorique-recherche', [HoistoriqueRechercheController::class, 'store'])
    ->middleware('auth') // Seuls les utilisateurs connectés peuvent enregistrer
    ->name('hoistorique.store');

Route::get('/mes-recherches', [HoistoriqueRechercheController::class, 'index'])
    ->middleware('auth')
    ->name('hoistorique.index');
// ---------------------------
// 4. Affichage d’une annonce
// ---------------------------


Route::get('/annonces/{id}', [AnnonceController::class, 'show'])
    ->name('annonces.show')
    ->whereNumber('id'); 

Route::get('/professionnels/{id}', [ProfessionnelController::class, 'show'])->name('professionnels.show');





// ------------------------------------
// LE calendrier
//-------------------------------------

Route::get('/annonce/{id}/calendar', [AnnonceController::class, 'showcalendar'])->name('annonce.calendar');
//Reservation 
Route::get('/annonce/{id}/payer', [AnnonceController::class, 'showpaiement'])->name('annonce.paiement');


// formulaire incidents 
Route::get('/formsincidents/{idReservation}', [IncidentController::class, 'index'])->name('incident.create')->whereNumber('idReservation');
Route::post('/formsincidents/save', [IncidentController::class, 'store']);
Route::get('/locationpb/{id}', [LocataireController::class, 'maFonction']);
// Incident a gerer 
Route::get('/incident/{id?}', [IncidentController::class, 'voirlesIncidents'])->name('gereincident.gererincident');
Route::patch('/incidents/{id}/reconnaitre', [IncidentController::class, 'reconnaitre'])->name('incidents.reconnaitre');
Route::post('/incidents/{id}/contester', [IncidentController::class, 'contester'])->name('incidents.contester');
// Plainte a gerer 
Route::get('/plainte/{id?}', [IncidentController::class, 'voirlesPlaintes'])->name('gereplainte.gererplainte');
Route::patch('/plaintes/{id}/annuler', [IncidentController::class, 'annuler'])->name('incidents.annuler');
Route::post('/plaintes/{id}/demander-info', [IncidentController::class, 'demanderinfo'])->name('incidents.demander-info');
Route::post('/plaintes/{id}/refus', [IncidentController::class, 'refus'])->name('incidents.refus');



// ---------------------------
// 5. Inscription
// ---------------------------
Route::get('/inscription', function () {
     return view('inscription.inscription');
 })->name('inscription.inscription');


// Personnelle


Route::name('inscription.')->group(function () {
    
    // --- PARTICULIER ---
    
    
    Route::get('/perso', [InscriptionController::class, 'showStepEmail'])->name('perso.email');
    
    
    Route::post('/perso/check', [InscriptionController::class, 'processStepEmail'])->name('perso.email.post');
    
    Route::get('/perso/check', function() { return redirect()->route('inscription.perso.email'); });

    
    Route::get('/perso/verify', [InscriptionController::class, 'showVerifyCode'])->name('perso.verify');
    
    
    Route::post('/perso/verify/check', [InscriptionController::class, 'checkVerifyCode'])->name('perso.verify.check');
    
    Route::get('/perso/verify/check', function() { return redirect()->route('inscription.perso.verify'); });

    
    Route::get('/perso/parametre', [InscriptionController::class, 'showStepDetails'])->name('perso.details');
    
    
    Route::post('/perso/final', [InscriptionController::class, 'registerFinal'])->name('perso.final');
    
    Route::get('/perso/final', function() { return redirect()->route('inscription.perso.details'); });


    
    Route::get('/entreprise', function () { return view('inscription.inscription_entreprise'); })->name('entreprise');
    Route::get('/entreprise/siret', function () { return view('inscription.inscription_entreprise_siret'); })->name('entreprise.siret');
});


Route::get('/excuse', function () {
    return view('excuse');
})->name('excuse');



//centre d'aide
Route::get('/centre-aide', [AideController::class, 'index'])->name('aide.index');
Route::get('/centre-aide/recherche', [AideController::class, 'search'])->name('aide.search');
Route::get('/centre-aide/{category}', [AideController::class, 'show'])->name('aide.show');
//contact

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// Professionnel
Route::get('/entreprise', function () {
    
    return view('inscription.inscription_entreprise'); 
})->name('inscription.entreprise');

Route::get('/entreprise/siret', function () {
    return view('inscription.inscription_entreprise_siret');
})->name('inscription.entreprise.siret');

Route::post('/entreprise/siret', [EntrepriseController::class, 'store'])
    ->name('inscription.entreprise.siret.store');


    Route::view('/confidentialite', 'legal.privacy')->name('legal.privacy');

// Pages juridiques et gestion des cookies
Route::post('/cookies/record', [ServiceJuridiqueController::class, 'recordChoice'])->name('cookies.record');





// ---------------------------)))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))

// Processus de réservation
Route::get('/annonces/{id}/reserver', [ReservationController::class, 'create'])->name('reservations.create');
    

Route::post('/companydetails', [InscriptionController::class, 'storeCompanyDetails'])->name('registration.company.details');


Route::middleware(['auth'])->group(function () {
    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');

    // RGPD UTILISATEUR (Vie Privée)
    Route::get('/profil/donnees-personnelles', [ProfilController::class, 'privacy'])->name('profil.privacy');
    Route::get('/profil/export', [ProfilController::class, 'exportData'])->name('profil.export');
   // Suppression de compte
    Route::delete('/profil/suppression', [ProfilController::class, 'destroy'])->name('profil.destroy');
    // SÉCURITÉ : Redirection si accès GET à la suppression
    Route::get('/profil/suppression', function() { return redirect()->route('profil.privacy'); });

    
    Route::get('/mes-annonces', [AnnonceController::class, 'mesAnnonces'])->name('annonces.mes_annonces');
    Route::get('/mes-locations', [ReservationController::class, 'mesLocations'])->name('reservations.mes_locations');
    Route::get('/mes-favoris', [AnnonceController::class, 'mesFavoris'])->name('annonces.mes_favoris');
    
    
    Route::get('/annonces/creer', [AnnonceController::class, 'create'])->name('annonces.create');
    Route::post('/annonces', [AnnonceController::class, 'store'])->name('annonces.store');

    Route::post('/favoris/{id}', [FavorisController::class, 'toggle'])->name('favoris.toggle');

    
    
    Route::delete('/annonces/{id}', [AnnonceController::class, 'destroy'])->name('annonces.destroy');

    // Locations & Réservations
    Route::get('/mes-locations', [ReservationController::class, 'mesLocations'])->name('reservations.mes_locations');
    
    // Nouvelle Réservation
    Route::post('/annonces/{id}/reserver', [ReservationController::class, 'store'])->name('reservations.store');

    // Modification Réservation
    Route::get('/reservations/{id}/modifier', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/reservations/{id}', [ReservationController::class, 'update'])->name('reservations.update');

    Route::get('/reservations/{id}', function($id) { 
        return redirect()->route('reservations.edit', $id); 
    });
    // Messagerie Réservation (POST)
    Route::post('/reservations/{id}/message', [ReservationController::class, 'sendMessage'])->name('reservations.message');
    // Redirection si accès GET à la messagerie
    Route::get('/reservations/{id}/message', function() { return redirect()->route('reservations.mes_locations'); });

    // --- PAIEMENT ---
    Route::get('/reservations/{id}/paiement', [ReservationController::class, 'showPayment'])->name('reservations.payment');
    // Traitement du paiement en simulation
     Route::post('/reservations/{id}/paiement', [ReservationController::class, 'processPayment'])->name('reservations.payment.process');
     // Déclenchement vers Stripe
    Route::post('/reservations/{id}/paiement/stripe', [ReservationController::class, 'processPaymentStripe'])->name('reservations.payment.stripe');
    
    // Retour Succès
    Route::get('/reservations/{id}/paiement/success', [ReservationController::class, 'paiementSuccess'])->name('reservations.payment.success');
    
    // Retour Annulation
    Route::get('/reservations/{id}/paiement/cancel', [ReservationController::class, 'paiementCancel'])->name('reservations.payment.cancel');


     // --- GESTION PROPRIÉTAIRE  ---
    Route::get('/mes-demandes', [ProprietaireController::class, 'mesDemandesRecues'])->name('proprietaire.demandes');
    Route::post('/mes-demandes/{id}/accepter', [ProprietaireController::class, 'accepter'])->name('proprietaire.accepter');
      Route::get('/mes-demandes/{id}/accepter', function() { 
        return redirect()->route('proprietaire.demandes'); 
    });
    // Route de refus (POST)
    Route::post('/mes-demandes/{id}/refuser', [ProprietaireController::class, 'refuser'])->name('proprietaire.refuser');
    
    // Redirection si accès en GET à la route de refus
    Route::get('/mes-demandes/{id}/refuser', function() { 
        return redirect()->route('proprietaire.demandes'); 
    });
    // ---------------------------
    // Service Annonce : Gestion Équipements Admin
    Route::get('/admin/annonces/equipements', [ServiceAnnonceController::class, 'index'])
        ->name('admin.annonces.equipements.index');
        
    Route::get('/admin/annonces/equipements/create', [ServiceAnnonceController::class, 'create'])
        ->name('admin.annonces.equipements.create');
        
    Route::post('/admin/annonces/equipements', [ServiceAnnonceController::class, 'store'])
        ->name('admin.annonces.equipements.store');

    // Service Annonce : Gestion Types d'Hébergement Admin
        Route::get('/admin/annonces/types', [ServiceTypeHebergementController::class, 'index'])
        ->name('admin.annonces.types.index');
        
    Route::get('/admin/annonces/types/create', [ServiceTypeHebergementController::class, 'create'])
        ->name('admin.annonces.types.create');
        
    Route::post('/admin/annonces/types', [ServiceTypeHebergementController::class, 'store'])
        ->name('admin.annonces.types.store');
    // ---------------------------

    // --- SERVICE DPO (RGPD) ---
    Route::get('/admin/rgpd', [RGPDController::class, 'index'])->name('admin.rgpd.index');
    Route::post('/admin/rgpd/anonymiser', [RGPDController::class, 'anonymiser'])->name('admin.rgpd.anonymiser');
    // La redirection de sécurité :
    Route::get('/admin/rgpd/anonymiser', function() { return redirect()->route('admin.rgpd.index'); });

    Route::post('/admin/rgpd/supprimer', [RGPDController::class, 'supprimer'])->name('admin.rgpd.supprimer');
    Route::get('/admin/rgpd/supprimer', function() { return redirect()->route('admin.rgpd.index'); });
    
    // --- GESTION DES DEMANDES DE SUPPRESSION  ---
    Route::get('/admin/rgpd/demandes', [RGPDController::class, 'listeDemandes'])->name('admin.rgpd.demandes');
    Route::post('/admin/rgpd/demandes/{id}/valider', [RGPDController::class, 'validerDemande'])->name('admin.rgpd.valider');

 
        // Route Admin pour la gestion des pages juridiques
    Route::get('/admin/juridique', [ServiceJuridiqueController::class, 'index'])->name('admin.juridique.index');

    Route::post('/admin/verify-phone/{userId}', [AdminController::class, 'togglePhoneVerification'])
        ->name('admin.verify_phone');
        Route::post('/admin/garantir-annonce/{id}', [AdminController::class, 'toggleAnnonceGarantie'])
        ->name('admin.garantir_annonce');

        
    Route::get('/admin/inscriptions', [AdminController::class, 'listeDemandesInscription'])
    ->name('admin.inscriptions');

    
    Route::get('/admin/location/incidents', [ServiceLocationController::class, 'index'])
        ->name('admin.location.incidents');
    Route::post('/admin/location/incidents/{id}/classer', [ServiceLocationController::class, 'classerSansSuite'])
        ->name('admin.location.classer');

        // Service Immobilier : Avis Expert
    Route::get('/admin/immobilier/avis', [ServiceImmobilierController::class, 'index'])
        ->name('admin.immobilier.index');
    Route::post('/admin/immobilier/avis/{id}', [ServiceImmobilierController::class, 'storeAvis'])
        ->name('admin.immobilier.avis');

    Route::get('/comparatif', [App\Http\Controllers\ComparatifController::class, 'index'])
        ->name('comparatif.comparatif');

});


Route::get('/annonces', [RechercheController::class, 'index'])->name('annonces.index');

Route::get('/particuliers/{id}', [ParticulierController::class, 'show'])
    ->name('particulier.show');

    

/* ---------------------------
   VERIFICATION EMAIL LARAVEL
----------------------------*/


Route::get('/email/verifier', function () {
    return view('auth.verification'); 
})->middleware('auth')->name('verification.notice');


Route::get('/email/verifier/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // email_verifie_le = now()
    return redirect()->route('inscription.perso.details');
})->middleware(['auth', 'signed'])->name('verification.verify');


Route::post('/email/renvoyer', function () {
    request()->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Un nouvel email de vérification vous a été envoyé.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

Route::name('inscription.')->group(function () {
    
    
    Route::get('/perso', [InscriptionController::class, 'showStepEmail'])
        ->name('perso.email'); 

    
    Route::post('/perso/check', [InscriptionController::class, 'processStepEmail'])
        ->name('perso.email.post');

    
    Route::get('/perso/parametre', [InscriptionController::class, 'showStepDetails'])
        ->name('perso.details'); 

    
    Route::post('/perso/final', [InscriptionController::class, 'registerFinal'])
        ->name('perso.final');
});

Route::get('/paiement/succes', [PaymentController::class, 'success'])
    ->name('payment.success');


Route::get('/paiement/echec', [PaymentController::class, 'cancel'])
    ->name('payment.cancel');

    Route::post('/payment/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
//Route::post('/paiement', [\App\Http\Controllers\PaymentController::class, 'checkout'])->name('checkout');

Route::get('/annonce/{id}/payer', [AnnonceController::class, 'showpaiement'])
    ->name('annonce.paiement');

   // Route::get('/annonces/{id}/reserver', [AnnonceController::class, 'pageReservation'])->name('annonces.reserver');


// Chat Bot (Botman) //

Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);

