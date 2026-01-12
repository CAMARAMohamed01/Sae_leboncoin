<?php

namespace App\Http\Controllers;

use App\Models\CompteUtilisateur;
use App\Models\Particulier;
use App\Models\Identite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Dates;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail; 
use App\Mail\VerificationEmail;

class InscriptionController extends Controller
{
    public function showStepEmail()
    {
        return view('inscription.inscription_perso');
    }

    public function processStepEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:compteutilisateur,emailutilisateur', 
            'newsletter' => 'nullable'
        ], [
            'email.unique' => 'Cet email est déjà utilisé.',
            'email.required' => 'L\'email est obligatoire.'
        ]);

        $code = rand(100000, 999999);

        session([
            'inscription_email' => $validated['email'],
            'inscription_newsletter' => $request->has('newsletter'),
            'verification_code' => $code
        ]);

        try {
            Mail::to($validated['email'])->send(new VerificationEmail($code));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => "Impossible d'envoyer l'email : " . $e->getMessage()]);
        }

        return redirect()->route('inscription.perso.verify');
    }

    public function showVerifyCode()
    {
        if (!session()->has('inscription_email')) {
            return redirect()->route('inscription.perso.email');
        }

        return view('inscription.verify_code', ['email' => session('inscription_email')]);
    }

    public function checkVerifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6'
        ], [
            'code.required' => 'Veuillez entrer le code reçu par email.',
            'code.digits' => 'Le code doit contenir 6 chiffres.'
        ]);

        if ($request->code == session('verification_code')) {
            
            session(['email_verified' => true]);
            
            return redirect()->route('inscription.perso.details');
        }

        return back()->withErrors(['code' => 'Code incorrect. Veuillez vérifier votre email.']);
    }
    
    public function showStepDetails()
    {
        if (!session()->has('inscription_email')) {
            return redirect()->route('inscription.perso.email');
        }

        return view('inscription.inscription_perso_para', [
            'email' => session('inscription_email')
        ]);

        

       
    }

    public function registerFinal(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:compteutilisateur,emailutilisateur',
            'civilite' => 'required|in:monsieur,madame',
            'nom' => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'date_naissance' => 'required|string|max:10',
            'password' => 'required|min:8',
            'adresse' => 'required|string', 
            'ville' => 'required|string',
            'cni_numero' => 'required|string|max:30',
            'cni_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ], [
            'cni_numero.required' => 'Le numéro de pièce d\'identité est obligatoire.',
            'cni_file.required' => 'Le fichier de la pièce d\'identité est obligatoire.',
            'cni_file.file' => 'Le fichier envoyé n\'est pas valide (vérifiez le format).',
            'cni_file.mimes' => 'Le fichier doit être au format : pdf, jpg, jpeg ou png.',
            'cni_file.max' => 'Le fichier est trop lourd (max 4 Mo).',
        ]);

        
        try {
            $dateInput = $validated['date_naissance'];

            if (str_contains($dateInput, '/')) {
                $dateNaissance = Carbon::createFromFormat('d/m/Y', $dateInput)->format('Y-m-d');
            } 
            else {
                $dateNaissance = $dateInput; 
            }
        } catch (\Exception $e) {
            return back()->withErrors(['date_naissance' => 'Date invalide. Utilisez le format JJ/MM/AAAA.'])->withInput();
        }

        $codeCivilite = ($validated['civilite'] === 'monsieur') ? 'MR' : 'ME';

        try {
            DB::beginTransaction();

            $compte = new CompteUtilisateur();
            
            $compte->emailutilisateur = $validated['email']; 
            
            $compte->motdepasse = Hash::make($validated['password']);
            $compte->solde = 0;
            $compte->statut_rgpd = true;
            $compte->telutilisateur = '0000000000';
            
            $compte->idadresse = null;
            
            $compte->save();

            $particulier = new Particulier();
            $particulier->idutilisateur = $compte->idutilisateur;
            $particulier->nomparticulier = $validated['nom'];
            $particulier->prenomparticulier = $validated['prenom'];
            
            
            
            $particulier->civilite = $codeCivilite;
            $particulier->datenaissance = $dateNaissance;
            
            $particulier->telutilisateur = '0000000000'; 
            $particulier->solde = 0; 
            $particulier->motdepasse = $compte->motdepasse; 
            $particulier->statut_rgpd = true;

            $particulier->save();

            if ($request->hasFile('cni_file')) {
                $path = $request->file('cni_file')->store('cni', 'public');

                $identite = new Identite();
                $identite->idutilisateur = $compte->idutilisateur;
                $identite->numeroidentite = $validated['cni_numero'];
                $identite->typeidentite = 'CNI';
                $identite->dateexpirationidentite = Carbon::now()->addYears(10);
                $identite->lien_document = '/storage/' . $path;
                
                $identite->save();
            }
            
            DB::commit();
            Auth::login($compte);
            
            Auth::login($compte);
            session()->forget(['inscription_email', 'inscription_newsletter']);

            return redirect()->route('home')->with('success', 'Compte créé avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => "Erreur lors de l'enregistrement : " . $e->getMessage()])->withInput();
        }
    }
}
