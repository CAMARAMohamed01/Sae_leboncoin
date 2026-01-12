<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class CompteUtilisateur extends Authenticatable
{
    use Notifiable;

    protected $table = 'compteutilisateur';
    protected $primaryKey = 'idutilisateur';
    public $timestamps = false;

    protected $fillable = [
        'telutilisateur',
        'motdepasse',
        'emailutilisateur',
        'solde',
        'statut_rgpd',
        'idphoto',
        'idadresse',
        'telephone_verifie', // État de vérification
        'role',
        'date_creation',          // creation du compte
        'date_derniere_connexion' // derniere connexion
    ];
/// début hashage
    protected $hidden = [
        'motdepasse',
    ];

    protected $casts = [
        'motdepasse' => 'hashed',
        'statut_rgpd' => 'boolean',
        'solde' => 'decimal:2',
        'telephone_verifie' => 'boolean',
        'date_creation' => 'date',
        'date_derniere_connexion' => 'date',
    ];


    public function isDPO() { return $this->role === 'dpo' || $this->role === 'admin'; }
    // Service Immobilier (Expertise)
    public function isServiceImmobilier()
    {
        return $this->role === 'service immobilier' || $this->role === 'admin';
    }
    public function isServiceInscription()
    {
        return $this->role === 'service inscription' || $this->role === 'admin';
    }
    
    public function isServiceAnnonce()
    {
        
        return $this->role === 'service petit annonce' || $this->role === 'admin';
    }
     
    public function isServiceLocation()
    {
        return $this->role === 'service location' || $this->role === 'admin';
    }
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isServiceJuridique()
    {
        return $this->role === 'service juridique' || $this->role === 'admin';
    }
    public function getAuthPassword()
    {
        return $this->motdepasse;
    }
   



    
    public function particulier()
    {
        return $this->hasOne(Particulier::class, 'idutilisateur', 'idutilisateur');
    }

    public function professionnel()  {
        return $this->hasOne(Professionnel::class, 'idutilisateur', 'idutilisateur');
    }
    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'idadresse', 'idadresse');
    }

    
    public function identite()
    {
        return $this->hasOne(Identite::class, 'idutilisateur', 'idutilisateur');
    }

    
    public function photo()
    {
        return $this->belongsTo(Photo::class, 'idphoto', 'idphoto');
    }
    public function getNomAffichageAttribute()
    {
        if ($this->particulier) {
            return $this->particulier->prenomparticulier . ' ' . $this->particulier->nomparticulier;
        }
        
        if ($this->professionnel) {
            return $this->professionnel->nomprofessionnel . ' (Pro)';
        }

        return 'Utilisateur Leboncoin';
    }
     
    public function favoris()
    {
        
        return $this->belongsToMany(Annonce::class, 'mettre_au_favoris', 'idutilisateur', 'idannonce');
    }

    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'idutilisateur', 'idutilisateur');
    }

    
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'idutilisateur', 'idutilisateur');
    }
    ///
    public function rechercher()  {
        return $this->hasMany(Annonce::class, 'idannonce', 'idannonce');
    }

    public function rechercherpersonnenom(){
        return $this->hasMany(Rechercher::class, 'idutilisateur', 'idutilisateur');
    
    }
    public function rechercherpersonne(){
        return $this->hasOne(Annonce::class, 'idutilisateur', 'idutilisateur');
    }
    
    // --- MÉTHODE D'ANONYMISATION ---
    public function anonymiser()
    {
        //  Anonymiser le Compte
        $this->emailutilisateur = 'anonyme_' . $this->idutilisateur . '@deleted.local';
        $this->telutilisateur = '0000000000';
        $this->motdepasse = 'DELETED';
        $this->statut_rgpd = false; // Marqué comme non-conforme/supprimé
        $this->idphoto = null; // Supprimer lien photo
        $this->save();

        // Anonymiser le Profil Particulier
        if ($this->particulier) {
            $this->particulier->update([
                'nomparticulier' => 'Anonyme',
                'prenomparticulier' => 'Utilisateur',
                'telutilisateur' => '0000000000',
                'datenaissance' => '1900-01-01', // Si possible
            ]);
        }

        // Anonymiser le Profil Pro
        if ($this->professionnel) {
            $this->professionnel->update([
                'nomprofessionnel' => 'Société Anonyme',
                'numerosiret' => '00000000000000',
            ]);
        }

        // Supprimer l'adresse (Donnée perso)
        if ($this->adresse) {
            // On peut détacher ou supprimer l'adresse
            // $this->adresse->delete(); 
            $this->idadresse = null;
            $this->save();
        }
    }

    public function supprimerTotalement()
    {
        // On utilise une transaction pour s'assurer que tout est supprimé ou rien
        \Illuminate\Support\Facades\DB::transaction(function () {
            
            // 1. Supprimer les éléments liés au profil Particulier/Pro
            if ($this->particulier) $this->particulier->delete();
            if ($this->professionnel) $this->professionnel->delete();
            if ($this->locataire) $this->locataire->delete();
            if ($this->identite) $this->identite->delete();

            // 2. Supprimer les annonces et leurs dépendances
            if ($this->annonces) {
                foreach ($this->annonces as $annonce) {
                    $annonce->photos()->delete();       // Supprime les photos
                    $annonce->prixPeriodes()->delete(); // Supprime les prix
                    $annonce->equipements()->detach();  // Détache les équipements
                    $annonce->services()->detach();     // Détache les services
                    $annonce->avis()->delete();         // Supprime les avis sur l'annonce
                    
                    // Si l'annonce a des réservations, il faut aussi les supprimer (ou les gérer)
                    // Attention : cela peut être destructif pour l'historique des locataires
                    // Ici on supprime tout radicalement
                    \App\Models\Reservation::where('idannonce', $annonce->idannonce)->delete();
                    
                    $annonce->delete();
                }
            }

            // 3. Supprimer les réservations faites par cet utilisateur (en tant que locataire)
            if ($this->reservations) {
                foreach ($this->reservations as $res) {
                    // Supprimer les règlements liés
                    \App\Models\Reglement::where('idreservation', $res->idreservation)->delete();
                    $res->delete();
                }
            }

            // 4. Supprimer les avis laissés par cet utilisateur
            \App\Models\Avis::where('idutilisateur', $this->idutilisateur)->delete();

            // 5. Détacher les favoris
            $this->favoris()->detach();

            // 6. Supprimer les demandes de suppression (RGPD)
            \App\Models\DemandeSuppression::where('idutilisateur', $this->idutilisateur)->delete();

            // 7. Enfin, supprimer le compte lui-même
            $this->delete();

            // 8. Supprimer l'adresse si elle est orpheline (Optionnel)
             if ($this->idadresse) {
                 $adresse = \App\Models\Adresse::find($this->idadresse);
                 // Vérifier si personne d'autre n'utilise cette adresse avant de supprimer
                 // (Code simplifié ici)
             }
        });
    }
}