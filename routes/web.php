<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\RechercheController;

//page d'accueil
use App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


// ---------------------------
// 1. Page d'accueil
// ---------------------------
 //Route::get('/', [AnnonceController::class, 'recherche/index'])->name('annonces.index');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/carte-osm', function () {
    return view('osm_map');
});

// ---------------------------
// 2. Connexion / Déconnexion
// ---------------------------
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// ---------------------------
// 3. Recherche d'annonces
// ---------------------------
// Route::get('/annonces/recherche/ville', [AnnonceController::class, 'rechercheParVille'])
//     ->name('annonces.recherche.ville');
//J ai changé 
Route::get('/recherche', [RechercheController::class, 'index'])->name('recherche.index');


// ---------------------------
// 4. Affichage d’une annonce
// ---------------------------
Route::get('/annonce/{id}', [AnnonceController::class, 'show'])->name('annonces.show');


// ---------------------------
// 5. Inscription
// ---------------------------
Route::get('/inscription', function () {
    return view('inscription');
})->name('inscription');

// Page du formulaire
Route::get('/inscription/perso', [InscriptionController::class, 'create'])
    ->name('inscription.form');

// Traitement du formulaire (C'est celle-ci qui posait problème)
Route::post('/inscription/perso', [InscriptionController::class, 'store'])
    ->name('inscription.traitement');

// Inscription Entreprise
Route::get('/inscription/entreprise/siret', function () {
    return view('inscription-entreprise');
})->name('inscription.entreprise');

Route::post('/inscription/entreprise/info', [EntrepriseController::class, 'submitSiret'])
    ->name('inscription.entreprise.info');

    
Route::post('/entreprise/verifier-siret', [EntrepriseController::class, 'getEntrepriseInfoAjax'])
    ->name('entreprise.verifier_siret');


//RechercheController

Route::get('/recherche', [RechercheController::class, 'index'])->name('recherche.index');
