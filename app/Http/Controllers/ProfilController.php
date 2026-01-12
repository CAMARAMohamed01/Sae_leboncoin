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

        // 1. Validation
        $rules = [
            'telutilisateur' => 'required|digits:10',
            // Email unique en ignorant l'utilisateur courant
            'email' => ['required', 'email', Rule::unique('compteutilisateur', 'emailutilisateur')->ignore($user->idutilisateur, 'idutilisateur')],
            'password' => 'nullable|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Nouveaux champs adresse (Optionnels mais liés entre eux)
            'numero_voie' => 'nullable|string|max:10',
            'nom_rue' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:5',
            'nom_ville' => 'nullable|string|max:50',
            'cni_numero' => 'nullable|string|max:30',
            'cni_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096', // Max 4Mo
        ];

        // Règles conditionnelles selon le profil
        if ($user->particulier) {
            $rules['nom'] = 'required|string|max:50';
            $rules['prenom'] = 'required|string|max:50';
        } elseif ($user->professionnel) {
            $rules['nom'] = 'required|string|max:50';
            $rules['siret'] = 'required|string|size:14';
        }

        $validated = $request->validate($rules);

        // 2. Gestion de l'Adresse (Si l'utilisateur a rempli les champs)
        if ($request->filled(['nom_rue', 'code_postal', 'nom_ville'])) {
            
            // A. Trouver ou créer la VILLE
            $ville = Ville::firstOrCreate(
                [
                    'nomville' => $request->nom_ville,
                    'cpville' => $request->code_postal
                ],
                ['t_108' => 1] // Valeur par défaut pour le département si inconnu
            );

            // B. Trouver ou créer l'ADRESSE
            // On vérifie si cette combinaison Ville + Rue + Numéro existe déjà
            $adresse = Adresse::firstOrCreate(
                [
                    'idville' => $ville->idville,
                    'nomrue' => $request->nom_rue,
                    'voie' => $request->numero_voie
                ]
                // On pourrait ajouter latitude/longitude ici via API plus tard
            );

            // C. Lier l'adresse à l'utilisateur
            $user->idadresse = $adresse->idadresse;
        }

        // 3. Gestion de la Photo de profil
        if ($request->hasFile('avatar')) {
            // Suppression de l'ancienne photo si elle existe (Optionnel mais recommandé pour nettoyer)
            if ($user->photo) {
                // $ancienChemin = str_replace('/storage/', 'public/', $user->photo->lienurl);
                // Storage::delete($ancienChemin);
                // $user->photo->delete(); 
            }

            $path = $request->file('avatar')->store('profils', 'public');
            
            $nouvellePhoto = Photo::create([
                'lienurl' => '/storage/' . $path,
                'legende' => 'Photo de profil de ' . ($request->prenom ?? $request->nom),
                'idannonce' => null
            ]);
            
            $user->idphoto = $nouvellePhoto->idphoto;
        }

        // Gestion de la CNI (Identité) 
        if ($request->filled('cni_numero') || $request->hasFile('cni_file')) {
            // On récupère l'identité existante ou on en crée une nouvelle
            $identite = $user->identite ?? new Identite();
            $identite->idutilisateur = $user->idutilisateur;
            
            if ($request->filled('cni_numero')) {
                $identite->numeroidentite = $request->cni_numero;
            }

            // Mise à jour du fichier si un nouveau est envoyé
            if ($request->hasFile('cni_file')) {
                $pathCni = $request->file('cni_file')->store('cni', 'public');
                $identite->lien_document = '/storage/' . $pathCni;
                $identite->typeidentite = 'CNI'; // Type par défaut
                
                // Si c'est une nouvelle pièce, on met une date d'expiration par défaut
                if (!$identite->exists) {
                    $identite->dateexpirationidentite = Carbon::now()->addYears(10);
                }
            }
            
            $identite->save();
        }

        //  Mise à jour Compte Utilisateur
        $user->telutilisateur = $validated['telutilisateur'];
        $user->emailutilisateur = $validated['email'];

        if ($request->filled('password')) {
            $user->motdepasse = Hash::make($validated['password']);
        }

        $user->save();

        // 5. Mise à jour Profil Spécifique
        if ($user->particulier) {
            $user->particulier->update([
                'nomparticulier' => $validated['nom'],
                'prenomparticulier' => $validated['prenom'],
                // 'civilite' => ... (si tu as ajouté ce champ dans la vue)
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
     * Affiche la page "Vie Privée" avec le récapitulatif des données
     */
    public function privacy()
    {
        $user = Auth::user();
        // On charge TOUTES les relations pour afficher les données stockées
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

    /**
     * Exporte les données au format JSON (Portabilité des données)
     */
    public function exportData()
    {
        $user = Auth::user();
        $user->load(['particulier', 'professionnel', 'adresse.ville', 'identite', 'annonces', 'reservations', 'favoris']);

        $data = $user->toJson(JSON_PRETTY_PRINT);
        $filename = 'donnees_personnelles_' . $user->idutilisateur . '_' . date('Y-m-d') . '.json';

        return response($data)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    
    /**
     * NOUVELLE MÉTHODE : DEMANDE DE SUPPRESSION (DPO)
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();

        // 1. Sécurité : Confirmation mot de passe obligatoire
        $request->validate([
            'password_delete' => 'required'
        ]);

        if (!Hash::check($request->password_delete, $user->motdepasse)) {
            return back()->withErrors(['password_delete' => 'Le mot de passe est incorrect.']);
        }

        // 2. Vérifier si une demande est déjà en cours
        $existe = DemandeSuppression::where('idutilisateur', $user->idutilisateur)
                    ->where('statut', 'En attente')
                    ->exists();

        if ($existe) {
            return back()->withErrors(['error' => 'Une demande de suppression est déjà en cours de traitement par notre Délégué à la Protection des Données.']);
        }

        // 3. Création de la demande pour le DPO
        DemandeSuppression::create([
            'idutilisateur' => $user->idutilisateur,
            'date_demande' => now(),
            'statut' => 'En attente'
        ]);

        // 4. Feedback utilisateur (On ne le déconnecte pas, on le prévient juste)
        return redirect()->route('profil.privacy')
            ->with('success', 'Votre demande de droit à l\'oubli a été transmise à notre DPO. Elle sera traitée dans un délai de 30 jours conformément à la réglementation.');
    }

}
