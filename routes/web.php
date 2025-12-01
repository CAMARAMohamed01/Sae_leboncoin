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
    return view('inscription.inscription');
})->name('inscription.inscription');


// Personnelle
Route::get('/perso', function () {
    return view('inscription.inscription_perso');
})->name('inscription.inscription.perso');

Route::get('/perso/paramètre', function () {
    return view('inscription.inscription_perso_para');
})->name('inscription.perso.parametre');

// Professionnel
Route::get('/entreprise', function () {
    // Au lieu de 'inscription_entreprise', on ajoute le dossier devant
    return view('inscription.inscription_entreprise'); 
})->name('inscription.entreprise');

Route::get('/entreprise/siret', function () {
    return view('inscription.inscription_entreprise_siret');
})->name('inscription.entreprise.siret');


//RechercheController

Route::get('/recherche', [RechercheController::class, 'index'])->name('recherche.index');
