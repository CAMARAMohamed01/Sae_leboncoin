<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\CompteUtilisateur;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = [
            'emailutilisateur' => $request->input('email'), 
            'password' => $request->input('password') 
        ];

        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            
            $user = Auth::user();
            $user->date_derniere_connexion = Carbon::now();
            $user->save();
            // -------------------------------------------
            if ($request->filled('redirect')) {
                return redirect($request->input('redirect'));
            }

            return redirect()->intended('/');
        }
        
        return back()->withErrors([
            'email' => 'Identifiant ou mot de passe incorrect.',
        ])->onlyInput('email');
    }

    public function show()
    {
        return view('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Vous êtes déconnecté.');
    }

}