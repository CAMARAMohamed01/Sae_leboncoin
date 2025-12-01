<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class inscriptionController extends Controller
{
    // Affiche la vue blade
    public function create()
    {
        return view('inscription_perso');
    }

    // Traite les données
    public function store(Request $request)
    {
        // 1. Validation des champs
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // Vérifie que l'email est unique
            'password' => 'required|string|min:8|confirmed', // 'confirmed' vérifie le champ password_confirmation
        ]);

        // 2. Création de l'utilisateur
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hachage du mot de passe (Important !)
        ]);

        // 3. Redirection
        return redirect('/')->with('success', 'Votre compte a été créé avec succès !');
    } 
}