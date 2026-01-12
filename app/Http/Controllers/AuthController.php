<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Utilisateur créé avec succès'], 201);
    }
    public function showLoginForm()
    {
        return view('login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'idutilisateur' => ['required', 'integer'],
            'password'      => ['required', 'string'],
        ], [
            'idutilisateur.integer' => "L'identifiant doit être un nombre.",
            'password.required'     => "Le mot de passe est requis."
        ]);

        $loginSuccess = Auth::attempt([
            'idutilisateur' => $credentials['idutilisateur'],
            'password'      => $credentials['password']
        ]);

        if ($loginSuccess) {
            $request->session()->regenerate();
            return redirect()->intended('/#')->with('success', 'Connecté avec succès !');
        }

        return back()->withErrors([
            'idutilisateur' => 'Identifiant ou mot de passe incorrect.',
        ])->onlyInput('idutilisateur');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}