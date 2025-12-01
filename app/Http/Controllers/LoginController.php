<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Nécessaire pour la méthode Auth::attempt

class LoginController extends Controller
{
    /**
     * Gère la tentative de connexion de l'utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
{
    // Validation
    $credentials = $request->validate([
        'idutilisateur' => ['required'], 
        'motdepasse' => ['required'],
    ]);

    // 1. On cherche l'utilisateur dans la base par son ID
    $user = User::where('idutilisateur', $request->idutilisateur)->first();
    
    // 2. On vérifie manuellement :
    // - Si l'utilisateur existe
    // - ET si le mot de passe envoyé est strictement égal (===) à celui en base
    if ($user && $user->motdepasse === $request->motdepasse) {
        
        // 3. On connecte l'utilisateur manuellement
        Auth::login($user);
        
        // 4. On régénère la session
        $request->session()->regenerate();

        // 5. Redirection vers l'accueil
        return redirect()->intended('/');
    }

    // En cas d'échec
    return back()->withErrors([
        'idutilisateur' => 'Identifiant ou mot de passe incorrect.',
    ])->onlyInput('idutilisateur');
}
    /**
 * Affiche le formulaire de connexion.
 * Ceci correspond à la route GET /login.
 *
 * @return \Illuminate\View\View
 */
public function show()
{
    // Remplacez 'login' par le nom exact de votre fichier de vue (e.g., resources/views/login.blade.php)
    return view('login'); 
}
}