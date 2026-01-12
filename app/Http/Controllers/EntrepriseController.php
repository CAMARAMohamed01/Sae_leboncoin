<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\CompteUtilisateur;
use App\Models\Professionnel;
use App\Models\Adresse;
use Illuminate\Validation\Rules\Password; 


class EntrepriseController extends Controller
{
    
    public function store(Request $request)
    {
        $request->merge([
            'siret' => str_replace(' ', '', $request->input('siret')),
            'telephone' => str_replace(' ', '', $request->input('telephone')),
        ]);

        $validated = $request->validate([
            'email' => 'required|email|unique:compteutilisateur,emailutilisateur', 
            'societe' => 'required|string|max:255',
            'siret' => 'required|numeric|digits:14',
            'telephone' => 'required|string|max:20', 
            'secteur' => 'required|string|max:50',
            
            'adresse' => 'required|string|max:50', 
            'cp' => 'required|string|max:10',
            'ville' => 'required|string|max:100',
            
            'mdp' => 'required|min:8', 
        ], [
            'email.unique' => 'Cet email est déjà utilisé.',
            'siret.digits' => 'Le SIRET doit faire 14 chiffres.',
        ]);

        try {
            DB::beginTransaction();
            $adresse = new Adresse();
            $adresseString = $validated['adresse'] . ' ' . $validated['cp'] . ' ' . $validated['ville'];
            $adresse->nomrue = Str::limit($adresseString, 50, ''); 
            $adresse->idville = 1; 
            $adresse->save(); 
            $idadresse = $adresse->idadresse;
            $compte = new CompteUtilisateur();
            $compte->emailutilisateur = $validated['email'];
            $compte->motdepasse = Hash::make($validated['mdp']);
            $compte->telutilisateur = $validated['telephone'];
            $compte->idadresse = $idadresse; 
            $compte->solde = 0;
            $compte->statut_rgpd = true;
            $compte->save(); 
            $idUtilisateur = $compte->idutilisateur;
            $pro = new Professionnel();
            $pro->idutilisateur = $idUtilisateur;
            $pro->idadresse = $idadresse;
            
            $pro->nomprofessionnel = $validated['societe'];
            $pro->numerosiret = $validated['siret'];
            $pro->secteuractivite = $validated['secteur'];
            
            $pro->emailutilisateur = $validated['email'];
            $pro->motdepasse = $compte->motdepasse;
            $pro->telutilisateur = $validated['telephone'];
            $pro->solde = 0;
            $pro->statut_rgpd = true;

            $pro->save();

            DB::commit();

            Auth::guard('pro')->login($pro);

            return redirect()->route('home')->with('success', 'Compte professionnel créé avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors(['erreur_enregistrement' => 'Erreur critique lors de la création du compte.'])->withInput();
 
        }
    }
}