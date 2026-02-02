<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
use App\Models\Ville;
use App\Models\Adresse;
use App\Models\Identite;
use Carbon\Carbon;
use App\Models\CompteUtilisateur;
USE App\Models\DemandeSuppression;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
class ProfilController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $user->load(['particulier', 'professionnel', 'photo', 'adresse.ville', 'identite']); 
        
        return view('profil.edit', compact('user'));
    }

     public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'telutilisateur' => 'required|digits:10',
            // Email unique
            'email' => ['required', 'email', Rule::unique('compteutilisateur', 'emailutilisateur')->ignore($user->idutilisateur, 'idutilisateur')],
            'password' => 'nullable|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            'numero_voie' => 'nullable|string|max:10',
            'nom_rue' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:5',
            'nom_ville' => 'nullable|string|max:50',
            'cni_numero' => 'nullable|string|max:30',
            'cni_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096', // Max 4Mo
        ];

        if ($user->particulier) {
            $rules['nom'] = 'required|string|max:50';
            $rules['prenom'] = 'required|string|max:50';
        } elseif ($user->professionnel) {
            $rules['nom'] = 'required|string|max:50';
            $rules['siret'] = 'required|string|size:14';
        }

        $validated = $request->validate($rules);

        if ($request->filled(['nom_rue', 'code_postal', 'nom_ville'])) {
            
            // ville
            $ville = Ville::firstOrCreate(
                [
                    'nomville' => $request->nom_ville,
                    'cpville' => $request->code_postal
                ],
                ['t_108' => 1] 
            );

            // adresse unique
            $adresse = Adresse::firstOrCreate(
                [
                    'idville' => $ville->idville,
                    'nomrue' => $request->nom_rue,
                    'voie' => $request->numero_voie
                ]
            );

            $user->idadresse = $adresse->idadresse;
        }

        // Pdp
        if ($request->hasFile('avatar')) {
            if ($user->photo) {
                $ancienChemin = str_replace('/storage/', 'public/', $user->photo->lienurl);
                Storage::delete($ancienChemin);
                $user->photo->delete(); 
            }

            $path = $request->file('avatar')->store('profils', 'public');
            
            $nouvellePhoto = Photo::create([
                'lienurl' => '/storage/' . $path,
                'legende' => 'Photo de profil de ' . ($request->prenom ?? $request->nom),
                'idannonce' => null
            ]);
            
            $user->idphoto = $nouvellePhoto->idphoto;
        }

        // CNI
        if ($request->filled('cni_numero') || $request->hasFile('cni_file')) {
            $identite = $user->identite ?? new Identite();
            $identite->idutilisateur = $user->idutilisateur;
            
            if ($request->filled('cni_numero')) {
                $identite->numeroidentite = $request->cni_numero;
            }

            if ($request->hasFile('cni_file')) {
                $pathCni = $request->file('cni_file')->store('cni', 'public');
                $identite->lien_document = '/storage/' . $pathCni;
                $identite->typeidentite = 'CNI'; 
                
                if (!$identite->exists) {
                    $identite->dateexpirationidentite = Carbon::now()->addYears(10);
                }
            }
            
            $identite->save();
        }

        $user->telutilisateur = $validated['telutilisateur'];
        $user->emailutilisateur = $validated['email'];

        if ($request->filled('password')) {
            $user->motdepasse = Hash::make($validated['password']);
        }

        $user->save();

        if ($user->particulier) {
            $user->particulier->update([
                'nomparticulier' => $validated['nom'],
                'prenomparticulier' => $validated['prenom'],
            ]);
        } elseif ($user->professionnel) {
            $user->professionnel->update([
                'nomprofessionnel' => $validated['nom'],
                'numerosiret' => $validated['siret'],
            ]);
        }

        return back()->with('success', 'Profil mis à jour avec succès !');
    }

    
    /**
     * récap des données
     */
    public function privacy()
    {
        $user = Auth::user();
        $user->load([
            'particulier', 
            'professionnel', 
            'adresse.ville', 
            'identite', 
            'photo',
            'annonces',
            'reservations',
            'favoris'
        ]);

        return view('profil.privacy', compact('user'));
    }


   public function exportData()
    {
        $user = Auth::user();
        $user->load(['particulier', 'professionnel', 'adresse.ville', 'identite', 'annonces', 'reservations', 'favoris']);

        $data = [
            'user' => $user,
            'date_export' => now()->format('d/m/Y H:i')
        ];

        // Génération du PDF avec la vue dédiée
        $pdf = Pdf::loadView('profil.pdf_export', $data);

        $filename = 'donnees_personnelles_' . $user->idutilisateur . '.pdf';
        
        return $pdf->download($filename);
    }

    
    
   public function destroy(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password_delete' => 'required'
        ]);

        if (!Hash::check($request->password_delete, $user->motdepasse)) {
            return back()->withErrors(['password_delete' => 'Le mot de passe est incorrect.']);
        }

        if ($user->aDesActivites()) {
            
            $user->anonymiser();
            $message = 'Votre compte a été anonymisé avec succès. Vos données personnelles ont été effacées, mais l\'historique de vos transactions est conservé anonymement pour des raisons légales.';

        } else {
            $user->favoris()->detach();
            if($user->identite) $user->identite->delete();
            DemandeSuppression::where('idutilisateur', $user->idutilisateur)->delete();
            
            $user->delete();
            $message = 'Votre compte et toutes vos données ont été supprimés définitivement.';
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', $message);
    }

}
